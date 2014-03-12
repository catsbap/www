
<section id="page-content" class="page-content">
	<header class="page-header">
	<? $person_name = urldecode($this->uri->segment(3));?>
		<h1 class="page-title">Welcome, <?php echo $person_name ?>! please change your password.</h1>
	</header>
			
			<form action="<?php echo site_url("reset_now_controller/update_registration") ?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">
				<input id="login" name="login" class="client-contact-info-input" type="hidden" tabindex="1" value="<?php echo $person_name?>" />
				<ul class="entity-list entity-details-list client-details-list">
					<li class="entity-details-item name client">
						<label for="password1" class="entity-details-label client required">Password:</label>
						<input id="password1" name="password1" class="client-contact-info-input" type="text" tabindex="1" value="" />
					</li>
					<li class="entity-details-item phoneNum client">
						<label for="password2" class="entity-details-label client required">Retype Password:</label>
						<input id="password2" name="password2" class="client-contact-info-input" type="text" tabindex="2" value="" />
					</li>
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label"></label>
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="Change Password" tabindex="11"/> 
						 or <a class="" href="" tabindex="11">Cancel</a>
					</li>
<!--END FORM-->
</form>
<footer id="site-footer" class="site-footer">

</footer>
<script src="../../../js/client-controls.js" type="text/javascript"></script>
</body>
</html>