<?php

namespace MediaOrganizer\MetadataProvider\Google;

/**
 * Caches results from search client
 */
class CachingSearchClient
{
    /**
     * @var SearchClient
     */
    private $searchClient;

    /**
     * CachingSearchClient constructor.
     *
     * @param SearchClient $searchClient
     */
    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param string $searchString
     * @return null|string
     */
    public function find($searchString)
    {
        $cacheKey = $this->createCacheKey($searchString);
        if ($results = $this->fetch($cacheKey)) {
            return $results;
        }

        $searchResults = $this->searchClient->find($searchString);
        if (empty($searchResults)) {
            return null;
        }

        return $this->store($cacheKey, $searchResults);
    }

    /**
     * @param string $cacheKey
     * @return string|null
     */
    private function fetch($cacheKey)
    {
        $cacheFile = $this->createCacheFileUrl($cacheKey);
        if (file_exists($cacheFile)) {
            return file_get_contents($cacheFile);
        }
    }

    /**
     * @param string $searchString
     * @return string
     */
    private function createCacheKey($searchString)
    {
        return base64_encode($searchString);
    }

    /**
     * @param string $cacheKey
     * @return string
     */
    private function createCacheFileUrl($cacheKey)
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . $cacheKey;
    }

    /**
     * @param string $cacheKey
     * @param string $searchResults
     * @return string
     */
    private function store($cacheKey, $searchResults)
    {
        file_put_contents($this->createCacheFileUrl($cacheKey), $searchResults);
        return $searchResults;
    }
}
