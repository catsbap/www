<!DOCTYPE html>
<html lang="en">
<?php echo $library_src;?>

<script>
/*$(document).ready( function() {
    $('#Bank').change( function() {
        // enter in an empty field code of the selected bank
        $('#UniversalCode').val( $(this).val() );
    });
});
*/
</script>
<table>
<td>View Clients</td> 
<td> 
<!--<select name="Bank" id="Bank" tabindex=24 /> 
        <option>2013</option>
		<option>2012</option>
		<option>2011</option>
</select>
<input value="" id="UniversalCode">
</td>--> 
<form action="<?php echo site_url('search_controller/search_client');?>" method = "post">
<input type="text" name = "client_id" />
<input type="text" name = "todate" />
<input type="text" name = "fromdate" />
<input type="submit" value = "Search" />
<br><br>
<input type=text list=clients name="clients"">
<datalist id=clients name=clients>
<?php print_r($client_results);?>
<?php foreach ($client_results as $result) {
	?><option><?php
	print_r($result->client_name);
?>
	</option>
<?php } ?>
</datalist>
</form>
</html>