<!DOCTYPE html>
<html lang="en">
<?php echo $library_src;?>
<script type="text/javascript" src="/time_tracker/ui/libraries/jquery.form.js"></script>
<script type="text/javascript">
//function FillBilling(f) {
    //window.alert(f);
    
    //f.projectidselect.value = f.projectidselect.value + f.projectid.value + ",";
    //f.shippingname.value;
    //f.billingcity.value = f.shippingcity.value;
    //return false;
    
//}
/*function showP(elem){
   if(elem.value == "Regular User"){
      document.getElementById('perm_ru').style.display = "block";
      document.getElementById('perm_pm').style.display = "none";
      document.getElementById('perm_a').style.display = "none";
   } else if(elem.value == "Project Manager") {
      document.getElementById('perm_ru').style.display = "none";
      document.getElementById('perm_pm').style.display = "block";
      document.getElementById('perm_a').style.display = "none";
   } else if(elem.value == "Administrator") {  
   	 document.getElementById('perm_ru').style.display = "none";
     document.getElementById('perm_pm').style.display = "none";
     document.getElementById('perm_a').style.display = "block";
	}
}
*/
$(document).ready( function() {
$('#person-perms').val($('#dropdown_value').val());
	if ($('#person-perms').val() == "Regular User") {
      $('#perm_ru').show();
      $('#perm_pm').hide();
      $('#perm_a').hide();
   } else if ($('#person-perms').val() == "Project Manager") {
      $('#perm_ru').hide();
      $('#perm_pm').show();
      $('#perm_a').hide();
   } else if ($('#person-perms').val() == "Administrator") {  
   	 $('#perm_ru').hide();
      $('#perm_pm').hide();
      $('#perm_a').show();
	}
$('#person-perms').change( function() {
	$('#dropdown_value').val($('#person-perms').val());
	if ($('#person-perms').val() == "Regular User") {
      $('#perm_ru').show();
      $('#perm_pm').hide();
      $('#perm_a').hide();
   } else if ($('#person-perms').val() == "Project Manager") {
      $('#perm_ru').hide();
      $('#perm_pm').show();
      $('#perm_a').hide();
   } else if ($('#person-perms').val() == "Administrator") {  
   	 $('#perm_ru').hide();
      $('#perm_pm').hide();
      $('#perm_a').show();
	}

});
});

</script>

<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Add Person</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list project">
				<!--
<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.html">View Archives</a></li>
-->
				<li class="page-controls-item"><a class="view-all-link" href="view_people">View All</a></li>
			</ul>
		</nav>
	</header>
	
			<form action="<?php echo site_url('person_controller/insert_person')?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">


<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Add Person</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<li class="page-controls-item add-client-button"><a class="add-client-link" href="insert_person">+ Add Person</a></li>
				<!--<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>-->
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li>
			</ul>
		</nav>
	</header>
			<p>We'll email this person instructions on how to sign in to Time Tracker.</p>
	<section class="content">
    <!--added because we need the information to be submitted in a form-->
      <form action="person-add.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
      <input type="hidden" name="action" value="person-add"/>
	<!--end add-->
		<!--<figure class="client-logo l-col-20">
			<img class="client-logo-img small" src="images/default.jpg" title="Client/Company name logo" alt="Client/Company name logo" />
			<fieldset class="client-logo-upload">
				<legend class="client-logo-title">Upload Client Logo</legend>
				<header class="client-logo-header">
					<h1 class="client-logo-title">Upload Client Logo</h1>
				</header>
				<input id="client-logo-file" name="client-logo-file" class="client-logo-file" type="file" value="Browse" />
				<input id="client-logo-upload-btn" name="client-logo-upload-btn" class="client-logo-upload-btn" type="button" value="Upload" /> or <a class="" href="#">Cancel</a>
			</fieldset>
		</figure>-->
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter person details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter person details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<?php echo $success_email ?>
				<ul class="details-list client-details-list">
		   			<li class="client-details-item name">
		   			<?php echo validation_errors(); ?>
		   			This person is a:
		   				<?php 
		   				//$row = Person::getEnumValues("person_type");																					  						$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
			   			//foreach ($person_types as $type) {	?>
			   				<!--input type="radio" name="person-type" value="<?php //echo $type->person_type?>" <?php //if ($type->person_type == "Employee") echo "checked";?>-->   <?php //echo $type->person_type ?>
		   				<?php //}	
			   				

						
		   				?><br/>
		   				<input type="radio" name="person-type" value="employee" id="person-type" <?php echo set_radio('person-type','employee'); ?> /> Employee
		   				<input type="radio" name="person-type" value="contractor" id="person-type" <?php echo set_radio('person-type','contractor'); ?> /> Contractor
		   				<br/>
		   				
		   				
						<label for="client-name" class="client-details-label">First Name:</label>
						<input id="client-name" name="person-first-name" class="client-name-input" type="text" tabindex="1" value="<?php echo set_value('person-first-name'); ?>" /><br />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" class="client-details-label">Last Name:</label>
						<input id="client-phone" name="person-last-name" class="client-phone-input" type="text" tabindex="2" value="<?php echo set_value('person-last-name'); ?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-email" class="client-details-label">Email:</label>
						<input id="client-email" name="person-email" class="client-email-input" type="text" tabindex="3" value="<?php echo set_value('person-email'); ?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-city" class="client-details-label">Department</label>
						<input id="client-city" name="person-department" class="client-city-input" type="text" tabindex="6" value="<?php echo set_value('person-department'); ?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" class="client-details-label">Hourly Rate:</label>
						$<input id="client-zip" name="person-hourly-rate" class="client-zip-input" type="text" tabindex="8" value="<?php echo set_value('person-hourly-rate'); ?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" class="client-details-label">Permissions:</label>
						<?php
	//					$row = Person::getEnumValues("person_perm_id");
	//					$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
						?>
						<!--select name="person-perm-id" onchange="showP(this)"-->
						<?php
						//foreach($person_perms as $perms) { ?>
							<!--option name="person-perm-id" value="<?php //echo $perms->person_perm_id ?>"--><?php //echo $perms->person_perm_id ?><!--/option-->
						<?php //} ?>
						<!--/select-->
						
						<?php 
						
						$person_perms = array(
							'default' => 'Please select a permission level',
							'Regular User'=> 'Regular User',
							'Project Manager' => 'Project Manager',
							'Administrator' => 'Administrator',
						);
							
						$js = 'id="person-perms" onChange="showP(this)"';
						echo form_dropdown('person-perms', $person_perms, '', 'id="person-perms"', set_value('person-perms')); ?>
						<input type="text" id="dropdown_value" name="dropdown-value" value="<?php echo set_value('dropdown-value', 'default'); ?>">
						<p id="perm_ru" style="display: none;">This person can track time and expenses.</p>
						<div id="perm_pm" style="display: none;">
						<input type="checkbox" name="create_projects" id="create_projects" value="<?php echo set_value('create_projects');?>">Create projects for all clients<br>
						<input type="checkbox" name="view_rates" id="view_notes" value="<?php echo set_value('view_notes');?>">View rates<br>
						<input type="checkbox" name="create_invoices" id="create_invoices" value="<?php echo set_value('create_invoices');?>">Create invoices for projects they manage<br>
						</div>
						<p id="perm_a" style="display: none;">This person can see all projects, invoices and reports in Time Tracker.</p>
					</li>
				</ul>
				<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
						<!--modified field to be of type submit instead of button-->
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="+ Add Person" tabindex="11"/> 
						 or <a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
</section>
</form>
<footer id="site-footer" class="site-footer">

</footer>
</body>
</html>