<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;
use MediaOrganizer\File\NameFilter\CompilationTrackFilter;
use MediaOrganizer\File\NameFilter\AlbumTrackFilter;
use MediaOrganizer\File\NameFilter\SingleTrackFilter;

class RenameTask
    implements TaskInterface
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param string $rootDir
     * @param array $config
     */
    public function __construct($rootDir, array $config)
    {
        $this->rootDir = $rootDir;

        $genreToDirMapper = new \MediaOrganizer\GenreToDirMapper($config['genre']);

        // Filters in order of importance (most complex one first)
        $this->filters = array(
            new CompilationTrackFilter($this->rootDir, $genreToDirMapper),
            new AlbumTrackFilter($this->rootDir, $genreToDirMapper),
            new SingleTrackFilter($this->rootDir, $genreToDirMapper)
        );
    }

    /**
     * Tries to rename file
     *
     * @param \MediaOrganizer\File $file
     */
    public function execute(File $file)
    {
        foreach($this->filters as $fileNameFilter) {
            $filePath = $fileNameFilter->filter($file);
            if ($filePath) {
                echo "name provided by " . get_class($fileNameFilter) . PHP_EOL;

                try {
                    $file->rename($filePath);
                } catch (\Exception $ex) {
                    echo 'Error: ' . $ex->getMessage() . PHP_EOL;
                }
                return;
            }
        }
    }
}