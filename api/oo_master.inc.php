<?php
// Include our HTML Page Class
require_once ("oo_page.inc.php");

class MasterPage
{

    // -------FIELD MEMBERS----------------------------------------
    private $_htmlpage;

    // Holds our Custom Instance of an HTML Page
    private $_dynamic_1;

    // Field Representing our Dynamic Content #1
    private $_dynamic_2;

    // Field Representing our Dynamic Content #2
    private $_dynamic_3;

    // Field Representing our Dynamic Content #3
    private $_game_ids;

    // -------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults();
        $this->_game_ids = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10
        ];
    }

    // -------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic1()
    {
        return $this->_dynamic_1;
    }

    public function getDynamic2()
    {
        return $this->_dynamic_2;
    }

    public function getDynamic3()
    {
        return $this->_dynamic_3;
    }

    public function setDynamic1($phtml)
    {
        $this->_dynamic_1 = $phtml;
    }

    public function setDynamic2($phtml)
    {
        $this->_dynamic_2 = $phtml;
    }

    public function setDynamic3($phtml)
    {
        $this->_dynamic_3 = $phtml;
    }

    public function getPage(): HTMLPage
    {
        return $this->_htmlpage;
    }

    // -------PUBLIC FUNCTIONS-------------------------------------
    public function createPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Return the HTML Page..
        return $this->_htmlpage->createPage();
    }

    public function renderPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Echo the page immediately.
        $this->_htmlpage->renderPage();
    }

    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }

    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }

    // -------PRIVATE FUNCTIONS-----------------------------------
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("css", "js", "fonts", "img", "data");
        $this->addCSSFile("bootstrap.css");
        $this->addCSSFile("bootstrap-theme.css");
        $this->addCSSFile("site.css");
        $this->addScriptFile("jquery-2.2.4.js");
        $this->addScriptFile("bootstrap.js");
        $this->addScriptFile("holder.js");
    }

    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");
        // Set the Three Dynamic Points to Empty By Default.
        $this->_dynamic_1 = <<<JUMBO
<h1>Top Shelf Reviews</h1>
<p class="lead">The #1 Site for PS4 Game Reviews, from both Critics and Users!</p>

JUMBO;
        $this->_dynamic_2 = "";
        $this->_dynamic_3 = <<<FOOTER
<p>Connor Sutcliffe - LJMU &copy; {$tcurryear}</p>
FOOTER;
    }

    private function setMasterContent()
    {
        $tlogin = "app_entry.php";
        $tlogout = "app_exit.php";
        if (appSessionLoginExists()) {
            $tusername = $_REQUEST["myname"];
        } else {
            $tusername = "Guest";
        }
        $tentryhtml = <<<FORM
        <form id="signin" action="{tlogin}" method="post"
         class="navbar-form navbar-right" role="form">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user"></i>
                </span>
                <input id="myname" type="email" class="form-control"
                    name="myname" value="" placeholder="">
            </div>
            <a href="app_entry.php"<button type="submit" class="btn btn-primary">Login</button></a>
            <p>Welcome, {$tusername}</p>
        </form>





FORM;

        $texithtml = <<<EXIT
        <p>Welcome, {$tusername}</p>
        <a class="btn btn-info navbar-right" href="{$tlogout}?action="{tlogout}">Exit</a>
EXIT;

        $tauth = "";
        if (isset($_SESSION["myuser"])) {
            $tauth = $texithtml;
        } else {
            $tauth = $tentryhtml;
        }
        $tid = $this->_game_ids[array_rand($this->_game_ids, 1)]; // to give myself more space to place nav links on the master content, i removed the "header clearfix" and "container" classes from the nav pills and moved them to just cover the jumbotron.
        $tmasterpage = <<<MASTER
		<nav>
		    {$tauth}

			<ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="gamelist.php">Highest Rated</a></li>
				<li role="presentation"><a href="console.php">Console Overview</a></li>
				<li role="presentation"><a href="game.php?id={$tid}">Give me a Game</a></li>
                <li role="presentation"><a href="news.php">News</a></li>
                <li role="presentation"><a href="createaccount.php">Create Account</a></li>
                <li role="presentation"><a href="review_entry.php">Write A Review</a></li>
                <li role="presentation"><a href="userreviews.php">View All User Reviews</a></li>
			</ul>
			<h3 class="text-muted"><a href="index.php">PS4 Game Reviews</a></h3>
		</nav>
	</div>
<div class="container">
	<div class="header clearfix">
    <div class="jumbotron">
        {$this->_dynamic_1}
    </div>
	<div class="row details">
		{$this->_dynamic_2}
    </div>
    <footer class="footer">
		{$this->_dynamic_3}
	</footer>
</div>
MASTER;
        $this->_htmlpage->setBodyContent($tmasterpage);
    }
}

?>