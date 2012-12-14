<?php
namespace MediaOrganizer;

use \MediaOrganizer\FileVisitor;

class MusicOrganizer
{
    private $_sourceDirectory;
    private $_destinationDirectory;

    public function __construct()
    {
        // @todo move to config
        $musicDir = '/home/lucasvanlierop/Music/';
        define('ROOT_DIR', $musicDir);
        $this->_destinationDirectory = $musicDir;
        $this->_sourceDirectory = $musicDir;
    }

    public function run()
    {
        $sourceDirectory = new \MediaOrganizer\Directory($this->_sourceDirectory);
        $fileVisitor = new FileVisitor(ROOT_DIR);
        $sourceDirectory->accept($fileVisitor);
        //$sourceDirectory->RemoveEmptyDirs();
    }
}