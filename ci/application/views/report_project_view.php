<!DOCTYPE html>
<html lang="en">
<?php
	//require_once("/Applications/MAMP/htdocs/time_tracker/common/common.inc.php");
	//probably shouldn't be in the view, but we'll leave it here for now
	//take this out for now until I can figure out what's wrog
	//checklogin();
	//include('header.php'); //add header.php to page moved to only be called when page is rendered so it's not sent back when page saved via JS/Ajax
?>
<style>
#menucss
ul{
list-style-type:none;
margin:0;
padding:0;
overflow:hidden;
}
#menucss
li
{
float:left;
}
#menucss
a:link
{
display:block;
width:120px;
font-weight:bold;
color:#FFFFFF;
background-color:#98bf21;
text-align:center;
padding:4px;
text-decoration:none;
text-transform:uppercase;
}
#menucss 
a:hover
{
background-color:#7A991A;
}
#menucss
a:active
{
background-color: aqua;
}

</style>
<?php echo $library_src;?>
<?php //echo $script_foot;?>
<script>
//call a function here to get the project_id.
//jquery to open the people under the project link. needs to be more specific to the project,
//right now it is opening all people.

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$(document).ready(function() { 
    
    project_id = getParameterByName('project_id')  
    
    $(".button_" + project_id).click(function(){	
                $( "." + project_id ).toggle( "slow" );
                //alert(getParameterByName('person_id'));
    });
    
    //inicial settings
    $("." + project_id).hide();

	//this is for the timeframe dropdown.
    var value = window.location.href.match(/[?&]type=([^&#]+)/) || [];
    
    //this is to show the lifespan report, which is only on the project page.			
    $(".lifespan").click(function() {
		var val = "type=lifespan";
        var val = val.split('=')[1];
        changeUrlValue('type', val);    
    });
	
	$("#timeframe").change(function(){	
                /*///////////////////////////////*/
                //if we go with things this way, we will have
                //to alter the URL based on the timeframe the 
                //first time the user comes into this page.
                //right now, the dates aren't updated, but the 
                //type is. The date is not updated in the URL
                //until the user selects previous and next.
                var val = $('#timeframe').val(); //get the value from the timeframe drop down
                var val = val.split('=')[1];
                changeUrlValue('type', val);
		});
		
		function getVarFromURL(varName){
            var url = window.location.href;
            url = url.substring(url.indexOf('?'));
            var urlLowerCase = url.toLowerCase();
            varName = varName.toLowerCase();
            if (urlLowerCase.indexOf(varName + "=") != -1) {
                var value = url.substring(urlLowerCase.indexOf(varName) + varName.length + 1);
                if (value.indexOf('&') != -1) {
                    value = value.substring(0, value.indexOf('&'));
                }
                return value;
            }
            else {
                return null;
            }
		}
		
		function changeUrlValue(name, value){
			var pag = window.location.pathname;
			var url = window.location.search;	
			var tmpRegex = new RegExp("(" + name + "=)[a-z]+", 'ig');
			window.location.href = url.replace(tmpRegex, '$1'+value);
		}
});
	
</script> 
<body>
	<div id="page-content" class="page-content">
		<header class="page-header">
			<h1>This week:</h1>
			<h3 class="page-title"><?php echo date_format(new DateTime($_GET['fromdate']), "F j, Y");?> to <?php echo date_format(new DateTime($_GET['todate']), "F j, Y");?></h3>
		</header>
	<table width="100%" style="border:1px solid;">
	<tr><td><?php echo $this->data['picker'];?>
	<tr><td><?php $this->data['project_name'][0];
	?>
	<tr><td><?php echo $this->data['breadcrumb'] ?></td></tr>
	<tr><td><form><?php $options = array('type=week' => 'Week', 'type=semimonthly' => 'Semimonthly',
'type=month' => 'Month', 'type=year' => 'Year');
echo form_dropdown('timeframe', $options, 'type=' . $this->input->get('type'), 'id=timeframe');
?></td></tr></form>
	<tr><td>
	<div id="message"></div>
	<b>Hours Tracked</b><br>
	<?php 
	print_r($aggregate_total_time);
	?>
	</td><td><b>Billable Hours</b><br>
	<?php print_r($aggregate_billable_time);
	?>
	<h5>Unbillable Hours</h5><h3><?php 
		echo $aggregate_total_time-$aggregate_billable_time;
	?>
	</h3></td><td>
	<h5>Billable Amount</h5><h3>
	<?php 
	print_r($aggregate_billable_amount) . ".00";
	echo ".00";?></h3></td></td></tr>

	
	</td></tr>
	<tr><td><a href="#" class="lifespan";>Project Lifespan Report</a></td></tr>

	<tr><td colspan="4">	<div id="menucss"><?php echo $this->data['menu'] ?></div>
</td></tr>
	<tr><td><h5>Name</h5></td><td><h5>Hours</h5></td><td><h5>Billable Hours</h5></td><td><h5>Billable Amount</h5></td></tr>
	<?php 
	$i = 0;
	foreach ($this->data['task_url'] as $key=>$value) {
		//print_r($task_url);
		foreach ($value as $key=>$val) {
			if ($key == 'task_id') {
				echo "<tr>";
				$this->timetrackerurls->display_person($value['task_id'], $project_id);
				echo "</tr>";
			} else {
				if ($val || $val == "0.00") {
					echo "<td>$val</td>";
				}
				$i++;
			}
		}
	}
						
	//error_log(print_r($client_url,true));
?>

	</table>
	</div>
	<footer id="site-footer" class="site-footer">
	
	</footer>

</body>
</html>