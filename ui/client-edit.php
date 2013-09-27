<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
	require_once("../common/errorMessages.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Edit Client</title>
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
		<h1 class="page-title">Edit Client Details</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="clients.html">View All</a></li>
			</ul>
		</nav>
	</header>
	<!--OVERALL CONTROL--->
	
<?php 			
				if (isset($_POST["client-save-btn"]) and $_POST["client-save-btn"] == "+Save Changes") {
					editClient();
				} elseif (isset($_POST["contact-save-btn"]) and $_POST["contact-save-btn"] == "+Save Contact") {
					editContact();
				} else {
					//user is here for the first time.
					//echo "here is the display form:";
					displayClientEditForm(array(), array(), new Client(array()));
					displayContactEditForm(array(), array(), new Contact(array()));
				}
?>
<!--DISPLAY CLIENT EDIT WEB FORM--->
<?php function displayClientEditForm($errorMessages, $missingFields, $client) { 
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	//if the user has already filled out the form and submitted, use the post values. Otherwise, retrieve the information from the object stored in the database.
	//if (!isset($_POST[])) {
		//get the client id out of the $_GET var.
		if (isset($_GET["client_id"])) {
			$client=Client::getClient($_GET["client_id"]);
		}
	//}
		
	
?>

	<section class="content">
	<form action="client-edit.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
      <input type="hidden" name="action" value="client-edit"/>
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="images/default.jpg" title="Client/Company name logo" alt="Client/Company name logo" />
			<fieldset class="client-logo-upload">
				<legend class="client-logo-title">Upload Client Logo</legend>
				<header class="client-logo-header">
					<h1 class="client-logo-title">Upload Client Logo</h1>
				</header>
				<input id="client-logo-file" name="client-logo-file" class="client-logo-file" type="file" value="Browse" tabindex="21" />
				<input id="client-logo-upload-btn" name="client-logo-upload-btn" class="client-logo-upload-btn" type="button" value="Upload" tabindex="22" /> or <a class="" href="#">Cancel</a>
			</fieldset>
		</figure>
		<section class="client-detail l-col-80">
			<fieldset class="client-details-entry">
				<!-- <legend class="client-details-title">Edit client details:</legend> -->
				<header class="client-details-header">
					<h1 class="client-details-title">Edit client details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<ul class="details-list client-details-list">
					<li class="client-details-item name">
						<!---USE A HIDDEN FORM TO KEEP THE ID!!--->
						<input type="hidden" name="client-id" value="<?php echo $client->getValue("client_id")?>">
						<label for="client-name" <?php validateField("client_name", $missingFields)?> class="client-details-label">Client's name:</label>
						<input id="client-name" name="client-name" class="client-name-input" type="text" tabindex="1" value="<?php echo $client->getValueEncoded("client_name")?>" /><br />
						
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" <?php validateField("client_phone", $missingFields)?> class="client-details-label">Phone number:</label>
						<input id="client-phone" name="client-phone" class="client-phone-input" type="text" tabindex="2" value="<?php echo $client->getValueEncoded("client_phone")?>" />					</li>
					<li class="client-details-item email">
						<label for="client-email" <?php validateField("client_email", $missingFields)?> class="client-details-label">Email address:</label>
						<input id="client-email" name="client-email" class="client-email-input" type="text" tabindex="3" value="<?php echo $client->getValueEncoded("client_email")?>" />
					</li>
					<li class="client-details-item fax">
						<label for="client-fax" <?php validateField("client_fax", $missingFields)?> class="client-details-label">Fax number:</label>
						<input id="client-fax" name="client-fax" class="client-contact-info-input" type="text" tabindex="4" value="<?php echo $client->getValueEncoded("client_fax")?>" />
					</li>
					<li class="client-details-item info-sync">
						<label for="contact-info-sync" class="client-details-label">Use same info for contact:</label>
						<input id="contact-info-sync" name="contact-info-sync" class="contact-info-sync-input" type="checkbox" tabindex="4" value="info-sync" />
					</li>
					<li class="client-details-item address">
						<label for="client-streetAddress" <?php validateField("client_address", $missingFields)?> class="client-details-label">Street Address:</label>
						<textarea id="client-streetAddress" name="client-streetAddress" class="client-streetAddress-input" tabindex="5"><?php echo $client->getValueEncoded("client_address")?></textarea><br />
						<label for="client-city" <?php validateField("client_city", $missingFields)?> class="client-details-label">City:</label>
						<input id="client-city" name="client-city" class="client-city-input" type="text" tabindex="6" value="<?php echo $client->getValueEncoded("client_city")?>" /><br />
						<label for="client-state" <?php validateField("client_state", $missingFields)?> class="client-details-label">State:</label>
						<select id="client-state" name="client-state" class="client-state-select" tabindex="7">
							<option selected="selected" value="default">Select state</option>
							<option value="AL">Alabama</option>
							<option value="AK">Alaska</option>
							<option value="AZ">Arizona</option>
							<option value="AR">Arkansas</option>
							<option value="CA">California</option>
							<option value="CO">Colorado</option>
							<option value="CT">Connecticut</option>
							<option value="DE">Delaware</option>
							<option value="FL">Florida</option>
							<option value="GA">Georgia</option>
							<option value="HI">Hawaii</option>
							<option value="ID">Idaho</option>
							<option value="IL">Illinois</option>
							<option value="IN">Indiana</option>
							<option value="IA">Iowa</option>
							<option value="KS">Kansas</option>
							<option value="KY">Kentucky</option>
							<option value="LA">Louisiana</option>
							<option value="ME">Maine</option>
							<option value="MD">Maryland</option>
							<option value="MA">Massachusetts</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MS">Mississippi</option>
							<option value="MO">Missouri</option>
							<option value="MT">Montana</option>
							<option value="NE">Nebraska</option>
							<option value="NV">Nevada</option>
							<option value="NH">New Hampshire</option>
							<option value="NJ">New Jersey</option>
							<option value="NM">New Mexico</option>
							<option value="NY">New York</option>
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="OH">Ohio</option>
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VT">Vermont</option>
							<option value="VA">Virginia</option>
							<option value="WA">Washington</option>
							<option value="WV">West Virginia</option>
							<option value="WI">Wisconsin</option>
							<option value="WY">Wyoming</option>
							<option value="DC">Washington DC</option>
							<option value="PR">Puerto Rico</option>
							<option value="VI">U.S. Virgin Islands</option>
							<option value="AS">American Samoa</option>
							<option value="GU">Guam</option>
							<option value="MP">Northern Mariana Islands</option>
						</select><br />
						<label for="client-zip" <?php validateField("client_zip", $missingFields)?> class="client-details-label">Zip code:</label>
						<input id="client-zip" name="client-zip" class="client-zip-input" type="text" tabindex="8" value="<?php echo $client->getValueEncoded("client_zip")?>" /><br />
						<label for="client-country" class="client-details-label">Client's country:</label>
						<select id="client-country" name="client-country" class="client-country-select" tabindex="9">
							<option value="">Select client's country...</option>
							<option selected="selected" value="US">United States of America</option>
						</select>
					</li>
					<?php 
						//get the currencies out to populate the drop down.
						$currency = Client::getCurrency();
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Preferred currency:</label>
                        <select name="client_currency_index" id="client_currency_index" size="1">    
						<?php foreach ($currency as $currencies) { ?>
   							<option value="<?php echo $currencies["client_currency_index"] ?>"<?php setSelected($client, "client_currency_index", $currencies["client_currency_index"]) ?>><?php echo $currencies["client_preferred_currency"]?></option>
    					<?php } ?>
                        </select><br />
					</li>
<!--		Just commented this out, because we're getting the currencies out of the currency table, no big deal.
			<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Preferred currency:</label>
						 <select id="client-currency" name="client-currency" class="client-currency-select" tabindex="10">
							<option value="">Select currency</option>
							<option selected="selected" value="USD">United States Dollar</option>
						</select><br />
					</li>-->
					<li class="client-details-item submit-client">
						<label for="client-save-btn" class="client-details-label">All done?</label>
						<input id="client-save-btn" name="client-save-btn" class="client-save-btn" type="submit" value="+Save Changes" tabindex="11" /> or
						<a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
	</form>
<?php } ?>


<!--DISPLAY CONTACT EDIT WEB FORM--->
<?php function displayContactEditForm($errorMessages, $missingFields, $contact) { 
	
	//if there are errors in the form display the message
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	?>

	<form action="client-edit.php" method="post" enctype="multipart/form-data">
		<section id="contact-detail" class="contact-detail">
			<header class="details-header contact-details-header">
				<h1 class="client-details-title">Contacts</h1>
			</header>
			<fieldset class="contact-details-entry">
				<!-- <legend class="contact-details-title">Edit contact details:</legend> -->
				<header class="contact-details-header">
					<h1 class="contact-details-title">Edit client details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<!-- <h4 class="required">= Required</h4> -->
				<ul class="details-list client-details-list">
					<li class="client-details-item name">
						<label for="contact-name" <?php validateField("contact_name", $missingFields)?>	class="client-details-label required">Your contact's name:</label>
						<input id="contact-name" name="contact-name" class="contact-contact-info-input" type="text" tabindex="12" value="<?php echo $contact->getValueEncoded("contact_name")?>" /><br />
						<label for="contact-primary" class="client-details-label">This the primary contact: </label>
						<input id="contact-primary" name="contact-primary" class="contact-info-input" type="checkbox" checked="checked" tabindex="13" value="1" />
					</li>
					<li class="client-details-item phoneNum">
						<label for="contact-officePhone" class="client-details-label">Office phone:</label>
						<input id="contact-officePhone" name="contact-officePhone" class="contact-contact-info-input" type="text" tabindex="14" value="" /><br />
						<label for="contact-mobilePhone" class="client-details-label">Mobile phone:</label>
						<input id="contact-mobilePhone" name="contact-mobilePhone" class="contact-info-input" type="text" tabindex="15" value="" />
					</li>
					<li class="client-details-item email">
						<label for="contact-email" class="client-details-label">Email:</label>
						<input id="contact-email" name="contact-email" class="contact-contact-info-input" type="text" tabindex="16" value="" />
					</li>
					<li class="client-details-item fax">
						<label for="contact-fax" class="client-details-label">Fax:</label>
						<input id="contact-fax" name="contact-fax" class="contact-contact-info-input" type="text" tabindex="17" value="" />
					</li>
					
					<li class="client-details-item add-additional">
						<label for="add-additional-link" class="client-details-label">Need to add more contacts?</label>
						<a id="add-additional-link" href="#" class="" tabindex="20">Add another contact</a>
					</li>
					
					<li class="contact-details-item submit-contact">
						<label for="contact-save-btn" class="contact-details-label">All done?</label>
						<input id="contact-save-btn" name="contact-save-btn" class="contact-save-btn" type="submit" value="+Save Contact" tabindex="18" /> or <a id="cancel-add-contact-link" class="cancel-action-link" href="#" tabindex="19">Cancel</a>
					</li>
				</ul>
			</fieldset>
			<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
						<input id="client-add-btn" name="client-add-btn" class="client-add-btn" type="submit" value="+Add Client" tabindex="11" /> or
						<a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
	</form>
<?php } ?>

<!--these guys don't warrant a form... yet!-->
		<section class="client-projects">
			<header class="details-header client-projects-header">
				<h1 class="client-details-title">Projects</h1>
			</header>
			<h1 class="client-projects-title active">Active Projects</h1>
			<ul class="details-list client-projects-list active">
				<li class="client-projects-list-item">Atomic Cupcakes</li>
			</ul>
			<h1 class="client-projects-title archive">Archived Projects</h1>
			<ul class="details-list client-projects-list archive">
				<li class="client-projects-list-item">Atomic Cupcakes 'Coming Soon' Campaign</li>
			</ul>
		</section>
	</section>
</section>

<!--PROCESS THE CLIENT THAT WAS SUBMITTED--->
<?php function editClient() {
	//these are the required client fields in this form
	$requiredFields = array("client_name","client_address","client_state","client_phone","client_city","client_zip","client_email");
	$missingFields = array();
	$errorMessages = array();
	
		//this is for the photo upload, and it is in the wrong place.
	if (isset($_FILES["client-logo-file"]) and $_FILES["client-logo-file"]["error"] == UPLOAD_ERR_OK) {
		if ( $_FILES["client-logo-file"]["type"] != "image/jpeg") {
			
			//I'm hardcoding the client_currency_index, because it's in the wrong place. This should be with the rest of the validation.
			$errorMessages[] = "<li>" . getErrorMessage("1","client_logo_link", "invalid_file") . "</li>";
		} elseif ( !move_uploaded_file($_FILES["client-logo-file"]["tmp_name"], "images/" . basename($_FILES["client-logo-file"]["name"]))) {
			$errorMessages[] = "<li>" . getErrorMessage("1","client_logo_link", "upload_problem") . "</li>";
		} else {
			$_POST["client_logo_link"] = $_FILES["client-logo-file"]["name"];
		}
	}

	
	//create the client object ($client)
	$client = new Client( array(
		//CHECK REG SUBS!!
		"client_logo_link" => isset($_POST["client_logo_link"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^.]/", "", $_POST["client_logo_link"]) : "",
		"client_name" => isset($_POST["client-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-name"]) : "",
		"client_phone" => isset($_POST["client-phone"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["client-phone"]) : "",
		"client_email" => isset($_POST["client-email"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["client-email"]) : "",
		"client_address" => isset($_POST["client-streetAddress"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-streetAddress"]) : "",
		"client_state" => isset($_POST["client-state"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-state"]) : "",
		"client_zip" => isset($_POST["client-zip"])? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["client-zip"]) : "",
		"client_city" => isset($_POST["client-city"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-city"]) : "",
		"client_currency_index" => isset($_POST["client_currency_index"])? preg_replace("/[^0-9]/", "", $_POST["client_currency_index"]) : "",
		"client_fax" => isset($_POST["client-fax"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["client-fax"]) : "",
	));

	
//error messages and validation script
	foreach($requiredFields as $requiredField) {
		if ( !$client->getValue($requiredField)) {
			$missingFields[] = $requiredField;
		}
		
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>" . getErrorMessage($client->getValue("client_currency_index"),$missingField, $errorType) . "</li>";
			$i++;
		}
	} else {
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
	}
		
	if ($errorMessages) {
		displayClientEditForm($errorMessages, $missingFields, $client);
		displayContactEditForm(array(), array(), new Contact(array()));
	} else {
		//there were no errors in the form. Update the client.
		//$client_email=$_POST["client-email"];
		//$client_name=$client->getValue("client_name");
		//$client_id = $client->getClientId($client_name);
		//don't allow duplicate entries in the database for the client.
		//if ($client_id[0]) {
		//	echo "Client " . $client_id[0] . " is already in the database. Please try again.";
		//} else {
			//$client->editClient($client_email);
			//print_r($_POST);
			//$client_name=$client->getValue("client_name");
			//echo "here: " . $client_name;
			//$client_id = $client->getClientId($client_name);
			//$client_id = $client->getValue["client_id"];
			$client_id = $_POST["client-id"];
			$client->updateClient($client_id);
			//$contact->insertContact($client_id[0]);
			//echo "You have successfully added client " . $client_email . "with client id " . $client_id[0] . ". You may add an additional client now. ";		
			//echo"<a href=\"clients.php\">View the full client list</a>";
		//}
		//headers already sent, call the page back with blank attributes.
		//just do this for now, it should really go to the clients page.
		displayClientEditForm(array(), array(), new Client(array()));
		displayContactEditForm(array(), array(), new Contact(array()));	
	}
}

?>

<!--PROCESS THE CONTACT THAT WAS SUBMITTED--->
<?php function editContact() {
	error_log("got here");
	//these are the required client fields in this form
	$requiredFields = array("contact_name");
	$missingFields = array();
	$errorMessages = array();

	
	//create the contact object ($contact)
	$contact = new Contact( array(
		//CHECK REG SUBS!!
		"contact_name" => isset($_POST["contact-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["contact-name"]) : "",
		"contact_primary" => isset($_POST["contact-primary"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["contact-primary"]) : "",
		"contact_office_number" => isset($_POST["contact-officePhone"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["contact-officePhone"]) : "",
		"contact_mobile_number" => isset($_POST["contact-mobilePhone"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["contact-mobilePhone"]) : "",
		"contact_email" => isset($_POST["contact-email"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["contact-email"]) : "",
		"contact_fax_number" => isset($_POST["contact-fax"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["contact-fax"]) : "",
	));

	
//error messages and validation script
	foreach($requiredFields as $requiredField) {
		if ( !$contact->getValue($requiredField)) {
			$missingFields[] = $requiredField;
		}
		
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>" . getErrorMessage(1,$missingField, $errorType) . "</li>";
			$i++;
		}
	} else {
		error_log("no weird errors here.");	
	}
		
	if ($errorMessages) {
		displayClientEditForm(array(), array(), new Client(array()));
		displayContactEditForm($errorMessages, $missingFields, $contact);
	} else {
		//there were no errors in the form. Update the client.
			//$client->editClient($client_email);
			//$client_id = $client->getClientId($client_name);
			echo "you are getting ready to call the update of the contact.";
			//$client->editClient($client_id[0]);
			//$contact->insertContact($client_id[0]);
			//echo "You have successfully added client " . $client_email . "with client id " . $client_id[0] . ". You may add an additional client now. ";		
			//echo"<a href=\"clients.php\">View the full client list</a>";
		//headers already sent, call the page back with blank attributes.
		//just do this for now, it should really go to the clients page.
		displayClientEditForm(array(), array(), new Client(array()));
		displayContactEditForm(array(), array(), new Contact(array()));	
	}
}

?>


<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>