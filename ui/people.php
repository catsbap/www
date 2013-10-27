<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Person.class.php");
		//remove auth
		//if(!isUserLoggedIn()){
		//redirect if user is not logged in.
		//$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
		//header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//}
	
	include('header.php'); //add header.php to page
	?>
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">People</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="add-person-link" href="person-add.php">+ Add Person</a></li>
				<!-- <li class="page-controls-item"><a class="view-client-archive-link" href="project_archives.php">View Project Archives</a></li> -->
			</ul>
		</nav>
	</header>
		<?php 
			//personList is an array of objects.
			//1. Get out the employee types, display the folks by their jobs.
			list($personTypes) = Person::getPersonTypes();
			list($people) = Person::getPeople();
			foreach($personTypes as $personType) { ?>
				<li style="background-color:lightgray;" class="client-info-contact"><?php echo $personType->getValue("person_type") . "s"; ?></li>
				<?php	
				foreach($people as $person) {
						//display the people as employees or contractors
						if ($personType->getValue("person_type") == $person->getValue("person_type")) {
						?>
						<section class="content">
							<ul id="client-list" class="client-list">
								<li class="client-list-item l-col-33">
									<ul class="client-info-list">
									<?php
									//this is the edit button. Since this is a $_GET, serialize the person object and send it to the page. ?>
										<li class="client-info-contact"><a class="client-info-contact-link" href="person-basic-info.php?person=<?php echo urlencode(serialize($person)) ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($person->getValue("person_first_name") . " " . $person->getValue("person_last_name")); ?></li>
										<br/><hr/>
									</ul>		
								</li>
							</ul>
						</section>
					<?php }} ?>	
			 <?php }?>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>