<?php
require_once ("oo_bll.inc.php");
require_once ("oo_pl.inc.php");

// ===========RENDER BUSINESS LOGIC OBJECTS=======================================================================

// ----------NEWS ITEM RENDERING------------------------------------------
function renderNewsItemAsList(BLLNewsItem $pitem)
{
    $titemsrc = ! empty($pitem->thumb_href) ? $pitem->thumb_href : "blanknewsimg.png";
    $tnewsitem = <<<NI
		    <section class="list-group-item clearfix">
		        <div class="media-left media-top">
                    <img src="img/news/{$titemsrc}" width="100" />
                </div>
                <div class="media-body">
				<h4 class="list-group-item-heading">{$pitem->heading}</h4>
				<p class="list-group-item-text">{$pitem->tagline}</p>
				<a class="btn btn-xs btn-default" href="news.php?storyid={$pitem->id}">Read...</a>
				</div>
			</section>
NI;
    return $tnewsitem;
}

function renderNewsItemAsSummary(BLLNewsItem $pitem)
{
    $titemsrc = ! empty($pitem->thumb_href) ? $pitem->thumb_href : "blanknewsimg.png";
    $tnewsitem = <<<NI
		    <section class="row details clearfix">
		    <div class="media-left media-top">
				<img src="img/news/{$titemsrc}" width="256" />
			</div>
			<div class="media-body">
				<h2>{$pitem->heading}</h2>

				<div class="ni-summary">
				<p>{$pitem->summary}</p>
				</div>
				<a class="btn btn-xs btn-primary" href="news.php?storyid={$pitem->id}">Get the Full Story</a>
	        </div>
			</section>
NI;
    return $tnewsitem;
}

function renderNewsItemFull(BLLNewsItem $pitem)
{
    $titemsrc = ! empty($pitem->img_href) ? $pitem->img_href : "blanknewsimg.png";
    $tnewsitem = <<<NI
		    <section class="row details">
		        <div class="well">
		        <div class="media-left">
				    <img src="img/news/{$titemsrc}" />
				</div>
				<div class="media-body">
				    <h1 style="color:black;">{$pitem->heading}</h1>
				    <p id="news-tag">{$pitem->tagline}</p>
				    <p id="news-summ">{$pitem->summary}</p>

				</div>
<br></br>
                <p id="news-main">{$pitem->content}</p>
				</div>
			</section>
NI;
    return $tnewsitem;
}

