<?

	require_once("../common/common.inc.php");
require_once("../classes/Project.class.php");
require_once("../classes/Client.class.php");
require_once("../classes/Contact.class.php");
require_once("../classes/Person.class.php");
require_once("../classes/Project_Person.class.php");
require_once("../classes/Project_Task.class.php");
require_once("../classes/Task.class.php");

?>

<!DOCTYPE html>
<html>
<head></head>
<body>
<h2>JSON Object Creaddtion in JavaScript</h2>
<?php 

$part_1 = "hello";
$part_2 = "Cathy";

$totalPart = "heck__$part_1" . "_$part_2";
echo $totalPart;
echo "<br>";


//get each object out from the returned lists
list($objects) = Person::getPeople();
foreach ($objects as $object) {
	$my_object = json_encode($object->jsonSerialize($object));
	//obviously this is in a loop, so the last item is always used.
}

//error_log("X");
print_r($_POST);
?>



<form action="try_js.php" method="post" style="margin-bottom:50px;">
<p>
Person ID: <input id="person_id" name="person_id"><br />
Person Username: <input id="person_username" name="person_username"><br />
person_name: <input id="person_name" name="person_name"><br />
person_first_name: <input id="person_first_name" name="person_first_name"><br />
person_last_name: <input id="person_last_name" name="person_last_name"><br />
person_email: <input id="person_email" name="person_email"><br />
person_department: <input id="person_department" name="person_department"><br />
person_hourly_rate: <input id="person_hourly_rate" name="person_hourly_rate"><br />
person_perm_id: <input id="person_perm_id" name="person_perm_id"><br />
person_type: <input id="person_type" name="person_type"><br />
person_logo_link: <input id="person_logo_link" name="person_logo_link"><br />
<input type="submit">
</p>
</form>

<script>
	var JSONObject=<?php echo $my_object?>;
	document.getElementById("person_first_name").value=JSONObject.person_first_name
	document.getElementById("person_last_name").value=JSONObject.person_last_name
	document.getElementById("person_email").value=JSONObject.person_email
	document.getElementById("person_department").value=JSONObject.person_department
	document.getElementById("person_hourly_rate").value=JSONObject.person_hourly_rate
	document.getElementById("person_perm_id").value=JSONObject.person_perm_id
	document.getElementById("person_type").value=JSONObject.person_type
	document.getElementById("person_logo_link").value=JSONObject.person_logo_link
</script>

<?php //print_r($my_object); ?>

</body>
</html>