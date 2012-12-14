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
        'Dance',
        'Feest',
        'Hiphop-RB',
        'SingerSongwriter-Solo-Rustig'
    );

    /**
     * @todo support subgenres for Dance
     */
    private $genreTokenToDirMapping = array(
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
        foreach($this->genreTokenToDirMapping as $token => $dir) {
            if (stristr($token, $simplifiedGenre)) {
                if (!in_array($dir, $this->knownDirs)) {
                    throw new \Exception("Uknown dir '{$dir}'' configured");
                }

                return $dir;
            }
        }

        throw new \Exception("Could not map genre '{$s_genre}'");
    }
}