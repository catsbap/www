<!DOCTYPE html>
<html lang="en">
<?php
	//require_once("/Applications/MAMP/htdocs/time_tracker/common/common.inc.php");
	//probably shouldn't be in the view, but we'll leave it here for now
	//take this out for now until I can figure out what's wrog
	//checklogin();
	//include('header.php'); //add header.php to page moved to only be called when page is rendered so it's not sent back when page saved via JS/Ajax
?>
<h1 style="align:center;">BUDGET REPORT</h1><br>
<?php //print_r($results) ?>

<table width=100% align=center border=1px solid>
<tr bgcolor="lightgrey" ><td colspan=4>Budgeted by Project Hourly Rate</td></tr>
<tr bgcolor="lightgrey";>
        <td>Name</td><td>Budget</td><td>Spent</td><td>Budget Left</td>
    </tr>
<?php foreach($budget_hours as $row){
	//if ($row->project_budget_total_fees > 0) {	?> 
    	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td><?php echo $row->rate?> Hours</td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php /*} elseif ($row->project_budget_total_hours > 0) {?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } elseif ($row->task_total_budget_hours > 0) { ?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } elseif ($row->task_total_budget_hours > 0) { ?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } */?>
<?php } 

?>
<tr bgcolor="lightgrey" ><td colspan=4>Budgeted by Project Fees</td></tr>
<tr bgcolor="lightgrey";>
        <td>Name</td><td>Budget</td><td>Spent</td><td>Budget Left</td>
    </tr>

<?php foreach($budget_fees as $row){
	//if ($row->project_budget_total_fees > 0) {	?> 
    	<tr><td><?php echo $row->project_name?></td><td>$<?php echo $row->budget?>.00</td><td>$<?php echo $row->rate?>.00</td><td>$<?php echo $row->hours_left?>.00 (<?php echo $row->budget_percentage?>)</td>
		</tr>
<?php } ?>
<tr bgcolor="lightgrey" ><td colspan=4>Budgeted by Task Hours</td></tr>
<tr bgcolor="lightgrey";>
        <td>Name</td><td>Budget</td><td>Spent</td><td>Budget Left</td>
    </tr>

<?php foreach($budget_tasks as $row){
	//if ($row->project_budget_total_fees > 0) {	?> 
    	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours </td><td><?php echo $row->rate?> Hours</td><td><?php echo $row->hours_left?> (<?php echo $row->budget_percentage?>)</td>
		</tr>
<?php } ?>
<tr><td><b><?php //echo $row->task_name ?></b></td></tr>
</table>

</html>