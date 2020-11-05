<?php

class BLLGame
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $gametitle;

    public $agerating;

    public $releasedate;

    public $developer;

    public $genre;

    public $platforms;

    public $publisher;

    public $score;

    public $userscore;

    public $desc_href;

    public $metacriticlink;

    public $ignlink;

    public $gamespotlink;

    public $gameshoplink;

    public $amazonlink;

    public $psstorelink;

    public $gameprice;

    public $amazonprice;

    public $psstoreprice;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLReview
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $reviewtitle;

    public $gametitle;

    public $reviewcontent;

    public $reviewscore;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLReviewList
{

    // class fields
    public $id = null;

    public $listcontents;

    public $listname;

    public $gametitle;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLGameList
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $istcontents;

    public $listname;

    public $scoreindex;

    public $genreindex;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLConsole
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $name;

    public $releasedate;

    public $manufacturer;

    public $storage;

    public $maxresolution;

    public $maxrefreshrate;

    public $backwardscompatible;

    public $imgsrc;

    public $rrp;

    public $rrplink;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLNewsItem
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $heading;

    public $tagline;

    public $thumb_href;

    public $img_href;

    public $item_href;

    public $summary;

    public $content;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

?>