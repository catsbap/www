<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Client.class.php");
	require_once("../classes/Project.class.php");
	require_once("../classes/Project_Person.class.php");
	require_once("../classes/Project_Task.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Task.class.php");
	require_once("../classes/Timesheet.class.php");
	require_once("../classes/Timesheet_Item.class.php");
	
	//protect this page
	checklogin();
	
	//get the value off the url for timesheet_id
	$processType = "A";
	if (isset($_GET["timesheet_id"]) && $_GET["timesheet_id"] == "") {
		$processType = "A";
	} elseif (isset($_GET["timesheet_id"])) {
		$processType = "E";
	}
	
	if (isset($_POST["func"])) {
		if ($_POST["func"] == "saveTimesheet") {
			//error_log(">>>>>>  save timesheet");
			if (isset($_POST["proc_type"])) {
				$processType = $_POST["proc_type"];
				echo saveTimesheet($processType);
			}
		}
	} else {
		if (isset($_POST["save_timesheet_button"]) and $_POST["save_timesheet_button"] == "Save Timesheet") {
			saveTimesheet($processType);
		} else {
			include('header.php'); //add header.php to page moved to only be called when page is rendered so it's not sent back when page saved via JS/Ajax
			displayTimesheet(new Timesheet(array()), new Timesheet_Item(array()));
		}
	}
	
	
	
function displayTimesheet($timesheet_aggregate) {
	//error_log("HERE IS THE POST!!!!!!!!!!!!!!!!!!");
	//error_log(print_r($_POST, true));
	//first, get the date off of the URL. This is not set up yet, but will be required to get
	//the correct timesheet out of the database. Right now it just returns the current date.
	if (isset($_GET["timesheet_date"])) {
		$timesheet_date = $_GET["timesheet_date"];
	} else {
		$timesheet_date = date('d-m-Y');
	}
	$d = strtotime($timesheet_date);

	//echo "here is timesheet aggregate when you first come in.";
	//print_r($timesheet_aggregate);
	//exit;
	if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] != "") {
		$person = Person::getByEmailAddress($_SESSION["logged_in"]);
	} else {
		error_log("Something is wrong here...this person is not logged in and you shouldn't be seeing this, timesheet.php.");
		exit();
	}
	
	
	/*	Timesheet::deleteTimesheet(356);
	Timesheet::deleteTimesheet(384);
	Timesheet::deleteTimesheet(385);
	Timesheet::deleteTimesheet(386);
	Timesheet::deleteTimesheet(390);
*/
	?>
	
	<div id="page-content" class="page-content">
		<header class="page-header">
			<h1 class="page-title"><?php echo date("F j, Y");?></h1>
			<nav class="page-controls-nav">
				<ul class="page-controls-list timesheet">
					<!-- <li class="page-controls-item link-btn"><a class="view-all-link" href="project-add.php">+ Add Timesheet</a></li> --> <!-- We probably don't need this -->
					<li class="page-controls-item"><a class="view-archive-link" href="projects.php?archives=1">View Archives</a></li>
					<li class="page-controls-item"><a class="view-all-link" href="projects.php">View All</a></li>
				</ul>
			</nav>
		</header>
		<div id="add-ts-entry-modal" class="entity-detail" title="Add Time Entry">
			<form id="ts-entry-input-form" action="timesheets.php" method="post" enctype="multipart/form-data">
				<input id="proc-type" type="hidden" name="action" value="<?php echo $processType ?>"/>
				<fieldset class="entity-details-entry  modal">
					<header class="entity-details-header timesheet">
						<h1 class="entity-details-title">Enter time entry details:</h1>
						<h4 class="required">= Required</h4>
					</header>
					<section id="timesheet-info" class="entity-detail">
						<ul class="details-list entity-details-list timesheet">
							<li class="entity-details-item name project">
								<label for="project_name" <?php validateField("project_name", $missingFields)?> class="entity-details-label">Project:</label>
								<select id="project-name" name="project_name" class="project-name-select" tabindex="1">
									<?php //this may be moved to JS
										list($projectsForPerson) = Project_Person::getProjectsForPerson($person->getValue("person_id"));
										//$first = 31;
										foreach ($projectsForPerson as $projectPerson) {
											$client = Client::getClient( $projectPerson->getValue("client_id") ); ?>
											<option value="<?php echo $projectPerson->getValue("project_id"); ?>"><span><?php echo $projectPerson->getValue("project_name"); ?></span> (<?php echo $client->getValue("client_name"); ?>)</option>
									<?php }	?> 
								</select>
							</li>
							<li class="entity-details-item hourly-rate task">
								<label for="task_name" <?php validateField("task_name", $missingFields)?> class="entity-details-label">Task:</label>
								<select id="task-name" name="task_name" class="task-name-select" tabindex="1">
								</select>
							</li>
						</ul>
					</section>
				</fieldset>
			</form>
		</div>
		<div id="timesheet" class="entity-detail">
			<nav class="timesheet-controls">
				<span id="time-period">
					<a href="#" class="ui-button previous-date">Previous week</a>
					<a href="#" class="ui-button current-date">This week</a>
					<a href="#" class="ui-button next-date">Next week</a>
				</span>
				<input id="date-picker" type="text" class="date-picker-input" title="Enter date" value="" placeholder="Enter date" />
				<span id="time-display">
					<input id="day-view" type="radio" name="time-view" class="ui-button time-view-button" title="Day" value="Day" /><label for="day-view">Day</label>
					<input id="week-view" type="radio" name="time-view" class="ui-button time-view-button" title="Week" value="Week" checked="checked" /><label for="week-view">Week</label>
				</span>
				
			</nav>
			<table id="timesheet-tasks-list" class="entity-table timesheet tablesorter" data-person_id="<?php echo $person->getValue('person_id'); ?>">
				<thead>
					<tr>
						<th class="task-name"></th>
						<th data-sorter="false" class="day">M</th>
						<th data-sorter="false" class="day">T</th>
						<th data-sorter="false" class="day">W</th>
						<th data-sorter="false" class="day">Th</th>
						<th data-sorter="false" class="day">F</th>
						<th data-sorter="false" class="day">Sa</th>
						<th data-sorter="false" class="day">Su</th>
						<th data-sorter="false" class="total day"></th>
						<th data-sorter="false" class="delete"></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td><a href="#" class="ui-button new-time-entry add-row ">+ Add Row</a> <a href="#" class="ui-button save">Save</a> <span class="last-saved"></span></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="day total"></td>
						<td class="week-total"></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="7"><a href="#" class="ui-button submit-timesheet">Submit For Approval</a></td>
					</tr>
				</tfoot>
				<tbody class="week-view">
				</tbody>
			</table>
		</div>
	</div>
	<footer id="site-footer" class="site-footer">
	
	</footer>
