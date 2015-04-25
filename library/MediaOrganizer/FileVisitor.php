<?php
namespace MediaOrganizer;

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
     * @param string $rootDir
     * @param array $config
     */
    public function __construct($rootDir, array $config)
    {
        $this->rootDir = $rootDir;

        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param stdClass $file
     * @return void
     */
    public function visit(stdClass $file)
    {
        if ($file instanceof \MediaOrganizer\Directory) {
            $this->visitDir($file);
        } else if ($file instanceof \MediaOrganizer\File) {
            $this->visitFile($file);
        }
    }

    /**
     * @param \MediaOrganizer\Directory $dir
     * @return boolean
     */
    protected function visitDir(\MediaOrganizer\Directory $dir)
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
//        echo "scannining dir: " . $dir->getPath() . "\n";
    }

    /**
     * @param \MediaOrganizer\File $file
     * @return void
     */
    protected function visitFile(\MediaOrganizer\File $file)
    {
        //echo "scannining file: " . $file->getPath() . "\n";

        $tasks = array(
            new \MediaOrganizer\Visitor\FileVisitor\Task\AddMetaDataTask(),
            new \MediaOrganizer\Visitor\FileVisitor\Task\RenameTask($this->getRootDir(), $this->config)
        );

        /** @var $task \MediaOrganizer\Visitor\FileVisitor\Task\TaskInterface */
        foreach ($tasks as $task) {
            $task->execute($file);
        }
    }
}
