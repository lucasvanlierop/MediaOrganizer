<?php
namespace MediaOrganizer;

class FileVisitor
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $config;

    public function __construct($rootDir, array $config)
    {
        $this->rootDir = $rootDir;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    public function visit($file) {
        if ($file instanceof \MediaOrganizer\Directory) {
            $this->visitDir($file);
        } else if ($file instanceof \MediaOrganizer\File) {
            $this->visitFile($file);
        }
    }

    /**
     * @param \MediaOrganizer\Directory $dir
     * @return bool
     */
    protected function visitDir(\MediaOrganizer\Directory $dir) {
        // @todo convert this to filter
        $directoryName = $dir->getFilename();
        if ($directoryName[0] == '_') {
            return false;
        }

        echo "scannining dir: " . $dir->getPath() . "\n";
    }

    /**
     * @param \MediaOrganizer\File $file
     * @return bool
     */
    protected function visitFile(\MediaOrganizer\File $file) {
        //echo "scannining file: " . $file->getPath() . "\n";

        $tasks = array(
            new \MediaOrganizer\Visitor\FileVisitor\Task\AddMetaDataTask(),
            new \MediaOrganizer\Visitor\FileVisitor\Task\RenameTask($this->getRootDir(), $this->config)
        );

        foreach($tasks as $task) {
            $task->execute($file);
        }
    }
}