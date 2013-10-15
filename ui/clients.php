<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/time_tracker/usercake/models/config.php");

	
if(!isUserLoggedIn()){
	//redirect if user is not logged in.
	$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
	header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
}
	require_once($_SERVER["DOCUMENT_ROOT"] . "/time_tracker/common/common.inc.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/time_tracker/classes/Client.class.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/time_tracker/classes/Contact.class.php");
	
	//FUNCTION RETURNS THE INDIVIDUAL OBJECTS. 
	list($clients) = Client::getClients();
	//LEAVE THIS AS A LIST FOR INVESTIGATION.
	error_log("the client is a LIST");
	?>
	<!DOCTYPE html>
<html lang="en">
<head>
	<title>Clients</title>
	<meta charset="utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
	<link href="styles.css" rel="stylesheet" type="text/css" />
	<script src="libraries/jquery-1.10.2.min.js" type="text/javascript"></script>
</head>

<body>
<header id="site-header" class="site-header">
	<h1 class="site-title">Time Tracker</h1>
	<nav id="site-nav" class="site-nav">
		<ul id="site-menu" class="site-menu">
			<li class="site-menu-item"><a class="site-menu-link" href="#">Timesheets</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Reports</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="#">Invoices</a></li>
			<li class="site-menu-item"><a class="site-menu-link" href="manage.html">Manage</a></li>
		</ul>
	</nav>
	<nav id="section-nav" class="section-nav manage">
		<h1 class="section-nav-title">Manage: </h1>
		<ul class="section-menu">
			<li class="section-menu-item"><a class="section-menu-link" href="projects.php">Projects</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="clients.php">Clients</a></li>
			<li class="section-menu-item"><a class="section-menu-link" href="#">Team</a></li>
		</ul>
	</nav>
</header>
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Clients</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.php">+ Add Client</a></li>
				<!--<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>-->
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<ul id="client-list" class="client-list">
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
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail thumbnail" src="<?php echo $client->getValueEncoded("client_logo_link")?>" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="<?php echo "client-detail.php?client_id=" . $client_id[0]?>" title="View client details"><?php echo $client->getValueEncoded("client_name")?></a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details"><?php echo $primary_contact->getValue("contact_name") ?></a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
					<form action="clients.php" method="post" style="margin-bottom:50px;">
						<button id="client-archive-btn" name="client-archive-btn" class="client-archive-btn" type="submit" value="<?php echo $client_id[0] ?>" tabindex="11">Archive Client</button></form> 			
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
