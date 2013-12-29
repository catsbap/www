<!DOCTYPE html>
<html lang="en">
<h1 style="align:center;">BUDGET REPORT</h1><br>
<?php //print_r($results) ?>

<table width=100% align=center border=1px solid>
<tr bgcolor="lightgrey";>
        <td>Name</td><td>Budget</td><td>Spent</td><td>Budget Left</td>
    </tr>
<?php foreach($budget as $row){
	if ($row->project_budget_total_fees > 0) {	?> 
    	<tr><td><?php echo $row->project_name?></td><td>$<?php echo $row->budget?>.00</td><td>$<?php echo $row->rate?></td><td>$<?php echo $row->hours_left?>.00 (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } elseif ($row->project_budget_total_hours > 0) {?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } elseif ($row->task_total_budget_hours > 0) { ?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } elseif ($row->task_total_budget_hours > 0) { ?>
	<tr><td><?php echo $row->project_name?></td><td><?php echo $row->budget?> Hours</td><td>$<?php echo $row->rate?></td><td><?php echo $row->hours_left?> Hours (<?php echo $row->budget_percentage?>)</td>
		</tr>
	<?php } ?>
<?php } ?>
</table>

</html>