<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pimgdir, $pcurrpage, $psortmode, $psortorder)
{
    $treviews = new BLLReviewList();
    $treviews->listname = "User Reviews";
    $treviews->listcontents = jsonLoadAllReview();

    // We need to sort the squad using our custom class-based sort function
    $tsortfunc = "";
    if ($psortmode == "score")
        $tsortfunc = "gamesortbyscore";
    else if ($psortmode == "gametitle")
        $tsortfunc = "gamesortbygametitle";

    // Only sort the array if we have a valid function name
    if (! empty($tsortfunc))
        usort($treviews->listcontents, $tsortfunc);

    // The pagination working out how many elements we need and
    $tnoitems = sizeof($treviews->listcontents);
    $tperpage = 5;
    $tnopages = ceil($tnoitems / $tperpage);

    // Create a Paginated Array based on the number of items and what page we want.
    $tfilterreviewlist = appPaginateArray($treviews->listcontents, $pcurrpage, $tperpage);
    $treviews->listcontents = $tfilterreviewlist;

    // Render the HTML for our Table and our Pagination Controls
    $treviewlisttable = renderReviewTable($treviews);
    $tpagination = renderPagination($_SERVER['PHP_SELF'], $tnopages, $pcurrpage);

    $tcontent = <<<PAGE
<div class="container">
	<div class="header clearfix">
		<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">All User Reviews</li>
		</ul>
		<div class="row">

		</div>
		<div class="row">
			<div class="panel panel-primary">
			<div class="panel-body">
				<h2>Results</h2>
				<p>{$treviews->listname}</p>
				<div id="games-table">
			    {$treviewlisttable}
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

$tpagetitle = "All User Reviews";
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