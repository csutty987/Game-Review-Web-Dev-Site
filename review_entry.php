<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createFormPage()
{
    $tmethod = appFormMethod();
    $taction = appFormActionSelf();
    $tcontent = <<<PAGE
<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
            <li class="active">Write A Review</li>
		</ul>
    <form class="form-horizontal" method="{$tmethod}" action="{$taction}">
	<fieldset>
			<legend>Enter new Review</legend>


		<div class="form-group">
			<label class="col-md-2 control-label" for="gametitle">Game Title</label>
			<div class="col-md-4">
				<select id="gametitle" name="gametitle" class="form-control">
					<option value="Destiny 2">Destiny 2</option>
					<option value="F1 2019">F1 2019</option>
					<option value="FIFA 20">FIFA 20</option>
					<option value="Horizon Zero Dawn">Horizon Zero Dawn</option>
					<option value="MARVEL's Spiderman">MARVEL's Spiderman</option>
					<option value="NBA 2K20">NBA 2K20</option>
					<option value="Rocket League">Rocket League</option>
                    <option value="Star Wars Battlefront 2">Star Wars Battlefront 2</option>
                    <option value="Star Wars Jedi Fallen Order">Star Wars Jedi Fallen Order</option>
                    <option value="Uncharted 4 A Thief's End">Uncharted 4 A Thief's End</option>
				</select>
                <span class="help-block">Select the game you will be reviewing</span>
			</div>
		</div>


		<div class="form-group">
			<label class="col-md-2 control-label" for="reviewtitle">Review Title:</label>
			<div class="col-md-4">
				<input id="reviewtitle" name="reviewtitle" type="text" placeholder=""
					class="form-control input-md" required=""> <span class="help-block">Enter the title of your review</span>
			</div>
		</div>


		<div class="form-group">
			<label class="col-md-2 control-label" for="reviewcontent">Review:</label>
			<div class="col-md-8">
				<textarea class="form-control" id="reviewcontent" name="reviewcontent" rows="10" columns="160" required=""> </textarea> <span class="help-block">Enter your review</span>
			</div>
		</div>


		<div class="form-group">
			<label class="col-md-2 control-label" for="reviewscore">Review Score(Out of 10):</label>
			<div class="col-md-4">
				<input id="reviewscore" name="reviewscore" type="text" placeholder=""
					class="form-control input-md" required="">
                <span class="help-block">Enter your score of the game</span>
			</div>
		</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-2 control-label" for="form-sub">Submit Form</label>
  <div class="col-md-4">
    <button id="form-sub" name="form-sub" type="submit" class="btn btn-success">Add New Review</button>
  </div>
</div>
	</fieldset>
</form>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------

session_start();
$tpagecontent = "";

// if (appSessionLoginExists()) {

if (appFormMethodIsPost()) {
    // Map the Form Data
    $treview = new BLLReview();
    $treview->gametitle = appFormProcessData($_REQUEST["gametitle"] ?? "");
    $treview->reviewtitle = appFormProcessData($_REQUEST["reviewtitle"] ?? "");
    $treview->reviewcontent = appFormProcessData($_REQUEST["reviewcontent"] ?? "");
    $treview->reviewscore = appFormProcessData($_REQUEST["reviewscore"] ?? "");

    $tvalid = true;
    // TODO: PUT SERVER-SIDE VALIDATION HERE

    if ($tvalid) {
        $tid = jsonNextReviewID();
        $treview->id = $tid;

        // convert the review to json
        $tsavedata = json_encode($treview) . PHP_EOL;

        // get existing content and append the data
        $tfilecontent = file_get_contents("data/json/reviews.json");
        $tfilecontent .= $tsavedata;
        // save the file
        file_put_contents("data/json/reviews.json", $tfilecontent);
        $tpagecontent = "<h1>Review with ID = {$treview->id} has been saved.</h1>";
    } else {
        $tdest = appFormActionSelf();
        $tpagecontent = <<<ERROR
                         <div class="well">
                            <h1>Form was Invalid</h1>
                            <a class="btn btn-warning" href="{$tdest}">Try Again</a>
                         </div>
ERROR;
    }
} else {
    // This page will be created by default.
    $tpagecontent = createFormPage();
}
// } else {
// $tpagecontent = <<<UNLOGGED
// <ul class="breadcrumb">
// <li><a href="index.php">Home</a></li>
// <li class="active">Write A Review</li>
// </ul>
// <html>
// <head>
// <style>
// h1 {text-align: center;}
// h4 {text-align: center;}
// h5 {text-align: center;}
// </style>
// </head>
// <body>
// <div class "well">
// <h1> You Must be Logged In to Post a Review</h1>
// <h4> Login using the form at the top of the page, or create a new account</h4>
// <a href="createaccount.php"><h5 style="color:cyan">Create Account</h5></a>
// <br></br>
// </body>
// </html>
// UNLOGGED;
// }
$tpagetitle = "Post a Review";
$tpagelead = "";
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