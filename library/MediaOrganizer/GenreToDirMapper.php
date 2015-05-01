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
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @param string $genre
     * @return string
     * @throws \Exception
     */
    public function toDir($genre)
    {
        if (empty($genre)) {
            throw new \Exception("Could not map empty genre");
        }

        $simplifiedGenre = preg_replace('/[^a-z]*/', '', strtolower($genre));
        foreach ($this->directories as $dir => $acceptedGenres) {
            if (in_array($simplifiedGenre, $acceptedGenres)) {
                return $dir;
            }
        }

        throw new \Exception("Could not map genre '{$genre}'");
    }
}
