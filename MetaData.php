<?php
class MusicOrganizer_MetaData
{
    private $_info;

    public function __construct($fileName) {
        $id3 = $this->_getId3Instance();

      //  echo PHP_EOL . 'FN: ' . $fileName;
        $this->_info = $id3->analyze($fileName);
        getid3_lib::CopyTagsToComments($this->_info);
    }

    protected function _getId3Instance()
    {
        static $id3Instance;

        if (!is_object($id3Instance)) {
            require_once('vendor/getid3/getid3.php');
            /*
            $getID3->setOption(array('encoding'=>$TaggingFormat));
            getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);

            $tagwriter = new getid3_writetags;
            $tagwriter->filename       = $Filename;
            $tagwriter->tagformats     = $TagFormatsToWrite;
            $tagwriter->overwrite_tags = false;
            $tagwriter->tag_encoding   = $TaggingFormat;
            */

            $id3Instance = new getID3;
            $id3Instance->setOption('md5_data', true);
     //       $id3Instance->setOption('md5_data_source', true); // where is this for?
    		$id3Instance->setOption('encoding', 'UTF-8');
        }

        return $id3Instance;
    }

    public function getHash() {
        $id3 = $this->_getId3Instance();
        $id3->getHashData('md5');
        $hash = $id3->info['md5_data'];
        return $hash;
    }

    public function mapGenre($s_genre)
    {
        $s_genre = preg_replace('[^a-z]+', '', strtolower($s_genre));

        switch (true) {
            case preg_match('/ambient/', $s_genre) :
                $s_genre = 'dance';
                break;
            case preg_match('/alt|emo|wave|hippie|indie|rock/', $s_genre) :
                $s_genre = 'rock';
                break;
            case preg_match('/blues/', $s_genre) :
                $s_genre = 'blues';
                break;
            case preg_match('/folk|ethnic|celtic/', $s_genre) :
                $s_genre = 'folk';
                break;
            case preg_match('/country/', $s_genre) :
                $s_genre = 'country';
                break;
            // @todo correct
            case preg_match('/emo|ska|punk/', $s_genre) :
                $s_genre = 'punk';
                break;
            case preg_match('/rap|hiphop|nederhop|r&b|swingbeat/', $s_genre) :
                $s_genre = 'Hip-Hop_-_R&B';
                break;
            case preg_match('/metal/', $s_genre) :
                $s_genre = 'metal';
                break;
            case preg_match('/nederlands/', $s_genre) :
                $s_genre = 'nederlands';
                break;
            case preg_match('/reggae/', $s_genre) :
                $s_genre = 'reggae';
                break;
            case preg_match('/singer|songwriter|solo/', $s_genre) :
                $s_genre = 'singer_songwriter';
                break;
            case preg_match('/dance|house|rave|techno|club|electro/', $s_genre) :
                $s_genre = 'dance';
                break;
            case preg_match('/piratenfeest/', $s_genre) :
                $s_genre = 'feest';
                break;
            case preg_match('/pop|top/', $s_genre) :
                $s_genre = 'pop';
                break;
            case preg_match('/scandinavian/', $s_genre) :
                $s_genre = 'scandinavian';
                break;
            default :
                throw new Exception('Could not map genre ' . $s_genre);
                break;
        }
        return $s_genre;
    }

    public function guessIsCompilation($albumTitle)
    {

    }

    public function buildArtist()
    {
        if (empty($this->_info['comments']['artist'])) {
            return;
        }

        $s_artist = $this->_info['comments']['artist'][0];
        // @todo move this to rename filter
        if (substr($s_artist, 0, 4) == 'The-') $s_artist = substr($s_artist, 4) . '_(the)';
        return $s_artist;
    }

    public function buildTitle() {
        if (empty($this->_info['comments']['title'])) {
            return;
        }

        $s_title = $this->_info['comments']['title'][0];
        return $s_title;
    }

    public function buildGenre() {
        if (empty($this->_info['comments']['genre'])) {
            return;
        }

        // Todo check clean name for genre
        $s_genre = $this->_info['comments']['genre'][0];
        return $s_genre;
    }

    public function buildAlbum()
    {
        if (empty($this->_info['comments']['album'])) {
            return;
        }

        $s_album = '';
        if (!empty($this->_info['comments']['album'][0])) {
            $s_album = $this->_info['comments']['album'][0];
            // @todo move this to rename filter
            if (!empty($this->_info['comments']['year'][0]) && preg_match('/\d{4}/', $this->_info['comments']['year'][0])) {
                $s_album = $this->_info['comments']['year'][0] . '-' . $s_album;
            }
        }

        return $s_album;
    }

    public function buildTrackNr()
    {
        if (empty($this->_info['comments']['track_number'])) {
            return;
        }

        $i_track = !isset($this->_info['comments']['track_number']) ? ''
            : intval(trim(reset($this->_info['comments']['track_number'])));
        $s_track = str_pad($i_track, 3, '0', STR_PAD_LEFT);
        return $s_track;
    }


    /**
     * @todo improve this
     */
    public function getComments() {


        return $this->_info['comments'];
    }

}