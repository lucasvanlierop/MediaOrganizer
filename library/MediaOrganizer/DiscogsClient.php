<?php

namespace OAuth\OAuth1\Service;
require_once __DIR__ . "/../../vendor/autoload.php";

use OAuth\OAuth1\Signature\SignatureInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Client\ClientInterface;

class Discogs extends AbstractService
{
    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        SignatureInterface $signature,
        UriInterface $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpClient, $storage, $signature, $baseApiUri);

        if (null === $baseApiUri) {
            $this->baseApiUri = new Uri('http://api.discogs.com/');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTokenEndpoint()
    {
        return new Uri('http://api.discogs.com/oauth/request_token');
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri('http://www.discogs.com/oauth/authorize');
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri('http://api.discogs.com/oauth/access_token');
    }

    /**
     * {@inheritdoc}
     */
    protected function parseRequestTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data['oauth_callback_confirmed']) || $data['oauth_callback_confirmed'] !== 'true') {
            throw new TokenResponseException('Error in retrieving token.');
        }

        return $this->parseAccessTokenResponse($responseBody);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth1Token();

        $token->setRequestToken($data['oauth_token']);
        $token->setRequestTokenSecret($data['oauth_token_secret']);
        $token->setAccessToken($data['oauth_token']);
        $token->setAccessTokenSecret($data['oauth_token_secret']);

        $token->setEndOfLife(StdOAuth1Token::EOL_NEVER_EXPIRES);
        unset($data['oauth_token'], $data['oauth_token_secret']);
        $token->setExtraParams($data);

        return $token;
    }
}



namespace MediaOrganizer;


use Discogs\ClientFactory;
use Discogs\Subscriber\ThrottleSubscriber;

/**
 * Connects to discogs
 *
 * Class DiscogsClient
 * @package MediaOrganizer
 */
class DiscogsClient
{



    /**
     * @param string $filename
     * @return void
     */
    public function findInfoByFilename($filename)
    {
        $cleanFilename = trim(preg_replace('/[^a-z0-9]+/', ' ', strtolower(substr($filename, 0, -4))));
        echo "\n" . $cleanFilename . "\n";

        $key = 'clHKGUtXhaPvaySimOKUpuWiZZrFRdIJehXKxdaI';
        $urlSuffix = 'f=xml&api_key=' . $key;
        $apiUrl = 'http://www.discogs.com/search?type=all&q=' . urlencode($cleanFilename) . '&' . $urlSuffix;

        $resultObject = $this->loadUrl($apiUrl);
        $item = $resultObject->searchresults->result[0];

        if (empty($item)) {
            return;
        }
        $detailUrl = strval($item->uri) . '?' . $urlSuffix;
        printr($detailUrl);
        $details = $this->loadUrl($detailUrl);

        $comparableFileName = $this->createComparableName($cleanFilename);

        // Find track
        $trackNr = 1;
        foreach ($details->release->tracklist->track as $track) {
            $title = strval($track->title);

            $comparableTitle = $this->createComparableName($title);
            echo "\n" . $comparableTitle . ' : ' . $comparableFileName . "\n";
            if (strstr($comparableFileName, $comparableTitle)) {
                //printr($track);
                $id3['title'][] = $title;
                $id3['track_number'][] = $trackNr;
            }

            $trackNr++;
        }

        if (!empty($id3['title'])) {
            $id3['artist'][] = strval($details->release->artists->artist->name);
            $id3['genre'][] = strval($details->release->styles->style);
            $id3['album'][] = strval($details->release->title);
            return $id3;
        }
    }

    /**
     * @param string $url
     * @return \SimpleXMLElement
     * @todo use guzzle
     */
    protected function loadUrl($url)
    {
        $client = ClientFactory::factory([
            'defaults' => [
                'headers' => ['User-Agent' => 'your-app-name/0.1 +https://www.awesomesite.com'],
            ]
        ]);
        $client->getHttpClient()->getEmitter()->attach(new ThrottleSubscriber());



        $response = $client->search([
            'q' => 'Meagashira'
        ]);

        var_dump($response);
// Loop through results
        foreach ($response['results'] as $result) {
            var_dump($result['title']);
        }
// Pagination data
        var_dump($response['pagination']);

// Dump all data
        var_dump($response->toArray());

//        var_dump($resultXml);
//        return simplexml_load_string($resultXml);
    }

    /**
     * Creates comparable name for search reasons
     *
     * @return string
     */
    protected
    function createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }
}


$consumerKey = 'woIIrYlwBrmrRiIoptjT';
$consumerSecret = 'oKZjozYDOZwwWEWXWTSPZWueNzWUryYX';

use OAuth\OAuth1\Service\BitBucket;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;
ini_set('date.timezone', 'Europe/Amsterdam');
$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');
$serviceFactory = new \OAuth\ServiceFactory();
// We need to use a persistent storage to save the token, because oauth1 requires the token secret received before'
// the redirect (request token request) in the access token request.
$storage = new Session();
// Setup the credentials for the requests
$credentials = new Credentials(
    $consumerKey,
    $consumerSecret,
    $currentUri->getAbsoluteUri()
);
// Instantiate the BitBucket service using the credentials, http client and storage mechanism for the token
/** @var $bbService BitBucket */
$bbService = $serviceFactory->createService('Discogs', $credentials, $storage);
if (isset($_GET['oauth_token'])) {
    $token = $storage->retrieveAccessToken('Discogs');
    $bbService->requestAccessToken(
        $_GET['oauth_token'],
        $_GET['oauth_verifier'],
        $token->getRequestTokenSecret()
    );
    header("Location: /");
    exit();
}
$isAuthorized = true;
try {
    $token = $storage->retrieveAccessToken('Discogs');
} catch (\OAuth\Common\Storage\Exception\TokenNotFoundException $e) {
    $isAuthorized = false;
}
if (! $isAuthorized) {
    $token = $bbService->requestRequestToken();
    $url = $bbService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
    header('Location: ' . $url);
}
$client = Discogs\ClientFactory::factory([]);
$oauth = new GuzzleHttp\Subscriber\Oauth\Oauth1([
    'consumer_key'    => $consumerKey, // from Discogs developer page
    'consumer_secret' => $consumerSecret, // from Discogs developer page
    'token'           => $token->getRequestToken(), // get this using a OAuth library
    'token_secret'    => $token->getRequestTokenSecret() // get this using a OAuth library
]);
$client->getHttpClient()->getEmitter()->attach($oauth);
$response = $client->search([
    'q' => 'searchstring'
]);
echo '<pre>';
print_r($response);


//$client = new DiscogsClient();
//$client->findInfoByFilename('test');
