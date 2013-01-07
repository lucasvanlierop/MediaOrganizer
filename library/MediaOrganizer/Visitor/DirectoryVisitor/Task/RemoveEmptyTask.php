<?php
namespace Visitor\DirectoryVisitor\Task;

use MediaOrganizer\Directory;

/**
 * @todo make this work
 */
class RemoveEmptyTask
{
    public function execute(Directory $directory) {

        //echo "\nReading: " . $srcDir;
        $files = scandir($srcDir);
        foreach ($files as $file) {
            if ($file[0] == '.') {
                continue;
            }

            $curPath = $srcDir . $file . '/';

            if (!is_dir($curPath)) {
                $fileInfo = pathinfo($curPath);

                $extension = strtolower($fileInfo['extension']);

                if ($extension != 'mp3') {

                }
                // @todo move to config
                switch ($extension) {
                    case 'ini' :
                    case 'db' :
                    case 'docx' :
                    case 'bmp' :
                    case 'png' :
                    case 'log' :
                    case 'jpg' :
                    case 'm3u' :
                    case 'nfo' :
                    case 'sfv' :
                    case 'gif' :
                    case 'url' :
                    case 'wpl' :
                        echo "\nRemoving file : " . $srcDir . $file;
                        unlink($srcDir . $file);
                        break;
                    case 'mp3' :
                        break;
                    default :
                        echo "\nWeird file" . $curPath;
                        break;
                }
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
    }

}
