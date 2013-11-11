<?php
	require_once("../common/common.inc.php");
	require_once("../classes/Task.class.php");
	require_once("../common/errorMessages.php");
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

		
		$processType = "A";
		if (isset($_GET["task_id"]) && $_GET["task_id"] == "") {
			$processType = "A";
		} elseif (isset($_GET["task_id"])) {
			$processType = "E";
		}
				
		if (isset($_POST["action"])) {
			$processType = $_POST["action"];
			processTask($processType);
		} else {	
			displayTaskInsertForm(array(), array(), new Task(array()), $processType);
		} 

function displayTaskInsertForm($errorMessages, $missingFields, $task, $processType) {
	include('header.php'); //add header.php to page

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

	<div id="add-task-modal" class="entity-detail" title="Add/Edit Task">
		<form action="tasks.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo $processType ?>"/>
			<fieldset class="entity-details-entry">
				<header class="entity-details-header project">
					<h1 class="entity-details-title">Enter task details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<section id="project-info" class="entity-detail">
					<ul class="details-list entity-details-list task">
						<li class="entity-details-item name task">
							<label for="task_name" <?php validateField("task_name", $missingFields)?> class="entity-details-label">Task Name:</label>
							<?php 
							$tasker = "";
							//the user wants to add this task, so get the task name off the object that was sent with the post.
							if ($processType == 'A') {
								$taskName = $task->getValueEncoded("task_name");
								$thisTask = $task;
							} else {
							//the user came in from edit, so get the task ID off of the get.
								if (isset($_GET["task_id"])) {
									$tasker = $_GET["task_id"];
								//this is just a safety thing.
								} elseif (isset($_POST["task_id"])) {
									$tasker = $_POST["task_id"];
								}
								//dude, tasker is the task_id, but this is at least funny, reminds me of Disney.
								$thisTask = $task->getTaskById($tasker);
								//get the task name.
								$taskName = $thisTask->getValue("task_name");
							}
							//so, in the end, we have a list of tasks with their names and their IDs. All of this so we can put it back in the UI.
							?>
							<input id="task-name" name="task_name" class="task-name-input" type="text" tabindex="1" value="<?php echo $taskName ?>" />
							<input type="hidden" name="task_id" value="<?php echo $tasker; ?>">
						</li>
						<li class="entity-details-item hourly-rate task">
							<label for="task_hourly_rate" <?php validateField("task_hourly_rate", $missingFields)?> class="entity-details-label">Hourly Rate:</label>
							<input id="task-hourly-rate" name="task_hourly_rate" class="task-rate-input" type="text" tabindex="2" value="<?php echo $thisTask->getValueEncoded("task_hourly_rate")?>" />
						</li>
						<li class="entity-details-item billable task">
							<label for="task_bill_by_default" class="entity-details-label">Billable By Default:</label>
							<input id="task-billable" name="task_bill_by_default" class="task-billable-input" type="checkbox" tabindex="3" value="1" <?php setChecked($thisTask, "task_bill_by_default", 1) ?>/>
						</li>
						<li class="entity-details-item archived task">
							<?php //only show the archive button if we are editing.
							if ($processType == "E") { ?>
								<button id="client-add-btn" name="task-archived" class="client-add-btn" value="1" tabindex="4"/>Archive Task</button>
							<?php } ?>
						</li>
						<li class="entity-details-item common task">
							<?php //this is here to expose when we get there.?>
							<label for="task_common" class="entity-details-label">Task common to all future projects:</label>
							<input id="task-common" name="task_common" class="task-common-input" type="checkbox" tabindex="5" value="1" <?php setChecked($thisTask, "task_common", 1) ?>/>
						</li>
						<li class="entity-details-item">
							<label for="save_task" class="entity-details-label">All done?</label>
							<!-- <a class="" href="#">+ Save Task</a> -->
							<input id="task-save-btn" name="task_save_btn" class="save-btn" type="submit" value="+ Save Task" tabindex="12" /> or <a class="" href="projects.php" tabindex="13">Cancel</a>
							<!-- <input id="client-add-btn" name="task-add-btn" class="client-add-btn" type="submit" value="+ Save Task" tabindex="11"/> -->
							<?php
	                        //this is here to expose when we get here.
	                        //<input id="client-add-btn" name="task-add-to-all-btn" class="client-add-btn" type="submit" value="+ Add Task To All Current Projects" tabindex="11"/> 
	                        ?>
						</li>
					</ul>
				</section>
			</fieldset>
		</form>
	</div>

		
		<?php
		//this is the display of all tasks.
			//1. Get out the task types, this is ugly but it works. Could have called a bunch of functions to get this right but NAAAAAH!!
			list($tasks) = Task::getTasks(0); 
				//common tasks here 
				?>
				<li style="background-color:lightblue;" class="client-info-contact">Tasks common to all projects</li>
				<!--billable tasks-->
				<li style="background-color:lightgray;" class="client-info-contact">Task billable by default</li>
			<?php foreach($tasks as $task) {
				if ($task->getValue("task_common")) {
						if ($task->getValue("task_bill_by_default")) {
							$task_id = Task::getTaskId($task->getValue("task_name"));
						?>
							<section class="content">
							<ul id="client-list" class="client-list">
							<li class="client-list-item l-col-33">
							<ul class="client-info-list">
							<li class="client-info-contact"><a class="client-info-contact-link" href="tasks.php?task_id=<?php echo $task_id[0]; ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($task->getValue("task_name")); ?></li>
							<br/><hr/>
							</ul>		
							</li>
							</ul>
							</section>
						<?php }
				}
			}?>
				<!--non billable tasks-->
				<li style="background-color:lightgray;" class="client-info-contact">Non-billable by default</li>
			<?php foreach($tasks as $task) {
				if ($task->getValue("task_common")) {
						if (!$task->getValue("task_bill_by_default")) {
							$task_id = Task::getTaskId($task->getValue("task_name"));
						?>
							<section class="content">
							<ul id="client-list" class="client-list">
							<li class="client-list-item l-col-33">
							<ul class="client-info-list">
							<li class="client-info-contact"><a class="client-info-contact-link" href="tasks.php?task_id=<?php echo $task_id[0]; ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($task->getValue("task_name")); ?></li>
							<br/><hr/>
							</ul>		
							</li>
							</ul>
							</section>
					<?php }
				}
			}
				//other tasks here
				?>
				<li style="background-color:lightblue;" class="client-info-contact">Other tasks</li>
				<!--billable tasks-->
				<li style="background-color:lightgray;" class="client-info-contact">Task billable by default</li>
			<?php foreach($tasks as $task) {
				if (!$task->getValue("task_common")) {
						if ($task->getValue("task_bill_by_default")) {
							$task_id = Task::getTaskId($task->getValue("task_name"));
						?>
							<section class="content">
							<ul id="client-list" class="client-list">
							<li class="client-list-item l-col-33">
							<ul class="client-info-list">
							<li class="client-info-contact"><a class="client-info-contact-link" href="tasks.php?task_id=<?php echo $task_id[0]; ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($task->getValue("task_name")); ?></li>
							<br/><hr/>
							</ul>		
							</li>
							</ul>
							</section>
						<?php }
				}
			}?>
				<!--non billable tasks-->
				<li style="background-color:lightgray;" class="client-info-contact">Non-billable by default</li>
			<?php foreach($tasks as $task) {
				if (!$task->getValue("task_common")) {
						if (!$task->getValue("task_bill_by_default")) {
						$task_id = Task::getTaskId($task->getValue("task_name"));
						?>
							<section class="content">
							<ul id="client-list" class="client-list">
							<li class="client-list-item l-col-33">
							<ul class="client-info-list">
							<li class="client-info-contact"><a class="client-info-contact-link" href="tasks.php?task_id=<?php echo $task_id[0]; ?>" title="View contact details"><button>Edit</button></a>  <?php echo ($task->getValue("task_name")); ?></li>
							<br/><hr/>
							</ul>		
							</li>
							</ul>
							</section>
						<?php }
				}
			}

}

