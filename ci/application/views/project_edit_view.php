<?php

//add the update and figure out why the fields copied to the input field are so funky!

	
	/*require_once("../common/common.inc.php");
	require_once("../common/errorMessages.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Project_Person.class.php");
	require_once("../classes/Project_Task.class.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Person.class.php");
*/
	
?>
<?php	/*OVERALL CONTROL*/	
 		/*	if (isset($_GET["func"])) {
				if ($_GET["func"] == "returnClientMenu") {
					echo returnClientMenu();
				}
			} else if (isset($_POST["func"])) {
				if ($_POST["func"] == "editProject") {
					editProject();
				}
			} else {
				if (isset($_POST["action"]) and $_POST["action"] == "edit_project") {
					editProject();
				} else {
					displayProjectEditForm(array(), array(), new Project(array()), new Project_Person(array()), new Project_Task(array()));
				}
			}
	*/
	/*DISPLAY PROJECT EDIT WEB FORM (displayProjectEditForm)
	*/
?>	
<?php
	//Returns a select menu of clients with ids. Meant to be called via ajax.
	function returnClientMenu() {
		//$select = "<strong>test</strong>";
		$select = "";
		//get the clients out to populate the drop down.
		list($clients) = Client::getClients();
		$select .= '<select name="client_id" id="project-client-select" size="1">';
		
		foreach ($clients as $client) {
			$select .= '<option value="' . $client->getValue("client_id") . '">' . $client->getValue("client_name") .'</option>';
		}
		$select .= '</select>';
		
		return $select;
	}
	
	/*function displayProjectEditForm($errorMessages, $missingFields, $project, $project_person, $project_task) {
		if ($errorMessages) {
			foreach($errorMessages as $errorMessage) {
				echo $errorMessage;
			}
		}
		
		//get  out the project ID, since the user came in from the edit project button.
		if (isset($_GET["project_id"])) {
			$project_id = $_GET["project_id"];
			$project=Project::getProjectByProjectId($project_id);
		} elseif (isset($_POST["project_id"])) {
			$project_id = $_POST["project_id"];
			$project=Project::getProjectByProjectId($project_id);
		} else {
			echo "You cannot edit a project unless you have provided a project ID.";
			exit();
		}
		

		
	include('header.php'); //add header.php to page*/
	//print_r($project);
