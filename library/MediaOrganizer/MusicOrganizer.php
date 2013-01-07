<?php
namespace MediaOrganizer;

use \MediaOrganizer\FileVisitor;

/**
 * @todo support remote metadata fetching
 * @todo support duplicate detection
 * @too add logging
 * @todo support multiple tasks
 * @todo support command line support
 */
class MusicOrganizer
{
    private $_sourceDirectory;
    private $_destinationDirectory;

    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        // @todo move to config
        $musicDir = '/home/lucasvanlierop/Music/';
        define('ROOT_DIR', $musicDir);
        $this->_destinationDirectory = $musicDir;
        $this->_sourceDirectory = $musicDir;

        $this->config = require_once 'config/config.php';
    }

    public function run()
    {
        $sourceDirectory = new \MediaOrganizer\Directory($this->_sourceDirectory);
        $fileVisitor = new FileVisitor(ROOT_DIR, $this->config);
        $sourceDirectory->accept($fileVisitor);
        //$sourceDirectory->RemoveEmptyDirs();
    }
}