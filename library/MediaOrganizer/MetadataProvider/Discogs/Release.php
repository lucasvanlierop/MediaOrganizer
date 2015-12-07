<?php

namespace MediaOrganizer\MetadataProvider\Discogs;

use MediaOrganizer\MetadataProvider\Discogs\Release\Track;
use stdClass;

/**
 * Dto for release metadata
 */
class Release
{
    /**
     * @var string
     */
    public $artist;

    /**
     * @var integer
     */
    public $year;

    /**
     * @var Track[]
     */
    public $tracks;

    /**
     * @param stdClass $data
     * @return static
     */
    public static function createFromRawData(stdClass $data)
    {
        $release = new static();
        $release->artist = $data->artists[0]->name;
        $release->year = $data->year;
        $release->tracks = static::mapTrackDataToDto($data->tracklist);

        return $release;
    }

    /**
     * @param array $tracklist
     * @return array
     */
    private static function mapTrackDataToDto(array $tracklist)
    {
        return array_map(function ($trackData) {
            $track = new stdClass();
            $track->title = $trackData->title;
            $track->duration = $trackData->duration;
            return $track;
        }, $tracklist);
    }
}
