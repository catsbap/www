<!DOCTYPE html>
<html lang="en">
<script>
$(document).ready( function() {
    	//this sets up the active toggle checkbox to be on or off and to maintain state after reload.
    	var href = window.location.href;
		var toggleVar = href.split("/")[10];
        //alert(toggleVar);
        if (toggleVar == 0) {
	        	$('#activeToggle').attr('checked','checked');
        } else if (toggleVar == 1) {
	        	$('#activeToggle').attr('unchecked','unchecked');
        } else {
	        toggleVar = 1;
        }
        
        var showVar = href.split("/")[11];
        //change the drop-down to the right value.
        //alert(showVar);
        if (showVar == "all_hours") {
	        	$('#show').val('all_hours');
        } else if (showVar == "billable_hours") {
	        	$('#show').val('billable_hours');
		} else if (showVar == "non_billable_hours") {
	        	$('#show').val('non_billable_hours');
		} 
        
        $('#show').change( function() {
        	showVar = $('#show').val();
        	if (showVar == "all_hours") {
        		var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/all_hours";
			} else if (showVar == "billable_hours") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/billable_hours";
			} else if (showVar == "non_billable_hours") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/non_billable_hours";
			}
			window.location.href = url;
        });
        
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
<tr bgcolor="lightgray"><td>Show: <?php $options = array('all_hours' => 'All Hours', 'billable_hours' => 'Billable Hours', 'non_billable_hours' => 'Non-billable Hours');
echo form_dropdown('show', $options, 'show=' . $this->input->get('show'), 'id=show');
?></td><td>Group By: <?php $options = array('date' => 'Date', 'client' => 'Client', 'project' => 'Project', 'task' => 'Task', 'staff' => 'Staff', 'department' => 'Department');
echo form_dropdown('group_by', $options, 'show=' . $this->input->get('show'), 'id=show');?></td><td><td></td><td></td><td><input class="check" type="checkbox" name="activeToggle" id="activeToggle">
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
