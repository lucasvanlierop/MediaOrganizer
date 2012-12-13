<?php
namespace MediaOrganizer;

/**
 * TODO: Add support for compilations
 * TODO: Add support for disc nrs
 * todo add support for unicode
 */
class FileVisitor
{
    /**
     * @var string
     */
    private $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    public function visit($file) {
        if ($file instanceof \MusicOrganizer_Directory) {
            $this->visitDir($file);
        } else if ($file instanceof \MediaOrganizer\File) {
            $this->visitFile($file);
        }
    }

    /**
     * @param \MusicOrganizer_Directory $dir
     * @return bool
     */
    protected function visitDir(\MusicOrganizer_Directory $dir) {
        // @todo convert this to filter
        $directoryName = $dir->getFilename();
        if ($directoryName[0] == '_') {
            return false;
        }

        echo "scannining dir: " . $dir->getPath() . "\n";
    }

    /**
     * @param \MediaOrganizer\File $file
     * @return bool
     */
    protected function visitFile(\MediaOrganizer\File $file) {
        echo "scannining file: " . $file->getPath() . "\n";

        if (!$file->parse($this->getRootDir())) {
            return false;
        }
    }
}