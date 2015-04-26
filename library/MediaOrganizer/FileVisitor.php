<?php
namespace MediaOrganizer;

use MediaOrganizer\Visitor\FileVisitor\Task\AddMetaDataTask;
use MediaOrganizer\Visitor\FileVisitor\Task\RenameTask;

/**
 * Visits files in a collection
 *
 * Class FileVisitor
 * @package MediaOrganizer
 */
class FileVisitor
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $currentGenreDir;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param string $rootDir
     * @param array $config
     * @param array $filters
     */
    public function __construct($rootDir, array $config, array $filters)
    {
        $this->rootDir = $rootDir;
        $this->config = $config;
        $this->filters = $filters;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param Visitable $file
     * @return void
     */
    public function visit(Visitable $file)
    {
        if ($file instanceof Directory) {
            $this->visitDir($file);
        } else if ($file instanceof File) {
            $this->visitFile($file);
        }
    }

    /**
     * @param Directory $dir
     * @return boolean
     */
    protected function visitDir(Directory $dir)
    {
        $relativePath = str_replace($this->rootDir, '', $dir->getPath());

        // @todo convert this to filter
        if ($relativePath[0] == '_') {
            return false;
        }

        // Update current genre dir
        if (in_array($relativePath, $this->config['genre']['knownDirs'])) {
            $this->currentGenreDir = $relativePath;
        }
        echo "scanning dir: " . $dir->getPath() . "\n";
    }

    /**
     * @param File $file
     * @return void
     */
    protected function visitFile(File $file)
    {
        echo "scanning file: " . $file->getPath() . "\n";

        $tasks = array(
            new AddMetaDataTask(),
            new RenameTask($this->filters)
        );

        /** @var $task \MediaOrganizer\Visitor\FileVisitor\Task\TaskInterface */
        foreach ($tasks as $task) {
            $task->execute($file);
        }
    }
}
