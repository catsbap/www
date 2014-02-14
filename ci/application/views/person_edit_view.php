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
			<? $person_id = $this->uri->segment(3);?>

			<form action="<?php echo site_url("person_controller/update_person/$person_id")?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">

<section id="page-content" class="page-content">
	<header class="page-header">
	<figure class="person-logo l-col-20">
		<?php
		//get the image out of the db. it is the default if the user hasn't udpated it yet, this would be the case if
		//they came from the add screen.
		?>
			<img class="person-logo-img small" src="<?php echo "$this->base/uploads/" . basename($person[0]->person_logo_link)?>" style="height:100px; width:100px;" title="Person Image" alt="Person image" />
			
		</figure>
		<h1 class="page-title"><?php echo $person[0]->person_first_name ?>'s Basic Info</h1>
		<nav class="page-controls-nav">
			<ul class="client-page-controls">
				<!--li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.php">+ Add Person</a></li-->
				<!--<li class="page-controls-item add-client-button"><a class="add-client-link" href="client-add.html">+ Add Client</a></li>-->
				<!--li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li-->
			</ul>
		</nav>
	</header>
	<section class="content">
    	<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<ul class="details-list client-details-list">
		   			<li class="client-details-item name">
		   				This person is a:
		   				<?php 
		   				//$row = Person::getEnumValues("person_type");																					  						$enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
			   			//foreach ($person_types as $type) {	?>
			   				<!--input type="radio" name="person-type" value="<?php //echo $type->person_type?>" <?php //if ($type->person_type == "Employee") echo "checked";?>-->   <?php //echo $type->person_type ?>
		   				<br/>
		   				<input type="radio" name="person-type" value="employee" id="person-type" <?php echo set_radio('person-type','employee'); ?> /> Employee
		   				<input type="radio" name="person-type" value="contractor" id="person-type" <?php echo set_radio('person-type','contractor'); ?> /> Contractor
		   				<br/>
		   				
		   				
						<label for="client-name" class="client-details-label">First Name:</label>
						<input id="client-name" name="person-first-name" class="client-name-input" type="text" tabindex="1" value="<?php echo set_value('person-first-name', $person[0]->person_first_name); ?>" /><br />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" class="client-details-label">Last Name:</label>
						<input id="client-phone" name="person-last-name" class="client-phone-input" type="text" tabindex="2" value="<?php echo set_value('person-last-name', $person[0]->person_last_name); ?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-email" class="client-details-label">Email:</label>
						<input id="client-email" name="person-email" class="client-email-input" type="text" tabindex="3" value="<?php echo set_value('person-email', $person[0]->person_email); ?>" />
					</li>
					<li class="client-details-item email">
						<label for="client-city" class="client-details-label">Department</label>
						<input id="client-city" name="person-department" class="client-city-input" type="text" tabindex="6" value="<?php echo set_value('person-department', $person[0]->person_department); ?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" class="client-details-label">Hourly Rate:</label>
						$<input id="client-zip" name="person-hourly-rate" class="client-zip-input" type="text" tabindex="8" value="<?php echo set_value('person-hourly-rate', $person[0]->person_hourly_rate); ?>" /><br />
					</li>
					<li class="client-details-item email">
						<label for="client-zip" class="client-details-label">Permissions:</label>
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
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="Save Person" tabindex="11"/> 
						 or <a class="" href="people.php" tabindex="11">Cancel</a></li>
						 <li>
						 <?php //$person_id=$person->getValue("person_id");?>
						 <input id="client-delete-btn" name="person-delete-btn" class="client-delete-btn" onclick="window.open('delete_person.php?person_id=<?php //echo $person_id ?>','myWindow','width=200,height=200,left=250%,right=250%,scrollbars=no')" type="button" value="- Delete Person" tabindex="11" />	
					</li>
				</ul>
			</fieldset>
			
	<section class="content">
		<fieldset class="client-details-entry">
				<legend class="client-details-title"><?php echo $person[0]->person_first_name ?>'s Projects</legend>
				<header class="client-details-header">
					<h1 class="client-details-title"><?php echo $person[0]->person_first_name?>'s Projects</h1>
				</header>
				
        	</fieldset>


		<form action="person-basic-info.php" method="post" style="margin-bottom:50px;" enctype="multipart/form-data">
		<label for="client-zip" class="client-details-label"></label>
		<input id="projectidselectname" name="projectidselectname" class="projectidselectname" type="text" tabindex="8" value="<?php
		//output all of this users current projects in name form.
		//list($projectForPerson) = Project_Person::getProjectsForPerson($person->getValue("person_id"));
		foreach ($person_projects as $projects) {
				 echo $projects->project_name . ",";
		} 
		?>">
		
		
		<input id="projectidselect" name="projectidselect" class="projectidselect" type="text" tabindex="8" value="<?php
		//output all of this users current projects.
		
		//list($projectForPerson) = Project_Person::getProjectsForPerson($person_perms->getValue("person_id"));
		
		foreach ($person_projects as $projects) {
				 echo $projects->project_id . ",";
		} ?>">
		<button name="Assign New Projects" onclick="FillProjects(this.form); return false;" >Assign New Projects</button>
		<br />
	
	
	
		<?php		//this is the select box.
		//			list($projects) = Project::getProjects();?>
					<?php //print_r($all_projects)?>
					<select name="projectid" id="projectid" size="1">    
						<?php foreach ($all_projects as $project) { ?>
							<option value="<?php echo $project->project_id ?>" text="<?php echo $project->project_name?>"><?php echo $project->project_name?></option>
    					<?php } ?>
    			 </select><br />

					<ul>
	
	
	
					<?php 
					//get out all of the people associated with this project.
					//if ($projectForPerson) {
					//	echo("<br/>" . $person->getValue("person_first_name") . " has the following assigned projects:<br/>");
					//	foreach ($projectForPerson as $projectPerson) {?>
							<li class="client-details-item phoneNum"><?php //echo $projectPerson->getValue("project_name")?></li>
						<?php //} ?>
					<?php //} else {
							//echo("<br/>" . $person->getValue("person_first_name") . " doesn't have any projects assigned yet.");
						//}
					?>
					</ul>
				<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
						<!--modified field to be of type submit instead of button-->
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="Save Projects" tabindex="11"/> 
						 or <a class="" href="people.php" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
