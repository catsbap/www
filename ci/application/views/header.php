<?php
//**This file is no longer in use.
//instead, in codeigniter load the header_view file
//as part of the controller.
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Manage</title>
	<meta charset="utf-8" />
	<script src='http://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js' type='text/javascript'></script>
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
	<link href="/time_tracker/ui/libraries/theme.blueprint.css" rel="stylesheet" type="text/css" /> <!--This should only be loaded for projects.php -->
	<link href="/time_tracker/ui/libraries/custom-theme/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" /> <!--This should only be loaded for projects.php -->
	<link href="/time_tracker/ui/styles.css" rel="stylesheet" type="text/css" />
	<script src="/time_tracker/ui/libraries/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="/time_tracker/ui/libraries/jquery.tablesorter.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="/time_tracker/ui/libraries/jquery.tablesorter.widgets.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="/time_tracker/ui/libraries/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="/time_tracker/ui/libraries/purl.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
</head>

<body>
<header id="site-header" class="site-header">
	<h1 class="site-title">Time Tracker</h1>
	<nav id="site-nav" class="site-nav">
		<ul id="site-menu" class="site-menu">
			<li class="site-menu-item"><a class="site-menu-link" href="timesheet.php">Timesheets</a></li>
			<?php //if ($header_controller_vars->getValueEncoded("person_perm_id") != "Regular User") {
				?>			<li class="site-menu-item"><a class="site-menu-link" href="http://localhost:8888/time_tracker/ci/index.php/report?fromdate=<?php echo date("Y-m-d", strtotime("last monday", strtotime(date("Y-m-d"))));?>&todate=<?php echo date("Y-m-d", strtotime("this sunday", strtotime(date("Y-m-d"))));?>&page=clients">Reports</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Invoices</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="manage.php">Manage</a></li>
		<?php //} ?>	
		</ul>
	</nav>
	<nav id="util-nav" class="util-nav">
		<ul id="util-menu" class="util-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="logout.php">Log Out <?php //echo $header_controller->getValue("person_email");?></a></li>
		</ul>
	</nav>
	<nav id="section-nav" class="section-nav timesheets">
		<h1 class="section-nav-title">Timesheets: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/timesheet.php">Time</a></li>
			<!--li class="section-menu-item"><a class="section-menu-link" href="#">Expenses</a></li-->
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/timesheet_submitted.php">Pending Approval</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/timesheet_unsubmitted.php">Unsubmitted</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/timesheet_archived.php">Archive</a></li>

		</ul>
	</nav>
	<?php // if ($header_controller_vars->getValueEncoded("person_perm_id") != "Regular User") {?>
	<nav id="section-nav" class="section-nav manage">
		<h1 class="section-nav-title">Manage: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/projects.php">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/clients.php">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/people.php">People</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="/time_tracker/ui/tasks.php">Tasks</a></li>
		</ul>
	</nav>
	<?php //} ?>
</header>