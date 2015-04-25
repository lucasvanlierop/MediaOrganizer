<?php
namespace MediaOrganizer;

use \MediaOrganizer\FileVisitor;

/**
 * Organizes music collection with given tasks.
 *
 * @todo support remote metadata fetching
 * @todo support duplicate detection
 * @too add logging
 * @todo support multiple tasks
 * @todo support command line support
 */
class MusicOrganizer
{
    /**
     * @var string
     */
    private $sourceDirectory;

    /**
     * @var string
     */
    private $destinationDirectory;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct()
    {
        // @todo move to config
        $musicDir = '/home/lucasvanlierop/Music/Rock';
        define('ROOT_DIR', $musicDir);
        $this->destinationDirectory = $musicDir;
        $this->sourceDirectory = $musicDir;

        $this->config = require_once 'config/config.php';
    }

    /**
     * @return void
     */
    public function run()
    {
        $sourceDirectory = new \MediaOrganizer\Directory($this->sourceDirectory);
        $fileVisitor = new FileVisitor(ROOT_DIR, $this->config);
        $sourceDirectory->accept($fileVisitor);
        //$sourceDirectory->RemoveEmptyDirs();
    }
}
