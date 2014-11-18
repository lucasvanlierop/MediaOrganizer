<?php
namespace MediaOrganizer;

class GenreToDirMapper
{
    /**
     * @var array
     */
    private $knownDirs;

    /**
     * @var array
     */
    private $genreToDirMapping;

    /**
     * @param array $genreConfig
     */
    public function __construct(array $genreConfig) {
        $this->knownDirs = $genreConfig['knownDirs'];
        $this->genreToDirMapping = $genreConfig['dirMapping'];
    }

    /**
     * @param $s_genre
     * @return string
     * @throws \Exception
     */
    public function toDir($s_genre)
    {
        return;

        if (empty($s_genre)) {
            throw new \Exception("Could not map empty genre");
        }

        $simplifiedGenre = preg_replace('/[^a-z]*/', '', strtolower($s_genre));
        foreach($this->genreToDirMapping as $genre => $dir) {
            if ($genre == $simplifiedGenre  ) {
                if (!$this->isKnownDir($dir)) {
                    throw new \Exception("Uknown dir '{$dir}'' configured");
                }

                return $dir;
            }
        }

        throw new \Exception("Could not map genre '{$s_genre}'");
    }

    /**
     * @param string $dir
     * @return bool
     */
    private function isKnownDir($dir)
    {
        return in_array($dir, $this->knownDirs);
    }
}