<tr><td colspan=4>
	<div id="menucss"><?php echo $menu ?></div>
	</td></tr>
	<tr><td><h5>Name</h5></td><td><h5>Hours</h5></td><td><h5>Billable Hours</h5></td><td><h5>Billable Amount</h5></td></tr>
	<?php 
	//error_log(print_r($client_url,true));
	$i = 0;
	foreach ($project_url as $key=>$value) {
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
	</div>
	<footer id="site-footer" class="site-footer">
	
	</footer>


</body>
</html>