<?php

	function displayClientPage() {
		//this probably isn't right, but I'll use it for now. won't work if JavaScript is off.
		printf("<script>location.href='clients.php'</script>");
	}

	require_once("/Applications/MAMP/htdocs/time_tracker/classes/Client.class.php");
	require_once("/Applications/MAMP/htdocs/time_tracker/classes/Contact.class.php");
	
	//OVERALL CONTROL
	//	1. first time user comes in, call the displayClientAndContactsEditForm function.
	//	2. Set the client and contact objects to the value pulled from the database.
	//	3. User clicks on a button to submit the form, call the editClientAndContacts function.
	//	4. If required fields are missing in the form, re-display the form with error messages.
	//	5. If there are no missing required fields, call Client::updateClient AND Contact:updateContact--->	
				if (isset($_POST["action"]) and $_POST["action"] == "edit_client") {
					echo "user came in from form.";
					editClientAndContacts();
				} else {
					echo "showing the form, this is the first time the user has come in.";
					displayClientAndContactsEditForm(array(), array(), new Client(array()), new Contact(array()));
				}


	//DISPLAY CLIENT AND CONTACT EDIT WEB FORM (displayClientAndContactEditForm)
	//***note...I think we can remove the PHP validation to update the style in validateField*****
	//1. This is the form displayed to the user, the first time the user comes in it gets the client_id out of the $_GET variable (please encode!!)
	//2. If first time, pull the client and contact objects from the database.
	//3. on reocurring pulls, error messages may or may not be there, based on the user's input, object details will come from the $_POST variable.

function displayClientAndContactsEditForm($errorMessages, $missingFields, $client, $contact) {
	print_r($contact);
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	if (isset($_GET["client_id"])) {
		$client=Client::getClient($_GET["client_id"]);
		$contact=Contact::getContacts($_GET["client_id"]);
	}
	//at this point, $contact is an array.
?>

	<section class="content">
	<!--BEGIN FORM-->
	<form action="testArray.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
	<input type="hidden" name="action" value="edit_client">
	<?
	//we need to get the client_id into the $_POST so it is there when the user posts the form.
	//perhaps in the $_SESSION variable?
	if (isset($_GET["client_id"])) {?>
		<input type="hidden" name="client_id" value="<?php echo $_GET["client_id"]?>">
	<?php } ?>
		<section class="client-detail l-col-80">
			<fieldset class="client-details-entry">
				<ul class="details-list client-details-list">
					<li class="client-details-item name">
						<label for="client-name" class="client-details-label required">Client's name:</label>
						<input id="client-name" name="client-name" class="client-contact-info-input" type="text" tabindex="1" value="<?php echo $client->getValueEncoded("client_name")?>" />
					</li>
				</ul>
				<?php 
						//get the currencies from the table to populate the drop down.
						$currency = Client::getCurrency();
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Preferred currency:</label>
						<select name="client_currency_index" id="client_currency_index" size="1">    
						<?php foreach ($currency as $currencies) { ?>
   							<option value="<?php echo $currencies["client_currency_index"] ?>">1<?php echo $currencies["client_preferred_currency"]?></option>
    					<?php } ?>
<!--only commented this out because currencies are from the DB.--->
						<!-- <select id="client-currency" name="client-currency" class="client-currency-select" tabindex="10">
							<option value="">Select currency</option>
							<option selected="selected" value="USD">United States Dollar</option> -->
						</select><br />
					</li>

				<li class="client-details-item submit-client">
						<label for="client-save-btn" class="client-details-label">All done?</label>
						<input id="client-save-btn" name="client-save-btn" class="client-save-btn" type="submit" value="+ Save Changes" tabindex="11" /> or
						<a class="" href="#" tabindex="11">Cancel</a>
					</li>
			</fieldset>
		</section>
		<input type="hidden" name="action" value="edit_client">
		<section id="contact-detail" class="contact-detail">
			<fieldset id="contact-details" class="contact-details-entry">
				<!-- <legend class="contact-details-title">Edit contact details:</legend> -->
				<header class="contact-details-header">
					<h1 class="contact-details-title">Edit contact details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<!-- <h4 class="required">= Required</h4> -->
				<ul class="details-list client-details-list">
				<!--there are multiple contacts. loop through them.--->
						<!--this is SO going to break the UI!-->
						<!--need to figure out how to get these things into an array.-->
						<!--these values ARE part of the post, but there could be many of them.-->
						<!--we need to call them into an array of objects, not just an object.-->
						<!--start here Saturday-->
						<!--the object does not hold any values when it is sent back.-->
						<?php
						//list($contact) = $contact;
						//echo getType($contact);
						//THIS MUST BE IN A LOOP.
						$i = 0;
						$result_count = count($contact);
						if ($result_count == 1) {
						$contact = array($contact);
						}
					foreach ($contact as $contact) {
						?>
					<li class="client-details-item name">
						<label for="contact-name"> Your contact's name:</label>
						<input id="contact-name" name="contact-name-<?php echo $i ?>" class="contact-info-input" type="text" value="<?php echo $contact->getValue("contact_name")?>" /><br />
						<label for="contact-primary" class="contact-details-label">This the primary contact: </label>
						<input id="contact-primary" name="contact-primary" class="contact-info-input" type="checkbox" checked="<?php echo $contact->getValue("contact_primary") ?>" value="1" />
					</li>
					<li class="client-details-item submit-contact">
						<label for="contact-save-btn" class="contact-details-label">All done?</label>
						<input id="contact-save-btn" name="contact-save-btn" class="contact-save-btn" type="submit" value="+ Save Contact" tabindex="11" />
			</fieldset>
		</section>
		<?php $i++;
		} 
		?>
		
		
		
<!--END FORM-->
</form>
<?php 
}
		
