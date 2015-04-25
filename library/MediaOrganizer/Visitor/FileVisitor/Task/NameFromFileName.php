<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

/**
 * Extracts name from filename
 *
 * @todo make this work with at least discogs
 */
class NameFromFileName
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
