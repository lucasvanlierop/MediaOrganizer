<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

/**
 * Class RenameTask
 * @package MediaOrganizer\Visitor\FileVisitor\Task
 */
class RenameTask implements TaskInterface
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @param array $filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Tries to rename file
     *
     * @param File $file
     * @return void
     */
    public function execute(File $file)
    {
        echo "Iterating over filters" . PHP_EOL;
        foreach ($this->filters as $fileNameFilter) {
            echo "Starting filter " . get_class($fileNameFilter) . PHP_EOL;
            $filePath = $fileNameFilter->filter($file);
            echo "Filepath: ";
            var_dump($filePath);
            echo PHP_EOL;
            if (!$filePath) {
                echo "NO name provided by " . get_class($fileNameFilter) . PHP_EOL;
                continue;
            }

            if ($filePath === $file->getPath()) {
                echo "No renaming necessary: " . $filePath . " and " . $file->getPath() . "match" . PHP_EOL;
                // File does not have to be renamed
                continue;
            }

            echo "name provided by " . get_class($fileNameFilter) . PHP_EOL;

            $newName = $filePath;

            $force = false;
            if (file_exists($newName)) {
                // @todo improve hack
                $ext = $file->getExtension();
                $hash = sha1(file_get_contents($newName));
                $newName = str_replace($ext, $hash . '.' . $ext, $newName);
                $force = true;
            }

            try {
                $file->rename($newName, $force);
            } catch (\Exception $ex) {
                echo 'Error: ' . $ex->getMessage() . PHP_EOL;
            }

            return;
        }
    }
}
