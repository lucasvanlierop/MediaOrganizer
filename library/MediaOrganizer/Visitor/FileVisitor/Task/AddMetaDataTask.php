<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

/**
 * Adds metadata
 *
 * Class AddMetaDataTask
 * @package MediaOrganizer\Visitor\FileVisitor\Task
 */
class AddMetaDataTask
{
    /**
     * @param File $file
     * @return void
     */
    public function execute(File $file)
    {
    }

    /**
     * Creates comparable name for search reasons
     *
     * @param string $name
     * @return string
     */
    protected function createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }
}
