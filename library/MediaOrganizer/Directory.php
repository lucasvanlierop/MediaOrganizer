<?php
namespace MediaOrganizer;

use MediaOrganizer\FileVisitor;

/**
 * Represents a directory on a filesystem
 *
 * Class Directory
 * @package MediaOrganizer
 */
class Directory extends \DirectoryIterator implements Visitable
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct($path);

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param FileVisitor $visitor
     * @return void
     */
    public function accept(FileVisitor $visitor)
    {
        // @todo check if it's ok to do this first
//        $visitor->visit($this);

        foreach ($this as $file) {
            if ($file->isDot()) {
                continue;
            } elseif ($file->isDir()) {
                $directory = new self($file->getPathName());
                $directory->accept($visitor);
            } else {
                try {
                    $mediaFile = \MediaOrganizer\File::factory($file->getPathName());
                    $mediaFile->accept($visitor);
                } catch (\Exception $ex) {
                    // @todo add logging
                    echo 'Could not accept visitor: "' . $file->getPathName() . '" ' . $ex->getMessage() . PHP_EOL;
                    continue;
                }
            }
        }
    }
}
