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
<script>$(document).ready(function() { 
    $("#button").click(function(){
        //id = $(this).val();
        //if ( id == 1 )    { 
                //$("#").show();
                $( "#198" ).fadeIn( "slow" );
                
                function callback() {
					setTimeout(function() {
						$("#198").removeAttr( "style" ).hide().fadeOut();
					}, 1000 );
				};
        //}
         //   else if    ( id == 2 ) {
           //     $("#Paypal").hide();
             //   $("#Check").show()
            //}
              //  else {
                //    $("#Paypal").hide();
                  //  $("#Check").hide();
                //}
    });
    
    //inicial settings
    $("#198").hide();
});
</script> 
<body>
	<div id="page-content" class="page-content">
		<header class="page-header">
			<h1>This week:</h1>
			<h3 class="page-title"><?php echo date_format(new DateTime($_GET['fromdate']), "F j, Y");?> to <?php echo date_format(new DateTime($_GET['todate']), "F j, Y");?></h3>
		</header>
	<table width="100%" style="border:1px solid;">
	<tr><td><?php echo $picker;?>
	<tr><td><?php echo $project_name[0]->project_name;
	?>
	</td></tr>
	<tr><td>
	<div><button id="button" class="button">click</button></div>
	<div id="message"></div>
	<b>Hours Tracked</b><br>
	<?php 
	print_r($total_time);
	?>
	</td><td><b>Billable Hours</b><br>
	<?php print_r($billable_time);
	?>
	<h5>Unbillable Hours</h5><h3><?php 
		echo "ert";
	?>
	</h3></td><td>
	<h5>Billable Amount</h5><h3>
	<?php 
	print_r($billable_rate) . ".00";
	echo ".00";?></h3></td></td></tr>

	
	</td></tr>
	<tr><td colspan="4">	<div id="menucss"><?php echo $menu ?></div>
</td></tr>
	<tr><td><h5>Name</h5></td><td><h5>Hours</h5></td><td><h5>Billable Hours</h5></td><td><h5>Billable Amount</h5></td></tr>
	<?php 
	$i = 0;
	foreach ($task_url as $key=>$value) {
		//print_r($task_url);
		foreach ($value as $key=>$val) {
			if ($key == 'task_id') {
				echo "<tr>";
				$this->timetrackerurls->display_person($value['task_id']);
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