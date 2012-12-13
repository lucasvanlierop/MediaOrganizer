<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;

class AlbumTrackFilter
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function filter(File $file)
    {
        $metadata = $file->getMetaData();
        $artist = $metadata->buildArtist();
        if (empty($artist)) {
            return;
        }

        $album = $metadata->buildAlbum();
        if(empty($album)) {
            return;
        }

        // @todo format track
        $trackNr = $metadata->buildTrackNr();
        if(empty($trackNr)) {
            return;
        }
        
        $title = $metadata->buildTitle();
        if(empty($title)) {
            return;
        }
        
        // Genre
        $genre = $metadata->buildGenre();

        // @todo pass to visitor
        $genreToDirMapper = new \MediaOrganizer\GenreToDirMapper();
        $genreDir = $genreToDirMapper->toDir($genre);

        // @todo fix hardcoded extension
        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $artist .
            DIRECTORY_SEPARATOR . $album .
            DIRECTORY_SEPARATOR . $trackNr . '-' . $title . '.mp3';
    }
}