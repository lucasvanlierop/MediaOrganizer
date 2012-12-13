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

        $fileNameFilters = array(
            new \MediaOrganizer\File\NameFilter\CompilationTrackFilter($this->getRootDir()),
            new \MediaOrganizer\File\NameFilter\AlbumTrackFilter($this->getRootDir()),
            new \MediaOrganizer\File\NameFilter\SingleTrackFilter($this->getRootDir())
        );

        // Try to rename file
        foreach($fileNameFilters as $fileNameFilter) {
            $filePath = $fileNameFilter->filter($file);
            if ($filePath) {
                echo "name provided by " . get_class($fileNameFilter) . PHP_EOL;

                try {
                    $file->rename($filePath);
                } catch (Exception $ex) {
                    echo 'Error: ' . $ex->getMessage() . PHP_EOL;
                }
            }
        }
    }
}