function processTask($processType) {
echo "processtype is " . $processType;

 	//these are the required task fields in this form
	$requiredFields = array("task_name");
	$missingFields = array();
	$errorMessages = array();
	
	//create the task object ($task)
	$task = new Task( array(
		//CHECK REG SUBS!!
		"task_id"=>isset($_POST["task_id"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task_id"]) : "",
		"task_name" => isset($_POST["task-name"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task-name"]) : "",
		"task_hourly_rate" => isset($_POST["task-hourly-rate"]) ? preg_replace("/[^ \$\.\-\_a-zA-Z0-9]/", "", $_POST["task-hourly-rate"]) : "",
		"task_bill_by_default" => isset($_POST["task-bill-by-default"]) ? preg_replace("/[^ \_0-9]/", "", $_POST["task-bill-by-default"]) : "",
		"task_common" => isset($_POST["task-common"])? preg_replace("/[^ \_0-9]/", "", $_POST["task-common"]) : "",
		"task_archived"=>isset($_POST["task-archived"]) ? preg_replace("/[^ \-\_a-zA-Z0-9]/", "", $_POST["task-archived"]) : "",
	));
	
	
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
					echo("Task " . $task->getValue('task_name') . " has been successfully added to the database.");
				} elseif ($processType == "E") {
					error_log("YOU WANT TO UPDATE THIS TASK: " . $_POST["task_id"]);
					$task->updateTask($_POST["task_id"]);
					echo("Task " . $task->getValue('task_name') . " has been successfully updated to the database.");
				}
			} catch (Error $e) {
				echo "Something went terribly wrong.";
			}
			
		}
		//headers already sent, call the page back with blank attributes.
		displayTaskInsertForm(array(), array(), $task, $processType);
	}
} 

?>
</div>

<footer id="site-footer" class="site-footer">

</footer>
<script src="task-controls.js"></script>
</body>
</html>
