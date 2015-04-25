<?php
namespace MediaOrganizer;

/**
 * @todo support m4a
 * @todo support wma
 */
class File
{
    const EXTENSION = 'abstract';

    private $filePath;

    /**
     * @var \MediaOrganizer\MetaData
     */
    private $metaData;

    public function accept(\MediaOrganizer\FileVisitor $fileVisitor)
    {
        $fileVisitor->visit($this);
    }

    public static function factory($filePath)
    {
        $pathInfo = pathinfo($filePath);
        switch ($pathInfo['extension']) {
            case 'mp3':
                return new \MediaOrganizer\File\Type\Mp3($filePath);
        }

        throw new \Exception('Unknown file format');
    }

    protected function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

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
            $this->metaData = new \MediaOrganizer\File\MetaData($this->filePath);
        }
        return $this->metaData;
    }

    public function getExtension()
    {
        return static::EXTENSION;
    }

    /**
     * @param $filePath
     */
    public function rename($filePath, $force = false)
    {
        if ($force || !file_exists($filePath)) {
            $dirPath = dirname($filePath);

            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0777, true);
            }

            rename($this->filePath, $filePath);
            echo "move: " . PHP_EOL .
                "from : " . $this->filePath . PHP_EOL .
                'to   : ' . $filePath . PHP_EOL;
        } else {
            echo "File exists, skipping: " . $this->filePath . PHP_EOL;
        }
    }
}
