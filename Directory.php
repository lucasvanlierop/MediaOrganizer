<?php
class MusicOrganizer_Directory extends DirectoryIterator
{
    /**
     * TODO: Add support for compilations
     * TODO: Add support for disc nrs
     * todo add support for unicode
     */
    public function convert()
    {
        $sourceDirectory = $this->getPathName();

        echo "scannining dir: " . $sourceDirectory . "\n";

        foreach ($this as $file) {
            if(!$this->_convertEntry($file)) {
                continue;
            }
        }
    }

    protected function _convertEntry($file) {
        if($file->isDot()) {
            return false;
        }

        if ($file->isDir()) {
            return $this->_convertDir($file);
        } else if ($file->isFile()) {
            return $this->_convertFile($file);
        }
    }

    protected function _convertDir($file) {
        // @todo convert this to filter
        $directoryName = $file->getFilename();
        if ($directoryName[0] == '_') {
            return false;
        }

        $directory = new self($file->getPathName());
        $directory->convert();
    }

    protected function _convertFile($file) {
        try {
           $file = MusicOrganizer_File::factory($file->getPathName());
        } catch (Exception $ex) {
           return false;
        }

        if (!$file->parse()) {
            return false;
        }
    }

    protected function _removeEmptyChildDirectories($srcDir)
    {
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