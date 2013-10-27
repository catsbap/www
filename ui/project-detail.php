<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	
	//	if(!isUserLoggedIn()){
	//	//redirect if user is not logged in.
	//	$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	//	header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//}
	
	//RETRIEVE THE PROJECT ID FROM GET OR POST.
	if (isset ($_GET["project_id"])) {
		$project_id = $_GET["project_id"];
	} elseif (isset ($_POST["project_id"])) {
		$project_id = $_POST["project_id"]; 
	} else {
		echo "no project identifier provided, cannot find details for empty project.";
		exit;
	}
	
	//RETRIEVE THE PROJECT DETAILS TO DISPLAY IN THE UI.
	$project_details = Project::getProjectByProjectId($project_id);
	error_log("HERE ARE THE PROJECT DETAILS IN Project DETAILS PAGE:");
	error_log(print_r($project_details,true));
	if (!isset($project_details)) {
    	error_log("The detailed data for this project is not available. Please investigate why this happened, project_details.php, line 20");
		exit;
	}
	
	
	
	include('header.php'); //add header.php to page

?>

<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Project: <?php echo $project_details->getValue("project_name")?></h1>
		<h2 class="page-sub-title"><a href="#" class="" title="View client's details">Client</a></h2>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="view-all-link" href="project-edit.php?project_id=<?php echo $project_id?>">Edit Project</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<div class="content tabs">
		<ul>
			<li><a href="#overview">Overview</a></li>
			<li><a href="#tasks">Tasks</a></li>
			<li><a href="#milestones">Milestones</a></li>
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
			<ul class="entity-list entity-sub-details-list">
				<li class="entity-details-item"></li>
			</ul>
		</article>
		<article id="milestones" class="entity-detail milestones">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Milestones</h1>
			</header>
			<ul class="entity-list entity-details-list">
				<li class="entity-details-item"></li>
			</ul>
		</article>
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
					<li class="entity-details-item"></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Team</a></li>
				</ul>
			</section>
			<section id="client-contacts" class="entity-detail">
				<h2 class="entity-sub-title">Client's Team</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item"></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Client's Contacts</a></li>
				</ul>
			</section>
		</article>
		<article id="settings" class="entity-detail settings">
			<header class="entity-details-sub-header">
				<h1 class="entity-details-title">Settings</h1>
			</header>
			<section id="project-info" class="entity-detail">
				<h2 class="entity-sub-title">Project Info</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">Name: <span class="edit project-name"><?php echo $project_details->getValue("project_name")?></span></li>
					<li class="entity-details-item">Client: <span class="select client-id"><a href="#" class="" title="View client's details">Client</a></span></li>
					<li class="entity-details-item">Project code: <span class="edit project-code"><?php echo $project_details->getValue("project_code")?></span></li>
					<?php if ($project_details->getValue("project_archived")) { ?>
						<li class="entity-details-item archive-project">This project is currently archived.</li>
					<?php } else { ?>
						<li class="entity-details-item archive-project">This project is currently active.</li>	
					<?php } ?>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a id="edit-project-btn" class="" href="#">Edit Project Info</a></li>
				</ul>
			</section>
			<section id="project-notes" class="entity-detail">
				<h2 class="entity-sub-title">Project Notes</h2>
				<p class="entity-list entity-details-block">
					<?php echo $project_details->getValue("project_notes")?>
				</p>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Project Notes</a></li>
				</ul>
			</section>
			<section id="project-invoicing" class="entity-detail">
				<h2 class="entity-sub-title">Invoicing</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">This project is invoiced by: <?php echo $project_details->getValue("project_invoice_by")?></li>
					<li class="entity-details-item">Project hourly rate: <?php echo $project_details->getValue("project_hourly_rate")?></li>
				</ul>
				<ul class="page-controls-list team">
					<li class="page-controls-item link-btn"><a class="" href="#">Edit Invoice Settings</a></li>
				</ul>
			</section>
			<section id="project-budget" class="entity-detail">
				<h2 class="entity-sub-title">Budget</h2>
				<ul class="entity-list entity-details-list">
					<li class="entity-details-item">Project budget: <?php echo $project_details->getValue("project_budget_by")?></li>
					<li class="entity-details-item">All employees and contractors can view budget: <?php echo $project_details->getValue("project_show_budget")?></li>
					<li class="entity-details-item">Send email when budget reaches xx%: <?php echo $project_details->getValue("project_send_email")?></li>
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
<script src="project-controls.js" type="text/javascript"></script>
</body>
</html>
