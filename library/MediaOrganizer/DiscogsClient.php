<?php
namespace MediaOrganizer;

/**
 * Created by JetBrains PhpStorm.
 * User: lucasvanlierop
 * Date: 2-12-11
 * Time: 18:39
 * To change this template use File | Settings | File Templates.
 */
class DiscogsClient
{
    protected function findInfoByFilename($filename)
    {
        $cleanFilename = trim(preg_replace('/[^a-z0-9]+/', ' ', strtolower(substr($filename, 0, -4))));
        echo "\n" . $cleanFilename . "\n";

        $key = '53459d4211';
        $urlSuffix = 'f=xml&api_key=' . $key;
        $apiUrl = 'http://www.discogs.com/search?type=all&q=' . urlencode($cleanFilename) . '&' . $urlSuffix;

        $resultObject = loadUrl($apiUrl);
        $item = $resultObject->searchresults->result[0];

        if (empty($item)) {
            return;
        }
        $detailUrl = strval($item->uri) . '?' . $urlSuffix;
        //printr($detailUrl);
        $details = loadUrl($detailUrl);


        $comparableFileName = _createComparableName($cleanFilename);

        // Find track
        $trackNr = 1;
        foreach ($details->release->tracklist->track as $track) {
            $title = strval($track->title);

            $comparableTitle = _createComparableName($title);
            //echo "\n" . $comparableTitle .' : ' . $comparableFileName . "\n";
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


    // @todo use guzzle
    protected function loadUrl($url)
    {
        $curlResource = curl_init($url);
        curl_setopt($curlResource, CURLOPT_HEADER, 0);
        curl_setopt($curlResource, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
        $resultXml = curl_exec($curlResource);
        curl_close($curlResource);
        return simplexml_load_string($resultXml);
    }

}