<script src="timesheet-controls.js" type="text/javascript"></script>
</body>
</html>		

<?php
}

function saveTimesheet($processType) {
	error_log("POST: " . $_POST["timesheetItems"] );
	$timesheet_items = json_decode($_POST["timesheetItems"]);
	$delete_items = json_decode($_POST["deleteItems"]);
	$timesheet_submitted = json_decode($_POST["timesheet_submitted"]);
	
	error_log(">>>>>> delete_items: " . count($delete_items));
	
	if ($delete_items) {
		foreach($delete_items as $delete_item) {
			error_log(">>>>>>>" . $delete_item->person_id . ", " . $delete_item->project_id . ", " . $delete_item->task_id . ", " .  $delete_item->timesheet_date);
			$deltsi = new Timesheet_Item( array(
				"timesheet_item_id" => preg_replace("/[^ 0-9]/", "", $delete_item->timesheet_item_id),
				"person_id" => preg_replace("/[^ 0-9]/", "", $delete_item->person_id),
				"timesheet_date" => preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $delete_item->timesheet_date),
				"task_id" => preg_replace("/[^ 0-9]/", "", $delete_item->task_id),
				"project_id" => preg_replace("/[^ 0-9]/", "", $delete_item->project_id),
				"timesheet_hours" => preg_replace("/[^ \.\:[0-9]]/", "", $delete_item->timesheet_hours),
				"timesheet_notes" => preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $delete_item->timesheet_notes),
			));
		
			$deltsi->deleteTimesheetItem($deltsi);
		}
	}
	
	foreach($timesheet_items as $timesheet_item) {
		//error_log(">>> " . $timesheet_item->timesheet_date . ", " . $timesheet_item->person_id . ", " . $timesheet_item->project_id . ", " . $timesheet_item->task_id);
		$tsi = Timesheet_Item::getTimesheetItemForDatePersonProjectTask($timesheet_item->timesheet_date, $timesheet_item->person_id, $timesheet_item->project_id, $timesheet_item->task_id);
		error_log("+++ " . $tsi);
		if ( $tsi ) {
			$tsi = $tsi[0];
			//update
			$tsi->setValue("timesheet_item_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->timesheet_item_id));
			$tsi->setValue("person_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->person_id));
			$tsi->setValue("timesheet_date", preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_date));
			$tsi->setValue("task_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->task_id));
			$tsi->setValue("project_id", preg_replace("/[^ 0-9]/", "", $timesheet_item->project_id));
			$tsi->setValue("timesheet_hours", preg_replace("/[^ \.\:[0-9]]/", "", $timesheet_item->timesheet_hours));
			$tsi->setValue("timesheet_notes", preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_notes));
			$tsi->updateTimesheetItem($timesheet_item->timesheet_item_id);
		} else {
			//insert
			$newtsi = new Timesheet_Item( array(
				"timesheet_item_id" => preg_replace("/[^ 0-9]/", "", $timesheet_item->timesheet_item_id),
				"person_id" => preg_replace("/[^ 0-9]/", "", $timesheet_item->person_id),
				"timesheet_date" => preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_date),
				"task_id" => preg_replace("/[^ 0-9]/", "", $timesheet_item->task_id),
				"project_id" => preg_replace("/[^ 0-9]/", "", $timesheet_item->project_id),
				"timesheet_hours" => preg_replace("/[^ \.\:[0-9]]/", "", $timesheet_item->timesheet_hours),
				"timesheet_notes" => preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $timesheet_item->timesheet_notes),
			));
			$newtsi->insertTimesheetItem($timesheet_item->person_id, $timesheet_item->timesheet_item_id);
		}
	}
	if ( $timesheet_submitted == 1 ) {
		Timesheet::submitTimesheet($timesheet_item->timesheet_item_id);
		$message = " and submitted for approval";
		error_log("timesheet submitted" );
	} else {
		$message = "";
	}
	

	return "Timesheet saved" . $message . ".";
}
 
