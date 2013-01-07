<?php
namespace MediaOrganizer;

class File
{
    const EXTENSION = 'abstract';

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

    public function getExtension() {
        return static::EXTENSION;
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
}