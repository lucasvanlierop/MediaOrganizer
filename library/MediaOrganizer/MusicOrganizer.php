<?php
namespace MediaOrganizer;

use MediaOrganizer\File\NameFilter\CompilationTrackFilter;
use MediaOrganizer\File\NameFilter\AlbumTrackFilter;
use MediaOrganizer\File\NameFilter\SingleTrackFilter;

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
     * @var array
     */
    private $config;

    /**
     * @var GenreToDirMapper;
     */
    private $genreToDirMapper;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->genreToDirMapper = new GenreToDirMapper($config['directories']);
    }

    /**
     * @param string $sourceDirectoryPath
     * @return void
     */
    public function run($sourceDirectoryPath)
    {
        // Filters in order of importance (most complex one first)
        $filters = array(
            new CompilationTrackFilter($sourceDirectoryPath, $this->genreToDirMapper),
            new AlbumTrackFilter($sourceDirectoryPath, $this->genreToDirMapper),
            new SingleTrackFilter($sourceDirectoryPath, $this->genreToDirMapper)
        );

        $sourceDirectory = new Directory($sourceDirectoryPath);
        $fileVisitor = new FileVisitor($sourceDirectory, $this->config, $filters);
        $sourceDirectory->accept($fileVisitor);
        //$sourceDirectory->RemoveEmptyDirs();
    }
}
