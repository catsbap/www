<!DOCTYPE html>
<html lang="en">


<table width=100%>
<tr><td><h1>Detailed Time Report</h1></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td><h4>Timeframe:<?php echo $todate?> - <?php echo $fromdate?></h4></td><td><h4>Client: <?php echo $client_name?></h4></tr>
<tr><td><h4>Total: <?php echo $running_total?> Hours</h4></td><td><h4>Project: <?php echo $project_name?></h4></td></tr>
<tr><td></td><td><h4>Task: <?php echo $task_name?></h4></td></tr>
<tr><td></td><td><h4>Staff: <?php echo $person_name?></h4></td></tr>
<tr><td align=center colspan=2>
<table width=90% border=1px solid;>
<br><br>
<tr bgcolor="#7A991A">
        <td>Client</td><td>Project</td><td>Task</td><td>Person</td><td>Department</td><td>Hours</td>
    </tr>
<?php
foreach($results as $row){
?> 
    <tr><td bgcolor="lightgray" colspan=6><?php echo $row->timesheet_date?></td></tr>
    <tr>
        <td><?php echo $row->client_name?></td><td><?php echo $row->project_name?></td><td><?php echo $row->task_name?></td><td><?php echo $row->person_first_name?></td><td><?php echo $row->person_department?></td><td><?php echo $row->timesheet_hours?></td>
    </tr>
<?php   
}
?>
</table></td></tr>
</table>
