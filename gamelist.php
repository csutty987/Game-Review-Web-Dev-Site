<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pimgdir, $pcurrpage, $psortmode, $psortorder)
{
    $tgames = new BLLGameList();
    $tgames->listname = "Sorted by Descending Order";
    $tgames->listcontents = jsonLoadAllGame();

    // We need to sort the list using our custom class-based sort function
    function gamesortbyscore($a, $b)
    {
        if ($a == $b)
            return 0;
        return ($a < $b) ? - 1 : 1;
    }
    $tsortfunc = "";
    if ($psortmode == "score")
        $tsortfunc = "gamesortbyscore";
    else if ($psortmode == "gametitle")
        $tsortfunc = "gamesortbygametitle";

    foreach ($tgames->listcontents as $tp) {
        $pscore[] = $tp->score;
    }
    // Only sort the array if we have a valid function name
    if (! empty($tsortfunc)) {

        usort($pscore, $tsortfunc);
    } else {
        usort($pscore, "gamesortbyscore");
    }

    // The pagination working out how many elements we need and
    $tnoitems = sizeof($tgames->listcontents);
    $tperpage = 5;
    $tnopages = ceil($tnoitems / $tperpage);

    // Create a Paginated Array based on the number of items and what page we want.
    $tfiltergamelist = appPaginateArray($tgames->listcontents, $pcurrpage, $tperpage);
    $tgames->listcontents = $tfiltergamelist;

    // Render the HTML for our Table and our Pagination Controls
    $tgamelisttable = renderGameTable($tgames);
    $tpagination = renderPagination($_SERVER['PHP_SELF'], $tnopages, $pcurrpage);

    $tcontent = <<<PAGE
<div class="container">
	<div class="header clearfix">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Highest Rated</li>
		</ul>
		<div class="row">

		</div>
		<div class="row">
			<div class="panel panel-primary">
			<div class="panel-body">
				<h2>Our Highest Rated Games</h2>
				<p>{$tgames->listname}</p>
				<div id="games-table">
			    {$tgamelisttable}
                {$tpagination}
		        </div>
		    </div>
			</div>
		</div>
		<div class="row">

		</div>
PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
$tcurrpage = $_REQUEST["page"] ?? 1;
$tcurrpage = is_numeric($tcurrpage) ? $tcurrpage : 1;
$tsortmode = $_REQUEST["sortmode"] ?? "";
$tsortorder = $_REQUEST["sortorder"] ?? "asc";

$tpagetitle = "Game Search Results";
$tpage = new MasterPage($tpagetitle);
$timgdir = $tpage->getPage()->getDirImages();

// Build up our Dynamic Content Items.
$tpagelead = "";
$tpagecontent = createPage($timgdir, $tcurrpage, $tsortmode, $tsortorder);
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>