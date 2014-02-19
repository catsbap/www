<?php
	//require_once("../common/common.inc.php");
	//require_once("../classes/Task.class.php");
	//require_once("../common/errorMessages.php");
		//remove auth
		//if(!isUserLoggedIn()){
		//redirect if user is not logged in.
		//$_SESSION["redirect"] = $_SERVER["PHP_SELF"];
		//header( 'Location: http://localhost:8888/time_tracker/usercake/login.php' ) ;
	//}
	
	//OVERALL CONTROL
	//I need this code to be first so I can redirect the page. We may need to do this for others
	//in this page, display is integrated with the add feature (no beeg)
		//checkLogin();

		
	/*$processType = "A";
	if (isset($_GET["task_id"]) && $_GET["task_id"] == "") {
		$processType = "A";
	} elseif (isset($_GET["task_id"])) {
		$processType = "E";
	}
	if (isset($_POST["func"])) {
		if ($_POST["func"] == "processTask") {
			if (isset($_POST["proc_type"])) {
				$processType = $_POST["proc_type"];
				echo processTask($processType);
			}
		}
	} else {
		if (isset($_POST["action"])) {
			$processType = $_POST["action"];
			processTask($processType);
		} else {	
			//displayTaskInsertForm(array(), array(), new Task(array()), $processType);
		}
	}	*/	

//function displayTaskInsertForm($errorMessages, $missingFields, $task, $processType) {
//	include('header.php'); //add header.php to page
?>

<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">All Tasks</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<li class="page-controls-item link-btn"><a id="add-task-btn" class="save-link" href="tasks.php">+ Add Task</a></li>
				<li class="page-controls-item"><a class="view-archive-link" href="task_archives.php">View Task Archives</a></li>
			</ul>
		</nav>
	</header>
	<?php 
	//this is the add task UI (IT IS NOT SEPARATE IN THIS MODULE!!!)?>
	<!-- <a class="client-info-contact-link" href="tasks.php?task_id=" title="View contact details">Add Task</a> -->

	<div id="add-task-modal" class="entity-detail" title="Add Task">
		<form id="task-input-form" action="tasks.php" method="post" enctype="multipart/form-data">
			<input id="proc-type" type="hidden" name="action" value="<?php echo $processType ?>"/>
			<fieldset class="entity-details-entry  modal">
				<header class="entity-details-header task">
					<h1 class="entity-details-title">Enter task details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<section id="project-info" class="entity-detail">
					<ul class="details-list entity-details-list task">
						<li class="entity-details-item name task">
							<label for="task_name" class="entity-details-label">Task Name:</label>
							<?php 
								//the user wants to add this task, so get the task name off the object that was sent with the post.
								//if ($processType == 'A') {
									$taskName = $tasks[0]->task_name;
									$thisTask = $tasks[0];
								//}
							?>
							<input id="task-name" name="task_name" class="task-name-input" type="text" tabindex="1" value="<?php echo $taskName ?>" />
						</li>
						<li class="entity-details-item hourly-rate task">
							<label for="task_hourly_rate" class="entity-details-label">Hourly Rate:</label>
							<input id="task-hourly-rate" name="task_hourly_rate" class="task-rate-input" type="text" tabindex="2" value="<?php echo $thisTask->task_hourly_rate?>" />
						</li>
						<li class="entity-details-item billable task">
							<label for="task_bill_by_default" class="entity-details-label">Billable By Default:</label>
							<input id="task-billable" name="task_bill_by_default" class="task-billable-input" type="checkbox" tabindex="3" value="1" <?php //setChecked($thisTask, "task_bill_by_default", 1) ?>/>
						</li>
						<li class="entity-details-item common task">
							<?php //this is here to expose when we get there.?>
							<label for="task_common" class="entity-details-label">Task common to all future projects:</label>
							<input id="task-common" name="task_common" class="task-common-input" type="checkbox" tabindex="5" value="1" />
						</li>
						<li class="entity-details-item archived task">
							<label for="task_archived" class="entity-details-label">Archive Task?</label>
							<input id="task-archived" name="task_archived" class="task-archived-input" type="checkbox" tabindex="6" value="1" <?php //setChecked($thisTask, "task_common", 1) ?>/>
						</li>
					</ul>
				</section>
			</fieldset>
		</form>
	</div>
		
	<?php
	//this is the display of all tasks.
	//list($tasks) = Task::getTasks(0);
	?>
	<table id="tasks-list" class="entity-table projects tablesorter">
		<thead>
			<tr>
				<th class="filter-false">Edit</th>
				<th data-placeholder="Try B*{space} or alex|br*|c" class="filter-match">Task</th>
				<th data-placeholder="Try >=33">Rate</th><!-- add class="filter-false" to disable the filter in this column -->
				<th data-placeholder="Try Y">Billable</th>
				<th data-placeholder="Try N">Common</th>
				<!-- <th data-placeholder="" class="filter-false"><input id="select-project" name="select-project" type="checkbox" value="all" title="Select project" /><th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach($tasks as $task) {
			$yesno_toggle = array( "No", "Yes"); ?>
				<tr data-options='{
					"task_id": "<?php echo $task->task_id; ?>",
					"task_name": "<?php echo $task->task_name ?>",
					"task_hourly_rate": "<?php echo $task->task_hourly_rate ?>",
					"task_bill_by_default": "<?php echo $task->task_bill_by_default ?>",
					"task_common": "<?php echo $task->task_common ?>",
					"task_archived": "<?php echo $task->task_archived ?>"
				}'>
					<td><a class="task-link" href="#" title="View task details">Edit</a></td>
					<td><?php echo ($task->task_name) ?></td>
					<td>$<?php echo $task->task_hourly_rate ?></td>
					<td><?php echo $yesno_toggle[$task->task_bill_by_default]; ?></td>
					<td><?php echo $yesno_toggle[$task->task_common]; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	
