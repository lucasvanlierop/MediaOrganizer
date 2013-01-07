<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\File\NameFilter\NameFilterAbstract;

class SingleTrackFilter extends NameFilterAbstract
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

        $title = $metadata->getTitle();
        if(empty($title)) {
            return;
        }

        // Genre
        $genre = $metadata->getGenre();

        // @todo pass to visitor
        $genreToDirMapper = new \MediaOrganizer\GenreToDirMapper();
        $genreDir = $genreToDirMapper->toDir($genre);

        // @todo fix hardcoded extension
        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $this->cleanName($artist) .
            DIRECTORY_SEPARATOR . $this->cleanName($title) . '.mp3';
    }
}