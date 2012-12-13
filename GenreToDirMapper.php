<?php
namespace MediaOrganizer;

class GenreToDirMapper
{
    public function toDir($s_genre)
    {
        $s_genre = preg_replace('/[^a-z]*/', '', strtolower($s_genre));

        switch (true) {
            case preg_match('/ambient/', $s_genre) :
                $s_genre = 'Dance';
                break;
            case preg_match('/alt|emo|wave|hippie|indie|rock/', $s_genre) :
                $s_genre = 'Rock';
                break;
            case preg_match('/blues/', $s_genre) :
                $s_genre = 'Blues';
                break;
            case preg_match('/folk|ethnic|celtic/', $s_genre) :
                $s_genre = 'Folk';
                break;
            case preg_match('/country/', $s_genre) :
                $s_genre = 'Country';
                break;
            // @todo correct
            case preg_match('/emo|ska|punk/', $s_genre) :
                $s_genre = 'Punk';
                break;
            case preg_match('/rap|hiphop|nederhop|r&b|swingbeat/', $s_genre) :
                $s_genre = 'Hip-Hop_-_R&B';
                break;
            case preg_match('/metal/', $s_genre) :
                $s_genre = 'Metal';
                break;
            case preg_match('/nederlands/', $s_genre) :
                $s_genre = 'Nederlandstalig';
                break;
            case preg_match('/reggae/', $s_genre) :
                $s_genre = 'Reggae';
                break;
            case preg_match('/singer|songwriter|solo/', $s_genre) :
                $s_genre = 'Singer_songwriter';
                break;
            case preg_match('/dance|house|rave|techno|club|electro/', $s_genre) :
                $s_genre = 'Dance';
                break;
            case preg_match('/piratenfeest/', $s_genre) :
                $s_genre = 'Feest';
                break;
            case preg_match('/pop|top/', $s_genre) :
                $s_genre = 'Pop';
                break;
            case preg_match('/scandinavian/', $s_genre) :
                $s_genre = 'Scandinavian';
                break;
            default :
                throw new \Exception('Could not map genre ' . $s_genre);
                break;
        }
        return $s_genre;
    }

}
