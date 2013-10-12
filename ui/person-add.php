<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Project.class.php");
	require_once("../common/errorMessages.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Add Person</title>
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
			<li class="section-menu-item"><a class="section-menu-link" href="#">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="clients.html">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Team</a></li>
		</ul>
	</nav>
</header>
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Add Person</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>

<!--OVERALL CONTROL--->
<?php 			if (isset($_POST["action"]) and $_POST["action"] == "person-add") {
					processPerson();
				} else {
					displayPersonInsertForm(array(), array(), new Person(array()));
				} 
?>
<!--DISPLAY PROJECT INSERT WEB FORM--->
<?php function displayPersonInsertForm($errorMessages, $missingFields, $person) { 
	
	//if there are errors in the form display the message
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	
?>
	<section class="content">
    <!--added because we need the information to be submitted in a form-->
      <form action="person-add.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
      <input type="hidden" name="action" value="person-add"/>
    <!--end add-->
		<!--<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="images/default.jpg" title="Client/Company name logo" alt="Client/Company name logo" />
			<fieldset class="client-logo-upload">
				<legend class="client-logo-title">Upload Client Logo</legend>
				<header class="client-logo-header">
					<h1 class="client-logo-title">Upload Client Logo</h1>
				</header>
				<input id="client-logo-file" name="client-logo-file" class="client-logo-file" type="file" value="Browse" />
				<input id="client-logo-upload-btn" name="client-logo-upload-btn" class="client-logo-upload-btn" type="button" value="Upload" /> or <a class="" href="#">Cancel</a>
			</fieldset>
		</figure>-->
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter person details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter person details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<ul class="details-list client-details-list">
		   			<li class="client-details-item name">
						<label for="client-name" <?php validateField("person_first_name", $missingFields)?> class="client-details-label">First Name:</label>
						<input id="client-name" name="person-first-name" class="client-name-input" type="text" tabindex="1" value="<?php echo $person->getValueEncoded("person_first_name")?>" /><br />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" <?php validateField("person_last_name", $missingFields)?> class="client-details-label">Last Name:</label>
						<input id="client-phone" name="person-last-name" class="client-phone-input" type="text" tabindex="2" value="<?php echo $person->getValueEncoded("person_last_name")?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-email" <?php validateField("person_email", $missingFields)?> class="client-details-label">Email:</label>
						<input id="client-email" name="person-email" class="client-email-input" type="text" tabindex="3" value="<?php echo $person->getValueEncoded("person_email")?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-city" <?php validateField("person_department", $missingFields)?> class="client-details-label">Department</label>
						<input id="client-city" name="person-department" class="client-city-input" type="text" tabindex="6" value="<?php echo $person->getValueEncoded("person_department")?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" <?php validateField("person_hourly_rate", $missingFields)?> class="client-details-label">Hourly Rate:</label>
						<input id="client-zip" name="person-hourly-rate" class="client-zip-input" type="text" tabindex="8" value="<?php echo $person->getValueEncoded("person_hourly_rate")?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" <?php validateField("person_perm_id", $missingFields)?> class="client-details-label">Permissions:</label>
						<input id="client-zip" name="person-perm-id" class="client-zip-input" type="text" tabindex="8" value="<?php echo $person->getValueEncoded("person_perm_id")?>" /><br />
					</li>
				</ul>
				<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
						<!--modified field to be of type submit instead of button-->
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="+ Add Person" tabindex="11"/> 
						 or <a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
</section>
</form>
<?php } ?>

<!--PROCESS THE CLIENT & THE CONTACT THAT WERE SUBMITTED--->
<?php function processPerson() {
 	//these are the required project fields in this form
	$requiredFields = array("person_first_name","person_last_name");
	$missingFields = array();
	$errorMessages = array();
	
	
	//create the project object ($project)
	$person = new Person( array(
		//CHECK REG SUBS!!
		"person_first_name" => isset($_POST["person-first-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^.]/", "", $_POST["person-first-name"]) : "",
		"person_last_name" => isset($_POST["person-last-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person-last-name"]) : "",
		"person_email" => isset($_POST["person-email"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["person-email"]) : "",
		"person_department" => isset($_POST["person-department"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["person-department"]) : "",
		"person_hourly_rate" => isset($_POST["person-hourly-rate"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person-hourly-rate"]) : "",
		"person_perm_id" => isset($_POST["person-perm-id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person-perm-id"]) : "",
		"person_type" => isset($_POST["person-type"])? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["person-type"]) : "",
//		"project_notes" => isset($_POST["project-notes"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-notes"]) : "",
//		"client_currency_index" => isset($_POST["client_currency_index"])? preg_replace("/[^0-9]/", "", $_POST["client_currency_index"]) : "",
//		"client_fax" => isset($_POST["client-fax"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["client-fax"]) : "",
	));
	error_log("here is the post<br>");
	error_log(print_r($_POST, true));
	error_log("here is the project array.<br>");
	error_log(print_r($person,true));
	
	
//error messages and validation script
	foreach($requiredFields as $requiredField) {
			if ( !$person->getValue($requiredField)) {
				$missingFields[] = $requiredField;
			}	
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>" . getErrorMessage("1",$missingField, $errorType) . "</li>";
			$i++;
		}
	} //else {
	/*take these out for people until a little later
		$email = $client->getValue("client_email");
		$phone = $client->getValue("client_phone");
		$zip = $client->getValue("client_zip");
		
		// validate the email address
		if(!preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email)) {
			$errorMessages[] = "<li>" . getErrorMessage($client->getValue("client_currency_index"),"client_email", "invalid_input") . "</li>";
		}
		
		// validate the phone number
		if(!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phone)) {
			$errorMessages[] = "<li>" . getErrorMessage($client->getValue("client_currency_index"),"client_phone", "invalid_input") . "</li>";
		}
		
		//validate the zip code
		if (!preg_match ("/^[0-9]{5}$/", $zip)) {
			$errorMessages[] = "<li>" . getErrorMessage($client->getValue("client_currency_index"),"client_zip", "invalid_input") . "</li>";
		}	
	}*/
		
	if ($errorMessages) {
		displayPersonInsertForm($errorMessages, $missingFields, $person);
	} else {
		//$client_id = $project->getValue("client_id");
		$person->insertPerson();
		echo "You have successfully added a person. You may add an additional project now. ";		
		echo"<a href=\"people.php\">View the full person list</a>";
		//headers already sent, call the page back with blank attributes.
		displayPersonInsertForm(array(), array(), new Person(array()));
	}
} 

?>
<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>