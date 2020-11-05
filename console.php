<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    $tconsole = jsonLoadOneConsole(1);

    $tconsolehtml1 = renderConsoleSummary($tconsole);

    $tconsole = jsonLoadOneConsole(2);

    $tconsolehtml2 = renderConsoleSummary($tconsole);
    $tcontent = <<<PAGE
            <div class="container">
	<div class="header clearfix">
<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<li class="active">Console Overview</li>
		</ul>
        <h1>Console Overview</h1>
        <div class="col-row">
        </div>
        <div clas="col-row">

        </div>

    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <h2>Details</h2>
          <p>The PS4 was released in 2013 with mixed reactions. The main issue with the PS4, and it's counterpart, were the lack of games to accompany the release of the consoles.
            Years later, this issue has been rectified with some of the most technologically advanced games of all time, allowing new ways for developers to innovate and reimagine
            classic game mechanics and characteristics, as well as invent new ones. In addition to the initial release, Sony have released a new version, not unlike a PS4.5, that results in far superior hardware,
            and gives the consoles a slight bump in the discrepancy between consoles and PCs. This was dubbed the PS4 Pro, and this console has difference specs to the base PS4, as listed below.</p>
        </div>

<div class="row">
        <div class="col-md-12">
          <h2>Video</h2>
          <iframe width="700" height="500" src="https://www.youtube.com/embed/DHGE-cYUjDY">
          </iframe>
        </div>

      </div>
		</div>
<br></br>
<div class="row">
    <h2>Specs</h2>
<br></br>

<div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Console Overview</h3>
      </div>
      <div class="panel-body">
        {$tconsolehtml1}
       </div>
    </div>
    </div>

<div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Console Overview</h3>
      </div>
      <div class="panel-body">
        {$tconsolehtml2}
       </div>
    </div>
    </div>
</div>

PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
session_start();

$tpagecontent = "";

$tid = $_REQUEST["fixid"] ?? - 1;

// Build up our Dynamic Content Items.
$tpagetitle = "Console Overview";
$tpagelead = "";
$tpagecontent = createPage();
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>