// ----------GAME RENDERING---------------------------------------
function renderGameTable(BLLGameList $pgames)
{
    $trowdata = "";
    foreach ($pgames->listcontents as $tp) {

        $tuserscore = userScores($tp);
        $trowdata .= <<<ROW

    <td><img src="img/games/{$tp->gametitle}.gif" alt="game thumbnail" width=100></td>
   <td><h4>{$tp->gametitle}</h4></td>
   <td>{$tp->genre}</td>
   <td>{$tp->score}</td>
   <td>{$tuserscore}/10</td>
   <td>{$tp->releasedate}</td>
   <td><a class="btn btn-primary" href="game.php?id={$tp->id}">More</a></td>
</tr>

ROW;
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
            <th id="sort-image"></th>
			<th id="sort-gametitle">A-Z</th>
			<th id="sort-genre">Genre</th>
			<th id="sort-ourscore">Our Score</th>
            <th id="sort-userscore">Average User Score</th>
			<th id="sort-releasedate">Release Date</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	{$trowdata}
    <?php

	</tbody>
</table>

TABLE;
    return $ttable;
}

function renderGameOverview(BLLGame $pp)
{
    $timgref = "img/games/{$pp->gametitle}.gif";
    $timg = file_exists($timgref) ? $timgref : "img/games/blank.png";

    $previews = [];
    $pgametitle = "{$pp->gametitle}";
    $previewtitle = $_REQUEST["reviewtitle"] ?? - 1;
    $pid = "{$pp->id}";

    // Handle our Requests and Search for games using different methods
    if (! empty($pgametitle)) {
        // Filter the name
        $pname = appFormProcessData($pgametitle);
        $previewlist = jsonLoadAllReview();
        foreach ($previewlist as $tp) {
            if (strtolower($tp->gametitle) === strtolower($pgametitle)) {
                $previews[] = $tp;
            }
        }
    }

    $treviews = new BLLReviewList();
    $treviews->listname = "User Reviews for {$pp->gametitle}";
    $treviews->listcontents = $previews;
    $treviewtable = renderReviewTable($treviews);
    $treviewgame = $_REQUEST["gametitle"] ?? - 1;
    $tuserscore = userScores($pp);
    $toverview = <<<OV

    <article class="row marketing">
        <h2>Game Details</h2>
        <div class="media-left">
            <img src="$timg" width="256" />
        </div>
        <div class="media-body">
            <div class="well">
                <h1 style="color:black">{$pp->gametitle}</h1>
            </div>
            <h3>Genre: {$pp->genre}</h3>
            <h4>Platforms: {$pp->platforms}</h4>
            <h4>Age rating: {$pp->agerating}</h4>
            <h4>Developer: {$pp->developer}</h4>
            <h1></h1>
            <h5>Publisher: {$pp->publisher}</h5>
        </div>
        <div class"media-right">
            <h3>Our Score: {$pp->score}</h3>
            <h3>Avg User Score: {$tuserscore}/10</h3>
        <p>{$pp->desc}</p>
    </article>
    <br></br>
    <h3>Trailer</h3>
    <div class="media-body">
        <iframe width="800" height="500" src="{$pp->vidembed}"></iframe>
    </div>
    <br></br>
    <h3>External Reviews</h3>

    <h6>A collection of reviews from esteemed critics around the internet:</h6>

    <a href="{$pp->metacriticlink}" class="btn btn-primary">Metacritic</a>
    <a href="{$pp->ignlink}" class="btn btn-primary">IGN</a>
    <a href="{$pp->gamespotlink}" class="btn btn-primary">GameSpot</a>

    <br></br>
    <br></br>
    <div class="col-md-12">
        <h5>Oh dear, it looks like there are no user reviews for this game yet.</h5>
        <h6>Want to add one yourself? Click <a href="review_entry.php">Here</a></h6>
       <div class="row">

		</div>
		<div class="row">
			<div class="panel panel-primary">
			<div class="panel-body">
				<h2>User Reviews</h2>
				<p>{$treviews->listname}</p>
				<div id="games-table">
			    {$treviewtable}
		        </div>
		    </div>
			</div>
		</div>
		<div class="row">

		</div>
    <br></br>
    </div>
    <br></br>
    <br></br>
    <br></br>
<div class="col-md-12">
    <h3>Retail Information</h3>
    <h1></h1>
    <table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>Retailer</th>
      <th>Price</th>
      <th>Link</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>GAME</td>
      <td>£{$pp->gameprice}</td>
      <td><a href="{$pp->gameshoplink}" class="btn btn-primary">View at GAME</a></td>
    </tr>
    <tr>
      <td>2</td>
      <td>Amazon</td>
      <td>£{$pp->amazonprice}</td>
      <td><a href="{$pp->amazonlink}" class="btn btn-primary">View at Amazon</a></td>
    </tr>
    <tr>
      <td>3</td>
      <td>PlayStation Store</td>
      <td>£{$pp->psstoreprice}</td>
      <td><a href="{$pp->psstorelink}" class="btn btn-primary">View at PS Store</a></td>
    </tr>
  </tbody>
</table>
</div>

OV;
    return $toverview;
}

// ----------REVIEW RENDERING--------------------------------------------
function renderReviewTable(BLLReviewList $previews)
{
    $trowdata = "";
    foreach ($previews->listcontents as $tp) {

        $trowdata .= <<<ROW
   <td><h5>{$tp->gametitle}</h5></td>
   <td><h5>{$tp->reviewtitle}</h5></td>
   <td>{$tp->reviewcontent}</td>
   <td>{$tp->reviewscore}</td>
</tr>

ROW;
    }
    $ttable = <<<TABLE
<table class="table table-striped table-hover">
	<thead>
		<tr>
            <th id="sort-gametitle">Game</th>
			<th id="sort-reviewtitle">Review Title</th>
			<th rowspan="4" id="sort-reviewcontents">Review Contents</th>
			<th id="sort-reviewscore">User Score</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
	{$trowdata}
    <?php

	</tbody>
</table>

TABLE;
    return $ttable;
}

function userScores(BLLGame $pp)
{
    $reviewscores = [];
    $pgametitle = "{$pp->gametitle}";
    // $previewscore = "{$pp->reviewscore}";
    $pid = "{$pp->id}";
    $previewscores[] = "";
    // Handle our Requests and Search for games using different methods
    if (! empty($pgametitle)) {
        // Filter the name
        $pname = appFormProcessData($pgametitle);
        $previewlist = jsonLoadAllReview();
        foreach ($previewlist as $tp) {
            if (strtolower($tp->gametitle) === strtolower($pgametitle)) {
                $previewscores[] = $tp->reviewscore;
            }
        }
    }
    $psumofscores = array_sum($previewscores);
    if ((sizeof($previewscores) - 1) > 0) {
        $pscoreav = ($psumofscores / (sizeof($previewscores) - 1));
        $pscoreav = (round($pscoreav, 2));
        return $pscoreav;
    } else
        return 0;
}

// ----------CONSOLE RENDERING--------------------------------------------
function renderConsoleSummary(BLLConsole $ps)
{
    $consolehtml = <<<OVERVIEW
    <div class="well">
            <ul class="list-group">
                <li class="list-group-item">
                    Console Name: <strong>{$ps->name}</strong>
                </li>
                <li class="list-group-item">
                    Release Date: <strong>{$ps->releasedate}</strong>
                </li>
                <li class="list-group-item">
                    Manufacturer: <strong>{$ps->manufacturer}</strong>
                </li>
                <li class="list-group-item">
                    Storage: <strong>{$ps->storage}</strong>
                </li>
                <li class="list-group-item">
                    Max Resolution: <strong>{$ps->maxresolution}</strong>
                </li>
                <li class="list-group-item">
                    Max Refresh Rate: <strong>{$ps->maxrefreshrate}</strong>
                </li>
                <li class="list-group-item">
                    Backwards Compatible: <strong>{$ps->backwardscompatible}</strong>
                </li>
                <li class="list-group-item">
                    <img src="{$ps->imgsrc}" alt={$ps->name} width="400">
                </li>
                <li class="list-group-item">
                    RRP: <strong>{$ps->rrp}</strong>
                </li>
            </ul>
            <a class="btn btn-info" href="$ps->rrplink">Go To Retailer</a>
    </div>
OVERVIEW;
    return $consolehtml;
}

// =============RENDER PRESENTATION LOGIC OBJECTS==================================================================
function renderUICarousel(array $pimgs, $pimgdir, $pid = "mycarousel")
{
    $tci = "";
    $count = 0;

    // -------Build the Images---------------------------------------------------------
    foreach ($pimgs as $titem) {
        $tactive = $count === 0 ? " active" : "";
        $thtml = <<<ITEM
        <div class="item{$tactive}">
            <img class="img-responsive" src="{$pimgdir}/{$titem->img_href}">
            <div class="container">
                <div class="carousel-caption">
                    <h1>{$titem->title}</h1>
                    <p class="lead">{$titem->lead}</p>
		        </div>
			</div>
	    </div>
ITEM;
        $tci .= $thtml;
        $count ++;
    }

    // --Build Navigation-------------------------
    $tdot = "";
    $tdotset = "";
    $tarrows = "";

    if ($count > 1) {
        for ($i = 0; $i < count($pimgs); $i ++) {
            if ($i === 0)
                $tdot .= "<li data-target=\"#{$pid}\" data-slide-to=\"$i\" class=\"active\"></li>";
            else
                $tdot .= "<li data-target=\"#{$pid}\" data-slide-to=\"$i\"></li>";
        }
        $tdotset = <<<INDICATOR
        <ol class="carousel-indicators">
        {$tdot}
        </ol>
INDICATOR;
    }
    if ($count > 1) {
        $tarrows = <<<ARROWS
		<a class="left carousel-control" href="#{$pid}" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
		<a class="right carousel-control" href="#{$pid}" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span></a>
ARROWS;
    }

    $tcarousel = <<<CAROUSEL
    <div class="carousel slide" id="{$pid}">
            {$tdotset}
			<div class="carousel-inner">
				{$tci}
			</div>
		    {$tarrows}
    </div>
CAROUSEL;
    return $tcarousel;
}

function renderUITabs(array $ptabs, $ptabid)
{
    $count = 0;
    $ttabnav = "";
    $ttabcontent = "";

    foreach ($ptabs as $ttab) {
        $tnavactive = "";
        $ttabactive = "";
        if ($count === 0) {
            $tnavactive = " class=\"active\"";
            $ttabactive = " active in";
        }
        $ttabnav .= "<li{$tnavactive}><a href=\"#{$ttab->tabid}\" data-toggle=\"tab\">{$ttab->tabname}</a></li>";
        $ttabcontent .= "<article class=\"tab-pane fade{$ttabactive}\" id=\"{$ttab->tabid}\">{$ttab->content}</article>";
        $count ++;
    }

    $ttabhtml = <<<HTML
        <ul class="nav nav-tabs">
        {$ttabnav}
        </ul>
    	<div class="tab-content" id="{$ptabid}">
			  {$ttabcontent}
		</div>
HTML;
    return $ttabhtml;
}

function renderUIHomeArticle(PLHomeArticle $phome, $pwidth = 6)
{
    $thome = <<<HOME
    <article class="col-lg-{$pwidth}">
		<h3>{$phome->heading}</h3>
		<h5>
			<span class="label label-success">{$phome->tagline}</span>
		</h5>
		<div class="home-thumb">
			<img src="img/news/psplusnewsthumb.jpg" />
		</div>
		<div>
		  <strong>
			{$phome->summary}
		  </strong>
		</div>
        <div>
		    {$phome->content}
        </div>
        <div class="options details">
			<a class="btn btn-info" href="{$phome->link}">{$phome->linktitle}</a>
        </div>
	</article>
HOME;
    return $thome;
}

function renderPagination($ppage, $pno, $pcurr)
{
    if ($pno <= 1)
        return "";

    $titems = "";
    $tld = $pcurr == 1 ? " class=\"disabled\"" : "";
    $trd = $pcurr == $pno ? " class=\"disabled\"" : "";

    $tprev = $pcurr - 1;
    $tnext = $pcurr + 1;

    $titems .= "<li$tld><a href=\"{$ppage}?page={$tprev}\">&laquo;</a></li>";
    for ($i = 1; $i <= $pno; $i ++) {
        $ta = $pcurr == $i ? " class=\"active\"" : "";
        $titems .= "<li$ta><a href=\"{$ppage}?page={$i}\">{$i}</a></li>";
    }
    $titems .= "<li$trd><a href=\"${ppage}?page={$tnext}\">&raquo;</a></li>";

    $tmarkup = <<<NAV
    <ul class="pagination pagination-sm">
        {$titems}
    </ul>
NAV;
    return $tmarkup;
}