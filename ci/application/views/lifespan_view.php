<tr><td colspan=4>
	<h1 align=center>Project Lifespan Report From <?php echo $this->data['results'][0]->from_date?> to <?php echo $this->data['results'][0]->to_date?></h1>
</td></tr>
<tr>
<td align=center>
<table width=90% border=1px solid;>
<tr><td colspan=4><h3>Project Name</h3></td></tr>
<tr><td colspan=4><?php echo $this->data['results'][0]->project_name?></td></tr>
<tr><td><b>Hours Tracked</b></td><td><b>Billable Hours</b></td><td><b>Billable Amount</b></td><td><b>Rate</b></td></tr>
<tr><td><?php echo $this->data['billable_hours'];?></td><td><?php echo $this->data['billable_hours'];?></td><td>$<?php echo $this->data['billable_amount']?></td><td>$<?php echo $this->data['rate']?></td></tr>
<tr><td><b>Budget</b></td><td><b>Budget</b></td><td><b>Spent</b></td><td><b>Left</b></td></tr>
<tr><td><?php echo $this->data['results'][0]->project_budget_by?></td><td>$<?php echo $this->data['budget']?></td><td>$<?php echo ($this->data['spent']) ?></td><td>$<?php echo ($this->data['budget']-$this->data['billable_amount'])?></td></tr>
</table>
	</table>
	</div>
	</td></tr>
	<footer id="site-footer" class="site-footer">
	
	</footer>


</body>
</html>