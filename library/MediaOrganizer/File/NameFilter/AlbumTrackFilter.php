<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\File\NameFilter\NameFilterAbstract;

/**
 * @todo add support for disc nrs
 */
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
        $artist = $metadata->getArtist();
        if (empty($artist)) {
            return;
        }

        $album = $metadata->getAlbum();
        if(empty($album)) {
            return;
        }

        $title = $metadata->getTitle();
        if(empty($title)) {
            return;
        }
        
        // Genre
        $genre = $metadata->getGenre();

        // @todo pass to visitor
        $genreToDirMapper = new \MediaOrganizer\GenreToDirMapper();
        $genreDir = $genreToDirMapper->toDir($genre);

        // Try to prefix track number
        $numberedTitle = $this->cleanName($title);
        // @todo format track
        $trackNr = $metadata->getTrackNr();
        if(!empty($trackNr)) {
            $numberedTitle = $trackNr . '_' . $numberedTitle;
        }

        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $this->cleanName($artist) .
            DIRECTORY_SEPARATOR . $this->cleanName($album) .
            DIRECTORY_SEPARATOR . $numberedTitle .
            '.' . $file->getExtension();
    }
}