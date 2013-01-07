<?php
namespace MediaOrganizer;

use MediaOrganizer\FileVisitor;

class Directory extends \DirectoryIterator
{
    /**
     * @var
     */
    private $path;

    public function __construct($path)
    {
        parent::__construct($path);

        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function accept(FileVisitor $visitor)
    {
        // @todo check if it's ok to do this first
        $visitor->visit($this);

        foreach ($this as $file) {
            if($file->isDot()) {
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
                    continue;
                }
            }
        }
    }
}