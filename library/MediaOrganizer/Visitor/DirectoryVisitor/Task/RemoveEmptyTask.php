<?php
namespace Visitor\DirectoryVisitor\Task;

use MediaOrganizer\Directory;

/**
 * @todo make this work
 */
class RemoveEmptyTask
{
    public function execute(Directory $directory)
    {
        foreach ($directory as $file) {
            $this->handleFile($file);
        }
    }

    private function handleFile($file)
    {
        // @todo fix, get from file
        $srcDir = '';

        if ($file[0] == '.') {
            return;
        }

        $curPath = $srcDir . $file . '/';

        if (!is_dir($curPath)) {
            $fileInfo = pathinfo($curPath);

            $extension = strtolower($fileInfo['extension']);

            if ($extension != 'mp3') {
            }
            $this->removeFile($file, $extension, $srcDir, $curPath);
        }

        if (is_dir($curPath)) {
            _removeEmptyDirs($curPath);
        }

        $fileList = scandir($srcDir);

        if (2 == count($fileList)) {
            echo "\n Removing empty dir " . $srcDir;
            //printr($fileList);
            rmdir($srcDir);
            //exit;
        }
    }

    /**
     * @param $file
     * @param $extension
     * @param $srcDir
     * @param $curPath
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function removeFile($file, $extension, $srcDir, $curPath)
    {
        $unwantedFileExtensions = [
            'ini',
            'db',
            'docx',
            'bmp',
            'png',
            'log',
            'jpg',
            'm3u',
            'nfo',
            'sfv',
            'gif',
            'url',
            'wpl'
        ];

        if (in_array($extension, $unwantedFileExtensions)) {
            echo "\nRemoving file : " . $srcDir . $file;
            unlink($srcDir . $file);
        }

        if ($extension === 'mp3') {
            return;
        }

        echo "\nWeird file" . $curPath;
    }
}
