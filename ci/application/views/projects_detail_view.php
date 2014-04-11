<?php
	//require_once("../common/common.inc.php");
	//require_once("../classes/Project.class.php");
	//require_once("../classes/Client.class.php");
	//require_once("../classes/Project_Person.class.php");
	//require_once("../classes/Project_Task.class.php");
	//require_once("../classes/Project.class.php");
	//require_once("../classes/Task.class.php");
	
	//	if(!isUserLoggedIn()){
	//	//redirect if user is not logged in.
	//	$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	//	header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//}
	
	//RETRIEVE THE PROJECT ID FROM GET OR POST.
	//if (isset ($_GET["project_id"])) {
	//	$project_id = $_GET["project_id"];
	//} elseif (isset ($_POST["project_id"])) {
	//	$project_id = $_POST["project_id"]; 
	//} else {
	//	echo "no project identifier provided, cannot find details for empty project.";
	//	exit;
	//}
	
	//RETRIEVE THE PROJECT DETAILS TO DISPLAY IN THE UI.
	//print_r($project);
	//print_r($clients);
	//print_r($tasks);
	//print_r($people);
	//$project_details = Project::getProjectByProjectId($project_id);
	//error_log("HERE ARE THE PROJECT DETAILS IN Project DETAILS PAGE:");
	///error_log(print_r($project_details,true));
	//if (!isset($project_details)) {
    //	error_log("The detailed data for this project is not available. Please investigate why this happened, project_details.php, line 20");
	//	exit;
	//}
	
	//$client_details = Client::getClient($project_details->getValue("client_id"));
	//include('header.php'); //add header.php to page

?>

