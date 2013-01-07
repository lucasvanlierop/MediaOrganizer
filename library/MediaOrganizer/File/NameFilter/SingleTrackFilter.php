<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\GenreToDirMapper;
use MediaOrganizer\File\NameFilter\NameFilterAbstract;

class SingleTrackFilter extends NameFilterAbstract
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var GenreToDirMapper
     */
    private $genreToDirMapper;

    /**
     * @param string $rootDir
     */
    public function __construct($rootDir, GenreToDirMapper $genreToDirMapper)
    {
        $this->rootDir = $rootDir;
        $this->genreToDirMapper = $genreToDirMapper;
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

        $genreDir = $this->genreToDirMapper->toDir($genre);

        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $this->cleanName($artist) .
            DIRECTORY_SEPARATOR . $this->cleanName($title) .
            '.' . $file->getExtension();
    }
}