<?php

namespace MediaOrganizer;

class File
{
    private $_filePath;

    /**
     * @var MusicOrganizer_MetaData
     */
    private $metaData;

    public function accept(\MediaOrganizer\FileVisitor $fileVisitor) {
        $fileVisitor->visit($this);
    }

    public static function factory($filePath)
    {
        $pathInfo = pathinfo($filePath);
        switch ($pathInfo['extension']) {
            case 'mp3' :
                return new \MediaOrganizer\File\Type\Mp3($filePath);
        }

        throw new \Exception('Unknown file format');
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

    public function getPath()
    {
        return $this->_filePath;
    }

    /**
     * @return MusicOrganizer_MetaData
     */
    public function getMetaData() {
        if(empty($this->metaData)) {
            $this->metaData = new \MusicOrganizer_MetaData($this->_filePath);
        }
        return $this->metaData;
    }

    /**
     * @param string $name
     * @return mixed|string
     */
    protected function _cleanName($name)
    {
        // Replace unwanted characters
        $replacements = array(
            '/[.]/'        => '',       // Unknown characters
            '/&/'          => 'and',    // And sign
            '/[^\w().]+/'  => ' '       // Non word characters
        );
        $out = preg_replace(array_keys($replacements), $replacements, $name);

        // Upper case every first word
        $out = ucwords(strtolower(trim($out)));

        // Concatenate every word
        $out = preg_replace('/( )+/', '-', $out);

        return $out;
    }

    protected function _createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }

    /**
     * @param $filePath
     */
    public function rename($filePath)
    {
        if (!file_exists($filePath)) {
            $dirPath = dirname($filePath);
            if (!file_exists($dirPath)) {
                //mkdir($dirPath, 0777, true);
            }
            //rename($this->_filePath, $filePath);
            echo "copy: " . PHP_EOL .$dirPath . PHP_EOL .
                "from : " . $this->_filePath . PHP_EOL .
                'to   : ' . $filePath . PHP_EOL;
        }    else {
            echo "skipping: " . $this->_filePath;
        }

    }

    protected function _getMetadataFromApi($file)
    {
        if ($this instanceof \MediaOrganizer\File\Type\Mp3) {
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

    /**
     * @param $rootDir
     * @return string
     *
     * @todo support unicode
     */
    protected function _buildFilename($rootDir)
    {
//          if (preg_match('/radio-soulwax/i', $album)) {
//            $comments['album_artist'][0] = '2-many-djs';
//        }

//        $fullFileNameParts = explode(DIRECTORY_SEPARATOR, str_replace(ROOT_DIR, '', $this->_filePath));
//        $mainGenre = $fullFileNameParts[0];
//
//       // @todo move
//        $subGenreDir = $mainGenre;
//        if ('Dance' == $mainGenre) {
//            $subGenreDir .= DIRECTORY_SEPARATOR . $fullFileNameParts[1];
//        }

//        if (isset($comments['album_artist'][0])) {
//            $file = $this->_buildCompilationFileName();
//        } else {
//            $file = $this->_buildDefaultFileName();
//        }
//
//        $file_path = $dir . $file;
//        return $file_path;
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



}