<?php
namespace MediaOrganizer\Visitor\Task;

use MediaOrganizer\File;

/**
 * @todo make this work with at least discogs
 */
class AddMetaDataTask
{
    public function execute(File $file) {

    }

    /**
     * Creates comparable name for search reasons
     *
     * @param $name
     * @return string
     */
    protected function _createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }
}
