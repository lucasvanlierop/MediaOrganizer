<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\File\NameFilter\NameFilterAbstract;

class AlbumTrackFilter extends NameFilterAbstract
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

        $title = $metadata->buildTitle();
        if(empty($title)) {
            return;
        }
        
        // Genre
        $genre = $metadata->buildGenre();

        // @todo pass to visitor
        $genreToDirMapper = new \MediaOrganizer\GenreToDirMapper();
        $genreDir = $genreToDirMapper->toDir($genre);

        // Try to prefix track number
        $numberedTitle = $this->cleanName($title);
        // @todo format track
        $trackNr = $metadata->buildTrackNr();
        if(!empty($trackNr)) {
            $numberedTitle = $trackNr . '_' . $numberedTitle;
        }

        // @todo fix hardcoded extension
        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $this->cleanName($artist) .
            DIRECTORY_SEPARATOR . $this->cleanName($album) .
            DIRECTORY_SEPARATOR . $numberedTitle . '.mp3';
    }
}