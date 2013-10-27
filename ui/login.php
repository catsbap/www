<?php 	include('header.php'); //add header.php to page 
	//I'm just trying this out. User is redirected here if checklogin in common.inc.php fails.
	//this calls the javascript function check_login() which is in the ajax-login.js file in this directory. 
	
?>
<div id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Log in to use Time Tracker</h1>
	</header>
	<div class="content">
		<form id="user-login" action="" method="post"> <!--event handling dealt with in js file-->
			<ul class="details-list">
				<li class="details-item username"><label for="username" class="entity-details-label">User name: </label><input name="username" type="text" id="username" value=""/></li>
				<li class="details-item password"><label for="password" class="entity-details-label">Password: </label><input name="password" type="password" id="password" value=""/></li>
				<li class="details-item username"><label for="Submit" class="entity-details-label">Ready? </label><input type="submit" name="Submit" value="Login"/></li>
			</ul>
		</form>
	</div>
</div>
<footer id="site-footer" class="site-footer">

</footer>
<script type='text/javascript' src='ajax-login.js'></script>

</body>
</html>
