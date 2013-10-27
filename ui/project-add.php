<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Person.class.php");
	require_once("../classes/Project_Person.class.php");
	require_once("../classes/Project_Task.class.php");
	require_once("../common/errorMessages.php");
	include('header.php'); //add header.php to page
?>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Add New Project</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>

<!--OVERALL CONTROL--->
<?php 			if (isset($_POST["action"]) and $_POST["action"] == "project-add") {
					processProject();
				} else {
					displayProjectInsertForm(array(), array(), new Project(array()), new Project_Person(array()), new Project_Task(array()));
				} 
?>
<!--DISPLAY PROJECT INSERT WEB FORM--->
<?php function displayProjectInsertForm($errorMessages, $missingFields, $project) { 
	
	//if there are errors in the form display the message
	if ($errorMessages) {
		foreach($errorMessages as $errorMessage) {
			echo $errorMessage;
		}
	}
	
?>
	<form action="project-add.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
      <section class="content">
      <input type="hidden" name="action" value="project-add"/>
		<?php //BEGIN PROJECT ?>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter project details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter project details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<ul class="details-list client-details-list">
				<?php 
						//get the clients out to populate the drop down.
						list($clients) = Client::getClients();
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please select a client:</label>
                        <select name="client_id" id="client_currency_index" size="1">    
						<?php foreach ($clients as $client) { ?>
   							<option value="<?php echo $client->getValue("client_id") ?>"><?php echo $client->getValue("client_name")?></option>
    					<?php } ?>
						</select><br />
					</li>
		   			<li class="client-details-item name">
						<label for="client-name" <?php validateField("project_name", $missingFields)?> class="client-details-label">Project Name:</label>
						<input id="client-name" name="project-name" class="client-name-input" type="text" tabindex="1" value="<?php echo $project->getValueEncoded("project_name")?>" /><br />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" <?php validateField("project_code", $missingFields)?> class="client-details-label">Project Code (optional):</label>
						<input id="client-phone" name="project-code" class="client-phone-input" type="text" tabindex="2" value="<?php echo $project->getValueEncoded("project_code")?>" />
					</li>
					<?php //took these out for now. Do not expose them in the UI!?>
					<!--<li class="client-details-item email">
						<label for="client-email" <?php validateField("client_invoice_method", $missingFields)?> class="client-details-label">Invoice Method:</label>
						<input id="client-email" name="invoice-method" class="client-email-input" type="text" tabindex="3" value="<?php echo $project->getValueEncoded("project_invoice_by")?>" />
					</li>
					<li class="client-details-item fax">
						<label for="client-fax" class="client-details-label">Invoice Rate:</label>
						<input id="client-fax" name="project-invoice-rate" class="client-fax-input" type="text" tabindex="4" value="" />
					</li>-->
					<li class="client-details-item address">
						<label for="client-streetAddress" <?php validateField("project_notes", $missingFields)?> class="client-details-label">Project Notes:</label>
						<textarea id="client-streetAddress" name="project_notes" class="client-streetAddress-input" tabindex="5"><?php echo $project->getValueEncoded("project_notes")?></textarea><br />
					<!--	<label for="client-city" <?php validateField("project_budget_type", $missingFields)?> class="client-details-label">Project Budget Type:</label>
						<input id="client-city" name="project-budget-type" class="client-city-input" type="text" tabindex="6" value="<?php echo $project->getValueEncoded("project_budget_by")?>" /><br />
						<label for="client-zip" <?php validateField("project_budget_hours", $missingFields)?> class="client-details-label">Project Budget Hours:</label>
						<input id="client-zip" name="project-budget-hours" class="client-zip-input" type="text" tabindex="8" value="<?php echo $project->getValueEncoded("project_budget_total_hours")?>" /><br />
						<select id="client-country" name="client-country" class="client-country-select" tabindex="9">
							<option value="">Show project budget?</option>
							<option selected="selected" value="1">Yes</option>
							<option selected="selected" value="0">No</option>
						</select-->
					</li>
				</ul>
        	</fieldset>
		</section>		
		<?php //BEGIN TASKS 
			//obviously this is just the beginning of how this should ultimately work.
		?>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter Task details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter Task details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<li class="client-details-item phoneNum">
					<label for="client-phone" <?php validateField("task_id", $missingFields)?> class="client-details-label">Tasks associated with this project:</label>
					<input id="client-phone" name="task_id" class="client-phone-input" type="text" tabindex="2" value="" />
				</li>
				<ul class="details-list client-details-list">
				<?php 
						//get the taskss out to populate the drop down.
						list($tasks) = Task::getTasks();
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please choose a task:</label>
                        <select name="task_ids" id="client_currency_index" size="1">    
						<?php foreach ($tasks as $task) { ?>
   							<option value="<?php echo $task->getValue("task_id") ?>"><?php echo $task->getValue("task_name")?></option>
    					<?php } ?>
    			 </select><br />
					</li>
				</ul>
        	</fieldset>
		</section>
		<?php //BEGIN PEOPLE
		//obviously this is just the beginning of how this should ultimately work.
		?>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter Person details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter Person details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<li class="client-details-item phoneNum">
					<label for="client-phone" <?php validateField("person_id", $missingFields)?> class="client-details-label">People assigned to this project:</label>
					<input id="client-phone" name="person_id" class="client-phone-input" type="text" tabindex="2" value="" />
				</li>
				<ul class="details-list client-details-list">
				<?php 
						//get the people out to populate the drop down.
						list($people) = Person::getPeople();
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please choose a person:</label>
                        <select name="person_ids" id="client_currency_index" size="1">    
						<?php foreach ($people as $person) { ?>
   							<option value="<?php echo $person->getValue("person_id") ?>"><?php echo $person->getValue("person_first_name");echo " " . $person->getValue("person_last_name")?></option>
    					<?php } ?>
    			 </select><br />
					</li>
				</ul>
        	</fieldset>
		</section>
				<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
                        <input id="client-add-btn" name="project-add-btn" class="client-add-btn" type="submit" value="+ Add Project" tabindex="11"/> 
						 or <a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
			</section>
	</form>
<?php } ?>