//CLIENT AND CONTACT PROCESSING FUNCTIONS (editClientAndContacts();)
//	1. Set up the required fields in each of the forms.
//	2. Create the objects based on the values that were submitted the last time the user submitted the form.
//	3. Set up the required fields in the $requiredFields array.
//	4. Compare the existence of the fields in the objects (based on the $_POST values) with the fields in the $requiredFields array. If
//	any are missing, put the fields into the $missingFields[] array.
//	5. If the $missingFields array exists, loop through them and call the error message. If there are NO missing fields, still call the error message for the NON missing field errors (email, phone, etc).
//	6. If there are error messages, call displayClientAndContactsEditForm with the error messages, the missing fields, and all the data for the object and the whole thing starts over again.
//	7. If there are no errors, update the database with the new client and contact information.
//	8. If all went well, display the client details page.
function editClientAndContacts() {
	$requiredFields = array("client_name","contact_name");
	$missingFields = array();
	$errorMessages = array();
	
	
	//CREATE THE CLIENT OBJECT ($CLIENT)
	$client = new Client( array(
		//CHECK REG SUBS!!
		"client_id" => isset($_POST["client_id"]) ? preg_replace("/[^ 0-9]/", "", $_POST["client_id"]) : "",
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


	//CREATE THE CONTACT OBJECT ($CONTACT)
	$client_id = $client->getValue("client_id");
	$numContacts = Contact::getNumberOfContacts($client_id);
	for ($i=0; $i<$numContacts; $i++) {
	$contact[] = new Contact( array(
		//CHECK REG SUBS!!
		"contact_name" => isset($_POST["contact-name-$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["contact-name-$i"]) : "",
		"contact_primary" => isset($_POST["contact-primary-$i"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["contact-primary-$i"]) : "",
		"contact_office_number" => isset($_POST["contact_office_number_$i"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["contact_office_number"]) : "",
		"contact_mobile_number" => isset($_POST["contact_mobile_number_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["contact_mobile_number"]) : "",
		"contact_email" => isset($_POST["contact_email_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["contact_email_$i"]) : "",
		"contact_fax_number" => isset($_POST["contact_fax_number_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["contact_fax_number_$i"]) : "",
	));
	}
	//print_r($contact);

//error messages and validation script.
//these errors may happen in the client OR the contact object, so we have to
//call each separately.
	foreach($requiredFields as $requiredField) {
		if (preg_match("/client/", $requiredField)) {
			if ( !$client->getValue($requiredField)) {
				$missingFields[] = $requiredField;
			}
		} elseif (preg_match("/contact/", $requiredField)) {
			foreach ($contact as $contact) {
			if (!$contact->getValue($requiredField)) {
				$missingFields[] = $requiredField;
			}
			}
		}			
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>problem</li>";
			$i++;
		}
	} 
		
	if ($errorMessages) {
		displayClientAndContactsEditForm($errorMessages, $missingFields, $client, $contact);
	} else {
		echo "GOT HERE";
		//OK, all is well, why isn't it showing in the UI?
		//there were no errors in the form. Update the client
		$client_id = $client->getValue("client_id");
		$client->updateClient($client_id);
		//$contact->updateContact($client_id);
		
		displayClientPage();
	}
}
?>


<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>
