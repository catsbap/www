<?php
	require_once("../common/common.inc.php");
	//require_once($_SERVER["DOCUMENT_ROOT"] . "/usercake/models/config.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");

//removed auth via userCake re:keith 10/17	
//if(!isUserLoggedIn()){
///	//redirect if user is not logged in.
	//$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	//header( 'Location: ../usercake/login.php' ) ;
//}
	
	//FUNCTION RETURNS THE INDIVIDUAL OBJECTS. 
	list($clients) = Client::getClients();
	
	include('header.php'); //add header.php to page
	
	?>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Clients</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list client">
				<li class="page-controls-item link-btn"><a class="add-client-link" href="client-add.php">+ Add Client</a></li>
				<!--<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>-->
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<ul id="client-list" class="entity-list client">
		<?php foreach ($clients as $client) { 
				//RETRIEVE THE CLIENT ID
				$client_id = Client::getClientId($client->getValueEncoded("client_name"));
				//GET OUT THE ARCHIVE FLAG
				$archive_flag = Client::getArchiveFlag($client_id[0]); 
				//THIS IS NOT THE WAY TO DO THIS. 
				if (isset($_POST["client-archive-btn"]) && $_POST["client-archive-btn"] == $client_id[0]){
					//echo "you clicked the archive button on the client page.";
					$archive_flag = 1;
					Client::setArchiveFlag($archive_flag, $client_id[0]);
					$archive_flag = Client::getArchiveFlag($client_id[0]);
				}
				//GET THE PRIMARY CONTACT OUT. ALL CONTACTS MUST HAVE A PRIMARY CONTACT, CHECK THE FLAG IN THE CLIENT TABLE.
				$primary_contact = Contact::getPrimaryContact($client_id[0]);
				if (!isset($primary_contact)) $primary_contact = new Contact(array("contact_name"=>"No contacts found, please investigate why this client doesn't have a contact."));
				
				//DISPLAY ONLY ACTIVE CLIENTS, ARCHIVED CLIENTS ARE ON THE ARCHIVE PAGE.
				if ($archive_flag != 1) {
				?>
			<li class="entity-list-item client l-col-33">
				<img class="client-logo-thumbnail thumbnail" src="<?php echo $client->getValueEncoded("client_logo_link")?>" title="Client Logo" alt="Client Logo" />
				<ul class="entity-info-list client">
					<li class="entity-info-item client"><a class="client-info-name-link" href="<?php echo "client-detail.php?client_id=" . $client_id[0]?>" title="View client details"><?php echo $client->getValueEncoded("client_name")?></a></li>
					<li class="entity-info-item client">Contact: <a class="client-info-contact-link" href="#" title="View contact details"><?php echo $primary_contact->getValue("contact_name") ?></a></li>
					<li class="entity-info-item client">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
					<!--
<form action="clients.php" method="post" style="margin-bottom:50px;">
						<button id="client-archive-btn" name="client-archive-btn" class="client-archive-btn" type="submit" value="<?php echo $client_id[0] ?>" tabindex="11">Archive Client</button></form>
--> 			
			<?php	} ?>
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
