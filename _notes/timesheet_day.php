<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Project_Person.class.php");
	require_once("../classes/Project_Task.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Timesheet.class.php");
	require_once("../classes/Timesheet_Item.class.php");
	
	//protect this page
	checklogin();
	
	include('header.php'); //add header.php to page
	
	
	if (isset($_POST["save_timesheet_button"]) and $_POST["save_timesheet_button"] == "Save Timesheet") {
		saveTimesheet();
	} else {
		displayTimesheet(new Timesheet(array()), new Timesheet_Item(array()));
	}
	


function displayTimesheet($timesheet, $timesheet_item) {


//first, get the date off of the URL. This is not set up yet, but will be required to get
//the correct timesheet out of the database. Right now it just returns the current date.
if (isset($_GET["timesheet_date"])) {
	$timesheet_date = $_GET["timesheet_date"];
} elseif (isset ($_POST["timesheet_date"])) {
	$timesheet_date = $_POST["timesheet_date"];
} else {
	$timesheet_date = date('d-m-Y');
} 
$d = strtotime($timesheet_date);



?>


<div id="page-content" class="page-content">
	<header class="page-header">
		<?php //show the date as an H1.?>
		<h1 class="page-title"><?php echo date("M d Y", $d);?></h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn">
				<?php //old HTML, not sure if we need these buttons. ?>
				<a class="view-all-link" href="project-add.php">+ Add Timesheet</a></li>
				<li class="page-controls-item"><a class="view-archive-link" href="projects.php?archives=1">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
			</ul>
		</nav>
	</header>
	<div class="content">
		<?php //BEGIN FORM as it was when it was BC...(before coolness)?>
		<form action="timesheet_day.php" method="post">
		
						<?php
						//get out the person ID (in the session) so that we always have the person_id in the form.
						if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
							$person=Person::getByEmailAddress($_SESSION["logged_in"]);
						} else {
							error_log("Something is wrong here...this person is not logged in and you shouldn't be seeing this, timesheet.php.");
							exit();
						}
						?>
						<?//php knows nothing about the client so stick the person_id field in a hidden field. Do the same for
						//project_id, task_id and notes. These are the values that came in from the horrid window. ?>
						<input type="hidden" name="person_id" value="<?php echo $person->getValueEncoded("person_id");?>">
						<input type="hidden" name="timesheet_date" value="<?php echo $timesheet_date?>">
						<input type="hidden" name="project_id" value="<?php 
	//if (isset($_GET["project_id"])) {
	//	$projectName = $_GET["project_id"];
	//} else {
	//	$projectName = "unassigned";
	//}
	?>">
	<?php //this is just the image, something Harvest doesn't do but it's groovy.?>
	<img class="client-logo-img small" style="height:100px; width:100px;" src="<?php echo "images/" . $person->getValue("person_logo_link")?>" title="Person Logo" alt="Person logo" />
	
	<?php 
	//php //just some text to let the user know what's going on when they're adding a new project.
	//if the user has no projects yet, then this will just not send anything right now.
	//echo  "Adding Timesheet for Project " . Project::getProjectName($projectName) ?>	
	<!input type="hidden" name="task_id" value="<?php 
	//if (isset($_GET["task_id"]))	 {
	//	$taskName = $_GET["task_id"];
	//} else {
	//	$taskName = "unassigned";
	//}
	?>"><?php //echo "<br>Adding Timesheet for Task " . Task::getTaskName($taskName)?>
	<!--input type="hidden" name="timesheet_notes" value="<?php 
	//if (isset($_GET["timesheet_notes"]))	 {
	//	echo $_GET["timesheet_notes"];
	//} else {
	//	echo $_POST["timesheet_notes_#"];
	//}
	//?>"-->
						
						
<table style="width:100%; border:1px solid;">
<?php 

//get out all of the user's current timesheets because we need to display them in the UI.
//this is calling direct functions and ONLY displays the items that are already stored in the DB.
list($current_timesheets) = Timesheet::getTimesheetByPersonForDate($person->getValueEncoded("person_id"), $timesheet_date);

