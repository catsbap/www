	<form method="post" action="timesheet_submitted.php">
	<h1>Pending Approval</h1>
		<table border="0px solid">
							
					<?php 
					if (isset($_GET["timesheet_id"]) && $_GET["timesheet_id"] == $timesheet->getValueEncoded("timesheet_id")) {
						?><input type="hidden" name="action" value="Approve Timesheet"><?php
					} else {
						?><input type="hidden" name="action" value="Approve All Timesheets"><?php
					}
		 
			$timesheets_for_approval = array();
			foreach($timesheets as $timesheet) {
			//print_r($timesheet);				
				list($timesheet_dates) = $timesheet->getTimesheetDatesByTimesheetId($timesheet->getValueEncoded("timesheet_id"));
				?>
				<tr><td style="background-color:grey;" colspan=8><?php echo $timesheet_dates->getValueEncoded("timesheet_start_date"); ?> THROUGH <?php echo $timesheet_dates->getValueEncoded("timesheet_end_date"); ?></td></tr>
				<tr><td colspan=8>
				
				<?php 
				$person = Person::getPersonById($timesheet_dates->getValueEncoded("person_id"));
				?><a href="timesheet_submitted.php?timesheet_id=<?php echo $timesheet->getValueEncoded("timesheet_id");?>"><?php echo $person->getValueEncoded("person_first_name"); echo(" "); echo $person->getValueEncoded("person_last_name");?></a><b><?php echo $timesheet_item->sumTimesheetHours($timesheet->getValueEncoded("timesheet_id"))["sum(timesheet_hours)"]?>Hours</b></td></tr> 
				
		<?php 
				$timesheets_for_approval[] = $timesheet->getValueEncoded("timesheet_id");
				if (isset($_GET["timesheet_id"]) && $_GET["timesheet_id"] == $timesheet->getValueEncoded("timesheet_id")) {
					//echo "<tr><td>show timesheet item. for " . $timesheet->getValueEncoded("timesheet_id") . "</td></tr>";
					
					list($timesheet_items)=$timesheet_item->getSubmittedTimesheetDetail($timesheet->getValueEncoded("timesheet_id"));
					//all of this for the UI. >(
					$projects = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "project_id");
					$tasks = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "task_id");
					$people = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "person_id");

					//echo("HERE" . $timesheet->getValue("timesheet_start_date"));
					//print_r($timesheet);	
						
					foreach($projects as $project) {
						foreach($tasks as $task) {
							foreach($people as $person) {
								$rows = Timesheet_Item::getTimesheetItemForPersonProjectTask($person,$project,$task,$timesheet->getValue("timesheet_start_date"), $timesheet->getValue("timesheet_end_date"));
								$i = 0;
								foreach ($rows as $row) {
									if ($i == 0) {
										$projects = Project::getProjectByProjectId($row->getValue("project_id"));
										$client_id = $projects->getValueEncoded("client_id");
										$client_name = Client::getClientNameById($client_id);
										echo "<tr><td>" . $client_name["client_name"] . " > ";
										$task = Task::getTask($row->getValue("task_id"));
										echo  $task->getValueEncoded("task_name") . "</br>";
										$project = Project::getProjectName($row->getValue("project_id"));
										echo $project["project_name"] . "</td>";
										echo "<td>" . $row->getValue("timesheet_date") . "<br/>";
										echo $row->getValue("timesheet_hours") . "</td>";
									} else {
										echo "<td>" . $row->getValue("timesheet_date") . "<br/>";
										echo $row->getValue("timesheet_hours") . "</td>";
									}
									$i++;
								}
							}
						}		
					}
					//this is just showing the same stuff in a different way
					if (isset($_GET["detail"])) {
						list($timesheet_items)=$timesheet_item->getSubmittedTimesheetDetail($timesheet->getValueEncoded("timesheet_id"));
						//all of this for the UI. >(
						$projects = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "project_id");
						$tasks = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "task_id");
						$people = Timesheet_Item::getDistinctValues($timesheet->getValueEncoded("timesheet_id"), "person_id");
						foreach($projects as $project) {
							foreach($tasks as $task) {
								foreach($people as $person) {
								$rows = Timesheet_Item::getTimesheetItemForPersonProjectTask($person,$project,$task,$timesheet->getValue("timesheet_start_date"), $timesheet->getValue("timesheet_end_date"));
								$i = 0;
								foreach ($rows as $row) {
									echo "<tr><td colspan=8 style='background-color:lightgrey;'>" . $row->getValue("timesheet_date") . " - " . date('D', strtotime($row->getValue("timesheet_date"))) . "</td></tr>";
									$projects = Project::getProjectByProjectId($row->getValue("project_id"));
									$client_id = $projects->getValueEncoded("client_id");
									$client_name = Client::getClientNameById($client_id);
									echo "<tr><td colspan=8>" . $client_name["client_name"];
									$project = Project::getProjectName($row->getValue("project_id"));
									echo " - " . $project["project_name"] . "<br>";
									$task = Task::getTask($row->getValue("task_id"));
									echo  $task->getValueEncoded("task_name");
									echo "<b>" . $row->getValue("timesheet_hours") . "</b></td>";
"</td></tr>";
								}
							}
						}		
					}

						?><tr><td colspan=8>
						<a href="timesheet_submitted.php?timesheet_id=<?php echo $timesheet->getValueEncoded("timesheet_id")?>">Hide Timesheet Details</a><br><?php
					} else {
						?><tr><td colspan=8><a href="timesheet_submitted.php?timesheet_id=<?php echo $timesheet->getValueEncoded("timesheet_id")?>&detail=yes">Show Timesheet Details</a><br><?php
					
					}
						?>
						
					<input type="submit" name="approve_timesheets" value="Approve Timesheet"></td></tr><?php
				}	
			}
					
		//put the array into the post.
		if (isset($_GET["timesheet_id"])) {
					?><input type="hidden" name="timesheet_id" value="<?php echo $_GET["timesheet_id"]?>">		
		<?php }	
		?>	
		
		<input type="hidden" name="timesheets_for_approval" value="<?php echo base64_encode(serialize($timesheets_for_approval))?>">		
		<?php 
		if (!isset($_GET["timesheet_id"])) {
				?><tr><td><input type="submit" name="approve_timesheets" value="Approve All Timesheets"></td></tr>
		<?php } ?>
		</table>
		</form>
		</html>
<?php 
}
