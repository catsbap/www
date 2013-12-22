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
<?php echo $script_foot;?>
<script>
//this gets the task ID out of the drop down menu and puts the value in the URL.
//this should be in a helper??


$(document).ready(function() { 
    			var value = window.location.href.match(/[?&]type=([^&#]+)/) || [];
				//alert(value[0]);
				//$('#timeframe').val(value[0]);
    
    $("#timeframe").change(function(){	
                
                var val = $('#timeframe').val(); //get the value from the timeframe drop down
                var pag = window.location.pathname;
				var url = window.location.search;
				url = url.replace("?", "").split("&"); // Clean up the URL, and create an array with each query parameter

				var n = 0;
				for (var count = 0; count < url.length; count++) {
					if (!url[count].indexOf("type")) { // Figure out if if/where the Currency is set in the array, then break out
						n = count;
						break;
					}
				}

				if (n !=0) {
					url.splice(n,1); // remove the type from the array
				}

				var len = url.length;
				var newUrl = url.join("&"); // Restringify the array

				if (len > 0) { // Check whether or not the timeframe is the only parameter, then build new URL with ? or &
					newUrl = pag + "?" + newUrl + "&" + val;
				} else {
					newUrl = pag + newUrl + "?" + val;
				}

				window.location.href = newUrl; //add the new value to the URL
		});
    
});
</script> 


<body>
	
	<div id="page-content" class="page-content">
	
		<header class="page-header">
			<?php echo $picker?><?php echo $from_date;?> to <?php echo $to_date;?>
		</header>
	<table width="100%" border=1px solid;>
	<tr><td><form><?php $options = array('type=week' => 'Week', 'type=semimonthly' => 'Semimonthly',
'type=month' => 'Month', 'type=quarter' => 'Quarter', 'type=year' => 'Year', 'type=custom' => 'Custom',);
echo form_dropdown('timeframe', $options, 'type=' . $this->input->get('type'), 'id=timeframe');
?></td></tr></form>
	<tr><td width=25%><h5>Hours Tracked</h5><h3><?php 
	print_r($total_time);
	?></h3>
	</td><td><td><h5>Billable Hours</h5><h3><?php 
	print_r($billable_time);
	?>
	<br>
	<h5>Unbillable Hours</h5><h3><?php 
	if (!$total_time) {
		echo "0";
	} elseif (!$billable_time) {
		print_r($total_time);
	} else {
		echo intval($total_time - $billable_time);
	}
	?>
	</h3></h3></td><td>
	<h5>Billable Amount</h5><h3>
	<?php echo "$ " . $billable_rate . ".00"?></h3></td></td></tr>
