<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Client.class.php");
	
	//this page doubles as the project archive page, so get the value off the get and display those projects here.
	$archivedView = 0;
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
	checkLogin();
	//$person = unserialize($_SESSION['person']);

	include('header.php'); //add header.php to page
?>
	

<section id="page-content" class="page-content">
	<section class="content">
		<form action="projects.php" method="post">
			<table id="project-list" class="entity-table projects tablesorter">
				<thead>
					<tr>
						<!-- you can also add a placeholder using script; $('.tablesorter th:eq(0)').data('placeholder', 'hello') -->
						<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Project(<span></span> filter-match )</th>
						<th data-placeholder="Try <d">Client</th>
						<th data-placeholder="Try >=33">Hours/Budget</th><!-- add class="filter-false" to disable the filter in this column -->
					</tr>
				</thead>
				<tbody>
				<input type="hidden" value="<?php echo $archivedView ?>" name="archives">
				<?php $projectList = Project::getClientsProjectsByStatus($archivedView);
					foreach($projectList as $project) { ?>
						<tr>
	
						<!-- <td><input id="select-project" name="select-project" type="checkbox" value="all" title="Select project" /> -->
							<td>
							<?php if ($archivedView) {
								$buttonTitle = "Unarchive this Project";
							} else {
								$buttonTitle = "Archive this Project";
							}
							?>
							<a class="project-info-name-link" href="<?php echo "project-detail.php?project_id=" . $project->getValueEncoded("project_id")?>" title="View project details"><?php echo $project->getValueEncoded("project_name")?></a>   							<?php $clientNames = Client::getClientNameById($project->getValueEncoded("client_id")) ?> <td><button type="submit" name="change_archive" value="<?php echo $project->getValueEncoded('project_id'); ?>"><?php echo $buttonTitle ?></button></td>
							
							<td><a class="client-info-contact-link" href="<?php echo "client-detail.php?client_id=" . $project->getValueEncoded("client_id")?>" title="View client details"><?php echo  $clientNames["client_name"]?></a></td>
							<td>x Hours/y budget</td>
	
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>

	</section>
		
</section>
<footer id="site-footer" class="site-footer">

</footer>
<script src="project-controls.js" type="text/javascript"></script>
</body>
</html>