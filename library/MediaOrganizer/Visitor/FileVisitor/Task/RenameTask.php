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
        $filePath = $this->findPath($file);

        if ($filePath === $file->getPath()) {
            echo "No renaming necessary: " . $filePath . " and " . $file->getPath() . "match" . PHP_EOL;
            // File does not have to be renamed
            return;
        }

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
            echo 'Could not rename file: ' . $ex->getMessage() . PHP_EOL;
        }

        return;
    }

    /**
     * @param File $file
     * @return mixed
     * @throws \RuntimeException
     */
    private function findPath(File $file)
    {
        foreach ($this->filters as $fileNameFilter) {
            echo "Starting filter " . get_class($fileNameFilter) . PHP_EOL;
            $filePath = $fileNameFilter->filter($file);

            if ($filePath) {
                return $filePath;
            }
        }

        throw new \RuntimeException("No filter matched this file");
    }
}
