<?php
class MusicOrganizer_File
{
    private $_filePath;

    private $metaData;

    public function factory($filePath)
    {
        $pathInfo = pathinfo($filePath);
        switch ($pathInfo['extension']) {
            case 'mp3' :
                return new MusicOrganizer_File_Mp3($filePath);
        }

        throw new Exception('Unknown file format');
    }

    protected function __construct($filePath) {
        $this->_filePath = $filePath;

        $hashDir = ROOT_DIR . '_hashes' . '/';
        $hash = $this->getMetaData()->getHash();
        $hashLink = $hashDir . $hash;

        if(file_exists($hashLink)){
            echo "\n org file = " . realpath($hashLink);
            exit;
        } else {
            echo "\n NO org file = " . $hashLink;

        }

        echo PHP_EOL;
    }

    public function getMetaData() {
        if(empty($this->metaData)) {
            $this->metaData = new MusicOrganizer_MetaData($this->_filePath);
        }
        return $this->metaData;
    }

    public function parse()
    {
        echo "parsing file " . $this->_filePath . PHP_EOL;

        $fileInfo = $this->getMetaData()->getComments();
        
        $isMp3 = 'mp3' == substr($this->_filePath, -3);

        if ($isMp3) {
            $this->_createAudioHashSoftLink();
        }
        return false;

        if (!isset($ThisFileInfo['comments'])) {
            $this->_getMetadataFromApi($file);
        } else {
           // $this->_
        }

        $file_path = $this->_buildFilename($ThisFileInfo);

        File::Create();

        $i++;
    }

    protected function _cleanName($s)
    {
        $patterns[] = '/[.]/';
        $replacements[] = '';
        $patterns[] = '/&/';
        $replacements[] = 'and';
        $patterns[] = '/[^\w().]+/';
        $replacements[] = ' ';

        $out = preg_replace($patterns, $replacements, $s);

        //printr($out);
        $out = ucwords(strtolower(trim($s)));
        $out = preg_replace('/( )+/', '-', $out);

        return $out;
    }

    protected function _createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }

    protected function _create()
    {
        if (!file_exists($s_file_path)) {
            if (!file_exists($s_dir)) mkdir($s_dir, 0777, true);
            rename($FullFileName, $s_file_path);
            echo "copied: " . $s_file_path . " (" . $s_org_genre . ")\n";
        }
        else {
            //echo "skipping: " . $s_file_path . " (" . $s_org_genre . ")\n";
        }

    }

    protected function _getMetadataFromApi($file)
    {
        if ($isMp3) {
            $comments = findInfoByFilename($file);

        }

        if (empty($comments)) {

            echo "no info: " . $FullFileName . "\n";


            // @todo [check] if file got moved to music clean
            return false;
            $dir = $this->_destinationDirectory . '_no-info/';
            $file_path = $dir . $file;


            if (!file_exists($file_path)) {
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                if (rename($FullFileName, $file_path)) {
                    echo "copied to unknown: " . $file_path . "\n";
                }
            }
            return false;
        }
    }

    protected function _buildFilename(array $ThisFileInfo)
    {
        $comments = $ThisFileInfo['comments'];
        if (empty($comments['artist'])
            || empty($comments['title'])
            || empty($comments['genre'])) {
            return;
        }

        $metadata = new MusicOrganizer_MetaData();

        $artist = $metadata->_buildArtist();
        $album = $metadata->_buildAlbum();
        $title = $metadata->_buildTitle();
        $track = $metadata->_buildTrackNr();


        // Genre
        $genre = $metadata->_buildGenre();
        $org_genre = $genre;
        // todo clean genre
        // todo use folder as genre
        $genre_dir = $this->_destinationDirectory . '_genres_found/' . $genre;
        if (!file_exists($genre_dir)) {
            mkdir($genre_dir, 0777, true);
        }
        $genre = _mapGenre($genre);
        if ('div' == $genre) {
            $genre = $metadata->_mapGenre($sourceDirectory);
        }


        if (preg_match('/radio-soulwax/i', $album)) {
            $comments['album_artist'][0] = '2-many-djs';
        }

        $fullFileNameParts = explode(DIRECTORY_SEPARATOR, str_replace(ROOT_DIR, '', $FullFileName));
        $mainGenre = $fullFileNameParts[0];

       // @todo move
        $subGenreDir = $mainGenre;
        if ('Dance' == $mainGenre) {
            $subGenreDir .= DIRECTORY_SEPARATOR . $fullFileNameParts[1];
        }

        if (isset($comments['album_artist'][0])) {
            $file = $this->_buildCompilationFileName();
        } else {
            $file = $this->_buildDefaultFileName();
        }

        $file_path = $dir . $file;
        return $file_path;
    }


    protected function _buildCompilationFileName()
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

    protected function _buildDefaultFileName()
    {
        $dir = $this->_destinationDirectory
            . $subGenreDir . DIRECTORY_SEPARATOR
            . $artist . DIRECTORY_SEPARATOR;
        $file = '';
        if (!empty($album)) {
            $dir .= $album . DIRECTORY_SEPARATOR;
            $file .= $track . '_';
        }
        $file .= $title . '.mp3';
        return $dir . DIRECTORY_SEPARATOR . $file;
    }

}