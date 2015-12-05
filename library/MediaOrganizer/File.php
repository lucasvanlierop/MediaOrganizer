<?php
namespace MediaOrganizer;

use MediaOrganizer\File\Type\Mp3;
use MediaOrganizer\File\MetaData;

/**
 * Represents a file on a filesystem
 *
 * @todo support m4a
 * @todo support wma
 */
class File implements Visitable
{
    const EXTENSION = 'abstract';

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var MetaData
     */
    private $metaData;

    /**
     * @var string
     */
    private $contentHash;

    /**
     * @param FileVisitor $fileVisitor
     * @return void
     */
    public function accept(FileVisitor $fileVisitor)
    {
        $fileVisitor->visit($this);
    }

    /**
     * @param string $filePath
     * @return File\Type\Mp3
     * @throws \Exception
     */
    public static function factory($filePath)
    {
        $pathInfo = pathinfo($filePath);
        switch ($pathInfo['extension']) {
            case 'mp3':
                return new Mp3($filePath);
        }

        throw new \Exception('Unknown file format');
    }

    /**
     * @param string $filePath
     */
    protected function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->contentHash = $this->getMetaData()->getContentHash();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->filePath;
    }

    /**
     * @return \MediaOrganizer\File\MetaData
     */
    public function getMetaData()
    {
        if (empty($this->metaData)) {
            $this->metaData = new MetaData($this);
        }
        return $this->metaData;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return static::EXTENSION;
    }

    /**
     * @return string
     */
    public function getContentHash()
    {
        return $this->contentHash;
    }


    /**
     * @param string $filePath
     * @param boolean $force
     * @return void
     */
    public function rename($filePath, $force = false)
    {
        if ($force || !file_exists($filePath)) {
            $dirPath = dirname($filePath);

            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0777, true);
            }

            $filePathBeforeRename = $this->filePath;
            rename($this->filePath, $filePath);
            $this->filePath = $filePath;

            // Metadata is based on path in getid3 lib, so it has to be reset
            $this->metaData = null;

            echo "move: " . PHP_EOL .
                "from : " . $filePathBeforeRename . PHP_EOL .
                'to   : ' . $filePath . PHP_EOL;
        } else {
            echo "File exists, skipping: " . $this->filePath . PHP_EOL;
        }
    }
}