<!--PROCESS THE CLIENT & THE CONTACT THAT WERE SUBMITTED--->
<?php function processProject() {
 	//these are the required project fields in this form
	$requiredFields = array("project_name");
	$missingFields = array();
	$errorMessages = array();
	
	
	//create the project object ($project)
	$project = new Project( array(
		"project_code" => isset($_POST["project-code"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-code"]) : "",
		"project_name" => isset($_POST["project-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-name"]) : "",
		"client_id" => isset($_POST["client_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client_id"]) : "",
		"project_billable" => isset($_POST["project_billable"]) ? preg_replace("/[^ A-Z]/", "", $_POST["project_billable"]) : "",
		"project_invoice_by" => isset($_POST["project-invoice-by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project-invoice-by"]) : "",
		"project_hourly_rate" => isset($_POST["project_hourly_rate"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_hourly_rate"]) : "",
		"project_budget_by" => isset($_POST["project-budget-by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project-budget-by"]) : "",
		"project_budget_total_fees" => isset($_POST["project-budget-total-fees"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-budget-total-fees"]) : "",
		"project_budget_total_hours" => isset($_POST["project-budget-total-hours"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-budget-total-hours"]) : "",
		"project_send_email" => isset($_POST["project_send_email"])? preg_replace("/[^ A-Z]/", "", $_POST["project_send_email"]) : "",
		"project_show_budget" => isset($_POST["project_show_budget"])? preg_replace("/[^ A-Z]/", "", $_POST["project_show_budget"]) : "",
		"project_budget_includes_expenses" => isset($_POST["project_budget_includes_expenses"])? preg_replace("/[^ A-Z]/", "", $_POST["project_budget_includes_expenses"]) : "",
		"project_notes" => isset($_POST["project-notes"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-notes"]) : "",
		"project_archived" => isset($_POST["project_archived"])? preg_replace("/[^ A-Z]/", "", $_POST["project_archived"]) : "",
	));
	//create the project_person object ($project_person)
	$project_person = new Project_Person( array(
		"person_id" => isset($_POST["person_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["person_id"]) : "",
		"total_budget_hours" => isset($_POST["total_project_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_project_hours"]) : "",
	));
	//create the project_task object ($project_task)
	$project_task = new Project_Task( array(
		"task_id" => isset($_POST["task_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["task_id"]) : "",
		"total_budget_hours" => isset($_POST["total_project_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_project_hours"]) : "",
	));
	
	error_log("here is the post<br>");
	error_log(print_r($_POST, true));
	error_log("here is the project array.<br>");
	error_log(print_r($project,true));
	error_log("here is the project_person array.<br>");
	error_log(print_r($project_person,true));
	error_log("here is the project_task array.<br>");
	error_log(print_r($project_task,true));	
	
//error messages and validation script
	foreach($requiredFields as $requiredField) {
			if ( !$project->getValue($requiredField)) {
				$missingFields[] = $requiredField;
			}	
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		//THIS NEEDS TO BE UDPATED WITH PREG_MATCH TO MAKE SURE EACH OBJECT IS PROPERLY VALIDATED, SEE CLIENT-EDIT FOR REFERENCE.
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>" . getErrorMessage("1",$missingField, $errorType) . "</li>";
			$i++;
		}
	} //else {
	/*take these out for projects until a little later
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
		displayProjectInsertForm($errorMessages, $missingFields, $project, $project_person);
	} else {
		try {
			$client_id = $project->getValue("client_id");
			//insert the project into the project table.
			$project->insertProject($client_id);
			//insert the project and the associated people into the project_people table.
			$project_id = Project::getProjectId($project->getValue("project_name"));
			$person_ids = explode(',', $project_person->getValue("person_id"));
			foreach ($person_ids as $person_id) {			
				$project_person->insertProjectPerson($person_id, $project_id[0]);
			}
			$task_ids = explode(',', $project_task->getValue("task_id"));
			foreach ($task_ids as $task_id) {				
				$project_task->insertProjectTask($task_id, $project_id[0]);
			}

			displayProjectInsertForm(array(), array(), new Project(array()), new Project_Person(array()), new Project_Task(array()));
		} catch (Error $e) {
			die("could not insert a project. " . $e->getMessage());
			
		}
	}
} 

?>
<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>