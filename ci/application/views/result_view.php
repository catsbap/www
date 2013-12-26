<!DOCTYPE html>
<html lang="en">
<table>
<tr><td><h1>Here are the results</h1></td></tr>
<?php
foreach($results as $row){
?> 
    <tr>
        <td><?php echo $row->client_name?></td><td><?php echo $row->timesheet_hours?></td>
        </tr>
<?php   
}
?>
</table>