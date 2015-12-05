<?php
namespace MediaOrganizer\Visitor\FileVisitor\Task;

use MediaOrganizer\Directory;
use MediaOrganizer\File;

/**
 * Class HashTask
 * @package MediaOrganizer\Visitor\FileVisitor\Task
 */
class HashTask implements TaskInterface
{
    /**
     * @var Directory
     */
    private $rootDir;

    /**
     * HashTask constructor.
     *
     * @param Directory $rootDir
     */
    public function __construct(Directory $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return string
     */
    private function getHashDir()
    {
        return $this->rootDir->getPath() . DIRECTORY_SEPARATOR . '_hashes';
    }

    /**
     * @param File $file
     * @return void
     */
    protected function createAudioHashSoftLink(File $file)
    {
        $hash = $file->getMetaData()->getContentHash();
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
        $hashDir = $this->getHashDir();
        if (!is_dir($hashDir)) {
            mkdir($hashDir);
        }

        $hashLink = $this->getHashDir() . DIRECTORY_SEPARATOR . $file->getContentHash();
        if ($this->isLinkUpToDate($file, $hashLink)) {
            return;
        }

        symlink($file->getPath(), $hashLink);
    }

    /**
     * @param File   $file
     * @param string $hashLink
     * @return boolean
     */
    private function isLinkUpToDate(File $file, $hashLink)
    {
        return is_link($hashLink) && realpath($hashLink) === realpath($file->getPath());
    }
}
