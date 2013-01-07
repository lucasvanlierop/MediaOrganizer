<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

interface TaskInterface
{
    public function execute(File $file);
}
