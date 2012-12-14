<?php
namespace MediaOrganizer;

class GenreToDirMapper
{
    private $knownDirs = array(
        'Blues',
        'Disco',
        'Folk',
        'Icelandic-Scandinavian',
        'Oldies',
        'Pop',
        '80s',
        'Componisten-Klassiek',
        'Diversen',
        'Funk-Soul',
        'Jazz',
        'Metal',
        'Punk-Ska-Emo',
        'Rock',
        'Apart',
        'Dance\AcidHouse',
        'Dance\Breakbeat',
        'Dance\Club',
        'Dance\Deephouse-Progressive',
        'Dance\Diversen',
        'Dance\Electro',
        'Dance\Eurodance',
        'Dance\Happy_Hardcore',
        'Dance\Hardcore-Hardstyle-jump',
        'Dance\Houseclassics',
        'Dance\House-Techno',
        'Dance\Jungle-DrumBass-Dubstep',
        'Dance\Lounge',
        'Dance\Minimal',
        'Dance\Rave',
        'Dance\Trance',
        'Dance\Triphop',
        'Feest',
        'Hiphop-RB',
        'SingerSongwriter-Solo-Rustig'
    );

    /**
     * @todo support subgenres for Dance
     */
    private $genreToDirMapping = array(
        'ambient' => 'Dance/Ambient',
        'alt' => 'Rock',
        'wave' => 'Rock',
        'hippie' => 'Rock',
        'indie' => 'Rock',
        'rock' => 'Rock',
        'blues' => 'Blues',
        'folk' => 'Folk',
        'ethnic' => 'Folk',
        'celtic' => 'Folk',
        'country' => 'Country',
        'emo' => 'Punk',
        'ska' => 'Punk',
        'punk' => 'Punk',
        'rap' => 'Hiphop-RB',
        'hiphop' => 'Hiphop-RB',
        'nederhop' => 'Hiphop-RB',
        'r&b' => 'Hiphop-RB',
        'swingbeat' => 'Hiphop-RB',
        'metal' => 'Metal',
        'nederlands' => 'Nederlandstalig',
        'reggae' => 'Reggae',
        'singersongwriter' => 'Singer_songwriter-solo',
        'dance' => 'Dance/Diversen',
        'house' => 'Dance/House-Techno',
        'rave' => 'Dance/Rave',
        'techno' => 'Dance/House-Techno',
        'club' => 'Dance/Club',
        'electro' => 'Dance/Electro',
        'piratenfeest' => 'Feest',
        'schlager' => 'Feest',
        'pop' => 'Pop',
        'scandinavian' => 'Scandinavian',
    );

    /**
     * @param $s_genre
     * @return string
     * @throws \Exception
     */
    public function toDir($s_genre)
    {
        if (empty($s_genre)) {
            throw new \Exception("Could not map empty genre");
        }

        $simplifiedGenre = preg_replace('/[^a-z]*/', '', strtolower($s_genre));
        foreach($this->genreToDirMapping as $genre => $dir) {
            if ($genre == $simplifiedGenre  ) {
                if (!in_array($dir, $this->knownDirs)) {
                    throw new \Exception("Uknown dir '{$dir}'' configured");
                }

                return $dir;
            }
        }

        throw new \Exception("Could not map genre '{$s_genre}'");
    }
}