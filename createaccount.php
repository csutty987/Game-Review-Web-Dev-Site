<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// -----FORM VALIDATION---------------------------------
function processForm(array $pformdata): array
{
    foreach ($pformdata as $tfield => $tvalue) {
        $pformdata[$tfield] = appFormProcessData($tvalue);
    }
    $tvalid = true;
    if ($tvalid && empty($pformdata["firstName"])) {
        $tvalid = false;
        $pformdata["err-firstName"] = "<p id=\"help-firstName\" class=\"help-block\">First Name Required</p>";
    }
    if ($tvalid && empty($pformdata["inputPassword"])) {
        $tvalid = false;
    }
    if ($tvalid && empty($pformdata["confirmPassword"])) {
        $tvalid = false;
    }
    if ($tvalid && $pformdata["confirmPassword"] != $pformdata["inputPassword"]) {
        $tvalid = false;
    }
    if ($tvalid) {
        appFormSetValid($pformdata);
    }
    return $pformdata;
}

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pmethod, $paction, array $pform)
{
    appFormNullAsEmpty($pform, "inputEmail");
    appFormNullAsEmpty($pform, "inputPassword");
    appFormNullAsEmpty($pform, "confirmPassword");
    appFormNullAsEmpty($pform, "firstName");
    appFormNullAsEmpty($pform, "lastName");
    appFormNullAsEmpty($pform, "phoneNumber");
    appFormNullAsEmpty($pform, "err-firstName");

    $tcontent = <<<PAGE
<ul class="breadcrumb">
			<li><a href="index.php">Home</a></li>
            <li class="active">Create Account</li>
		</ul>
    <div>
    <h1> Create an Account</h1>
    </div>
    <br></br>
<form class="form-horizontal" method="{$pmethod}" action="{$paction}">
            <div>
            <h1 Create an Account</h1>
            </div>
		    <div class="form-group">
		        <label for="inputEmail" class="control-label col-xs-3">Email: *</label>
		        <div class="col-xs-9">
		            <input type="email" class="form-control" id="inputEmail" name="inputEmail"
                      placeholder="Email" value="{$pform["inputEmail"]}">
		        </div>
		    </div>
		    <div class="form-group">
		        <label for="inputPassword" class="control-label col-xs-3">Password: *</label>
		        <div class="col-xs-9">
		            <input type="password" class="form-control" id="inputPassword"
                     name="inputPassword" placeholder="Password" value="{$pform["inputPassword"]}">
		        </div>
		    </div>
            <div class="form-group">
		        <label class="control-label col-xs-3" for="confirmPassword">Confirm Password: *</label>
		        <div class="col-xs-9">
		            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                       placeholder="Confirm Password" value="{$pform["confirmPassword"]}">
		        </div>
		    </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="firstName">First Name: *</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="firstName" name="firstName"
                    placeholder="First Name" value="{$pform["firstName"]}">
                    {$pform["err-firstName"]}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="lastName">Last Name:</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="lastName"
                    name="lastName" placeholder="Last Name" value="{$pform["lastName"]}">
                </div>
            </div>
            <div class="form-group">
	           <label class="control-label col-xs-3">Date of Birth:</label>
                <div class="col-xs-9">
                    <input type="date" id="birthday" name="birthday">
		       </div>
            </div>
            <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <label class="checkbox-inline">
		                <input type="checkbox" value="news" checked> Send me latest news and updates.
		            </label>
		        </div>
		    </div>
		    <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <label class="checkbox-inline">
		                <input type="checkbox" value="agree" checked>  I agree to the <a href="#">Terms and Conditions</a>.
		            </label>
		        </div>
		    </div>
		    <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <div class="checkbox">
		                <label><input type="checkbox"> Remember me</label>
		            </div>
		        </div>
		    </div>
            <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <input type="submit" class="btn btn-primary" value="Submit">
		            <input type="reset" class="btn btn-default" value="Reset">
		        </div>
		    </div>
		</form>
    <div class="col-md-1">
    </div>
    <div class="col-md-4">
    <p>* : required fields</p>
    </div>
PAGE;
    return $tcontent;
}

function createResponse(array $pformdata)
{
    $tresponse = <<<RESPONSE
		<section class="panel panel-primary" id="Form Response">
				<div class="jumbotron">
					<h1>Thank You {$pformdata["firstName"]} {$pformdata["lastName"]}</h1>
					<p class="lead">Your account has been created.
                    Thank you for keeping up to date with the latest PS4 news & reviews!</p>
					<p class="lead">You will receive weekly updates to {$pformdata["inputEmail"]} </p>
				</div>
		</section>
RESPONSE;
    return $tresponse;
}

// ----BUSINESS LOGIC---------------------------------
$taction = appFormActionSelf();
$tmethod = appFormMethod();
$tformdata = processForm($_REQUEST) ?? array();

if (appFormCheckValid($tformdata)) {
    $tpagecontent = createResponse($tformdata);
} else {
    $tpagecontent = createPage($tmethod, $taction, $tformdata);
}

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Create an Account");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>