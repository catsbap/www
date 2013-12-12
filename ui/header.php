<?php
	require_once("../classes/Person.class.php");
	require_once("../classes/Person_Permissions.class.php");

?>

<?php
/*
Header file for all Manage sections. Needs to eventually be able to tell which page is displaying and edit the <title> element and whatever else accordingly. Eventually it should also be able to adjust displayed menu for other sections and states (i.e. logged in or not, user permissions, etc.)

Edit this file for updating links to pages/screens

What do we do about the login screen? In the case of a user that has not logged in, we don't want to show them the header; we don't have enough information to figure out what to do. 
*/

//I'm taking out this code until we have a chance to look at it.
//The header needs to know who this person is if it's not already set in order to control the header UI.
//BUT...if the person variable is already set, keep it.
//if (!isset($person)) {
	/*
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
		$header_controller = Person::getByEmailAddress($_SESSION["logged_in"]);
		$header_controller_vars = Person_Permissions::getPermissionsAsObject($header_controller->getValueEncoded("person_id"));
		if (!$header_controller_vars) {
			error_log("this person has no header control vars set. Please have a person with the proper access set up their permissions.");
		}
	}else{
		echo "You are not logged in and cannot view the internal header.";
	}
*/
//} else {
	//the person variable is already set here. Use it to figure out what to display in the header.
//	error_log(print_r($person));
	//->getValueEncoded("person_id"));
//	$header_controller_vars = Person_Permissions::getPermissionsAsObject($person->getValueEncoded("person_id"));
//	error_log(print_r($header_controller_vars));
//	exit;
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Manage</title>
	<meta charset="utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
	<link href="libraries/theme.blueprint.css" rel="stylesheet" type="text/css" /> <!--This should only be loaded for projects.php -->
	<link href="libraries/custom-theme/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" /> <!--This should only be loaded for projects.php -->
	<link href="styles.css" rel="stylesheet" type="text/css" />
	<script src="libraries/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="libraries/jquery.tablesorter.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="libraries/jquery.tablesorter.widgets.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="libraries/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="libraries/purl.js" type="text/javascript"></script> <!--This should only be loaded for projects.php -->
	<script src="libraries/numeral.min.js"></script>
</head>

<body>
<header id="site-header" class="site-header">
	<h1 class="site-title">Time Tracker</h1>
	<nav id="site-nav" class="site-nav">
		<ul id="site-menu" class="site-menu">
			<li class="site-menu-item"><a class="site-menu-link" href="../ui/timesheet.php">Timesheets</a></li>
			<?php //if ($header_controller_vars->getValueEncoded("person_perm_id") != "Regular User") {?>
			<li class="site-menu-item"><a class="site-menu-link" href="../ci/index.php/report?fromdate=<?php echo date("Y-m-d", strtotime("last monday", strtotime(date("Y-m-d"))));?>&todate=<?php echo date("Y-m-d", strtotime("this sunday", strtotime(date("Y-m-d"))));?>&page=clients">Reports</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Invoices</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="../ui/projects.php">Manage</a></li>
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
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/timesheet.php">Time</a></li>
			<!--li class="section-menu-item"><a class="section-menu-link" href="#">Expenses</a></li-->
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/timesheet_submitted.php">Pending Approval</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/timesheet_unsubmitted.php">Unsubmitted</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/timesheet_archived.php">Archive</a></li>

		</ul>
	</nav>
	<nav id="section-nav" class="section-nav timesheets">
		<h1 class="section-nav-title">Reports: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="../ci/index.php/report?fromdate=<?php echo date("Y-m-d", strtotime("last monday", strtotime(date("Y-m-d"))));?>&todate=<?php echo date("Y-m-d", strtotime("this sunday", strtotime(date("Y-m-d"))))?>&page=clients">Time</a></li>
			<!--li class="section-menu-item"><a class="section-menu-link" href="#">Expenses</a></li-->
			<li class="section-menu-item"><a class="section-menu-link" href="#">Detailed Time</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Uninvoiced</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Project Budget</a></li>

		</ul>
	</nav>
	<?php // if ($header_controller_vars->getValueEncoded("person_perm_id") != "Regular User") {?>
	<nav id="section-nav" class="section-nav manage">
		<h1 class="section-nav-title">Manage: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/projects.php">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/clients.php">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/people.php">People</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="../ui/tasks.php">Tasks</a></li>
		</ul>
	</nav>
	<?php //} ?>
</header>