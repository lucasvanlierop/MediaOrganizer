<?php
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

    public function autoload($className)
    {

    }

    public function run()
    {
        $sourceDirectory = new MusicOrganizer_Directory($this->_sourceDirectory);
        $sourceDirectory->convert();
        //$sourceDirectory->RemoveEmptyDirs();
    }
}