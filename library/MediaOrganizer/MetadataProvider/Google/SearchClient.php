<?php

namespace MediaOrganizer\MetadataProvider\Google;

/**
 * Searches using google custom search
 */
class SearchClient
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $customSearchEngineKey;

    /**
     * @param string $apiKey
     * @param string $customSearchEngineKey
     */
    public function __construct($apiKey, $customSearchEngineKey)
    {
        $this->apiKey = $apiKey;
        $this->customSearchEngineKey = $customSearchEngineKey;
    }

    /**
     * @param string $searchString
     * @return string
     */
    public function find($searchString)
    {
        $queryData['key'] = $this->apiKey;
        $queryData['cx'] = $this->customSearchEngineKey;
        $queryData['t'] = 'release';
        $queryData['q'] = $searchString;

        $query = http_build_query($queryData);
        $url = 'https://www.googleapis.com/customsearch/v1?' . $query;

        return file_get_contents($url);
    }
}
