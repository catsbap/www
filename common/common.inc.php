<?php

/*
function displayPageHeader($pageTitle) {
?>
<!doctype html public "-//w3c//dtd html 1.0 strict//en" "http://www.w3.org/TR/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $pageTitle?></title>
<link rel="stylesheet" type="text/css" href="/common/common.css" />
<style type="text/css">
th {text-align:left; background-color:#bbb;}
th,td {padding:0.4em;}
tr.alt td {background: #ddd;}
.error {background:#d33; color:white; padding:0.2em;}
</style>
</head>
<body>
<h1><?php echo $pageTitle?></h1>
<?php
}


//display page footer information
function displayPageFooter() {
?>
</body>
</html>
<?php
}

*/
ini_set ('display_errors', 0);

//is the value in the missing field array? If so, highlight the field using the "error" style..
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
	//echo "CALLED ERT";
}

function setSelected(DataObject $obj, $fieldName, $fieldValue) {
	if ($obj->getValue($fieldName) == $fieldValue) {
		echo ' selected="selected"';
	}
}

/*
//these functions are for the secuirty piece of the application
function checkLogin() {
	session_start();
	if (!$_SESSION["member"] or !$_SESSION["member"] = Member::getMember($_SESSION["member"]->getValue("id"))) {
		$_SESSION["member"] = "";
		header("Location: login.php");
		exit;
	}else{
		$logEntry = new LogEntry(array (
		"memberId" => $_SESSION["member"]->getValue("id"),
		"pageUrl" => basename($_SERVER["PHP_SELF"])
		));
		$logEntry->record();
	}
}
*/

?>