function saveTimesheetOld() {

	//****************************************************************//
	/*NOTE TO PATRICIA:
	the way i used to do this is as follows (not sure if the line #s will translate here, but hopefully they will).
	LINE 183: get out all of the timesheets for a given person. Can probably delete the code.
	LINE 189: Loop through each timesheet in the array of timesheets in the database.
	LINE 190: store the timesheet id as a "tid" variable. Use that variable to identify the values that were sent with the post.
	LINE 191: store each value in the timesheet object in an array of objects labeled with the timesheet_id (TID) 
	LINE 202: add the timesheet array to the timesheet_aggregate array, which put together all of the timesheets and all of the timesheet items so they could be inserted or updated later.
	LINE 206: loop through the dates (0-6) and create timesheet items. These items were identified in the post in the form:
	[name]_$tid_$i.
	LINE 218: add the timesheet items to the the timesheet_aggregate array, 
	
	//CREATE THE TIMESHEET OBJECTS ($timesheet).
	//get out each timesheet for this person put each timesheet and its data into an array labeled with the timesheet ID.
	//this code only deals with timesheets the user has stored.
	//BUG FIX 3: we already have this, don't call the session again.
	$person=Person::getByEmailAddress($_SESSION["logged_in"]);
	//list($timesheet_ids) = Timesheet::getTimesheetIds($person->getValueEncoded("person_id"));
	//**WE CAN PEOBABLY REMOVE THIS, IT IS ONLY HERE SO THAT WE COULD LOOP THROUGH THE ARRAY.***/
	list($timesheet_ids) = Timesheet::getTimesheetByPersonForDate($person->getValueEncoded("person_id"), $timesheet_date);
			
	//We're getting the values from the database, so task_id, project_id and person_id follow the field_name_$tid_$i style here.
	
	//**WE SHOULD ONLY NEED ONE LOOP HERE, SINCE WE DON'T HAVE TO DISTINGUISH BETWEEN THE UN-ADDED AND ADDED TIMESHEETS THIS WAY ANYMORE.
	//**WE SHOULD BE ABLE TO COMMENT OUT THIS CODE.
	foreach ($timesheet_ids as $timesheet_id) {
		$tid = $timesheet_id->getValueEncoded("timesheet_id");
		$timesheet[$tid] = new Timesheet( array(
		//CHECK REG SUBS!!
		"timesheet_id" => isset($_POST["timesheet_id_$tid"]) ? preg_replace("/[^ 0-9]/", "", $_POST["timesheet_id_$tid"]) : "",
		"timesheet_approved" => isset($_POST["timesheet_approved_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_approved_$tid"]) : "",
		"timesheet_submitted" => isset($_POST["timesheet_submitted_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_submitted_$tid"]) : "",
		"timesheet_start_date" => isset($_POST["timesheet_start_date_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_start_date_$tid"]) : "",
		"timesheet_end_date" => isset($_POST["timesheet_end_date_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_end_date_$tid"]) : "",
		"person_id" => isset($_POST["person_id_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["person_id_$tid"]) : ""
		));
			
		//store the timesheet fields in an array
		$timesheet_aggregate[] = $timesheet[$tid];


		//create the timesheet item objects ($timesheet_items)
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
			//store the timesheet items in an array.
			$timesheet_item_aggregate[] = $timesheet_item[$tid][$i];
		}
	} 
		//debug code to check arrays after the values have been pulled
		//error_log("timesheet aggregate");
		//error_log(print_r($timesheet_aggregate,true));
		//error_log("timesheet item aggregate");
		//error_log(print_r($timesheet_item_aggregate, true));

	
	//USER IS ADDING A TIMESHEET FOR THE FIRST TIME, or they have no timesheets at all, so we don't have a timesheet id to put in the field
	//in the UI. Instead, use "#".
	//BUG FIX 4 instructions:
	//I am not going to fight the UI anymore. It is inconsistent, but the task_id, the project_id and the person_id all are based on the field_name_$tid style
	//and not field_name_$tid_$i style. If we keep this UI in any way, I will fight it later. 11/16
	
	//**WE DON'T NEED A "#TID" VALUE ANYMORE. WE DO NEED THE TIMESHEET ID, OR SOME OTHER WAY TO SET THE PROCESSTYPE (IF WE STILL EVEN NEED TO DO THAT HERE**//
	if	(isset($_POST["person_id_#"])) {
		$tid = "#";
		$timesheet[$tid] = new Timesheet( array(
		//CHECK REG SUBS!!
		"timesheet_id" => isset($_POST["timesheet_id_$tid"]) ? preg_replace("/[^ 0-9]/", "", $_POST["timesheet_id_$tid"]) : "",
		"timesheet_approved" => isset($_POST["timesheet_approved_$tid"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_approved_$tid"]) : "",
		"timesheet_submitted" => isset($_POST["timesheet_submitted_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_submitted_$tid"]) : "",
		"timesheet_start_date" => isset($_POST["timesheet_start_date_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_start_date_$tid"]) : "",
		"timesheet_end_date" => isset($_POST["timesheet_end_date_$tid"])? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["timesheet_end_date_$tid"]) : "",
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
	error_log("here are the timesheet aggregate");
	error_log(print_r($timesheet_aggregate,true));
	error_log(print_r($timesheet_item_aggregate, true));
	

		//**ADD OR EDIT THE TIMESHEETS BASED ON THE PROCESS TYPE***//
		
		//**THIS WAS GETTING ALL THE OBJECTS AND FIGURING OUT WHAT THEY WERE, NOT SURE IF THIS MATTERS ANYMORE
		//foreach ($timesheet_aggregate as $timesheet_object) {
			//**THIS WAS DETERMINING WHAT OBJECT WE ARE WORKING WITH, BUT SINCE WE'RE NOT GETTING THE ITEMS OUT OF AN ARRAY LIKE THIS ANYMORE
			//**WE CAN PROBABLY COMMENT OUT THIS IF STATEMENT.
			//if ((get_class($timesheet_object)) == "Timesheet") {
				//**WE CAN PROBABLY GET RID OF THIS SINCE WE ARE NOW ASSUMING WE ARE EITHER UPDATING OR ADDING**//
				//$timesheet_exists = $timesheet_object->getTimesheetById($timesheet_object->getValueEncoded("person_id"), $timesheet_object->getValueEncoded("timesheet_start_date"),$timesheet_object->getValueEncoded("timesheet_end_date"));
				
//**GET THE PROCESS TYPE IN ONE WAY OR ANOTHER
//**I AM NOT 100% SURE HOW THIS WILL COME IN SO IT WILL BE JUST BASED ON THE GET HERE.
//***TIMESHEET_OBJECT USED TO BE THE OBJECT WE WERE UPDATING OR ADDING WHEN IT WAS BROUGHT IN FROM THE UI WITH PHP.
	if ($processType = "E") {
		//UPDATE TIMESHEET
		error_log("<br><br>timesheet doesn't exist.");
		$timesheet_object->getValueEncoded("person_id");
		//**ACTUALLY INSERT THE TIMESHEET
		$lastInsertId = $timesheet_object->insertTimesheet($timesheet_object->getValueEncoded("person_id"));
		//**THIS JUST PUTS THE LAST INSERT ID INTO THE OBJECT, AGAIN NOT SURE IF IT MATTERS ANYMORE
		//SINCE THE INFORMATION ISN'T GOING THROUGH THE POST.
		$timesheet_object->setValue("timesheet_id", $lastInsertId[0]);
		//UPDATE TIMESHEET ITEM
		$timesheet_object->updateTimesheetItem($timesheet_object->getValueEncoded("timesheet_item_id"));	
		error_log("that timesheet info does exist.");
	} elseif ($processType = "A") {

		//*INSERT THE TIMESHEET (ADD IT TO THE DB)
		$timesheet_object->updateTimesheet($timesheet_object->getValueEncoded("timesheet_start_date"),$timesheet_object->getValueEncoded("timesheet_end_date"));		//*INSERT THE TIMESHEET ITEM (ADD IT TO THE DB)
		error_log("checking value of lastInsertId:" . $lastInsertId[0]);
		//this isn't right, this needs to also send the project ID and the task_id. Otherwise, timesheet_id is not specific enough.
		$timesheet_object->insertTimesheetItem($timesheet_object->getValueEncoded("person_id"), $lastInsertId[0]);
	}	
			displayTimesheet($timesheet_aggregate, $timesheet_item_aggregate);
}

?>
