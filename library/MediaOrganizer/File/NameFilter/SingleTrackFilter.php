<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\GenreToDirMapper;

/**
 * Filters album tracks by metadata
 *
 * Class SingleTrackFilter
 * @package MediaOrganizer\File\NameFilter
 */
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
     * @param GenreToDirMapper $genreToDirMapper
     */
    public function __construct($rootDir, GenreToDirMapper $genreToDirMapper)
    {
        $this->rootDir = $rootDir;
        $this->genreToDirMapper = $genreToDirMapper;
    }

    /**
     * @param File $file
     * @return void
     * @throws \Exception
     */
    public function filter(File $file)
    {
        $metadata = $file->getMetaData();
        $artist = $metadata->getArtist();
        if (empty($artist)) {
            return;
        }

        $title = $metadata->getTitle();
        if (empty($title)) {
            return;
        }

        // Start with root dir
        $filePath = $this->rootDir;

        // Add genre
        $genre = $metadata->getGenre();
        $genreDir = $this->genreToDirMapper->toDir($genre);
        if ($genreDir) {
            $filePath .= DIRECTORY_SEPARATOR . $genreDir;
        }

        // Add artist/title.extension
        return $filePath .
        DIRECTORY_SEPARATOR . $this->cleanName($artist) .
        DIRECTORY_SEPARATOR . $this->cleanName($title) .
        '.' . $file->getExtension();
    }
}
