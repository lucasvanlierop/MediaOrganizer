<?php
namespace MediaOrganizer;

/**
 * Maps a given genre to a directory
 *
 * Class GenreToDirMapper
 * @package MediaOrganizer
 */
class GenreToDirMapper
{
    /**
     * @var array
     */
    private $directories;

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @param array $directories
     * @param string $rootDirectory
     */
    public function __construct(array $directories, $rootDirectory)
    {
        $this->directories = $directories;
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * @param string $genre
     * @param string $filePath
     * @return string
     */
    public function toDir($genre, $filePath)
    {
        $simplifiedGenre = preg_replace('/[^a-z]*/', '', strtolower($genre));
        foreach ($this->directories as $dir => $acceptedGenres) {
            if (in_array($simplifiedGenre, $acceptedGenres)) {
                return $dir;
            }
        }

        // Fallback on current dir
        $dirPath = substr($filePath, strlen($this->rootDirectory) + 1);
        while (!array_key_exists($dirPath, $this->directories)) {
            $dirPath = substr($dirPath, 0, strrpos($dirPath, DIRECTORY_SEPARATOR));
            echo "Trying: {$dirPath}" . PHP_EOL;
            if ($dirPath === '') {
                return;
            }
        }

        return $dirPath;
    }
}
