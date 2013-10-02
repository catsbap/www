<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
	
	//RETRIEVE THE CLIENT ID FROM GET OR POST.
	if (isset ($_GET["client_id"])) {
		$client_id = $_GET["client_id"];
	} elseif (isset ($_POST["client_id"])) {
		$client_id = $_POST["client_id"]; 
	} else {
		echo "no client identifier provided, cannot find details for empty client.";
		exit;
	}
	
	//RETRIEVE ALL OF THE CONTACTS FOR THIS CLIENT. NOTE THIS IS ALL CLIENTS, ACTIVE AND ARCHIVED.
	$contacts = Contact::getContacts($client_id);
	//RETRIEVE THE CLIENT DETAILS TO DISPLAY IN THE UI.
	$client_details = Client::getClient($client_id);
	error_log("HERE ARE THE CLIENT DETAILS IN CLIENT DETAILS PAGE:");
	error_log(print_r($client_details,true));
	if (!isset($client_details)) {
    	error_log("The detailed data for this client is not available. Please investigate why this happened, client_details.php, line 23");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Client Detail</title>
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
		<h1 class="page-title">Client Details</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<!-- I am just putting this here because I need to send the client id into the client-edit php file.-->
				<li class="page-controls-item"><a class="view-all-link" href="client-edit.php?client_id=<?php echo $client_id?>">Edit This Client</a></li>
<!--end-->
<li class="page-controls-item"><a class="view-all-link" href="clients.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<section class="content">
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="<?php echo $client_details->getValue("client_logo_link")?>" title="Client/Company name logo" alt="Client/Company name logo" />
		</figure>
		<section class="client-detail l-col-80">
			<header class="client-details-header">
				<h1 class="client-details-title"><?php echo $client_details->getValue("client_name")?></h1>
			</header>
			<ul class="details-list client-details-list">
				<li class="client-details-item address"><?php echo $client_details->getValue("client_address")?></li>
				<li class="client-details-item phoneNum"><?php echo $client_details->getValue("client_phone")?></li>
				<li class="client-details-item email"><?php echo $client_details->getValue("client_email")?></li>
				<li class="client-details-item fax"><?php echo $client_details->getValue("client_fax")?></li>
				<li class="client-details-item address">
					<?php echo $client_details->getValue("client_address")?>
				</li>
				<li class="client-details-item currency"><?php echo Client::getCurrencyByIndex($client_details->getValue("client_currency_index"))?></li>
			</ul>
		</section>
		<section class="contact-detail">
			<header class="details-header contact-details-header">
				<h1 class="client-details-title">Contacts</h1>
			</header>
           <?php 
           //DISPLAY THE CONTACT DETAILS FOR THIS CLIENT.
           foreach ($contacts as $contact) { ?>
			<ul class="details-list contact-details-list">
				<li class="contact-details-item name"><?php echo $contact->getValue("contact_name")?></li>
				<li class="contact-details-item phoneNum"><?php echo $contact->getValue("contact_office_number")?></li>
				<li class="contact-details-item email"><?php echo $contact->getValue("contact_email")?></li>
				<li class="contact-details-item fax"><?php echo $contact->getValue("contact_fax_number")?></li>
			</ul>
        <?php } ?>
		</section>
    	<section class="client-projects">
			<header class="details-header client-projects-header">
				<h1 class="client-details-title">Projects</h1>
			</header>
			<h1 class="client-projects-title active">Active Projects</h1>
			<ul class="details-list client-projects-list active">
				<li class="client-projects-list-item">Atomic Cupcakes</li>
			</ul>
			<h1 class="client-projects-title archive">Archived Projects</h1>
			<ul class="details-list client-projects-list archive">
				<li class="client-projects-list-item">Atomic Cupcakes 'Coming Soon' Campaign</li>
			</ul>
		</section>
	</section>
</section>
<footer id="site-footer" class="site-footer">

</footer>

</body>
</html>
