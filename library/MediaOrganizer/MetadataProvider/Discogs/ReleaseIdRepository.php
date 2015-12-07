<?php

namespace MediaOrganizer\MetadataProvider\Discogs;

use MediaOrganizer\MetadataProvider\Google\CachingSearchClient;

/**
 * Finds release ids
 */
class ReleaseIdRepository
{
    /**
     * @var CachingSearchClient
     */
    private $searchClient;

    /**
     * @param CachingSearchClient $searchClient
     */
    public function __construct(CachingSearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param string $searchString
     * @return array
     */
    public function findBySearchString($searchString)
    {
        return array_unique(
            $this->extractIdsFromReleaseLinks(
                $this->filterReleaseLinks(
                    $this->extractLinksFromSearchResults(
                        json_decode(
                            $this->searchClient->find($searchString)
                        )->items
                    )
                )
            )
        );
    }

    /**
     * @param array $links
     * @return array
     */
    public function extractIdsFromReleaseLinks(array $links)
    {
        return array_map(
            function ($link) {
                return (int)end(
                    explode('/', $link)
                );
            },
            $links
        );
    }

    /**
     * @param array $releaseLinks
     * @return array
     */
    public function filterReleaseLinks(array $releaseLinks)
    {
        return array_filter(
            $releaseLinks,
            function ($link) {
                return strstr($link, 'release');
            }
        );
    }

    /**
     * @param array $searchResults
     * @return array
     */
    public function extractLinksFromSearchResults(array $searchResults)
    {
        return array_map(
            function ($item) {
                return $item->link;
            },
            $searchResults
        );
    }
}
