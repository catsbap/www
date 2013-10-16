<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	include('header.php'); //add header.php to page

	if(!isUserLoggedIn()){
		//redirect if user is not logged in.
		$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
		header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	}
	
	?>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Projects</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="add-project-link" href="project-add.php">+ Create Project</a></li>
				<li class="page-controls-item"><a class="view-project-archive-link" href="project_archives.php">View Project Archives</a></li>
			</ul>
		</nav>
	</header>
		<?php $clientList = Project::getClientsProjectsByStatus(0);
			foreach($clientList as $clients) {
		?>
	<section class="content">
		<ul id="project-list" class="entity-list project">
			<li class="entity-list-item project l-col-33">
				<ul class="entity-info-list project">
					<li class="client-info-contact">Client: <a class="client-info-contact-link" href="<?php echo "client-detail.php?client_id=" . $clients["client_id"]?>" title="View client details"><?php echo $clients["client_name"] ?></li>
					<?php 
					//get the array of project objects for this client.
					$projects = Project::getProjectByClientId($clients["client_id"]);
					foreach ($projects as $project) { 
					?>
						<li class="project-info-name"><a class="project-info-name-link" href="<?php echo "project-detail.php?project_id=" . $project->getValueEncoded("project_id")?>" title="View project details"><?php echo $project->getValueEncoded("project_name")?></a></li>
					<?php } ?>
				</ul>		
			</li>
		</ul>
	</section>
	<?php } ?>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>