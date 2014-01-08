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
        if (showVar == "all_hours") {
	        	$('#show').val('all_hours');
        } else if (showVar == "billable_hours") {
	        	$('#show').val('billable_hours');
		} else if (showVar == "non_billable_hours") {
	        	$('#show').val('non_billable_hours');
		}
		
		var group_byVar = href.split("/")[12];
        if (group_byVar == "client_name") {
	        	$('#group_by').val("client_name");
        } else if (group_byVar == "project_name") {
	        	$('#group_by').val("project_name");
		} else if (group_byVar == "task_name") {
	        	$('#group_by').val("task_name");
		} else if (group_byVar == "person_first_name") {
				$('#group_by').val("person_first_name") 
		} else if (group_byVar == "person_department") {
				$('#group_by').val("person_department")
		} else {
				$('#group_by').val("timesheet_date")
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


		$('#group_by').change( function() {
        	group_byVar = $('#group_by').val();
        	if (group_byVar == "client_name") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/client_name";
			} else if (group_byVar == "project_name") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/project_name";
			} else if (group_byVar == "task_name") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/task_name";
			} else if (group_byVar == "person_first_name") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/person_first_name";
			} else if (group_byVar == "person_department") {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/person_department";
			} else {
				var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/<?php echo $this->uri->segment(3)?>/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>/<?php echo $this->uri->segment(6)?>/timesheet_date";
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
?></td><td>Group By: <?php $options = array('timesheet_date' => 'Date', 'client_name' => 'Client', 'project_name' => 'Project', 'task_name' => 'Task', 'person_first_name' => 'Staff', 'person_department' => 'Department');
echo form_dropdown('group_by', $options, 'group_by=' . $this->input->get('group_by'), 'id=group_by');?></td><td><td></td><td></td><td><input class="check" type="checkbox" name="activeToggle" id="activeToggle">
Active Projects Only</td><td></td></tr>
<?php
//exclude these variables from the output, including the value returned in the url.

$tempval[] = array();
foreach($results as $row){
	    //build up all of the group by variables in an array so we can key off of them to display other variables.
	    foreach ($row as $key=>$val) {
	        //make the $key dynamic based on the user input.
	        if (!array_search($val, $tempval) && $key == $group_by) {
		        $tempval[] = $val;
	        }
	     }	 
}

	     sort($tempval);
	    
	    //$test = array_unique($tempval);    


?>
<tr bgcolor="#7A991A">
    <?php
    foreach ($header_vars as $key=>$value) {
	    ?><td><?php echo $value ?></td><?php
    }
    ?></tr>
<?php 	
//debug the array here.
//print_r($tempval);
//do we need to clean up the array to get rid of extraneous var types?
foreach ($tempval as $key=>$value) {
	if (is_string($value)) {
		//if ($value == "") $value = "N/A";
		echo "<tr><td bgcolor=lightgray colspan=6>" . $value . "</td></tr>";
	}
	foreach($results as $resultkey=>$resultvalue){
		if ($value == $resultvalue->$group_by) {
			foreach($resultvalue as $finalkey=>$finalval) {
				//we'll have to make timesheet_date dynamic here, I am not sure why it's being included in the output
				//since it is in the exclude array.
				if (!array_search($finalkey, $exclude_vars) && $finalkey != $group_by) {
					?><td><?php echo $finalval; ?></td><?php
				}
			}
			echo "<tr>";
		}
	}
} 

?>
</table></td></tr>
</table>
