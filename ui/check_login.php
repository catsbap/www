<?php

	require_once("../classes/Person.class.php");
	//session_start();
	//error_log(print_r($_POST,true));
	
	$person = new Person( array(
	"person_username" => isset($_POST["username"]) ? preg_replace("/[^\-\.\@\_a-zA-Z0-9]/", "", $_POST["username"]) : "",
	"person_password" => isset($_POST["password"]) ? preg_replace("/[^\-\_a-zA-Z0-9]/", "", $_POST["password"]) : "",
	));
		
	$person->authenticate();
	
	if ($person->authenticate()) {
		error_log("SETTING THE SESSION VARIABLE RIGHT HERE!");
		session_start();
		$_SESSION['person'] = $person->getValue("person_username");
		echo 1;
	} else {
		echo 2;
	}

?>