<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Project: <?php echo $project[0]->project_name?></h1>
		<h2 class="page-sub-title"><a href="<?php echo "client_detail/" . $project[0]->client_id?>" class="" title="View client's details"><?php echo $clients[0]->client_name ?></a></h2>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="view-all-link" href="<?php echo "../edit_project/" . $project[0]->project_id?>">Edit Project</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<div class="content tabs">
		<ul>
			<li><a href="#overview">Overview</a></li>
			<li><a href="#tasks">Tasks</a></li>
			<!-- <li><a href="#milestones">Milestones</a></li> -->
			<li><a href="#timesheets">Timesheets</a></li>
			<li><a href="#team">Team</a></li>
			<li><a href="#settings">Settings</a></li>
		</ul>
		<article id="overview" class="entity-detail overview">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Overview</h1>
			</header>
			<ul class="entity-list entity-details-list">
				<li class="entity-details-item"></li>
			</ul>
		</article>
		<article id="tasks" class="entity-detail tasks">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Tasks</h1>
			</header>
			<section id="active-tasks" class="entity-detail">
				<h2 class="entity-sub-title">Currently Assigned to Project</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">
						<label for="" <?php //validateField("task_id", $missingFields)?> class="entity-details-label"></label>
						<table id="task-list" class="entity-table tasks tablesorter">
							<thead>
								<tr>
									<!-- you can also add a placeholder using script; $('.tablesorter th:eq(0)').data('placeholder', 'hello') -->
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Task</th>
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Billable</th>
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Common</th>
								</tr>
							</thead>
							<tbody>
							<?php
								//get out all of the tasks associated with this project.
								//list($tasksForProject) = Project_Task::getTasksForProject($project_id);
								foreach ($tasks as $task) { ?>
									<tr>
										<td><?php echo $task->task_name ?></td>
										<td><?php 
											if ($task->task_bill_by_default) {
												echo "Yes";
											} else {
												echo "No";
											}; ?></td>
										<td><?php 
											if ($task->task_common) {
												echo "Yes";
											} else {
												echo "No";
											}; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a id="edit-active-tasks-btn" class="" href="#">Edit Tasks</a></li>
				</ul>
			</section>
		</article>
		<!--
		<article id="milestones" class="entity-detail milestones">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Milestones</h1>
			</header>
			<ul class="entity-list entity-details-list">
				<li class="entity-details-item"></li>
			</ul>
		</article>
		-->
		<article id="timesheets" class="entity-detail timesheets">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Timesheets</h1>
			</header>
			<ul class="entity-list entity-details-list">
				<li class="entity-details-item"></li>
			</ul>
		</article>
		<article id="team" class="entity-detail team">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Team</h1>
			</header>
			<section id="assigned-people" class="entity-detail">
				<h2 class="entity-sub-title">Assigned People</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">
						<label for="" <?php //validateField("person_id", $missingFields)?> class="entity-details-label"></label>
						<table id="people-list" class="entity-table people tablesorter">
							<thead>
								<tr>
									<!-- you can also add a placeholder using script; $('.tablesorter th:eq(0)').data('placeholder', 'hello') -->
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Team Member</th>
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Type</th>
									<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Department</th>
								</tr>
							</thead>
							<tbody>
							<?php
								//get out all of the tasks associated with this project.
								//get out all of the people associated with this project.
								//list($peopleForProject) = Project_Person::getPeopleForProject($project_id);
								
								//$peopleList = "";
								foreach ($people as $person) { ?>
									<tr>
										<td><?php echo $person->person_first_name ?></td>
										<td><<?php echo $person->person_type ?></td>
										<td><?php echo $person->person_department ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Team</a></li>
				</ul>
			</section>
			<!--
			<section id="client-contacts" class="entity-detail">
				<h2 class="entity-sub-title">Client's Team</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item"></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Client's Contacts</a></li>
				</ul>
			</section>
			-->
		</article>
		<article id="settings" class="entity-detail settings">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Settings</h1>
			</header>
			<section id="project-info" class="entity-detail">
				<h2 class="entity-sub-title">Project Info</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">Name: <span class="edit project_name required"><?php echo $project[0]->project_name ?></span></li>
					<li class="entity-details-item">Client: <span class="select client_id"><a href="<?php echo "client-detail/" . $project[0]->client_id ?> class="" title="View client's details"><?php echo $clients[0]->client_name ?></a></span></li>
					<li class="entity-details-item">Project code: <span class="edit project_code"><?php echo $project[0]->project_code ?></span></li>
					<li class="entity-details-item">Status: <span class="checkbox project_archived" data-status-toggle="Active:Archived">
					<?php if ($project[0]->project_archived) {
						echo "Archived";
					} else {
						echo "Active";
					} ?></span></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a id="edit-project-info-btn" class="" href="#">Edit Project Info</a></li>
				</ul>
			</section>
			<section id="project-notes" class="entity-detail">
				<h2 class="entity-sub-title">Project Notes</h2>
				<p class="entity-list entity-details-block textarea project_notes"><?php echo $project[0]->project_notes?></p>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a id="edit-project-notes-btn" href="#">Edit Project Notes</a></li>
				</ul>
			</section>
			<section id="project-invoicing" class="entity-detail">
				<h2 class="entity-sub-title">Invoicing</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">This project is invoiced by: <?php echo $project[0]->project_invoice_by ?></li>
					<li class="entity-details-item">Project hourly rate: <?php echo $project[0]->project_hourly_rate?></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="edit-invoicing-btn" href="#">Edit Invoice Settings</a></li>
				</ul>
			</section>
			<section id="project-budget" class="entity-detail">
				<h2 class="entity-sub-title">Budget</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">Project budget: <?php echo $project[0]->project_budget_by?></li>
					<li class="entity-details-item">All employees and contractors can view budget: <?php echo $project[0]->project_show_budget ?></li>
					<li class="entity-details-item">Send email when budget reaches <?php echo $project[0]->project_send_email_percentage?>%</li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Budget Settings</a></li>
				</ul>
			</section>
		</article>
	</div>
</div>
<footer id="site-footer" class="site-footer">

</footer>
<script src="<?php echo "$this->base/js/project-controls.js" ?>" type="text/javascript"></script>
</body>
</html>
