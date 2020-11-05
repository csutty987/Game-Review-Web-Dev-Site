<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Page-Specific Static Content
    $twelcome = file_get_contents("data/static/index_welcome.part.html");

    // Content Classes via XML and JSON
    $tarticles = xmlLoadAll("data/xml/articles-index.xml", "PLHomeArticle", "Article");

    // Get the News Item Array
    $tnilist = jsonLoadAllNewsItems();
    $ttest = true;

    // Create the News Items for Article 2.
    $tnews = "";
    $tcount = 0;
    foreach ($tnilist as $tni) {
        $tnews .= renderNewsItemAsList($tni);
        $tcount ++;
    }

    // $tarticles[1]->content = $tnews;

    // Build the Articles
    $tarticlehtml = "";
    foreach ($tarticles as $ta) {
        $tarticlehtml .= renderUIHomeArticle($ta);
    }
    $tcontent = <<<PAGE
        <div class="container">
	<div class="header clearfix">

	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
          <li data-target="#carousel-example-generic" data-slide-to="1"></li>
          <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <a href="game.php?id=8"><img src="img/carousel/bf2.jpg"  alt="Our Top Pick" width="1140" height="500"></a>
             <h1>New & Improved: BF2 Updates</h1>
             <h6>DICE's hard work to improve the state of Battlefront 2</h6>
          </div>
          <div class="item">
            <a href="game.php?id=2"><img src="img/carousel/zerodawn2.jpg" alt="Your Favourite" width="1140" height="500"></a>
             <h1>Looking back on a PS4 Classic</h1>
             <h6>A look back at one of the staple experiences of the PS4</h6>
          </div>
          <div class="item">
            <a href="game.php?id=4"><img src="img/carousel/fallenorder4.jpeg" alt="New & Trending" width="1140" height="500"></a>
            <h1>New Kid on the Block</h1>
            <h6>Respawn Entertainment take on the Star Wars franchise</h6>
          </div>
        </div>
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
        <h1></h1>

</div>


        <div class="row">
            {$twelcome}
		</div>
        <div class="row details">
        <h1>News of the Day</h1>
            {$tarticlehtml}
		</div>



PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Build up our Dynamic Content Items.
$tpagetitle = "Home Page";
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