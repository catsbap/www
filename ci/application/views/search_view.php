<!DOCTYPE html>
<html lang="en">
<?php echo $library_src;?>


<script>
//KEEP WORKING THIS, I'M SICK OF DATES ON 12/26
$(document).ready( function() {
    $('#timeframe').change( function() {
        //user selected week, update the hidden fromdate and todate
        if($(this).val() == 'type=thisweek') {
        	//get the dates for Monday and Sunday and
        	//output them to hidden fields
        	var date = date || Date.today();
        	d1 = date.is().monday() ? date : date.last().monday();
			d2 = d1.clone().next().sunday();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate)
			//d2 = Date.next().sunday();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate)		
		} else if ($(this).val() == 'type=lastweek') {
			var date = date || Date.today().last().week();
        	d1 = date.is().monday() ? date : date.last().monday();
			d2 = d1.clone().next().sunday();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate)
			//d2 = Date.next().sunday();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate)
		} else if ($(this).val() == 'type=thismonth') {
			var date = date || Date.today();
        	d1 = date.clearTime().moveToFirstDayOfMonth();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate)
			var date = date || Date.today();
			d2 = date.clearTime().moveToLastDayOfMonth();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate)
		} else if ($(this).val() == 'type=lastmonth') {
			var date = date || Date.today();
        	d1 = date.clearTime().moveToFirstDayOfMonth().add(-1).months();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate)
			var date = date || Date.today();
			d2 = date.clearTime().moveToLastDayOfMonth();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate)
		} 
		var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/" + fromdate + "/" + todate;
		window.location.href = url;

    });
});

</script>
<table width="100%" align="center" border="1px solid">
<tr>
<td align="center" colspan=2><h1>Detailed Time Report</h1></td></tr><tr> 
<td> 
<form action="<?php echo site_url('search_controller/search_data/' . $this->uri->segment(4) . '/' . $this->uri->segment(3))?>" method = "post">
<tr><td align="right" width="40%">Timeframe: </td><td align="left">
<?
$options = array('type=thisweek' => 'This Week', 'type=lastweek' => 'Last Week',
'type=thissmperiod' => 'This Semimonthly Period', 'type=lastsmperiod' => 'Last Semimonthly Period', 'type=thismonth' => 'This Month', 'type=lastmonth' => 'Last Month', 'type=thisquarter' => 'This Quarter', 'type=lastquarter' => 'Last Quarter', 'type=thisyear' => 'This Year', 'type=lastyear' => 'Last Year');
echo form_dropdown('timeframe', $options, 'type=' . $this->input->get('type'), 'id=timeframe');
?>
<input type="hidden" name = "todate" id="fromdate"/>
<input type="hidden" name = "fromdate" id="todate"/>
</td></tr>
<tr><td align="right" width="40%">Clients:</td><td align="left">
<input type=text list=clients name="clients">
<datalist id=clients name=clients>
<?php print_r($client_results);?>
<?php foreach ($client_results as $result) {
	?><option><?php
	print_r($result->client_name);
?>
	</option>
<?php } ?>
</datalist>
</td></tr><tr><td align="right" width="40%">Projects:</td><td align="left">
<input type=text list=projects name="projects">
<datalist id=projects name=projects>
<?php print_r($project_results);?>
<?php foreach ($project_results as $result) {
	?><option><?php
	print_r($result->client_name);?></option><option><?php
	print_r($result->project_name);
?>
	</option>
<?php } ?>
</datalist>
</td></tr><tr><td align="right" width="40%">Tasks:</td><td align="left">
<input type=text list=tasks name="tasks">
<datalist id=tasks name=tasks>
<?php foreach ($task_results as $result) {
	?><option><?php
	print_r($result->task_name);
?>
	</option>
<?php } ?>
</datalist>
</td></tr><tr><td align="right" width="40%">People:</td><td align="left">
<input type=text list=people name="people">
<datalist id=people name=people>
<?php foreach ($person_results as $result) {
	?><option><?php
	print_r($result->person_first_name);
?>
	</option>
<?php } ?>
</datalist>
</td></tr><tr><td align="center" colspan="2"><input type="submit" value = "Search" />
</td></tr></table>
</form>
</html>