?>
<script>
$(document).ready( function() {
		//set up the budget fields to show defaults here
		if ($('#project_budget_by').val() == "Hours per task") {
			$('.hours_per_task').show();	
		} else {
			$('.hours_per_task').hide();
		}
		if ($('#project_budget_by').val() == "Hours per person") {
			$('.hours_per_person').show();	
		} else {
			$('.hours_per_person').hide();
		}
		if ($('#project_budget_by').val() == "Total project hours") {
			$('.project_budget_total_hours').show();	
		} else {
			$('.project_budget_total_hours').hide();
		}
		if ($('#project_budget_by').val() == "Total project fees") {
			$('.project_budget_total_hours').hide();	
		} else {
			$('.project_budget_total_hours').show();
		}
		//set up the invoice fields to show defaults here
		if ($('#project_invoice_by').val() == "Person hourly rate") {
			$('.person_hourly_rate').show();	
		} else {
			$('.person_hourly_rate').hide();
		}
		if($('#project_invoice_by').val() == "Project hourly rate"){
			$('#project_hourly_rate').show();
	    } else {
		    $('#project_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Task hourly rate"){
			$('.task_hourly_rate').show();
	    } else {
		    $('.task_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Person hourly rate"){
			$('.person_hourly_rate').show();
	    } else {
		    $('.person_hourly_rate').hide();
	    }


	var task_ids = [];
	//get out the task ids that are already there.
	task_ids.push($('#task_id').val());	
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
	//get out the person ids that are already there.
	person_ids.push($('#person_id').val());	
	var person_names = [];
	 $('#person_ids').change( function() {
	 	//alert($('.person_id').val());
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
		if ($('#project_budget_by').val() == "Hours per task") {
			$('.hours_per_task').show();	
		} else {
			$('.hours_per_task').hide();
		}
		if ($('#project_budget_by').val() == "Hours per person") {
			$('.hours_per_person').show();	
		} else {
			$('.hours_per_person').hide();
		}
		if ($('#project_budget_by').val() == "Total project hours") {
			$('.project_budget_total_hours').show();	
		} else {
			$('.project_budget_total_hours').hide();
		}
		if ($('#project_budget_by').val() == "Total project fees") {
			$('.project_budget_total_hours').hide();	
		} else {
			$('.project_budget_total_hours').show();
		}
	});

	
	$('#project_invoice_by').change( function() {
		if($('#project_invoice_by').val() == "Project hourly rate"){
			$('#project_hourly_rate').show();
	    } else {
		    $('#project_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Task hourly rate"){
			$('.task_hourly_rate').show();
	    } else {
		    $('.task_hourly_rate').hide();
	    }
	    if($('#project_invoice_by').val() == "Person hourly rate"){
			$('.person_hourly_rate').show();
	    } else {
		    $('.person_hourly_rate').hide();
	    }
	});
});
</script>
<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Edit Project: <?php echo $project[0]->project_name ?></h1>
		<h2 class="page-sub-title"><a href="<?php echo "client_detail/" . $project[0]->client_id?>" class="" title="View client's details"><?php echo $clients[0]->client_name ?></a></h2>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="save-link" href="#">Save Project</a></li>
				<li class="page-controls-item"><a class="view-archive-link" href="projects.php?archives=1">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>


	<div class="content">
		<!--BEGIN FORM-->
			<? $project_id = $this->uri->segment(3);?>
			<form action="<?php echo site_url("project_controller/update_project/$project_id")?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">
			<?php echo validation_errors(); 
					//print_r($client);
				?>
			<input type="hidden" name="action" value="edit_project">
			<input type="hidden" name="project_id" value="<?php echo $project[0]->project_id?>">
			<article class="entity-detail">
				<fieldset class="entity-details-entry">
					<header class="entity-details-header project">
						<h1 class="entity-details-title">Project Settings:</h1>
						<h4 class="required">= Required</h4>
					</header>
					
					<section id="project-info" class="entity-detail">
						<h2 class="entity-sub-title">Project Info</h2>
						<ul class="details-list entity-details-list project">
							<li class="entity-details-item name project">
								<label for="project_name" class="entity-details-label">Project Name:</label>
								<input id="project-name" name="project-name" class="project-name-input" type="text" tabindex="1" value="<?php echo set_value('project-name', $project[0]->project_name);?>" />
							</li>
							<li class="entity-details-item project-code project">
								<label for="project_code" class="entity-details-label">Project Code</label>
								<input id="project-code" name="project-code" class="project-code-input" type="text" tabindex="2" value="<?php echo set_value('project-code', $project[0]->project_code);?>" />
							</li>
							<li class="entity-details-item project-client project">
								<label for="client_id" class="entity-details-label">Select the client:</label>
								<select name="client_id" id="project-client-select" size="1">    
									<?php 
										//get the clients out to populate the drop down.
										//list($clients) = Client::getClients();
										foreach ($all_clients as $client) { ?>
										<option value="<?php echo $client->client_id ?>" <?php //setSelected($client, "client_id", $project->getValue("client_id")) ?>><?php echo $client->client_name ?></option>
									<?php } ?>
								</select>
							</li>
							<!--li class="entity-details-item project-archive project">
								<label for="project-archived" <?php //validateField("project_archived", $missingFields)?> class="entity-details-label">Archived project?</label>
								<input id="project-archived" name="project-archived" class="project-archive-input" type="text" tabindex="3" value="<?php //echo $project->getValueEncoded("project_archived")?>" /-->
							</li>
						</ul>
					</section>
					<h4>Invoice Methods:</h4>
					<li class="billable">
						<input type="radio" id="project_billable" name="project_billable" class="client-email-input" tabindex="3" value="0" <?php //setChecked($project, "project_billable", 0) ?>/>
						<label for="project_billable" class="client-details-label">This project is not billable</label><br/>
						<input type="radio" id="project_billable" name="project_billable" class="client-email-input" tabindex="3" value="1" <?php //setChecked($project, "project_billable", 1) ?>/>
						<label for="project_billable" class="client-details-label">This project is billable and we invoice by:</label>
					</li>
					<?php
						//$row = Project::getEnumValues("project_invoice_by");
						//$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
						?>
						<!--select name="project_invoice_by" id="project_invoice_by">
						<?php
						//$selected = "";
						//foreach($enumList as $value) {
						//	if ($value == $project->getValueEncoded("project_invoice_by")) {
						//		$selected = "selected";
						//	} else {
						//		$selected = "";
						//	}
						?>
							<option name="project_invoice_by" id="project_invoice_by" value="<?php //echo $value?>" <?php //echo $selected ?>><?php //echo $value ?></option>
						<?php //} ?>
						</select-->    
						<?php 
						
						$invoice_type = array(
							'default' => 'Please select an invoice method',
							'Do not apply hourly rate' => 'Do not apply hourly rate',
							'Project hourly rate' => 'Project hourly rate',
							'Person hourly rate' => 'Person hourly rate',
							'Task hourly rate' => 'Task hourly rate',
							);
							echo form_dropdown('invoice-type', $invoice_type, set_value('invoice-type', $project[0]->project_invoice_by), 'id="project_invoice_by"');
							?>
						<input id="project_hourly_rate" name="project_hourly_rate" style="width:50px;display:none;" value="$"/>

						<h4>Budget</h4>
						<?php
						//$row = Project::getEnumValues("project_budget_by");
						//$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
						?>
						<!--select name="project_budget_by" id="project_budget_by">
						<?php
						//$selected = "";
						//foreach($enumList as $value) {
						//error_log("value is " . $value . " and project is " . $project->getValueEncoded("project_budget_by"));
							//if ($value == $project->getValueEncoded("project_budget_by")) {
							//	error_log("value is " . $value . " and project is " . $project->getValueEncoded("project_budget_by"));
							//	error_log(print_r($project,true));
							//	$selected = "selected=\"selected\"";
							//} else {
							//	$selected = "";
							//}
						?>
							<option <?php //echo $selected ?> name="project_budget_by" id="project_budget_by"  value="<?php //echo $value?>"><?php //echo $value ?></option>
						<?php //} ?>
						</select-->     
						<?
						$budget_type = array(
							'default' => 'Please select a budget type',
							'No budget' => 'No budget',
							'Total project hours' => 'Total project hours',
							'Total project fees' => 'Total project fees',
							'Hours per person' => 'Hours per person',
							'Hours per task' => 'Hours per task',
							);
							echo form_dropdown('budget-type', $budget_type, set_value('budget-type', $project[0]->project_budget_by), 'id="project_budget_by"');
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
					<!--END DEMO-->
					
					<section id="project-info" class="entity-detail">
						<h2 class="entity-sub-title">Project Notes</h2>
						<ul class="details-list entity-details-list project">
							<li class="entity-details-item project-notes project"><label for="project_notes" <?php //validateField("project_notes", $missingFields)?> class="entity-details-label">Project Notes:</label>
							<textarea id="project-notes" name="project-notes" class="entity-details-block" tabindex="4"><?php echo set_value('project_notes', $project[0]->project_notes);?></textarea></li>
						</ul>
					</section>
					<!--section id="project-info" class="entity-detail">
						<h2 class="entity-sub-title">Invoicing Method</h2>
						<ul class="details-list entity-details-list project">
							<li class="entity-details-item invoicing project">
								<label for="project-billable" class="entity-details-label">Is this project billable?</label>
								<input id="project-billable" name="project-billable" class="project-billable" type="radio" tabindex="5"/> Yes.
								<input id="project-billable" name="project-billable" class="project-billable" type="radio" checked="checked" tabindex="6" /> No.
							</li>
							<li class="entity-details-item invoicing project">
								<label for="invoice-method" class="entity-details-label" tabindex="7">Invoice project by:</label>
								<select id="invoice-method" name="invoice-method" class="">
									<option value="task-hourly">Task hourly rate</option>
									<option value="person-hourly">Person hourly rate</option>
									<option value="project-hourly">Project hourly rate</option>
									<option value="no-rate" selected="selected">No hourly rate applied</option>
								</select>
							</li>
							<li class="entity-details-item invoicing project">
								<label for="project-hourly-rate" class="entity-details-label">Project hourly rate is:</label>
								<input id="project-hourly-rate" name="project-hourly-rate" class="project-hourly-rate" type="text" tabindex="8"/>
							</li>
						</ul>
					</section>
					<section id="project-budget" class="entity-detail">
						<h2 class="entity-sub-title">Budget</h2>
						<ul class="entity-list entity-details-list">
							<li class="entity-details-item">
								<label for="project-budget" class="entity-details-label">Project budget uses:</label>
								<select id="budget-method" name="budget-method" class="" tabindex="9">
									<option value="total-hours">Total project hours</option>
									<option value="total-fees">Total project fees</option>
									<option value="task-hours">Hours per task</option>
									<option value="person-hours">Hours per person</option>
									<option value="no-budget" selected="selected">No budget</option>
								</select>
							</li>
							<li class="entity-details-item">
								<label for="project-budget-view-permissions" class="entity-details-label">Who can view project?</label>
								<input id="project-budget-view-permissions" name="project-budget-view-permissions" class="project-budget" type="radio" value="employees" tabindex="10"/> Employees
								<input id="project-budget-view-permissions" name="project-budget-view-permissions" class="project-budget" type="radio" value="contractors" tabindex="11" /> Contractors
								<input id="project-budget-view-permissions" name="project-budget-view-permissions" class="project-budget" type="radio" checked="checked" value="all" tabindex="11" /> Both
							</li>
							<
<li class="entity-details-item">
								<label for="project-budget-email" class="entity-details-label">Send email?</label>
							</li>
-->
						</ul>
					</section>
					<ul class="page-controls-list team">
						<li class="entity-details-item submit-btn client">
							<label for="project-save-btn" class="entity-details-label project">All done?</label>
							<input id="project-save-btn" name="project-save-btn" class="save-btn" type="submit" value="+ Save Changes" tabindex="12" /> or <a class="" href="projects.php" tabindex="13">Cancel</a>
						</li>
					</ul>
				</fieldset>
			</article>
			<article id="tasks" class="entity-detail tasks">
				<fieldset class="entity-details-entry">
					<header class="entity-details-header project">
						<h1 class="entity-details-title">Project Tasks:</h1>
						<h4 class="required">= Required</h4>
					</header>
					
					<ul class="entity-list entity-sub-details-list">
						<li class="entity-details-item">
							<label for="" <?php //validateField("task_id", $missingFields)?> class="entity-details-label">Tasks currently assigned to project:</label>
							<table id="task-list" class="entity-table tasks tablesorter">
								<thead>
									<tr>
										<!-- you can also add a placeholder using script; $('.tablesorter th:eq(0)').data('placeholder', 'hello') -->
										<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Task</th>
										<th>Budget (Hours)</th>
										<th>Hourly Rate (USD $)</th>
										<th class="filter-false" data-placeholder="Try <d">Remove From Project</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//get out all of the tasks associated with this project.
									//list($tasksForProject) = Project_Task::getTasksForProject($project_id);
									//$taskList = "";
									
									foreach ($tasks as $task) { ?>
										<tr>
											<td><?php	echo $task->task_name ?></td>
											<td><input style=width:50px;display:none; name=<?php echo $task->task_id ?>_hours_per_task class=hours_per_task value="<?php echo $task->total_budget_hours ?>"></td>
<td><input style=width:50px;display:none; name=<?php echo $task->task_id?>_task_hourly_rate class=task_hourly_rate value="<?php echo $task->task_hourly_rate?>"></td>
											<td><a href="#" class="remove-btn"></a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</li>
						
						<input id="task_id" name="task_id" class="task_id" type="text" tabindex="2" value="<?php 
											//list($tasksForProject) = Project_Task::getTasksForProject($project_id);
											foreach ($tasks as $task) { 
												echo $task->task_id . ",";
											}
											?>" />
				</li>
						<li class="entity-details-item">
						<p id="associated_tasks"><b>Tasks Associated with this project:</b></p>
							<label for="task_ids" class="entity-details-label">Add additional tasks:</label>
							<?php 
								//get the tasks out to populate the drop down.
								//list($tasks) = Task::getTasks("0");
							?>
							<select name="task_ids" id="task_ids" size="1">    
								<?php foreach ($all_tasks as $thisTask) { ?>
		   						<option value="<?php echo $thisTask->task_id ?>" name="<?php echo $thisTask->task_name ?>"><?php echo $thisTask->task_name ?></option>
		    					<?php } ?>
			 				</select>
						</li>
						<ul class="page-controls-list tasks">
							<li class="entity-details-item submit-btn tasks">
								<label for="project-save-btn" class="entity-details-label project">All done?</label>
								<input id="project-save-btn" name="project-save-btn" class="save-btn" type="submit" value="+ Save Changes" tabindex="12" /> or <a class="projects.php" href="projects.php" tabindex="13">Cancel</a>
							</li>
						</ul>
					</ul>
				</fieldset>
			</article>
			<article id="people" class="entity-detail tasks">
				<fieldset class="entity-details-entry">
					<header class="entity-details-header people">
						<h1 class="entity-details-title">Project Team:</h1>
						<h4 class="required">= Required</h4>
					</header>
					<ul class="entity-list entity-sub-details-list">
						<li class="entity-details-item">
							<label for="" <?php //validateField("person_id", $missingFields)?> class="entity-details-label">People currently assigned to project:</label>
							
							<p id="associated_people"><b>People Associated with this project:</b></p>
							<table id="people-list" class="entity-table people tablesorter">
								<thead>
									<tr>
										<!-- you can also add a placeholder using script; $('.tablesorter th:eq(0)').data('placeholder', 'hello') -->
										<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Team Member</th>
										<th>Budget (Hours)</th>
										<th>Hourly Rate (USD $)</th>
										<th class="filter-false" data-placeholder="Try <d">Remove From Project</th>
									</tr>
								</thead>
								<tbody>
									<?php
										//get out all of the people associated with this project.
										//list($peopleForProject) = Project_Person::getPeopleForProject($project_id);
										//$peopleList = "";
										foreach ($people as $person) { ?>
											<tr>
												<td><?php 
											//	$personName = Person::getPersonById($projectPerson->getValue("person_id"));
												echo $person->person_first_name . " ";
												echo $person->person_last_name;
												?></td>
												<td><input style=width:50px;display:none; name=<?php echo $person->person_id ?>_hours_per_person class=hours_per_person value="<?php echo $person->total_budget_hours ?>"></td>
<td><input style=width:50px;display:none; name=<?php echo $person->person_id ?>_person_hourly_rate class=person_hourly_rate value="<?php echo $person->person_hourly_rate ?>"></td>
												<td><a href="#" class="remove-btn"></a></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</li>
											<input id="person_id" name="person_id" class="person_id" type="text" tabindex="2" value="<?php
											//list($peopleForProject) = Project_Person::getPeopleForProject($project_id);
											foreach ($people as $person) { 
											//	$personName = Person::getPersonById($projectPerson->getValue("person_id"));
												echo $person->person_id . ",";
											}
											?>" />

						<li class="entity-details-item">
							<label for="person_ids" class="entity-details-label">Add additional people:</label>
							<?php 
								//get the people out to populate the drop down.
								//list($people) = Person::getPeople();
							?>
							<select name="person_ids" id="person_ids" size="1">    
								<?php foreach ($all_people as $all_person) { ?>
		   						<option value="<?php echo $all_person->person_id ?>"><?php echo $all_person->person_first_name;echo " " . $all_person->person_last_name?></option>
		    					<?php } ?>
			 				</select>
						</li>
					</ul>
					<ul class="page-controls-list team">
						<li class="entity-details-item submit-btn client">
							<label for="project-save-btn" class="entity-details-label project">All done?</label>
							<input id="project-save-btn" name="project-save-btn" class="save-btn" type="submit" value="+ Save Changes" tabindex="12" /> or <a class="projects.php" href="#" tabindex="13">Cancel</a>
						</li>
					</ul>
				</fieldset>
			</article>
		</form><!--END FORM-->
	</div>
</div>
<footer id="site-footer" class="site-footer">

</footer>
<script src="../../../js/client-controls.js" type="text/javascript"></script>
</body>
</html><?php //} ?>

<?php 
/*function editProject() {
	error_log(print_r($_POST,true));
	//PROJECT PROCESSING FUNCTIONS (editProjects();)
	//1. Set up the required fields.
	//2. Create the object based on the values that were submitted the last time the user submitted the form.
	//3. Set up the required fields in the $requiredFields array.
	//4. Compare the existence of the fields in the objects (based on the $_POST values) with the fields in the $requiredFields array. If
	//any are missing, put the fields into the $missingFields[] array.
	//5. If the $missingFields array exists, loop through them and call the error message. If there are NO missing fields, still call the error message for the NON missing field errors (email, phone, etc).
	//6. If there are error messages, call displayProjectEditForm with the error messages, the missing fields, and all the data for the object and the whole thing starts over again.
	//7. If there are no errors, update the database with the new project information.
	//8. If all went well, display the project details page.

	$requiredFields = array("project_name");
	$missingFields = array();
	$errorMessages = array();
		
	//EDIT THE PROJECT OBJECT ($PROJECT)
	$project = new Project( array(
		"project_code" => isset($_POST["project_code"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_code"]) : "",
		"project_id" => isset($_POST["project_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_id"]) : "",
		"project_name" => isset($_POST["project_name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_name"]) : "",
		"client_id" => isset($_POST["client_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["client_id"]) : "",
		"project_billable" => isset($_POST["project_billable"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_billable"]) : "",
		"project_invoice_by" => isset($_POST["project_invoice_by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project_invoice_by"]) : "",
		"project_hourly_rate" => isset($_POST["project_hourly_rate"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_hourly_rate"]) : "",
		"project_budget_by" => isset($_POST["project_budget_by"])? preg_replace("/[^ a-zA-Z]/", "", $_POST["project_budget_by"]) : "",
		"project_budget_total_fees" => isset($_POST["project_budget_total_fees"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_total_fees"]) : "",
		"project_budget_total_hours" => isset($_POST["project_budget_total_hours"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_total_hours"]) : "",
		"project_send_email_percentage" => isset($_POST["project_send_email_percentage"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_send_email_percentage"]) : "",
		"project_show_budget" => isset($_POST["project_show_budget"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_show_budget"]) : "",
		"project_budget_includes_expenses" => isset($_POST["project_budget_includes_expenses"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_budget_includes_expenses"]) : "",
		"project_notes" => isset($_POST["project_notes"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_notes"]) : "",
		"project_archived" => isset($_POST["project_archived"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_archived"]) : "",
	));

	//edit the project_person object ($project_person)
	$project_person = new Project_Person( array(
		"person_id" => isset($_POST["person_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["person_id"]) : "",
		"total_budget_hours" => isset($_POST["total_project_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_project_hours"]) : "",
	));
	//edit the project_task object ($project_task)
	$project_task = new Project_Task( array(
		"task_id" => isset($_POST["task_id"]) ? preg_replace("/[^ \,\-\_a-zA-Z0-9]/", "", $_POST["task_id"]) : "",
		"total_budget_hours" => isset($_POST["total_project_hours"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["total_project_hours"]) : "",
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
		}	
	}
		
	if ($errorMessages) {
		error_log("There were errors in the input (errorMessages was not blank. Redisplaying the edit form with missing fields.");
		displayProjectEditForm($errorMessages, $missingFields, $project, $project_person, $project_task);
	} else {
		try {
			$client_id = $project->getValue("client_id");
			//update the project into the project table.
			error_log(print_r($project,true));
			$project->updateProject($client_id);
			$project_id = Project::getProjectId($project->getValue("project_name"));
			$person_ids = explode(',', $project_person->getValue("person_id"));
			//delete all of the rows in the table associated with this project.
			Project_Person::deleteProjectPerson($project_id["project_id"]);
			//add the new ones, since we don't know how many people will be deleted and how many will be added.
			foreach ($person_ids as $person_id) {
			//echo "inserting person id " . $person_id . " and " . "project id " . $project_id["project_id"]; 
				if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
						$project_assigned_by = Person::getByEmailAddress($_SESSION["logged_in"]);
				} else {
						error_log("Something is wrong here...this person is not logged in and you shouldn't be seeing this, timesheet.php.");
						exit();
				}
			if ($person_id) {
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
			//do the same for tasks.
			$task_ids = explode(',', $project_task->getValue("task_id"));
			Project_Task::deleteProjectTask($project_id["project_id"]);
			foreach ($task_ids as $task_id) {
			if ($task_id) {	
			$budget_hours = 0;
				if (isset($_POST[$task_id."_hours_per_task"])) {
					$budget_hours = $_POST[$task_id . "_hours_per_task"];
				}
			$project_task->insertProjectTask($task_id, $project_id["project_id"], $budget_hours);
			$task_hourly_rate = 0;
				if (isset($_POST[$task_id."_task_hourly_rate"])) {
					$task_hourly_rate = $_POST[$task_id . "_task_hourly_rate"];
				}
				Task::updateTaskHourlyRate($task_id, $task_hourly_rate);
			}
			}

			displayProjectEditForm(array(), array(), $project, $project_person, $project_task);
		} catch (Error $e) {
			die("could not insert a project. " . $e->getMessage());
			
		}
	}

}*/

?>