</div>
	
<footer id="site-footer" class="site-footer">

</footer>
<script src="<?php echo "$this->base/js/task-controls.js" ?>"></script>
</body>
</html>
<?php //} ?>

<?php
/*function processTask($processType) {
	//echo "processtype is " . $processType;

 	//these are the required task fields in this form
	$requiredFields = array("task_name");
	$missingFields = array();
	$errorMessages = array();
	
	//create the task object ($task)
	$task = new Task( array(
		//CHECK REG SUBS!!
		"task_id"=>isset($_POST["task_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_id"]) : "",
		"task_name" => isset($_POST["task_name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_name"]) : "",
		"task_hourly_rate" => isset($_POST["task_hourly_rate"]) ? preg_replace("/[^ \$\.\-\_a-zA-Z0-9]/", "", $_POST["task_hourly_rate"]) : "",
		"task_bill_by_default" => isset($_POST["task_bill_by_default"]) ? preg_replace("/[^ \_0-9]/", "", $_POST["task_bill_by_default"]) : "",
		"task_common" => isset($_POST["task_common"])? preg_replace("/[^ \_0-9]/", "", $_POST["task_common"]) : "",
		"task_archived"=>isset($_POST["task_archived"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_archived"]) : "",
	));
	//print_r($task);
	
	//error messages and validation script
	foreach($requiredFields as $requiredField) {
		if ( !$task->getValue($requiredField)) {
			$missingFields[] = $requiredField;
		}	
	}
	
	
	if ($missingFields) {
		$i = 0;
		$errorType = "required";
		foreach ($missingFields as $missingField) {
			$errorMessages[] = "<li>" . getErrorMessage(1,$missingField, $errorType) . "</li>";
			$i++;
		}
	} 
		
	if ($errorMessages) {
		displayTaskInsertForm($errorMessages, $missingFields, $task, $processType);
	} else {
		//steal this code for people. 
		$task_name=$task->getValue("task_name");
		$task_id = $task->getTaskId($task_name);
		if ($task_id && $processType == "A") {
			echo "Task " . $task->getValue("task_name") . " is already in the database. Please try again.";
		} else {
			error_log("here is the post");
			error_log(print_r($_POST,true));
			error_log("here is the task");
			error_log(print_r($task,true));
			try	{
				if ($processType == "A") {
					error_log("You want to ADD this task.");
					$task->insertTask();
					//echo("Task " . $task->getValue('task_name') . " has been successfully added to the database.");
					return json_encode($task->getTaskId($task_name));
				} elseif ($processType == "E") {
					error_log("YOU WANT TO UPDATE THIS TASK: " . $_POST["task_id"]);
					$task->updateTask($_POST["task_id"]);
					//echo ("Task " . $task->getValue('task_name') . " has been successfully updated to the database.");
					return "Task '" . $task->getValue('task_name') . "' successfully updated.";
				}
			} catch (Error $e) {
				echo "Something went terribly wrong.";
			}
			
		}
		//headers already sent, call the page back with blank attributes.
		//displayTaskInsertForm(array(), array(), $task, $processType);
	}
} 
*/
?>