//get the timesheet_item information for the user's current timesheets
foreach ($current_timesheets as $current_timesheet) {
	//get out the details for a particular timesheet
	list($current_timesheet_items) = Timesheet_Item::getTimesheetDates($current_timesheet->getValueEncoded("timesheet_id"));
	
	?><tr>
	
	<td style="color:orange;border:1px solid;">Person ID:<?php echo $person->getValueEncoded("person_id");?>
	<input type="hidden" name="person_id_<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>" value="<?php echo $person->getValueEncoded("person_id");?>"></td>
	<td style="color:blue;border:1px solid;">Timesheet ID:<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>
	<input type="hidden" name="timesheet_id_<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>" value="<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>"></td>
	</tr><tr>
	<?php 
	//There are two loops here because that was easier than figting tags for tables that will be gone eventually anyway. 
	$i = 0;
	foreach ($current_timesheet_items as $current_timesheet_item) {
		

	//this outputs the timesheet field name in format "timesheet_id_$timesheet_id" with the value timesheet_id
		?>
		
		<input type="hidden" name="timesheet_notes_<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>" value="<?php echo $current_timesheet_item->getValueEncoded("timesheet_notes");?>">
		<input type="hidden" name="timesheet_id_<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>" value="<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>">
		
		<?php //this outputs fields with the name "timesheet_date_$timesheet_item_id_$i (so these are the timesheet dates)
		?>
		<td style="border:1px solid;"><input type="hidden" name="timesheet_date_<?php echo $current_timesheet->getValueEncoded("timesheet_id");?>_<?php echo $i?>" value="<?php echo $current_timesheet_item->getValueEncoded("timesheet_date");?>"><?php echo $current_timesheet_item->getValueEncoded("timesheet_date");?></td>
	<?php $i++; 
	} ?>
	</tr>
	<tr>
	<?php
	$i = 0;
	foreach ($current_timesheet_items as $current_timesheet_item) {
		//this outputs fields with the name "timesheet_hours_$timesheet_id_$i (so these are the timesheet dates)
		?><td style="border:1px solid;"><input name="timesheet_hours_<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>_<?php echo $i ?>" value="<?php echo $current_timesheet_item->getValueEncoded("timesheet_hours");?>">
		<input type="hidden" name="task_id_<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>_<?php echo $i ?>" value="<?php echo $current_timesheet_item->getValueEncoded("task_id");?>">
		<input type="hidden" name="person_id_<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>_<?php echo $i ?>" value="<?php echo $current_timesheet_item->getValueEncoded("person_id");?>">
		<input type="hidden" name="project_id_<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>_<?php echo $i ?>" value="<?php echo $current_timesheet_item->getValueEncoded("project_id");?>">
		<input type="hidden" name="timesheet_item_id_<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>_<?php echo $i ?>" value="<?php echo $current_timesheet_item->getValueEncoded("timesheet_item_id");?>">
		</td>
	<?php $i++;
	} ?>
	</tr>
<?php }


//this is code for when the user adds a new timesheet. Just put blank data in the UI.
//can we compress this code???
?>
<tr>

	
	<?php 
	//this is only here because we need these values in a hidden field the first time the project goes in.
	//insert the project ID. 
	if (isset($_GET["project_id"])) {?>
		<input type="hidden" name="person_id_#" value="<?php echo $person->getValueEncoded("person_id");?>">
		<input type="hidden" name="project_id_#" value="<?php echo $_GET["project_id"]?>">
		<input type="hidden" name="task_id_#" value="<?php echo $_GET["task_id"] ?>">
		<input type="hidden" name="timesheet_notes_#" value="<?php echo $_GET["timesheet_notes"] ?>">

	<?php } ?>


<?if (isset($_GET["project_id"])) {
//$i here should be configurable.
	for ($i=0; $i<1; $i++) {
		?><td><?php echo date("D M d", strtotime('sunday this week -1 week + ' . $i . ' days', $d))?>
		<input type="hidden" name="timesheet_date_#_<?php echo $i?>" value="<?php echo date("D M d", strtotime('sunday this week -1 week + ' . $i . ' days', $d))?>">
		<!--input type="hidden" name="task_id_#_<?php echo $i?>" value="<?php echo $task_id?>"-->
		<!--input type="hidden" name="person_id_#_<?php echo $i?>" value="<?php echo $person->getValueEncoded("person_id");?>">
		<input type="hidden" name="project_id_#_<?php echo $i?>" value="<?php echo $project_id?>">
		<input type="hidden" name="timesheet_notes_#_<?php echo $i?>" value="<?php echo $timesheet_notes?>"-->
		<input name="timesheet_hours_#_<?php echo $i ?>"></td>
		<?php } ?>
		</tr><tr>
		<?php 
		//	for ($i=0; $i<7; $i++) {
			?><!--td><input name="timesheet_hours_#_<?php //echo $i ?>"></td-->
	<?php //} 
}
	?>	

