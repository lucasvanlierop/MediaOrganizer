<?php
namespace MediaOrganizer;

class File
{
    private $_filePath;

    /**
     * @var \MediaOrganizer\MetaData
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
    }

    public function getPath()
    {
        return $this->_filePath;
    }

    /**
     * @return \MediaOrganizer\MetaData
     */
    public function getMetaData() {
        if(empty($this->metaData)) {
            $this->metaData = new \MediaOrganizer\File\MetaData($this->_filePath);
        }
        return $this->metaData;
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
            //echo "skipping: " . $this->_filePath;
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
//
//        $file_path = $dir . $file;
//        return $file_path;
    }





}