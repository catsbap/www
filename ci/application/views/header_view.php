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
		<script src="/time_tracker/ui/libraries/numeral.min.js"></script>
		
		<script>
$(document).ready( function() {
$(".dropdownMenu1").click(function() {
	 $("ul.one").toggle();
});
$(".dropdownMenu2").click(function() {
	 $("ul.two").toggle();
});
$(".dropdownMenu3").click(function() {
	 $("ul.three").toggle();
});
});
</script>
		
<!--bootstrap
when and if this UI officially gets treated we may want to consider setting this up.
--->
<!--link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->
<?php
if ($this->base == "") {
		$this->base="$this->base/time_tracker/ci";
	}
?>

</head>

<body>


<header id="site-header" class="site-header">
	<h1 class="site-title"><a href="<?php echo "$this->base/index.php/"?>">Time Tracker</a></h1>
		<?php
	//this should be in the various controllers for the header, but we're putting it here for now.
	
	$group = $this->ion_auth->get_users_groups()->row()->id;
	?>

	<nav id="site-nav" class="site-nav">
		<div class="dropdown">
  <button type="button" class="dropdownMenu1">
    Timesheets
    <span class="caret"></span>
  </button>
  <ul style="display:none" class="one">
    <li><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/timesheet_controller/display_timesheet"?>">Timesheets</a></li>
    <li><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/timesheet_controller/pending_approval"?>">Pending Approval</a></li>
    <li><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/timesheet_controller/unsubmitted"?>">Unsubmitted</a></li>
    <li><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/timesheet_controller/archive"?>">Archive</a></li>
  </ul>
</div>
	</nav>
	
	
	<?php 
	//if the group is administrator, let them see the report and manage menus.
	if ($group == "3") {?>
	<nav id="site-nav" class="site-nav">
<div class="dropdown">
  <button type="button" class="dropdownMenu2">
    Reports
    <span class="caret"></span>
  </button>
  <ul style="display:none" class="two">
    <li><a role="menuitem" tabindex="-1" href="/time_tracker/ci/index.php/report/index/<?php echo date("Y-m-d", strtotime("last monday", strtotime(date("Y-m-d"))));?>/<?php echo date("Y-m-d", strtotime("this sunday", strtotime(date("Y-m-d"))));?>/clients/week">Time</a></li>
    <li><a role="menuitem" tabindex="-1" href="#">Expenses</a></li>
    <li><a role="menuitem" tabindex="-1" href="http://<?php echo $_SERVER['SERVER_NAME'];?>:8888/time_tracker/ci/index.php/search_controller/index/<?php echo date("Y-m-d", strtotime("last monday", strtotime(date("Y-m-d"))));?>/<?php echo date("Y-m-d", strtotime("this sunday", strtotime(date("Y-m-d"))));?>">Detailed Time</a></li>
    <li><a role="menuitem" tabindex="-1" href="#">Uninvoiced</a></li>
    <li><a role="menuitem" tabindex="-1"  href="/time_tracker/ci/index.php/budget_controller/index">Project Budget</a></li>
  </ul>
</div>
	</nav>
	
	
	<nav id="site-nav" class="site-nav">
	<div class="dropdown">
  <button type="button" class="dropdownMenu3">
    Manage
    <span class="caret"></span>
  </button>
  <ul style="display:none" class="three">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/project_controller/display_projects"?>">Projects</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/client_add_controller/view_clients"?>">Clients</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/person_controller/view_people"?>">People</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo "$this->base/index.php/task_controller/display_task"?>">Tasks</a></li>
  </ul>
</div>
	</nav>	
		<?php } ?>

	
	<nav id="util-nav" class="util-nav">
		<ul id="util-menu" class="util-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="<?php echo "$this->base/index.php/auth/logout/"?>">Log Out <?php 
	$user = $this->ion_auth->user()->row();
	echo $user->email?></a></li>
		</ul>
	</nav>

</header>