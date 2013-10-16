<!DOCTYPE html>
<?php
	function displayProjectPage() {
		//this probably isn't right, but I'll use it for now. won't work if JavaScript is off.
		printf("<script>location.href='projects.php'</script>");
	}
	
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Client.class.php");

	include('header.php'); //add header.php to page
?>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Edit Client Details</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<li class="page-controls-item link-btn"><a class="add-client-link" href="project-add.php">+ Add Project</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="project-archives.php">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<!--OVERALL CONTROL
		1. first time user comes in, call the displayClientAndContactsEditForm function.
		2. Set the client and contact objects to the value pulled from the database.
		3. User clicks on a button to submit the form, call the editClientAndContacts function.
		4. If required fields are missing in the form, re-display the form with error messages.
		5. If there are no missing required fields, call Project::updateProject-->	
<?php 			
				if (isset($_POST["action"]) and $_POST["action"] == "edit_project") {
					editProject();
				} else {
					displayProjectEditForm(array(), array(), new Project(array()));
				}
	
	/*DISPLAY PROJECT EDIT WEB FORM (displayProjectEditForm)
	note...I think we can remove the PHP validation to update the style in validateField
	1. This is the form displayed to the user, the first time the user comes in it gets the client_id out of the $_GET variable (please encode!!)
	2. If first time, pull the project object from the database.
	3. on reocurring pulls, error messages may or may not be there, based on the user's input, object details will come from the $_POST variable.*/
?>	
<?php function displayProjectEditForm($errorMessages, $missingFields, $project) {
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	if (isset($_GET["project_id"])) {
		$project=Project::getProjectByProjectId($_GET["project_id"]);
	}
	
?>

	<section class="content">
	<!--BEGIN FORM-->
	<form action="project-edit.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
	<input type="hidden" name="action" value="edit_project">
	<input type="hidden" name="project_id" value="<?php echo $_GET["project_id"]?>">
		<section class="client-detail l-col-80">
			<fieldset class="client-details-entry">
				<!-- <legend class="client-details-title">Edit client details:</legend> -->
				<header class="client-details-header">
					<h1 class="client-details-title">Edit project details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<ul class="details-list client-details-list">
				<?php 
						//get the clients out to populate the drop down.
						list($clients) = Client::getClients();
					?>
					<li class="client-details-item address">
						<label for="client-zip" <?php validateField("project_name", $missingFields)?> class="client-details-label">Project Name:</label>
						<input id="client-zip" name="project-name" class="client-zip-input" type="text" tabindex="8" value="<?php echo $project->getValueEncoded("project_name")?>" /><br />
						<label for="client-city" <?php validateField("project_code", $missingFields)?> class="client-details-label">Project Code</label>
						<input id="client-city" name="project_code" class="client-city-input" type="text" tabindex="6" value="<?php echo $project->getValueEncoded("project_code")?>" /><br />
						<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please select a client:</label>
                        <select name="client_id" id="client_currency_index" size="1">    
						<?php foreach ($clients as $client) { ?>
   							<option value="<?php echo $client->getValue("client_id") ?>"><?php echo $client->getValue("client_name")?></option>
    					<?php } ?>
    			 </select><br />
						<label for="client-streetAddress" <?php validateField("project_notes", $missingFields)?> class="client-details-label">Project Notes:</label>
						<textarea id="client-streetAddress" name="project-notes" class="client-streetAddress-input" tabindex="5"><?php echo $project->getValueEncoded("project_notes")?></textarea><br />
						<label for="client-city" <?php validateField("project_archived", $missingFields)?> class="client-details-label">Project is Archived?</label>
						<input id="client-city" name="project-archived" class="client-city-input" type="text" tabindex="6" value="<?php echo $project->getValueEncoded("project_archived")?>" /><br />
				</ul>
			</fieldset>
		</section>
						<input id="contact-save-btn" name="project-save-btn" class="contact-save-btn" type="submit" value="+ Save Project" tabindex="11" /> or
						<a class="" href="#" tabindex="11">Cancel</a>
<!--END FORM-->
</form><?php } ?>
		

