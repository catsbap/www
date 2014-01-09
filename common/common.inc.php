<?php
require_once("../config/config.php");
require_once("../classes/Person.class.php");
session_start();
//print_r($_SESSION);

//set the default timezone
date_default_timezone_set('America/New_York');
ini_set ('display_errors', 0);
//server configuration variables for each server.
//$location_id = gethostname();
//echo($location_id);


//THIS FILE IS ALWAYS INCLUDED, SO GET THE CONFIG FOR THE AUTH.
//require_once($_SERVER["SITE_BASE"] . $_SERVER["DOCUMENT"] . "usercake/models/config.php");
//if (!securePage($_SERVER['PHP_SELF'])){die();}


//is the value in the missing field array? If so, highlight the field using the "error" style. Only do this for primary key values, handle the rest client-side.
function validateField($fieldName, $missingFields) {
	if (in_array($fieldName, $missingFields)) {
		echo ' class="client-details-label required"';
	}
}

//these functions pre-select checkboxes and menus on the page.
function setChecked(DataObject $obj, $fieldName, $fieldValue) {
	if ($obj->getValue($fieldName) == $fieldValue) {
		echo ' checked=checked';
	}
}

function setSelected(DataObject $obj, $fieldName, $fieldValue) {
	if ($obj->getValue($fieldName) == $fieldValue) {
		echo ' selected="selected"';
	}
}

//these functions are for the login part of the application. This function is called at the start of every page. (maybe it should go right here?)
function checkLogin() {
//if the session variable doesn't exist show the log in page.
	//session_start();
	//error_log(print_r($_SESSION["person"], true));
	//exit;
	if (!$_SESSION["logged_in"]) {
	//if (!$_SESSION["person"]) {
	//not sure why there is supposed to be a person variable here.
	//or !$_SESSION["person"] = Person::getPerson($_SESSION["person"]->getValue( "person_username" ))) 
	//{
		//$_SESSION["person"] = "";
		//$_SESSION["callLoginFromPage"] = $page;
		//error_log("no session!");
		//error_log(print_r($_SESSION["person"],true));
		//this refers the application back to the login screen so we can leave CI.
		//$url = ($_SERVER['HTTP_REFERER']);
		$url = $_SERVER["REQUEST_URI"];
		$get_page_name = explode('/', $url);
		$page_name = $get_page_name[2];
		if ($page_name == "ci") {
			
			//this needs to be more dynamic.
			header("Location: http://localhost:8888/time_tracker/ui/login.php");
		} else {
			header("Location: login.php");
		}
	}
	//else{
		//not logging right now.
		//probably never will, but we'll leave this in here just in case.
	//	echo "blerg";
		//$logEntry = new LogEntry(array (
		//"memberId" => $_SESSION["member"]->getValue("id"),
		//"pageUrl" => basename($_SERVER["PHP_SELF"])
		//));
		//$logEntry->record();
	//}
}

?>