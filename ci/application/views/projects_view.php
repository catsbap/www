<?php
	/*require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Client.class.php");
	
	//protect this page
	//checklogin();
	
	//this page doubles as the project archive page, so get the value off the get and display those projects here.
	$archivedView = "0";
	if (isset($_GET["archives"])) {
		$archivedView = $_GET["archives"];
	} elseif (isset($_POST["archives"])) {
		//print_r($_POST["archives"]);
		$archivedView = $_POST["archives"];
	} else {
		$archivedView = 0;
	}
	
	
	$project_id = "";
	if (isset($_POST["change_archive"])) {
		$project_id = $_POST["change_archive"];
	}
	
	//HOLY FUNKY LOGIC, BATMAN!!
	
	if (isset($_POST["change_archive"])) {
		if ($archivedView) {
			Project::setArchiveFlag('0', $project_id);
		} else {
			Project::setArchiveFlag('1', $project_id);
		}
	}


	
	//if(!isUserLoggedIn()){
	//	//redirect if user is not logged in.
	//	$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	//	header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//	//header( 'Location: http://strawberry.dev/MBTimeTtracker/usercake/login.php' ) ;
	//}
	//I am taking this out temporarily until we come back to it.
	//checkLogin();
	//$person = unserialize($_SESSION['person']);

	include('header.php'); //add header.php to page*/
?>
	

<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Active Projects</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="view-all-link" href="<?php echo "$this->base/index.php/project_controller/view_project" ?>">+ Add Project</a></li>
				<li class="page-controls-item"><a class="view-archive-link" href="projects.php?archives=1">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<div class="content">
		<!--BEGIN FORM-->
		<form action="projects.php" method="post">
			<input type="hidden" value="<?php //echo $archivedView ?>" name="archives">
			<table id="project-list" class="entity-table projects tablesorter">
				<thead>
					<tr>
						<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Project</th>
						<th data-placeholder="Try <d">Client</th>
						<th data-placeholder="Try >=33">Hours/Budget</th><!-- add class="filter-false" to disable the filter in this column -->
						<th class="filter-false">Archive?</th>
						<!-- <th data-placeholder="" class="filter-false"><input id="select-project" name="select-project" type="checkbox" value="all" title="Select project" /><th> -->
					</tr>
				</thead>
				<tbody>
					<?php //$projectList = Project::getClientsProjectsByStatus($archivedView);
					foreach($projects as $project) { 
						//$clientName = Client::getClientNameById($project->getValueEncoded("client_id")); ?>
						<tr>
							<td><a class="project-info-name-link" href="<?php echo "project-detail.php?project_id=" . $project->project_id ?>" title="View project details"><?php echo $project->project_name?></a></td>
							<td><a class="client-info-contact-link" href="<?php echo "$this->base/index.php/client_add_controller/client_detail/$project->client_id" ?>" title="View client details"><?php echo  $project->client_name?></a></td>
							<td>x Hours/y budget</td>
							<!--td><input name="select-project" class="archive-checkbox" type="checkbox" value="<?php //echo $project->getValueEncoded('project_id'); ?>" title="Select project" /><td-->
							<td><input name="change_archive" class="archive-checkbox" type="submit" value="<?php echo $project->project_id ?>"></td>
							<!-- <td><strong>Expenses</strong> to data</td> -->
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>

	</div>
</div>
<footer id="site-footer" class="site-footer">

</footer>
<script src="<?php echo "$this->base/js/project-controls.js" ?>" type="text/javascript"></script>
</body>
</html>