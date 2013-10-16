<!-- Include AJAX Framework -->
<script src="ajax/ajax_framework.js" language="javascript"></script>
<script type='text/javascript' src='libraries/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='ajax-delete.js'></script>

<?php	
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Contact.class.php");
	require_once("../common/errorMessages.php");
	require_once("../classes/Project.class.php");

	
	if (isset($_GET["client_id"])) {
			$client_id = $_GET["client_id"];
	} elseif (isset($_POST["client_id"])) {
				$client_id = $_POST["client_id"];
	} else {
				echo "There was a problem getting the client id to prepare for the delete.";
				$client_id = "0";
	}

	if (isset($_POST["button"])) {
			$activeProjects = Project::hasActiveProjects($client_id);
			if ($activeProjects[0] > 0) {
				error_log("you can't delete that client, they have " . $activeProjects[0] . " active projects.");
				echo "You may not delete a client with active projects.";
			} else {
				error_log("now deleting client" . $client_id);
				Client::deleteClient($client_id);
			}
			echo "<html><body>";
			echo "<script>window.close()</script>";
			echo "</body></html>";
	} else {
	
	//include('header.php'); //add header.php to page
	?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Delete Client</title>
	<meta charset="utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css' />
    <link rel='stylesheet' type='text/css' href='ajax_login.css' />
	<script src="libraries/jquery-1.10.2.min.js" type="text/javascript"></script>
</head>

<body>
<section class="content" align="center">
	        <label class='error' id='error' style='display: none; font-size: 12px;'></label>
	<!--form action="delete.php" method="post"-->
	<!-- Show Message for AJAX response -->
<H4 align="center">You are about to delete this client. Are you sure? There is no undo.</H4>
<form action="javascript:check_projects()" method="post">

	<input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id?>">
	<input type="submit" name="button" value="Delete" style="text-align:center;">
	</form>
</section>
<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>
<?php } ?>