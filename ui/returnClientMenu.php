<?php
	//this shouldn't be necessary. headers are NOT sent yet if this is coded correctly.
	//function displayProjectPage() {
	//	//this probably isn't right, but I'll use it for now. won't work if JavaScript is off.
	//	printf("<script>location.href='projects.php'</script>");
	//}
	
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Client.class.php");

	//include('header.php'); //add header.php to page
	
	function returnClientMenu() {
		$select = "<strong>test</strong>";
		//get the clients out to populate the drop down.
		list($clients) = Client::getClients();
		//$select .= '<select name="client-id" id="project-client-select" size="1">';
		
		/*
foreach ($clients as $client) {
			$select .= '<option value="' . $client->getValue("client_id") . '">' . $client->getValue("client_name") .'</option>';
		}
		$select .= '</select>';
*/
		//return $select;
		return $select;
	}
	
	if (isset($_GET["func"])) {
		if ($_GET["func"] == "returnClientMenu") {
			echo returnClientMenu();
		}
	}

?>