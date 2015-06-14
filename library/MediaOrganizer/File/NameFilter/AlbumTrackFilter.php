<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\GenreToDirMapper;

/**
 * Filters album tracks by metadata
 *
 * @todo add support for disc nrs
 */
class AlbumTrackFilter extends NameFilterAbstract
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

        $album = $metadata->getAlbum();
        if (empty($album)) {
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
        $genreDir = $this->genreToDirMapper->toDir($genre, $file->getPath());
        if ($genreDir) {
            $filePath .= DIRECTORY_SEPARATOR . $genreDir;
        }

        // Try to prefix track number
        $numberedTitle = $this->cleanName($title);
        // @todo format track
        $trackNr = $metadata->getTrackNr();
        if (!empty($trackNr)) {
            $numberedTitle = $trackNr . '_' . $numberedTitle;
        }

        // Add artist/album/numbered_title.extension
        return $filePath .
        DIRECTORY_SEPARATOR . $this->cleanName($artist) .
        DIRECTORY_SEPARATOR . $this->cleanName($album) .
        DIRECTORY_SEPARATOR . $numberedTitle .
        '.' . $file->getExtension();
    }
}
