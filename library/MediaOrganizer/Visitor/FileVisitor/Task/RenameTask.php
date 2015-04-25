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
        foreach ($this->filters as $fileNameFilter) {
            $filePath = $fileNameFilter->filter($file);
            if (!$filePath) {
                echo "NO name provided by " . get_class($fileNameFilter) . PHP_EOL;
                continue;
            }

            if ($filePath === $file->getPath()) {
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
