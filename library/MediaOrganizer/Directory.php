<?php
namespace MediaOrganizer;

use MediaOrganizer\FileVisitor;

class Directory extends \DirectoryIterator
{
    public function getPath()
    {
        return $this->getFilename();
    }

    public function accept(FileVisitor $visitor)
    {
        foreach ($this as $file) {
            if($file->isDot()) {
                return false;
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

        $visitor->visit($this);
    }
}