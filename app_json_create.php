<?php
require_once ("api/api.inc.php");

function jsonCreateGameFormat($pfile)
{
    $tnewgame = new BLLGame();
    $tnewgame->id = 1;
    $tnewgame->gametitle = "";
    $tnewgame->agerating = "";
    $tnewgame->releasedate = "";
    $tnewgame->developer = "";
    $tnewgame->genre = "";
    $tnewgame->platforms = "";
    $tnewgame->publisher = "";
    $tnewgame->score = "";
    $tnewgame->userscore = "";
    $tnewgame->desc_href = "";
    $tnewgame->metacriticlink = "";
    $tnewgame->ignlink = "";
    $tnewgame->gamespotlink = "";
    $tnewgame->gameshoplink = "";
    $tnewgame->amazonlink = "";
    $tnewgame->psstorelink = "";
    $tnewgame->gameprice = "";
    $tnewgame->amazonprice = "";
    $tnewgame->psstoreprice = "";
    // $tnewgame->criticreviews = "";
    // $tnewgame->userreviews = "";
    // $tnewgame->gameimage = "";
    $tnewgame->publisher = "";
    $tdata = json_encode($tnewgame) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

function jsonCreateReviewFormat($pfile)
{
    $treview = new BLLReview();
    $treview->id = 1;
    $treview->reviewtitle = "";
    $treview->gametitle = "";
    $treview->reviewcontent = "";
    $treview->reviewscore = "";
    $tdata = json_encode($treview) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

function jsonCreateConsoleFormat($pfile)
{
    $tconsole = new BLLConsole();
    $tconsole->id = 1;
    $tconsole->name = "";
    $tconsole->releasedate = 0;
    $tconsole->manufacturer = "";
    $tconsole->storage = "";
    $tconsole->maxresolution = "";
    $tconsole->maxrefreshrate = 0.0;
    $tconsole->backwardscompatible = 0.0;
    $tconsole->imgsrc = "";
    $tconsole->rrp = "";
    $tconsole->rrplink = "";
    $tdata = json_encode($tconsole) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

function jsonCreateNewsItemsFormat($pfile)
{
    $tni = new BLLNewsItem();
    $tni->id = 1;
    $tni->heading = "";
    $tni->img_href = "news-mainXX.jpg";
    $tni->thumb_href = "news-mainXX.jpg";
    $tni->item_href = "niXX.part.html";
    $tni->content = "";
    $tni->tagline = "";
    $tni->summary = "";
    $tdata = json_encode($tni) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

// ---------Create JSON Files---------------------------------------------
// UNCOMMENT TO CREATE A NEW FILE
// jsonCreateGameFormat("data/json/games1.json");
// jsonCreateNewsItemsFormat("data/json/newsitems1.json");

?>