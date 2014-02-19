<!DOCTYPE html>
<html lang="en">
<?php echo $library_src;?>
<script>
$(document).ready( function() {
	var task_ids = [];
	var task_names = [];
	 $('#task_ids').change( function() {
		task_ids.push($('#task_ids').val());
		task_names.push($("#task_ids option:selected").text());
		var budgetOptions=document.getElementById("project_budget_by");
		var selectedBudgetOption = budgetOptions.options[budgetOptions.selectedIndex].text;
		//create a table with the task names and task ids, they will be used to send the
		//hours per task and task hourly rate fields to the db 
		taskTable = "<table><tr><td>Task</td><td>Budget</td><td>Hourly Rate</td></tr><tr><td>" + $("#task_ids option:selected").text() + "</td><td><input style=width:50px;display:none; name=" + $('#task_ids').val() + "_hours_per_task class=hours_per_task></td><td><input style=width:50px;display:none; name=" + $('#task_ids').val() + "_task_hourly_rate class=task_hourly_rate></td></tr></table>";
		$('<div class="task_input">' + taskTable + '</div>').css({
			width: 400,
			height: 70
		
		}).appendTo('#associated_tasks');
		$('.task_id').val(task_ids); 
		if ($('#project_budget_by').val() == "Hours per task") {
			$('.hours_per_task').show();	
		} else {
			$('.hours_per_task').hide();
		}
		if ($('#project_invoice_by').val() == "Task hourly rate") {
			$('.task_hourly_rate').show();	
		} else {
			$('.task_hourly_rate').hide();
		}
	}); 
	
	
	var person_ids = [];
	var person_names = [];
	 $('#person_ids').change( function() {
		person_ids.push($('#person_ids').val());
		person_names.push($("#person_ids option:selected").text());
		var budgetOptions=document.getElementById("project_budget_by");
		var selectedBudgetOption = budgetOptions.options[budgetOptions.selectedIndex].text;

		personTable = "<table><tr><td>Person</td><td>Budget</td><td>Hourly Rate</td></tr><tr><td>" + $("#person_ids option:selected").text() + "</td><td><input style=width:50px;display:none; name=" + $('#person_ids').val() + "_hours_per_person class=hours_per_person></td><td><input style=width:50px;display:none; name=" + $('#person_ids').val() + "_person_hourly_rate class=person_hourly_rate></td></tr></table>";
		$('<div class="person_input">' + personTable + '</div>').css({
			width: 400,
			height: 70
		
		}).appendTo('#associated_people');
		$('.person_id').val(person_ids); 
		if ($('#project_budget_by').val() == "Hours per person") {
			$('.hours_per_person').show();	
		} else {
			$('.hours_per_person').hide();
		}
		if ($('#project_invoice_by').val() == "Person hourly rate") {
			$('.person_hourly_rate').show();	
		} else {
			$('.person_hourly_rate').hide();
		}
	}); 
	
	
	$('#project_budget_by').change( function() {
		if ($('#project_budget_by').val() == "Hours Per Task") {
			$('.hours_per_task').show();
			alert('x');	
		} else {
			$('.hours_per_task').hide();
		}
		if ($('#project_budget_by').val() == "Hours Per Person") {
			$('.hours_per_person').show();	
		} else {
			$('.hours_per_person').hide();
		}
		if ($('#project_budget_by').val() == "Total Project Hours") {
			$('.project_budget_total_hours').show();	
		} else {
			$('.project_budget_total_hours').hide();
		}
		if ($('#project_budget_by').val() == "Total Project Fees") {
			$('.project_budget_total_hours').hide();	
		} else {
			$('.project_budget_total_hours').show();
		}
	});

	
	$('#project_invoice_by').change( function() {
		if($('#project_invoice_by').val() == "Project Hourly Rate"){
			$('#project_hourly_rate').show();
	    } else {
		    $('#project_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Task Hourly Rate"){
			$('.task_hourly_rate').show();
	    } else {
		    $('.task_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Person Hourly Rate"){
			$('.person_hourly_rate').show();
	    } else {
		    $('.person_hourly_rate').hide();
	    }
	});
});
</script>

		<form action="<?php echo site_url('project_controller/insert_project')?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">
      <section class="content">
      <!--input type="hidden" name="action" value="project-add"/-->
		<?php //BEGIN PROJECT ?>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter project details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter project details:</h1>
					<h4 class="required">= Required</h4>
				</header>
													<?php echo validation_errors(); ?>

				<ul class="details-list client-details-list">
				<?php 
						//get the clients out to populate the drop down.
						//list($clients) = Client::getClients();
					?>

					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please select a client:</label>
                        <?php $client_list = array(); 
							foreach ($clients as $client) {
								$client_list[$client->client_id]=$client->client_name; 
							}
							echo form_dropdown('client_ids', $client_list, set_value('client_ids'),'id="client_ids"');
						?>

                        <br />
					</li>
		   			<li class="client-details-item name">
						<label for="client-name" class="client-details-label">Project Name:</label>
						<input id="client-name" name="project-name" class="client-name-input" type="text" tabindex="1" value="<?php echo set_value('project-name') ?>" /><br />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone"  class="client-details-label">Project Code (optional):</label>
						<input id="client-phone" name="project-code" class="client-phone-input" type="text" tabindex="2" value="<?php echo set_value('project-code') ?>" />
					</li>
					<?php //took these out for now. Do not expose them in the UI!?>
					<h4>Invoice Methods:</h4>
					<li class="billable">
						<input type="radio" id="project_billable" name="project_billable" class="client-email-input" tabindex="3" value="0" />
						<label for="project_billable" class="client-details-label">This project is not billable</label><br/>
						<input type="radio" id="project_billable" name="project_billable" class="client-email-input" tabindex="3" value="1" checked/>
						<label for="project_billable" class="client-details-label">This project is billable and we invoice by:</label>
					</li>
						<?php 
						
						$invoice_list = array(
							'Do not apply hourly rate' => 'Do not apply hourly rate',
							'Project Hourly Rate' => 'Project Hourly Rate',
							'Person Hourly Rate' => 'Person Hourly Rate',
							'Task Hourly Rate' => 'Task Hourly Rate',
							);
							echo form_dropdown('project_invoice_by', $invoice_list, set_value('project_invoice_by'), 'id="project_invoice_by"');
							?>
					</li>
						 <input id="project_hourly_rate" name="project_hourly_rate" style="width:50px;display:none;" value="$"/>

						<h4>Budget</h4>
						
						<?php 
						
						$budget_list = array(
							'No budget' => 'No budget',
							'Total Project Hours' => 'Total Project Hours',
							'Total Project Fees' => 'Total Project Fees',
							'Hours Per Task' => 'Hours Per Task',
							'Hours Per Person' => 'Hours Per Person',
							);
							echo form_dropdown('project_budget_by', $budget_list, set_value('project_budget_by'),'id="project_budget_by"');
							?>    
						<input id="project_budget_total_hours" name="project_budget_total_hours" style="width:50px;display:none;" value="Hours"/>
						<input id="project_budget_total_fees" name="project_budget_total_fees" style="width:50px;display:none;" value="$"/><br/>
						<input type="checkbox" id="project_budget_includes_expenses" name="project_budget_includes_expenses" style="display:none;"tabindex="3" />
						<div style="display:none;" id="project_budget_includes_expenses_label" for="project_budget_includes_expenses_label" class="project_budget_includes_expenses_label" >Budget includes project expenses</div>						
						<li class="invoice_instructions">
						<input type="checkbox" id="project_show_budget" name="project_show_budget" class="project_show_budget" tabindex="3" />
						<label for="project_show_budget" class="project_show_budget">Show budget report to all employees and contractors on this project</label><br/>
						<input type="checkbox" id="project_send_email" name="project_send_email" tabindex="3" />
						<label for="project_email" class="client-details-label">Send email alerts if project reaches	<input id="project_send_email_percentage" name="project_send_email_percentage" style="width:50px;" value=""/> of budget</label>
						</li>
						<label for="client-streetAddress" class="client-details-label">Project Notes:</label>
						<textarea id="project-notes" name="project-notes" class="project-notes" tabindex="5"><?php echo set_value('project-notes') ?></textarea><br />
						</select>
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
					<label for="client-phone" class="client-details-label"></label>
					<input id="task_id" name="task_id" class="task_id" type="text" tabindex="2" value="<?php echo set_value('task_id') ?>" />

				<p id="associated_tasks"><b>Tasks Associated with this project:</b></p>

				<ul class="details-list client-details-list">
				<?php 
						//get the taskss out to populate the drop down.
						//list($tasks) = Task::getTasks("0");
					?>
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please choose a task:</label>
						<?php $task_list = array(); 
							foreach ($tasks as $task) {
								$task_list[$task->task_id]=$task->task_name; 
							}
							echo form_dropdown('task_ids', $task_list, set_value('task_ids'),'id="task_ids"');
						?>
    			 <br />
					</li>
				</ul>
        	</fieldset>
		</section>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter Person details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter Person details:</h1>
					<h4 class="required">= Required</h4>
				</header>
					<label for="client-phone" class="client-details-label">People assigned to this project:</label>
					<input id="person_id" name="person_id" class="person_id" type="text" tabindex="2" value="<?php echo set_value('person_id') ?>" />
				
				<p id="associated_people"><b>People Associated with this project:</b></p>

				<ul class="details-list client-details-list">
					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Please choose a person:</label>
						<?php $person_list = array(); 
							foreach ($people as $person) {
								$person_list[$person->person_id]=$person->person_first_name . " " . $person->person_last_name; 
							}
							echo form_dropdown('person_ids', $person_list, set_value('person_ids'),'id="person_ids"');
							?>
                        <br />
					</li>
				</ul>
        	</fieldset>
		</section>
				<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
                        <input id="client-add-btn" name="project-add-btn" class="client-add-btn" type="submit" value="+ Add Project" tabindex="11"/> 
						 or <a class="" href="projects.php" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
			</section>
	</form>


<!--PROCESS THE CLIENT & THE CONTACT THAT WERE SUBMITTED--->
<?php /*function processProject() {
 	//these are the required project fields in this form
	$requiredFields = array("project_name");
	$missingFields = array();
	$errorMessages = array();
	
	
	//create the project object ($project)
	
	$project = new Project( array(
		"project_code" => isset($_POST["project-code"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-code"]) : "",
		"project_name" => isset($_POST["project_name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_name"]) : "",
		"client_id" => isset($_POST["client_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client_id"]) : "",
		"project_billable" => isset($_POST["project_billable"]) ? preg_replace("/[^ A-Z]/", "", $_POST["project_billable"]) : "",
		"project_invoice_by" => isset($_POST["project_invoice_by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project_invoice_by"]) : "",
		"project_hourly_rate" => isset($_POST["project_hourly_rate"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_hourly_rate"]) : "",
		"person_hourly_rate" => isset($_POST["person_hourly_rate"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_hourly_rate"]) : "",
		"task_hourly_rate" => isset($_POST["task_hourly_rate"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_hourly_rate"]) : "",
		"project_budget_by" => isset($_POST["project_budget_by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project_budget_by"]) : "",
		"project_budget_total_fees" => isset($_POST["project_budget_total_fees"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_total_fees"]) : "",
		"project_budget_total_hours" => isset($_POST["project_budget_total_hours"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_total_hours"]) : "",
		"project_send_email_percentage" => isset($_POST["project_send_email_percentage"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_send_email_percentage"]) : "",
		"project_show_budget" => isset($_POST["project_show_budget"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_show_budget"]) : "",
		"project_budget_includes_expenses" => isset($_POST["project_budget_includes_expenses"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_includes_expenses"]) : "",
		"project_notes" => isset($_POST["project-notes"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project-notes"]) : "",
		"project_archived" => isset($_POST["project_archived"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_archived"]) : "",
	));
	//create the project_person object ($project_person)
	$project_person = new Project_Person( array(
		"person_id" => isset($_POST["person_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["person_id"]) : "",
		"total_budget_hours" => isset($_POST["total_project_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_project_hours"]) : "",
	));
	//create the project_task object ($project_task)
	$project_task = new Project_Task( array(
		"task_id" => isset($_POST["task_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["task_id"]) : "",
		"total_budget_hours" => isset($_POST["total_budget_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_budget_hours"]) : "",
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
		
	/*if ($errorMessages) {
		displayProjectInsertForm($errorMessages, $missingFields, $project, $project_person);
	} else {
		try {
			//clean up the checkboxes here.
			if (isset($project) && $project->getValue("project_show_budget") == "on") {
				$project->setValue("project_show_budget", 1);					
			} else {
				$project->setValue("project_show_budget",0);
			}
			if (isset($project) && $project->getValue("project_billable") == "on") {
				$project->setValue("project_billable", 1);					
			} else {
				$project->setValue("project_billable",0);
			}
			if (isset($project) && $project->getValue("project_budget_includes_expenses") == "on") {
				$project->setValue("project_budget_includes_expenses", 1);					
			} else {
				$project->setValue("project_budget_includes_expenses",0);
			}
			//end checkbox cleanup
			$client_id = $project->getValue("client_id");
			//get out who is assigning this project.
			if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
				$person = Person::getByEmailAddress($_SESSION["logged_in"]);
			} else {
				error_log("Something is wrong here...this person is not logged in and you shouldn't be seeing this, project_add.php.");
				exit();
			}
			//insert the project into the project table.
			$project->insertProject($client_id);
			//insert the project and the associated people into the project_people table.
			$project_id = Project::getProjectId($project->getValue("project_name"));
			$person_ids = explode(',', $project_person->getValue("person_id"));
			foreach ($person_ids as $person_id) {
			if ($person_id) {
				//echo "inserting person id " . $person_id . " and " . "project id " . $project_id["project_id"]; 
				if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
						$project_assigned_by = Person::getByEmailAddress($_SESSION["logged_in"]);
				} else {
						error_log("Something is wrong here...this person is not logged in and you shouldn't be seeing this, project_add.php.");
						echo "please log in.";
						exit();
				}
				$budget_hours = 0;
				if (isset($_POST[$person_id."_hours_per_person"])) {
					$budget_hours = $_POST[$person_id."_hours_per_person"];
				}
				$project_person->insertProjectPerson($person_id, $project_id["project_id"], $project_assigned_by->getValueEncoded("person_email"), $budget_hours);
				$person_hourly_rate = 0;
				if (isset($_POST[$person_id."_person_hourly_rate"])) {
					$person_hourly_rate = $_POST[$person_id . "_person_hourly_rate"];
				}
				Person::updatePersonHourlyRate($person_id, $person_hourly_rate);
			}
			}
			$task_ids = explode(',', $project_task->getValue("task_id"));
			list($commonTasks) = Task::getCommonTasks();
			foreach($commonTasks as $commonTask) {
				$task_ids[] = $commonTask->getValue("task_id");
			}
			//don't try to insert common tasks twice if we are putting them in
			$task_ids = array_unique($task_ids);
			//print_r($task_ids);
			foreach ($task_ids as $task_id) {				
			if ($task_id) {
				//update the project_task table with the project and the task, and the budgeted hours if the project
				//is budgeted by hours per task.
				$budget_hours = 0;
				if (isset($_POST[$task_id."_hours_per_task"])) {
					$budget_hours = $_POST[$task_id . "_hours_per_task"];
				}
				//echo "inserting task id " . $task_id . " and " . "project id " . $project_id["project_id"] . " and " . $_POST[$task_id] . "with budget " . $budget_hours;
				$project_task->insertProjectTask($task_id, $project_id["project_id"], $budget_hours);
				//update the task table with the task hourly rate if the project is invoiced by task hourly rate.
				$task_hourly_rate = 0;
				if (isset($_POST[$task_id."_task_hourly_rate"])) {
					$task_hourly_rate = $_POST[$task_id . "_task_hourly_rate"];
				}
				Task::updateTaskHourlyRate($task_id, $task_hourly_rate);
			}
			}

			displayProjectInsertForm(array(), array(), new Project(array()), new Project_Person(array()), new Project_Task(array()));
		} catch (Error $e) {
			die("could not insert a project. " . $e->getMessage());
			
		}
	}
} 

*/?>

<footer id="site-footer" class="site-footer">

</footer>
<script src="<?php echo "$this->base/js/client-controls.js" ?>" type="text/javascript"></script>
</body>
</html>