<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\File;

/**
 * Class HashTask
 * @package MediaOrganizer\Visitor\FileVisitor\Task
 * @todo make this work
 */
class HashTask implements TaskInterface
{
    /**
     * @return string
     */
    private function getHashDir()
    {
        return ROOT_DIR . '_hashes' . '/';
    }

    /**
     * @param File $file
     * @return void
     */
    protected function createAudioHashSoftLink(File $file)
    {
        $hash = $file->getHash();
        $command = 'ln -s "' . $file->getPath() . '" ' . $this->getHashDir() . $hash;
        exec($command);

        echo "\n" . $command;
    }

    /**
     * @param File $file
     * @return void
     */
    public function execute(File $file)
    {
//        $hashDir = ROOT_DIR . '_hashes' . '/';
//        $hash = $this->getMetaData()->getHash();
//        $hashLink = $hashDir . $hash;
//
//        if(file_exists($hashLink)){
//            echo "\n org file = " . realpath($hashLink);
//            exit;
//        } else {
//            echo "\n NO org file = " . $hashLink;
//
//        }
//
//        echo PHP_EOL;
    }
}