<!--PROJECT PROCESSING FUNCTIONS (editProjects();)
	1. Set up the required fields.
	2. Create the object based on the values that were submitted the last time the user submitted the form.
	3. Set up the required fields in the $requiredFields array.
	4. Compare the existence of the fields in the objects (based on the $_POST values) with the fields in the $requiredFields array. If
	any are missing, put the fields into the $missingFields[] array.
	5. If the $missingFields array exists, loop through them and call the error message. If there are NO missing fields, still call the error message for the NON missing field errors (email, phone, etc).
	6. If there are error messages, call displayProjectEditForm with the error messages, the missing fields, and all the data for the object and the whole thing starts over again.
	7. If there are no errors, update the database with the new project information.
	8. If all went well, display the project details page.
	-->
<?php function editProject() {
	$requiredFields = array("project_name");
	$missingFields = array();
	$errorMessages = array();
		
	//CREATE THE PROJECT OBJECT ($PROJECT)
	$project = new Project( array(
		//CHECK REG SUBS!!
		"project_id" => isset($_POST["project_id"]) ? preg_replace("/[^ 0-9]/", "", $_POST["project_id"]) : "",
		//not available for edit.
		"project_code" => isset($_POST["project_code"]) ? preg_replace("/[^ 0-9]/", "", $_POST["project_code"]) : "",
		"project_name" => isset($_POST["project-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-name"]) : "",
		"client_id" => isset($_POST["client_id"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["client_id"]) : "",
		"project_invoice_method" => isset($_POST["project_invoice_method"]) ? preg_replace("/[^ \-\_a-zA-Z0-9^@^.]/", "", $_POST["project_invoice_method"]) : "",
		"project_invoice_rate" => isset($_POST["project_invoice_rate"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_invoice_rate"]) : "",
		"project_budget_type" => isset($_POST["project_budget_type"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_type"]) : "",
		"project_budget_hours" => isset($_POST["project_budget_hours"])? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["project_budget_hours"]) : "",
		"project_show_budget" => isset($_POST["project_show_budget"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_show_budget"]) : "",
		"project_send_email" => isset($_POST["project_send_email"])? preg_replace("/[^0-9]/", "", $_POST["project_send_email"]) : "",
		"project_notes" => isset($_POST["project-notes"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["project-notes"]) : "",
		"project_archived" => isset($_POST["project-archived"]) ? preg_replace("/[^ \-\_a-zA-Z^0-9]/", "", $_POST["project-archived"]) : "",
	));
	
	error_log("here are the values in the POST array");
	error_log(print_r($_POST,true));
	error_log("Here are the values in the client array.");
	error_log(print_r($project,true));	
		
//error messages and validation script.
//these errors may happen in the client OR the contact object, so we have to
//call each separately.
	foreach($requiredFields as $requiredField) {
		if ( !$project->getValue($requiredField)) {
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
		//TAKE THIS OUT FOR NOW AND DEAL WITH IT LATER!!
		/*$clientEmail = $client->getValue("client_email");
		$clientPhone = $client->getValue("client_phone");
		$clientZip = $client->getValue("client_zip");
		$contactEmail = $contact->getValue("contact_email");
		$contactOfficePhone = $contact->getValue("contact_office_number");
		$contactMobilePhone = $contact->getValue("contact_mobile_number");
		$contactFax = $contact->getValue("contact_fax_number");
		
		
		// validate the email addresses
		if(!preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $clientEmail)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"client_email", "invalid_input") . "</li>";
		}
		if(!preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $contactEmail)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"contact_email", "invalid_input") . "</li>";
		}
		
		// validate the phone numbers
		if(!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $clientPhone)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"client_phone", "invalid_input") . "</li>";
		}
		if(!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $contactOfficePhone)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"contact_office_number", "invalid_input") . "</li>";
		}
		if(!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $contactMobilePhone)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"contact_mobile_number", "invalid_input") . "</li>";
		}
		if(!preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $contactFax)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"contact_fax_number", "invalid_input") . "</li>";
		}
		
		//validate the zip code
		if (!preg_match ("/^[0-9]{5}$/", $clientZip)) {
			$errorMessages[] = "<li>" . getErrorMessage(1,"client_zip", "invalid_input") . "</li>";
		}	*/
	}
		
	if ($errorMessages) {
		error_log("There were errors in the input (errorMessages was not blank. Redisplaying the edit form with missing fields.");
		//error_log($contact[0]);
		//error_log(gettype($contact));
		displayClientAndContactsEditForm($errorMessages, $missingFields, $project);
	} else {
		error_log("All of the required fields are there...Updating database...");
		$project_id=$project->getValue("project_id");
		$project->updateProject($project_id);		
		displayProjectPage();	
	}
}

?>

<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>
