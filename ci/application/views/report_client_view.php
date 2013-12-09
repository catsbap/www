<!DOCTYPE html>
<html lang="en">
<?php
	require_once("/Applications/MAMP/htdocs/time_tracker/common/common.inc.php");
	//probably shouldn't be in the view, but we'll leave it here for now
	//take this out for now until I can figure out what's wrog
	//checklogin();
	include('header.php'); //add header.php to page moved to only be called when page is rendered so it's not sent back when page saved via JS/Ajax
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

<body>
	
	<div id="page-content" class="page-content">
		<header class="page-header">
			<h1>This week:</h1>
			<h3 class="page-title"><?php echo date_format(new DateTime($_GET['fromdate']), "F j, Y");?> to <?php echo date_format(new DateTime($_GET['todate']), "F j, Y");?></h3>
		</header>
	<table width="100%" style="border:1px solid;">
	<tr><td><?php echo $picker ?>
	</td></tr>
	<tr><td><?php echo $client_name[0]->client_name;?>
	<tr><td>
	<b><h3>Hours Tracked</h3></b><br>
	<?php 
	print_r($sum_project_hours);
	?>
	</td><td><td><h5>Billable Hours</h5><h3><?php 
	if (!$sum_project_billable_hours) {
		echo 0;
	} else {
		echo $sum_project_billable_hours[0]->timesheet_hours;
	}
	?>
	<br>
	<h5>Unbillable Hours</h5><h3><?php 
	if (!$sum_project_hours) {
		echo "0";
	} elseif (!$sum_project_billable_hours) {
		echo intval($sum_project_hours) - 0;
	} else {
		echo intval($sum_project_hours - intval($sum_project_billable_hours[0]->timesheet_hours) . ".00");
	}
	?>
	</h3></h3></td><td>
	<h5>Billable Amount</h5><h3>
	<?php 
	//figure this out on Monday
	$billable_amount = "0";
	echo "$ " . $billable_amount . ".00"?></h3></td></td></tr>

	
	</td></tr>
	<tr><td colspan="4">	<div id="menucss"><?php echo $menu ?></div>
</td></tr>
	<tr><td><h5>Name</h5></td><td><h5>Hours</h5></td><td><h5>Billable Hours</h5></td><td><h5>Billable Amount</h5></td></tr>
	<?php 
	$i = 0;
	foreach ($project_url as $key=>$value) {
		//print_r($project_url);
		foreach ($value as $val) {
			if ($val || $val == "0.00") {
				echo "<td>$val</td>";
				if ($i%4 == 2) {
					echo "</tr><tr>";
				}
			}
			$i++;
		}
	}
	echo "<BR><BR>";
	//error_log(print_r($client_url,true));
?>
	</table>

	</table>
	</div>
	<footer id="site-footer" class="site-footer">
	
	</footer>

</body>
</html>