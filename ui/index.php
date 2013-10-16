<?php

	require_once("../common/common.inc.php");
	require_once("../classes/Person.class.php");
	checkLogin(basename($_SERVER["PHP_SELF"]));

	include('header.php'); //add header.php to page
?>

<section id="page-content" class="page-content">
This page will eventually be the dashboard
<a href="logout.php"><br><br>LOG OUT</a>

</section>
<footer id="site-footer" class="site-footer">

</footer>
</body>
</html>
