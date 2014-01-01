<tr><td colspan=4>
	<h1 align=center>Project Lifespan Report From <?php echo $results[0]->from_date?> to <?php echo $results[0]->to_date?></h1>
</td></tr>
<tr>
<td align=center>
<table width=90% border=1px solid;>
<tr><td colspan=4><h3>Project Name</h3></td></tr>
<tr><td colspan=4><?php echo $results[0]->project_name?></td></tr>
<tr><td><b>Hours Tracked</b></td><td><b>Billable Hours</b></td><td><b>Billable Amount</b></td><td></td></tr>
<tr><td><?php echo $results[0]->timesheet_hours?></td><td><?php echo $billable_hours?></td><td>$<?php echo $billable_amount?>.00</td><td></td></tr>

<tr><td><b>Budget</b></td><td><b>Budget</b></td><td><b>Spent</b></td><td><b>Left</b></td></tr>
<tr><td><?php echo $results[0]->project_budget_by?></td><td><?php echo $budget?></td><td><?php echo ($billable_hours) ?></td><td><?php echo ($budget-$billable_hours)?></td></tr>

<tr><td colspan=4><?php print_r($results) ?><br><br><?php print_r($this->data)?></td></tr>
</table>
	</table>
	</div>
	</td></tr>
	<footer id="site-footer" class="site-footer">
	
	</footer>


</body>
</html>