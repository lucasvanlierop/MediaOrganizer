<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

/**
 * Definition for tasks that can be executed on a file or directory.
 *
 * Interface TaskInterface
 * @package MediaOrganizer\Visitor\FileVisitor\Task
 */
interface TaskInterface
{
    /**
     * @param File $file
     * @return void
     */
    public function execute(File $file);
}
