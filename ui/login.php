<!DOCTYPE html>
<?php

	require_once("../common/common.inc.php");
	require_once("../classes/Person.class.php");
	
	session_start();

if(isset($_POST["action"]) and $_POST["action"] == "login") {
	processForm();
}else{
	displayForm(array(), array(), new Person(array()));
}

function displayForm($errorMessages, $missingFields, $person) {
	//displayPageHeader("Login to view this area", true);
	
	if ($errorMessages) {
		foreach ($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	} else {?>

<html lang="en">
<head>
	<title>Manage</title>
	<meta charset="utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
	<link href="styles.css" rel="stylesheet" type="text/css" />
	<script src="libraries/jquery-1.10.2.min.js" type="text/javascript"></script>
</head>

<body>
<header id="site-header" class="site-header">
	<h1 class="site-title">Time Tracker</h1>
	<nav id="site-nav" class="site-nav">
		<ul id="site-menu" class="site-menu">
			<li class="site-menu-item"><a class="site-menu-link" href="#">Timesheets</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Reports</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Invoices</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="manage.html">Manage</a></li>
		</ul>
	</nav>
	<nav id="section-nav" class="section-nav manage">
		<h1 class="section-nav-title">Manage: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="projects.php">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="clients.php">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Team</a></li>
		</ul>
	</nav>
</header>
<section id="page-content" class="page-content">

		<p>To access this area, please enter your username and password below then click Login.</p>
		<form action="login.php" method="post" style="margin-bottom:50px;">
			<div style="width:30em;">
				<input type="hidden" name="action" value="login" />
				<label for="username"<?php validateField("username", $missingFields)?>>Username</label>
				<input type="text" name="username" id="username" value="<?php echo $person->getValueEncoded("person_username")?>"/>
				<label for="password"<?php validateField("", $missingFields)?>>Password</label>
				<input type="password" name="password" id="password" value="" />
				<div style="clear:both;">
					<input type="submit" name="submitButton" id="submitButton" value="Login" />
				</div>
			</div>
		</form>
		If you do not have an account yet, create an account <a href="register.php">here.</a>
<?php
	}
}

function processForm() {
	$requiredFields = array("person_username", "person_password");
	$missingFields = array();
	$errorMessages = array();
	
	$person = new Person( array(
	"person_username" => isset($_POST["username"]) ? preg_replace("/[^\-\_a-zA-Z0-9]/", "", $_POST["username"]) : "",
	"person_password" => isset($_POST["password"]) ? preg_replace("/[^\-\_a-zA-Z0-9]/", "", $_POST["password"]) : "",
	));
	
	error_log(print_r($person,true));
	
	foreach($requiredFields as $requiredField) {
		if (!$person->getValue($requiredField)) {
			$missingFields[] = $requiredField;
		}
	}
	
	if ($missingFields) {
		$errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Login to resend the form.</p>';
	} elseif ( !$loggedInPerson = $person->authenticate()) {
		$errorMessages[] = '<p class="error">Sorry, we could not log you in with those details. Please check your username and password and try again.</p>';
	}
	
	if ($errorMessages) {
		displayForm($errorMessages, $missingFields, $person);
	} else {
		$_SESSION["person"] = $loggedInPerson;
		header( "Location: " . $_SESSION["callLoginFromPage"]) ;
		//echo "you successfully logged in with ";
		//displayThanks();
	}
}

function displayThanks() {
	//displayPageHeader("Thanks for logging in!", true);
	header( "Location: " . $_SESSION["callLoginFromPage"]) ;
}
?>
</section>
<footer id="site-footer" class="site-footer">
</footer>
</body>
</html>