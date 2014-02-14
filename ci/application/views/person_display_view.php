
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">People</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a class="add-person-link" href="insert_person">+ Add Person</a></li>
				<!-- <li class="page-controls-item"><a class="view-client-archive-link" href="project_archives.php">View Project Archives</a></li> -->
			</ul>
		</nav>
	</header>
		<?php 
			//personList is an array of objects.
			//1. Get out the employee types, display the folks by their jobs.
			//list($personTypes) = Person::getPersonTypes();
			//list($people) = Person::getPeople();
			foreach($person_perms as $perms) { ?>
				<li style="background-color:lightgray;" class="client-info-contact"><?php echo $perms->person_perm_id . "s"; ?></li>
				<?php	
				//foreach($person_types as $types) {
				//	echo $types->person_perm_id;
				//}
				
				//print_r($person_types);
				foreach($people as $person) {
				//echo $person->person_type;
					//we need to get the person permissions so we can pass them in with the object.
				//	$person_perms = Person_Permissions::getPermissionsAsObject($person->getValueEncoded("person_id"));
					//display the people as employees or contractors
					if ($person->person_perm_id == $perms->person_perm_id) {
					?>
					<section class="content">
						<ul id="client-list" class="client-list">
							<li class="client-list-item l-col-33">
								<ul class="client-info-list">
								<?php
								//this is the edit button. Since this is a $_GET, serialize the person object and send it to the page. ?>
									<li class="client-info-contact"><a class="client-info-contact-link" href="<?php echo "edit_person/" . $person->person_id ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($person->person_first_name . " " . $person->person_last_name) ?></li>
									<br/><hr/>
								</ul>		
							</li>
						</ul>
					</section>
					<?php } ?>
				<?php } ?>	
			 <?php }?>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>