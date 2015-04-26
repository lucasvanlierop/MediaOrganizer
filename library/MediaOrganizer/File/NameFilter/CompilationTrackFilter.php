<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\GenreToDirMapper;

/**
 * Filters compilation tracks by metadata
 *
 * @todo add support for disc nrs
 * @todo Improve support for detecting compilations
 */
class CompilationTrackFilter extends NameFilterAbstract
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

        $albumArtist = $metadata->getAlbumArtist();
        $isCompilation = $metadata->isCompilation();
        if (empty($albumArtist)) {
            if ($isCompilation) {
                $albumArtist = 'various';
            } else {
                return;
            }
        }

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

        // Genre
        $genre = $metadata->getGenre();

        $genreDir = $this->genreToDirMapper->toDir($genre);

        // Try to prefix track number
        $numberedTitle = $this->cleanName($artist) . '-' . $this->cleanName($title);
        // @todo format track
        $trackNr = $metadata->getTrackNr();
        if (!empty($trackNr)) {
            $numberedTitle = $trackNr . '_' . $numberedTitle;
        }

        return $this->rootDir . DIRECTORY_SEPARATOR . $genreDir .
        DIRECTORY_SEPARATOR . $this->cleanName($albumArtist) .
        DIRECTORY_SEPARATOR . $this->cleanName($album) .
        DIRECTORY_SEPARATOR . $numberedTitle .
        '.' . $file->getExtension();
    }
    /**
     * protected function buildCompilationFileName()
     * {
     * $albumArtist = cleanName($comments['album_artist'][0]);
     * $dir = $this->destinationDirectory
     * . $subGenreDir . DIRECTORY_SEPARATOR
     * . $albumArtist . DIRECTORY_SEPARATOR
     * . $album . DIRECTORY_SEPARATOR;
     * $file = '';
     * $file .= $track . '_';
     * //"todo get extension from file
     * $file .= $artist . '-' . $title . '.mp3';
     *
     * return $dir . DIRECTORY_SEPARATOR . $file;
     * }

     */
}