</section>
<header class="page-header">
<?php
	//if the password is not set up, resend the invitation. Otherwise, change the password.
	//use the email here, since it is unique.
	//$password_is_set = Person::isPasswordSet($person->getValue("person_email"));
	//if (!$password_is_set) {
	?>
			<h1 class="page-title">Resend <?php //echo $person->getValueEncoded("person_first_name")?>'s Invitation</h1>
			<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label"></label>
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="Resend Invitation" tabindex="11"/> 
						 or <a class="" href="people.php" tabindex="11">Cancel</a>
					</li>
				</ul>
	<?php //} else {?>
			<h1 class="page-title">Change Your Password</h1>
	<input type="hidden" name="emailAddress" value="<?php //echo $person->getValue("person_email")?>"/>
	<div style="width:30em;">
    <label for="password1" class="required">Choose a password</label>
    <input type="password" name="password1" id="password1" value="" /><br/>
    <label for="password2" class="required">Retype password</label>
    <input type="password" name="password2" id="password2" value="" /><br/>
    <div style="clear:both">
    </div>
    </div>
			<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label"></label>
                        <input id="client-add-btn" name="person-add-btn" class="client-add-btn" type="submit" value="Change Password" tabindex="11"/> 
						 or <a class="" href="people.php" tabindex="11">Cancel</a>
					</li>
				</ul>
	<?php //} ?>	
	</header>
	<div id='cow'>

</form>
<script type="text/javascript">
//yeah, OK, so my javascript is rusty.
showP(document.getElementById('person-perm-id'));
</script>
<footer id="site-footer" class="site-footer">

</footer>
<script src="client-controls.js" type="text/javascript"></script>
</body>
</html>