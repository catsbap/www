<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
	//retrieve the active client array into a list
	//so we can get the items out easily in the for loop
	list($clients) = Client::getClients();
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
			<li class="section-menu-item"><a class="section-menu-link" href="#">Projects</a></li>
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
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<ul id="client-list" class="client-list">
        <?php foreach ($clients as $client) { 
				//get out the client id, since it is an autoincrement field, and we need it to retrieve the primary contact.
				$client_id = Client::getClientId($client->getValueEncoded("client_name"));
				//retrieve the primary contact for the UI
				$primary_contact = Contact::getPrimaryContact($client_id[0]);
				//if the client has no contacts, spit that info out to the UI.
				if (!isset($primary_contact)) $primary_contact = new Contact(array("contact_first_name"=>"No contacts found"));
	?>
			<li class="client-list-item l-col-33">
				<img class="client-logo-thumbnail thumbnail" src="<?php echo $client->getValueEncoded("client_logo_link")?>" title="Client Logo" alt="Client Logo" />
				<ul class="client-info-list">
					<li class="client-info-name"><a class="client-info-name-link" href="<?php echo "client-detail.php?client_id=" . $client_id[0]?>" title="View client details"><?php echo $client->getValueEncoded("client_name")?></a></li>
					<li class="client-info-contact">Contact: <a class="client-info-contact-link" href="#" title="View contact details"><?php echo $primary_contact->getValue("contact_first_name") ?></a></li>
					<li class="client-info-active-projects">X Active <a class="client-info-active-projects-link" href="#" title="View active projects">Projects</a></li>
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
