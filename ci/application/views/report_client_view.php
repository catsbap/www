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
//this gets the task ID out of the drop down menu and puts the value in the URL.
//this should be in a helper??


$(document).ready(function() { 
    			var value = window.location.href.match(/[?&]type=([^&#]+)/) || [];
				
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
			<h3 class="page-title"><?php echo date_format(new DateTime($this->input->get('fromdate')), "F j, Y");?> to <?php echo date_format(new DateTime($this->input->get('todate')), "F j, Y");?></h3>
		</header>
	<table width="100%" border=1px solid>
	<tr><td><?php echo $this->data['picker'] ?></td></tr>
	<tr><td><?php echo $client_name[0]->client_name;?></td></tr>
		<tr><td><?php echo $this->data['breadcrumb'] ?></td></tr>
	<tr><td><form><?php $options = array('type=week' => 'Week', 'type=semimonthly' => 'Semimonthly',
'type=month' => 'Month', 'type=year' => 'Year');
echo form_dropdown('timeframe', $options, 'type=' . $this->input->get('type'), 'id=timeframe');
?></td></tr></form>
	<tr><td>
	<b><h3>Hours Tracked</h3></b><br>
	<?php 
	print_r($aggregate_total_time);
	?>
	</td><td><td><h5>Billable Hours</h5><h3><?php 
	print_r($aggregate_billable_time);
	?>
	<br>
	<h5>Unbillable Hours</h5><h3><?php 
		echo $aggregate_total_time-$aggregate_billable_time;
	?>
	</h3></h3></td><td>
	<h5>Billable Amount</h5><h3>
	<?php 
	print_r($aggregate_billable_amount) . ".00"?></h3></td></td></tr>

	
	</td></tr>
	<tr><td colspan="4">	<div id="menucss"><?php echo $this->data['menu'] ?></div>
</td></tr>
	<tr><td><h5>Name</h5></td><td><h5>Hours</h5></td><td><h5>Billable Hours</h5></td><td><h5>Billable Amount</h5></td></tr>
	<?php 
	$i = 0;
	foreach ($project_url as $key=>$value) {
		//print_r($project_url);
		foreach ($value as $val) {
			if ($val || $val == "0.00") {
				echo "<td>$val</td>";
				if ($i%4 == 3) {
					echo "</tr><tr>";
				}
			}
			$i++;
		}
	}
	echo "<BR><BR>";
?>
<tr><td></td></tr>
	</table>

	</table>
	</div>
	<footer id="site-footer" class="site-footer">
	
	</footer>

</body>
</html>