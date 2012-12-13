<?php
namespace MediaOrganizer\File\NameFilter;

use MediaOrganizer\File;

class CompilationTrackFilter
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

        $albumArtist = $metadata->buildAlbumArtist();
        if (empty($albumArtist)) {
            return;
        }

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
            DIRECTORY_SEPARATOR . $albumArtist .
            DIRECTORY_SEPARATOR . $album .
            DIRECTORY_SEPARATOR . $trackNr . '-' . $artist . '-' . $title . '.mp3';
    }

    /**
     * protected function _buildCompilationFileName()
    {
    $albumArtist = _cleanName($comments['album_artist'][0]);
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