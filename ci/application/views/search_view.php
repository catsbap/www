<!DOCTYPE html>
<html lang="en">

<?php echo $library_src;?>


<script>

$(document).ready( function() {
	//initial value is hide departments
	$( '.departmentInputDiv' ).hide();
	$( '.staffInput' ).hide();
	//hide and show staff
	$('.departmentInput').click( function() {
		$( '.departmentInputDiv' ).show();
		$( '.departmentInput' ).hide();
		$( '.staffInputDiv' ).hide();
		$( '.staffInput' ).show();
	});
	//hide and show departments
	$('.staffInput').click( function() {
		$( '.staffInputDiv' ).show();
		$( '.staffInput' ).hide();
		$( '.departmentInputDiv' ).hide();
		$( '.departmentInput' ).show();
	});
	
    
    //semimonthly and custom are the only ones left!!! :)
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
		} else if ($(this).val() == 'type=lastyear') {
			var date = date || Date.today();
        	d1 = date.add(-2).year();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate);
			var date = date || Date.today();
			d2 = date.add(1).years();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate);
		} else if ($(this).val() == 'type=thisyear') {
			var date = date || Date.today();
        	d1 = date.add(-1).year();
			fromdate = d1.toString('yyyy-M-d');
			$('#fromdate').val(fromdate);
			var date = date || Date.today();
			d2 = date.add(1).year();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate);
		} else if ($(this).val() == 'type=thisquarter') {
			var today = Date.parse('today').toString('yyyy-MM-dd');
			var year = Date.parse('today').toString('yyyy');
			var month = Date.parse('today').toString('MM');
			var quarterMonth = (Math.floor((month-1)/3)*3)+1;
			var quarter = (Math.floor(month-1)/3)+1;
			var lastQuarter = (quarter > 1) ? quarter - 1 : lastQuarter = 4;
			var quarterStartDate = (quarterMonth < 10) ? year+'-0'+quarterMonth+'-01' : year+'-'+quarterMonth+'-01';
			fromdate = quarterStartDate.toString('yyyy-M-d');
			$('#fromdate').val(fromdate);
			//var date = date || Date.today();
			var date = Date.parse(quarterStartDate);
			tempdate = date.add(3).months();
			d2 = tempdate.add(-1).day();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate);
		} else if ($(this).val() == 'type=lastquarter') {
			var today = Date.parse('today').toString('yyyy-MM-dd');
			var year = Date.parse('today').toString('yyyy');
			var month = Date.parse('today').toString('MM');
			var quarterMonth = (Math.floor((month-1)/3)*3)+1;
			var quarter = (Math.floor(month-1)/3)+1;
			var lastQuarter = (quarter > 1) ? quarter - 1 : lastQuarter = 4;
			var quarterStartDate = (quarterMonth < 10) ? year+'-0'+quarterMonth+'-01' : year+'-'+quarterMonth+'-01';
			quarterStartDate = Date.parse(quarterStartDate);
			tempdate = quarterStartDate.add(-3).months()
			fromdate = quarterStartDate.toString('yyyy-M-d');
			$('#fromdate').val(fromdate);
			//var date = date || Date.today();
			var date = Date.parse(quarterStartDate);
			tempdate = date.add(3).months();
			d2 = tempdate.add(-1).day();
			todate = d2.toString('yyyy-M-d');
			$('#todate').val(todate);
		}
		
		var url = "<?php echo base_url();?>index.php/<?php echo $this->uri->segment(1)?>/<?php echo $this->uri->segment(2)?>/" + fromdate + "/" + todate + "/0/all_hours";
		window.location.href = url;

    });
});

</script>
<table width="100%" align="center" border="1px solid">
<tr>
<td align="center" colspan=2><h1>Detailed Time Report</h1></td></tr><tr> 
<td> 
<form action="<?php echo site_url('search_controller/search_data/' . $this->uri->segment(4) . '/' . $this->uri->segment(3)) . '/0/all_hours/timesheet_date' ?>" method = "post">
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
</td></tr><tr><td align="right" width="40%" class="departmentInputDiv">Departments:</td><td align="left" class="departmentInputDiv">
<input type=text list=department name="department">
<datalist id=department name=department>
<?php foreach ($department_results as $result) {
	?><option><?php
	print_r($result->person_department);
?>
	</option>
<?php } ?>
</datalist>
</td>
<td align="right" width="40%" class="staffInputDiv">Staff:</td><td align="left" class="staffInputDiv">
<input type=text list=people name="people">
<datalist id=people name=people>
<?php foreach ($person_results as $result) {
	?><option><?php
	print_r($result->person_first_name);
?>
	</option>
<?php } ?>
</datalist>
</td><td><a href="#" class="departmentInput">Search Departments</a><a href="#" class="staffInput">Search Staff</a></td></tr><tr><td align="center" colspan="2"><input type="submit" value = "Search" />
</td></tr></table>
</form>
</html>