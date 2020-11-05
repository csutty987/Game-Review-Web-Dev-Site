<?php
// Include the Other Layers Class Definitions
require_once ("oo_bll.inc.php");
require_once ("oo_pl.inc.php");

// ---------JSON HELPER FUNCTIONS-------------------------------------------------------
function jsonOne($pfile, $pid)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek($pid - 1);
    $tdata = json_decode($tsplfile->current());
    return $tdata;
}

function jsonAll($pfile)
{
    $tentries = file($pfile);
    $tarray = [];
    foreach ($tentries as $tentry) {
        $tarray[] = json_decode($tentry);
    }
    return $tarray;
}

function jsonNextID($pfile)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek(PHP_INT_MAX);
    return $tsplfile->key() + 1;
}

// ---------ID GENERATION FUNCTIONS-------------------------------------------------------
function jsonNextReviewID()
{
    return jsonNextID("data/json/reviews.json");
}

// ---------JSON-DRIVEN OBJECT CREATION FUNCTIONS-----------------------------------------
function jsonLoadOneGame($pid): BLLGame
{
    $tgame = new BLLGame();
    $tgame->fromArray(jsonOne("data/json/games.json", $pid));

    if (! empty($tgame->desc_href)) {
        $tgame->desc = file_get_contents("data/html/{$tgame->desc_href}");
    }
    return $tgame;
}

function jsonLoadOneReview($pid): BLLReview
{
    $treview = new BLLReview();
    $treview->fromArray(jsonOne("data/json/reviews.json", $pid));

    return $treview;
}

function jsonLoadOneConsole($pid): BLLConsole
{
    $tconsole = new BLLConsole();
    $tconsole->fromArray(jsonOne("data/json/consoles.json", $pid));
    return $tconsole;
}

function jsonLoadOneNewsItem($pid): BLLNewsItem
{
    $tni = new BLLNewsItem();
    $tni->fromArray(jsonOne("data/json/newsitems.json", $pid));
    return $tni;
}

// --------------MANY OBJECT IMPLEMENTATION--------------------------------------------------------
function jsonLoadAllGame(): array
{
    $tarray = jsonAll("data/json/games.json");
    return array_map(function ($a) {
        $tc = new BLLGame();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

function jsonLoadAllReview(): array
{
    $tarray = jsonAll("data/json/reviews.json");
    return array_map(function ($a) {
        $tc = new BLLReview();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

function jsonLoadAllNewsItems(): array
{
    $tarray = jsonAll("data/json/newsitems.json");
    return array_map(function ($a) {
        $tc = new BLLNewsItem();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

// ---------XML HELPER FUNCTIONS--------------------------------------------------------
function xmlLoadAll($pxmlfile, $pclassname, $parrayname)
{
    $txmldata = simplexml_load_file($pxmlfile, $pclassname);
    $tarray = [];
    foreach ($txmldata->{$parrayname} as $telement) {
        $tarray[] = $telement;
    }
    return $tarray;
}

function xmlLoadOne($pxmlfile, $pclassname)
{
    $txmldata = simplexml_load_file($pxmlfile, $pclassname);
    return $txmldata;
}

?>