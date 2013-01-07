<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;
use MediaOrganizer\File\NameFilter\NameFilterAbstract;

/**
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
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function filter(File $file)
    {
        $metadata = $file->getMetaData();

        $albumArtist = $metadata->getAlbumArtist();
        $isCompilation = $metadata->getIsCompilation();
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
        $numberedTitle = $this->cleanName($artist) . '-' . $this->cleanName($title);
        // @todo format track
        $trackNr = $metadata->getTrackNr();
        if(!empty($trackNr)) {
            $numberedTitle = $trackNr . '_' . $numberedTitle;
        }

        return $this->rootDir . $genreDir .
            DIRECTORY_SEPARATOR . $this->cleanName($albumArtist) .
            DIRECTORY_SEPARATOR . $this->cleanName($album) .
            DIRECTORY_SEPARATOR . $numberedTitle .
            '.' . $file->getExtension();
    }

    /**
     * protected function _buildCompilationFileName()
    {
    $albumArtist = cleanName($comments['album_artist'][0]);
    $dir = $this->_destinationDirectory
    . $subGenreDir . DIRECTORY_SEPARATOR
    . $albumArtist . DIRECTORY_SEPARATOR
    . $album . DIRECTORY_SEPARATOR;
    $file = '';
    $file .= $track . '_';
    //"todo get extension from file
    $file .= $artist . '-' . $title . '.mp3';

    return $dir . DIRECTORY_SEPARATOR . $file;
    }

     */

}