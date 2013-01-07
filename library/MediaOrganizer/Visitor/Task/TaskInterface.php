<?php
namespace MediaOrganizer\Visitor\Task;

use MediaOrganizer\File;

interface TaskInterface
{
    public function execute(File $file);
}
