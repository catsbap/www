<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
		if(!isUserLoggedIn()){
		//redirect if user is not logged in.
		$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
		header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	}
	
	//RETRIEVE THE CLIENT ID FROM GET OR POST.
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
	error_log("HERE ARE THE PROJECT DETAILS IN CLIENT DETAILS PAGE:");
	error_log(print_r($project_details,true));
	if (!isset($project_details)) {
    	error_log("The detailed data for this project is not available. Please investigate why this happened, project_details.php, line 20");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project Detail</title>
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
			<li class="section-menu-item"><a class="section-menu-link" href="projects.php">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="clients.php">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Team</a></li>
		</ul>
	</nav>
</header>
<section id="page-content" class="page-content">
<header class="page-header">
		<h1 class="page-title">Project Details</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<!-- I am just putting this here because I need to send the client id into the client-edit php file.-->
				<li class="page-controls-item"><a class="view-all-link" href="project-edit.php?project_id=<?php echo $project_id?>">Edit This Project</a></li>
<!--end-->
<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<!--we don't have an image for the project here.
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="<?php //echo $project_details->getValue("client_logo_link")?>" title="Client/Company name logo" alt="Client/Company name logo" />
		</figure>-->
		<section class="client-detail l-col-80">
			<header class="client-details-header">
				<h1 class="client-details-title"><?php echo $project_details->getValue("project_name")?></h1>
			</header>
			<ul class="details-list client-details-list">
								<li class="client-details-item phoneNum"><?php echo $project_details->getValue("project_notes")?></li>
				<?php if ($project_details->getValue("project_archived")) { ?>
				<li class="client-details-item email">This project is currently archived.</li>
				<?php } else { ?>
				<li class="client-details-item email">This project is currently active.</li>	
				<?php } ?> 
				<!--<li class="client-details-item fax"><?php //echo $client_details->getValue("client_fax")?></li>
				<li class="client-details-item address">
					<?php //echo $client_details->getValue("client_address")?>
				</li>
				<li class="client-details-item currency"><?php //echo Client::getCurrencyByIndex($client_details->getValue("client_currency_index"))?></li>-->
			</ul>
		</section>
	</section>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>
