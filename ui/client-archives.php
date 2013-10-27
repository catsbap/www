<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
	//if(!isUserLoggedIn()){
		//redirect if user is not logged in.
	//	$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	//	header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//}
	//retrieve the active clients array into a list
	//so we can get the items out easily in the for loop
	list($clients) = Client::getClients();
	
	include('header.php'); //add header.php to page
	?>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Archived Clients</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list client">
				<!-- <li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li> -->
				<li class="page-controls-item"><a class="view-client-active-link" href="clients.php">View Active Clients</a></li>
			</ul>
		</nav>
	</header>
	<!--Patricia, I just commented this out to do some coding on this page.--client.html is in the same state it was previously.>
	<!--<section class="content">
				<ul id="client-list" class="client-list">
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail" src="images/default.jpg" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="client-detail.html" title="View client details">Client/Company Name</a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details">Contact Name</a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
				</ul>		
			</li>
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail" src="images/default.jpg" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="client-detail.html" title="View client details">Client/Company Name</a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details">Contact Name</a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
				</ul>		
			</li>
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail" src="images/default.jpg" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="client-detail.html" title="View client details">Client/Company Name</a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details">Contact Name</a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
				</ul>		
			</li>
		</ul>
	</section>-->
	<section class="content">
		<ul id="client-list" class="client-list">
        <?php foreach ($clients as $client) { 
				
				//get out the client id, since it is an autoincrement field, and we need it to retrieve the primary contact.
				$client_id = Client::getClientId($client->getValueEncoded("client_name"));
				//get the flag to out of the db for display on the non-archived page.
				$archive_flag = Client::getArchiveFlag($client_id[0]); 
				//the user changed the value of the archive button to 0 (not archived) for this specific client, update
				//it in the client table. The client-archive-btn has the client_id as its value.
				if (isset($_POST["client-unarchive-btn"]) && $_POST["client-unarchive-btn"] == $client_id[0]) {
					$archive_flag = 0;
					Client::setArchiveFlag($archive_flag, $client_id[0]);
					$archive_flag = Client::getArchiveFlag($client_id[0]);
				}
				//retrieve the primary contact for the UI
				$primary_contact = Contact::getPrimaryContact($client_id[0]);
				//if the client has no contacts, spit that info out to the UI.
				if (!isset($primary_contact)) $primary_contact = new Contact(array("contact_name"=>"No contacts found, please investigate why this client doesn't have a contact."));
				
				//only show archived clients in this page.
				if ($archive_flag) {
				?>

			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail thumbnail" src="<?php echo $client->getValueEncoded("client_logo_link")?>" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="<?php echo "client-detail.php?client_id=" . $client_id[0]?>" title="View client details"><?php echo $client->getValueEncoded("client_name")?></a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details"><?php echo $primary_contact->getValue("contact_name") ?></a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
					<?php 
				//just putting this here for now, remove it once the appropriate UI solution is in place.?>
				<!--so, you want I should archive your client?-->
				<form action="client-archives.php" method="post" style="margin-bottom:50px;">
				<button id="client-unarchive-btn" name="client-unarchive-btn" class="client-unarchive-btn" type="submit" value="<?php echo $client_id[0] ?>" tabindex="11">Unarchive Client</button></form> 
				<?php	} ?>
				</ul>		
			</li>
          <?php } ?>
<!--			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail" src="images/default.jpg" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="client-detail.html" title="View client details">Client/Company Name</a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details">Contact Name</a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
				</ul>		
			</li>
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail" src="images/default.jpg" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="client-detail.html" title="View client details">Client/Company Name</a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details">Contact Name</a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
				</ul>		
			</li>-->
		</ul>
	</section>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>
