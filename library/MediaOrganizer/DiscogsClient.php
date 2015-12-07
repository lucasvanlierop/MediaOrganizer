<?php

namespace MediaOrganizer;

/**
 * Connects to Discogs to find metadata
 */
class DiscogsClient
{
    /**
     * @param string $filename
     * @return void
     */
    public function findInfoByFilename($filename)
    {
        $cleanFilename = trim(preg_replace('/[^a-z0-9]+/', ' ', strtolower(substr($filename, 0, -4))));
        echo "\n" . $cleanFilename . "\n";

        $key = 'clHKGUtXhaPvaySimOKUpuWiZZrFRdIJehXKxdaI';
        $urlSuffix = 'f=xml&api_key=' . $key;
        $apiUrl = 'http://www.discogs.com/search?type=all&q=' . urlencode($cleanFilename) . '&' . $urlSuffix;

        $resultObject = $this->loadUrl($apiUrl);
        $item = $resultObject->searchresults->result[0];

        if (empty($item)) {
            return;
        }
        $detailUrl = strval($item->uri) . '?' . $urlSuffix;
        printr($detailUrl);
        $details = $this->loadUrl($detailUrl);

        $comparableFileName = $this->createComparableName($cleanFilename);

        // Find track
        $trackNr = 1;
        foreach ($details->release->tracklist->track as $track) {
            $title = strval($track->title);

            $comparableTitle = $this->createComparableName($title);
            echo "\n" . $comparableTitle . ' : ' . $comparableFileName . "\n";
            if (strstr($comparableFileName, $comparableTitle)) {
                //printr($track);
                $id3['title'][] = $title;
                $id3['track_number'][] = $trackNr;
            }

            $trackNr++;
        }

        if (!empty($id3['title'])) {
            $id3['artist'][] = strval($details->release->artists->artist->name);
            $id3['genre'][] = strval($details->release->styles->style);
            $id3['album'][] = strval($details->release->title);
            return $id3;
        }
    }

    /**
     * Creates comparable name for search reasons
     *
     * @param string $name
     * @return string
     */
    protected function createComparableName($name)
    {
        return trim(preg_replace('/[^a-z]+/', '', strtolower($name)));
    }
}

//$client = new DiscogsClient();
//$client->findInfoByFilename('test');
