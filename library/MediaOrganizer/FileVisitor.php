<?php
namespace MediaOrganizer;

class FileVisitor
{
    /**
     * @var string
     */
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
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
            new \MediaOrganizer\Visitor\Task\RenameTask($this->getRootDir())
        );

        foreach($tasks as $task) {
            $task->execute($file);
        }
    }
}