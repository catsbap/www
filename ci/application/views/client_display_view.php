<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Clients</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list client">
				<li class="page-controls-item link-btn"><a class="add-client-link" href="../../../display_client">+ Add Client</a></li>
				<!--<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>-->
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<ul id="client-list" class="entity-list client">
		<?php 
		//foreach ($clients as $key=>$val) {
		//	 print_r($val);
		//}
		foreach ($clients as $client) { 
				//RETRIEVE THE CLIENT ID
				//$client_id = Client::getClientId($client->getValueEncoded("client_name"));
				$client_id = $client->client_id;
				//GET OUT THE ARCHIVE FLAG
				//$archive_flag = Client::getArchiveFlag($client->getValueEncoded("client_id")); 
				$archive_flag = $client->client_archived;
				//THIS IS NOT THE WAY TO DO THIS. 
				//if (isset($_POST["client-archive-btn"]) && $_POST["client-archive-btn"] == $client_id[0]){
				//	$archive_flag = 1;
				//	Client::setArchiveFlag($archive_flag, $client->getValueEncoded("client_id"));
				//	$archive_flag = Client::getArchiveFlag($client->getValueEncoded("client_id"));
				//}
				//GET THE PRIMARY CONTACT OUT. ALL CONTACTS MUST HAVE A PRIMARY CONTACT, CHECK THE FLAG IN THE CLIENT TABLE.
				//$primary_contact = Contact::getPrimaryContact($client->getValueEncoded("client_id"));
				//this is actually setting the value in the $primary_contact array.
				//if (!isset($primary_contact)) $primary_contact = new Contact(array("contact_name"=>"No contacts found, please investigate why this client doesn't have a contact."));
				
				//DISPLAY ONLY ACTIVE CLIENTS, ARCHIVED CLIENTS ARE ON THE ARCHIVE PAGE.
				//if ($archive_flag != 1) {
				?>
			<li class="entity-list-item client l-col-33">
				<img class="client-logo-thumbnail thumbnail" src="<?php echo "$this->base/uploads/" . $client->client_logo_link ?>" title="Client Logo" alt="Client Logo" />
				<ul class="entity-info-list client">
				<!--just sending this link to client-edit for now.-->
					<li class="entity-info-item client"><a class="client-info-name-link" href="<?php echo "client_detail/" . $client->client_id ?>" title="View client details"><?php echo $client->client_name?></a></li>
					<li class="entity-info-item client">Contact: <a class="client-info-contact-link" href="#" title="View contact details"><?php //echo $primary_contact->getValue("contact_name") ?></a></li>
					
					<?php 
					//we'll use an existing function to work this magic. Get all the clients with
					//active projects and then display them by count for a particular client.
					/*$clientProjects = Project::getClientsProjectsByStatus(0);
					$activeProjectCount = 0;
					foreach($clientProjects as $clientProject) {
						if ($client->getValueEncoded("client_id") == $clientProject->getValueEncoded("client_id")) {
							$activeProjectCount++;
						}
					}*/
					?>
					
					<li class="entity-info-item client"><?php //echo $activeProjectCount; ?> Active <a class="client-info-active-projects-link" href="projects.php" title="View active projects">Projects</a></li>
					<!--
<form action="clients.php" method="post" style="margin-bottom:50px;">
						<button id="client-archive-btn" name="client-archive-btn" class="client-archive-btn" type="submit" value="<?php echo $client_id[0] ?>" tabindex="11">Archive Client</button></form>
--> 			
			<?php	//} ?>
				</ul>		
			</li>
<?php } ?>

		</ul>
	</section>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>
