<!DOCTYPE html>
<html lang="en">
BUDGET REPORT<br><br>
<?php //print_r($results) ?>

<table>
<?php
//work on this SUNDAY
foreach($results as $row){
?> 
    <tr>
        <td><?php echo $row->client_name?></td><td><?php echo $row->project_name?></td><td><?php echo $row->project_budget_by?><?php echo $row->total_budget_hours?></td></tr>
<?php   
}
?>
</table>

</html>