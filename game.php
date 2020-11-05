<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pgames)
{
    $tgameprofile = "";
    foreach ($pgames as $tp) {
        $tgameprofile .= renderGameOverview($tp);
    }

    $tcontent = <<<PAGE

    <div class="container">
	<div class="header clearfix">
<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
            <li class="active">{$tp->gametitle}</li>
		</ul>
    <div class="row">

    </div>
    <div class"col-md-8">
      {$tgameprofile}
        <div class="col-row">
            <h1></h1>
        </div>
    </div>
    <div id="review-table">

		        </div>
    </div>


PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tgames = [];
$tgametitle = $_REQUEST["gametitle"] ?? "";
$tgenre = $_REQUEST["genre"] ?? - 1;
$tid = $_REQUEST["id"] ?? - 1;

// Handle our Requests and Search for games using different methods
if (is_numeric($tid) && $tid > 0) {
    $tgame = jsonLoadOneGame($tid);
    $tgames[] = $tgame;
} else if (! empty($tgametitle)) {
    // Filter the name
    $tname = appFormProcessData($tgametitle);
    $tgamelist = jsonLoadAllGame();
    foreach ($tgamelist as $tp) {
        if (strtolower($tp->gametitle) === strtolower($tgametitle)) {
            $tgames[] = $tp;
        }
    }
} else if ($tgenre > 0) {
    $tgamelist = jsonLoadAllGame();
    foreach ($tgamelist as $tp) {
        if ($tp->genre === $tgenre) {
            $tgames[] = $tp;
            break;
        }
    }
}

// Page Decision Logic - game found?
// Doesn't matter the route of finding them
if (count($tgames) === 0) {
    appGoToError();
} else {
    // We've found our games
    $tpagecontent = createPage($tgames);
    $tpagetitle = "Game Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>