<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Add Client</title>
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
		<h1 class="page-title">Add New Client</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<li class="page-controls-item"><a class="view-all-link" href="clients.html">View All</a></li>
			</ul>
		</nav>
	</header>
    <?php if (isset($_POST["action"]) and $_POST["action"] == "client-add") {
					processClient();
				} else {
					displayClientInsertForm(array(), array(), new Client(array()));
				} 
	?>
    
	<?php function displayClientInsertForm($errorMessages, $missingFields, $client) { 
	//print_r($client);
	//if there are errors in the form display the message
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	?>
	<section class="content">
    <!--added because we need the information to be submitted in a form-->
      <form action="client-add.php" method="post" style="margin-bottom:50px;">
      <input type="hidden" name="action" value="client-add"/>
    <!--end add-->
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="images/default.jpg" title="Client/Company name logo" alt="Client/Company name logo" />
			<fieldset class="client-logo-upload">
				<legend>Upload Client Logo</legend>
				<input id="client-logo-file" name="client-logo-file" class="client-logo-file" type="file" value="Browse" />
				<input id="client-logo-upload-btn" name="client-logo-upload-btn" class="client-logo-upload-btn" type="button" value="Upload" /> or <a class="" href="#">Cancel</a>
			</fieldset>
		</figure>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter client details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter client details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<ul class="details-list client-details-list">
		   			<li class="client-details-item name">
						<label for="clientname" <?php validateField("client_name", $missingFields)?> class="client-details-label">Client's name:</label>
						<input id="client-name" name="client-name" class="client-name-input" type="text" tabindex="1" value="<?php echo $client->getValueEncoded("client_name")?>" /><br />
						<label for="contact-info-sync" class="client-details-label">Client and contact the same:</label>
						<input id="contact-info-sync" name="contact-info-sync" class="contact-info-sync-input" type="checkbox" tabindex="11" value="info-sync" />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" <?php validateField("client_phone", $missingFields)?> class="client-details-label">Phone number:</label>
						<input id="client-phone" name="client-phone" class="client-phone-input" type="text" tabindex="2" value="<?php echo $client->getValueEncoded("client_phone")?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-email" <?php validateField("client_email", $missingFields)?> class="client-details-label">Email address:</label>
						<input id="client-email" name="client-email" class="client-email-input" type="text" tabindex="3" value="<?php echo $client->getValueEncoded("client_email")?>" />
					</li>
					<li class="client-details-item fax">
						<label for="client-fax" class="client-details-label">Fax number:</label>
						<input id="client-fax" name="client-fax" class="client-fax-input" type="text" tabindex="4" value="" />
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
                        </select>
                        
<!--					 <select id="client-currency" name="client-currency" class="client-currency-select" tabindex="10">
							<option value="">Select currency</option>
							<option selected="selected" value="USD">United States Dollar</option>
						</select>--><br />
					</li>
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
                        <!--modified field to be of type submit instead of button-->
                        <input id="client-add-btn" name="client-add-btn" class="client-add-btn" type="submit" value="+Add Client" /> or
						<!--end change-->
                        <a class="" href="#">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
        </form>
 <?php } 
 
 function processClient() {
	$requiredFields = array("client_name","client_address","client_state","client_phone","client_city","client_zip","client_email");
	$missingFields = array();
	$errorMessages = array();
	
	//create the object here and pass in the appropriate fields to the constructor. These values are now part of the client object.
	$client = new Client( array(
		//CHECK REG SUBS!!
		"client_name" => isset($_POST["client-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-name"]) : "",
		"client_phone" => isset($_POST["client-phone"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-phone"]) : "",
		"client_email" => isset($_POST["client-email"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-email"]) : "",
		"client_address" => isset($_POST["client-streetAddress"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-streetAddress"]) : "",
		"client_state" => isset($_POST["client-state"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-state"]) : "",
		"client_zip" => isset($_POST["client-zip"])? preg_replace("/[^0-9]/", "", $_POST["client-zip"]) : "",
		"client_city" => isset($_POST["client-city"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-city"]) : "",
		"client_currency_index" => isset($_POST["client_currency_index"])? preg_replace("/[^0-9]/", "", $_POST["client_currency_index"]) : "",
		"client_fax" => isset($_POST["client-fax"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client-fax"]) : "",
	));
	
	
	foreach($requiredFields as $requiredField) {
		if ( !$client->getValue($requiredField)) {
			$missingFields[] = $requiredField;
		}
	}
	
	if ($missingFields) {
		$errorMessages[] = '<p class="client-details-label required">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Send Details to resend the form.</p>';
	}
		
	if ($errorMessages) {
		displayClientInsertForm($errorMessages, $missingFields, $client);
	} else {
		$client_email=$_POST["client-email"];
		$client->insertClient($client_email);
		echo"You have successfully added client " . $client_email . ". You may add an additional client now. ";		
		echo"<a href=\"clients.php\">View the full client list</a>";
		//headers already sent, call the page back with blank attributes.
		displayClientInsertForm(array(), array(), new Client(array()));
	}
} 

//function displayClients() {
//	header("Location: clients.php");
//}
?>
 
 
 
		<section class="contact-detail">
			<header class="details-header contact-details-header">
				<h1 class="client-details-title">Contacts</h1>
			</header>
			<fieldset class="contact-details-entry">
				<legend class="contact-details-title">Enter contact details:</legend>
				<h4 class="required">= Required</h4>
				<ul class="details-list contact-details-list">
					<li class="contact-details-item name">
						<label for="contact-name" class="contact-details-label required">Your contact's name:</label>
						<input id="contact-name" name="contact-name" class="contact-name-input" type="text" tabindex="9" value="" /><br />
						<label for="contact-primary" class="contact-details-label">This the primary contact: </label>
						<input id="contact-primary" name="contact-primary" class="contact-primary-input" type="checkbox" tabindex="10" value="primary" />
					</li>
					<li class="contact-details-item phoneNum">
						<label for="contact-info-sync" class="contact-details-label">Client and contact the same:</label>
						<input id="contact-info-sync" name="contact-info-sync" class="contact-info-sync-input" type="checkbox" tabindex="11" value="info-sync" /><br />
						<label for="contact-officePhone" class="contact-details-label">Office phone:</label>
						<input id="contact-officePhone" name="contact-officePhone" class="contact-officePhone-input" type="text" tabindex="12" value="" /><br />
						<label for="contact-mobilePhone" class="contact-details-label">Mobile phone:</label>
						<input id="contact-mobilePhone" name="contact-mobilePhone" class="contact-mobilePhone-input" type="text" tabindex="13" value="" />
					</li>
					<li class="contact-details-item email">
						<label for="contact-email" class="contact-details-label">Email:</label>
						<input id="contact-email" name="contact-email" class="contact-email-input" type="text" tabindex="14" value="" />
					</li>
					<li class="contact-details-item fax">
						<label for="contact-fax" class="contact-details-label">Fax:</label>
						<input id="contact-fax" name="contact-fax" class="contact-fax-input" type="text" tabindex="15" value="" />
					</li>
					<li class="contact-details-item submit-contact">
						<label for="contact-add-btn" class="contact-details-label">All done?</label>
						<input id="contact-add-btn" name="contact-add-btn" class="contact-add-btn" type="button" value="+Add Contact" /> or
						<a class="" href="#">Cancel</a>
					</li>
					<li class="contact-details-item submit-additional">
						<label for="contact-add-btn" class="contact-details-label">Need to add more contacts?</label>
						<a href="#" class="">Add another contact</a>
					</li>
				</ul>
			</fieldset>
		</section>
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
<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>
