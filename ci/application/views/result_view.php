<!DOCTYPE html>
<html lang="en">
<script>
$(document).ready( function() {
    	//this sets up the active toggle checkbox to be on or off and to maintain state after reload.
    	var href = window.location.href;
		var toggleVar = href.substr(href.lastIndexOf('/') + 1);
        if (toggleVar == 0) {
	        	$('#activeToggle').attr('checked','checked');
        } else if (toggleVar == 1) {
	        	$('#activeToggle').attr('unchecked','unchecked');
        } else {
	        toggleVar = 1;
        }
        $("#activeToggle").change(function(){ 
        	//change and reload the URL based on what is in the URL. 	
        	if (toggleVar == 1) {
	        	var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/0";
        	} else if (toggleVar == 0) {
	        	var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/1";
        	}
			window.location.href = url;
    });
});

</script>

<table width=100%>
<tr><td><h1>Detailed Time Report</h1></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td><h4>Timeframe:<?php echo $todate?> - <?php echo $fromdate?></h4></td><td><h4>Client: <?php echo $client_name?></h4></tr>
<tr><td><h4>Total: <?php echo $running_total?> Hours</h4></td><td><h4>Project: <?php echo $project_name?></h4></td></tr>
<tr><td></td><td><h4>Task: <?php echo $task_name?></h4></td></tr>
<tr><td></td><td><h4>Staff: <?php echo $person_name?></h4></td></tr>
<tr><td align=center colspan=2>
<br><br>
<table width=90% border=1px solid;>
<tr bgcolor="lightgray"><td>Show:</td><td>Group by:</td><td></td><td></td><td><input class="check" type="checkbox" name="activeToggle" id="activeToggle">
Active Projects Only</td><td></td></tr>
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