</tr>
</table>
			<?php //OK, all of the timesheets should be in the UI. ?>
			<input type="button" name="add_row_button" value="Add Row" onclick="javascript:window.open('add_timesheet_row.php?person_id=<?php echo $person->getValueEncoded("person_id")?>&timesheet_date=<?php echo $timesheet_date?>','myWindow','width=300,height=200,left=250%,right=250%,scrollbars=no')">
			<input type="submit" name="save_timesheet_button" value="Save Timesheet">
		</form>

	</div>
</div>
<?php
}

 
function saveTimesheet() {

	//CREATE THE TIMESHEET OBJECTS ($timesheet).
	//put each timesheet and its data into an array labeled with the timesheet ID.
	$person=Person::getByEmailAddress($_SESSION["logged_in"]);
	list($timesheet_ids) = Timesheet::getTimesheetIds($person->getValueEncoded("person_id"));
			
	//GET OUT THE STORED TIMESHEETS AND CREATE THE item OBJECTS TO DISPLAY IN THE UI.	
	//in this world, we're getting the values from the database, so task_id, project_id and person_id follow the field_name_$tid_$i style here.
	
	foreach ($timesheet_ids as $timesheet_id) {
		$tid = $timesheet_id->getValueEncoded("timesheet_id");
		$timesheet[$tid] = new Timesheet( array(
		//CHECK REG SUBS!!
		"timesheet_id" => isset($_POST["timesheet_id_$tid"]) ? preg_replace("/[^ 0-9]/", "", $_POST["timesheet_id_$tid"]) : "",
		"timesheet_approved" => isset($_POST["timesheet_approved_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_approved_$tid"]) : "",
		"timesheet_submitted" => isset($_POST["timesheet_submitted_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_submitted_$tid"]) : "",
		"person_id" => isset($_POST["person_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_id_$tid"]) : ""
		));
			
		$timesheet_aggregate[] = $timesheet[$tid];
		//print_r($timesheet_aggregate);

		//create the timesheet detail object ($timesheet_detail)
		for ($i=0; $i<7; $i++) {
			$timesheet_item[$tid][$i] = new Timesheet_Item( array(
			//CHECK REG SUBS!!
			"timesheet_item_id" => isset($_POST["timesheet_item_id_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_item_id_$tid"."_$i"]) : "",
			"person_id" => isset($_POST["person_id_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_id_$tid"."_$i"]) : "",
			"timesheet_date" => isset($_POST["timesheet_date_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_date_$tid"."_$i"]) : "",
			"task_id" => isset($_POST["task_id_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_id_$tid"."_$i"]) : "",
			"project_id" => isset($_POST["project_id_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_id_$tid"."_$i"]) : "",
			"timesheet_hours" => isset($_POST["timesheet_hours_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_hours_$tid"."_$i"]) : "",
			"timesheet_notes" => isset($_POST["timesheet_notes_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_notes_$tid"."_$i"]) : ""
			));
			$timesheet_item_aggregate[] = $timesheet_item[$tid][$i];
		}
	} 
		error_log("timesheet aggregate here");
		error_log(print_r($timesheet_aggregate,true));
		error_log("timesheet item aggregate here");
		error_log(print_r($timesheet_item_aggregate, true));

	
	//USER IS ADDING A TIMESHEET FOR THE FIRST TIME, or they have no timesheets at all, so we don't have a timesheet id to put in the field
	//in the UI. Instead, use "#".
	//I am not going to fight the UI anymore. It is inconsistent, but the task_id, the project_id and the person_id all are based on the field_name_$tid style
	//and not field_name_$tid_$i style. If we keep this UI in any way, I will fight it later. 11/16
	
	
	if	(isset($_POST["person_id_#"])) {
		$tid = "#";
		$timesheet[$tid] = new Timesheet( array(
		//CHECK REG SUBS!!
		"timesheet_id" => isset($_POST["timesheet_id_$tid"]) ? preg_replace("/[^ 0-9]/", "", $_POST["timesheet_id_$tid"]) : "",
		"timesheet_approved" => isset($_POST["timesheet_approved_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_approved_$tid"]) : "",
		"timesheet_submitted" => isset($_POST["timesheet_submitted_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_submitted_$tid"]) : "",
		"person_id" => isset($_POST["person_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_id_$tid"]) : ""
		));
		
		$timesheet_aggregate[] = $timesheet[$tid];
		
		//create the timesheet detail object ($timesheet_item), all of the values are blank, since this timesheet is not in the db yet.
	
		for ($i=0; $i<7; $i++) {
			$timesheet_item[$tid][$i] = new Timesheet_Item( array(
			//CHECK REG SUBS!!
			"timesheet_item_id" => isset($_POST["timesheet_item_id_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_item_id_$tid"."_$i"]) : "",
			"timesheet_date" => isset($_POST["timesheet_date_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_date_$tid"."_$i"]) : "",
			"task_id" => isset($_POST["task_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_id_$tid"]) : "",
			"project_id" => isset($_POST["project_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["project_id_$tid"]) : "",
			"person_id" => isset($_POST["person_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_id_$tid"]) : "",
			"timesheet_hours" => isset($_POST["timesheet_hours_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_hours_$tid"."_$i"]) : "",
			"timesheet_notes" => isset($_POST["timesheet_notes_$tid"."_$i"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_notes_$tid"."_$i"]) : ""
			));
			$timesheet_item_aggregate[] = $timesheet_item[$tid][$i];
		} 
	}
	
	error_log("here is the post");
	error_log(print_r($_POST,true));
	error_log("here is the timesheet aggregate");
	error_log(print_r($timesheet_aggregate,true));
	error_log(print_r($timesheet_item_aggregate, true));
	

		//timesheet aggregate has all the timesheets
		//loop through the timesheets in the aggregate and update or insert each based on the class.
		foreach ($timesheet_aggregate as $timesheet_object) {
			//insert or update the timesheet(s).
			if ((get_class($timesheet_object)) == "Timesheet") {
				$timesheet_exists = $timesheet_object->getTimesheetById($timesheet_object->getValueEncoded("timesheet_id"));	
				
				if ($timesheet_exists == 0) {
					echo "<br><br>timesheet doesn't exist.<br><br>";
					print_r($timesheet_object);
					//echo "PERSON VALUE IS";
					$timesheet_object->getValueEncoded("person_id");
					//echo $timesheet_object->getValueEncoded("person_id");
					$lastInsertId = $timesheet_object->insertTimesheet($timesheet_object->getValueEncoded("person_id"));
					
					//put the timesheet id into the object, maybe we'll have it later.
					$timesheet_object->setValue("timesheet_id", $lastInsertId[0]);
					//echo "<br><br>LAST INSERT ID IS ";
					//print_r($lastInsertId[0]);
					//echo "<br><br><br>";
				} else {
					//echo "<br><br>timesheet exists.<br><br>";
					//print_r($timesheet_object);
					//timesheet exists, update it.
					$timesheet_object->updateTimesheet($timesheet_object->getValueEncoded("timesheet_id"));	
				}
			} 
		}
		
		
		
		//timesheet item aggregate has all the timesheet items.
		//loop through the timesheet items in the aggregate and update or insert each based on the class.
		foreach ($timesheet_item_aggregate as $timesheet_object) {
			if ((get_class($timesheet_object)) == "Timesheet_Item") {
				$timesheet_item_exists = $timesheet_object->getTimesheetItems($timesheet_object->getValueEncoded("timesheet_item_id"), $timesheet_object->getValueEncoded("timesheet_date"));
				//echo "HERE!!";
				//print_r($timesheet_item_exists);
				//no timesheet item, insert it.
				if (!$timesheet_item_exists) {
					//echo "this timesheet item doesn't exist.";
					//print_r($timesheet_object);
					//echo "<br><br>LAST sINSERT ID IS ";
					//print_r($lastInsertId[0]);
					//echo "<br><br><br>here is what we're trying to insert: ";
					//echo print_r($timesheet_object);
					$timesheet_object->insertTimesheetItem($timesheet_object->getValueEncoded("person_id"), $lastInsertId[0]);
				} else {
					//echo "<br><br>LAST sINSERT ID IS ";
					//print_r($lastInsertId[0]);
					//echo "<br><br><br>";
					//timesheet item exists, update it.
					$timesheet_object->updateTimesheetItem($timesheet_object->getValueEncoded("timesheet_item_id"));	
					//echo "that timesheet info does exist.";
				}
			}
				
		}	
	displayTimesheet($timesheet_aggregate, $timesheet_item_aggregate);
}

?>
<footer id="site-footer" class="site-footer">

</footer>
<script src="project-controls.js" type="text/javascript"></script>
</body>
</html>