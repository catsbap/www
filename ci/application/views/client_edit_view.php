
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Edit Client Details</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list client">
				<li class="page-controls-item link-btn"><a class="add-client-link" href="../display_client">+ Add Client</a></li>
				<li class="page-controls-item"><a class="view-client-archive-link" href="client-archives.php">View Archives</a></li>
				<li class="page-controls-item"><a class="view-all-link" href="../view_clients">View All</a></li>
			</ul>
		</nav>
	</header>
			<? $client_id = $this->uri->segment(3);?>
			<form action="<?php echo site_url("client_add_controller/update_client/$client_id")?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">
		 
		
		
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" id="image" src="<?php echo "$this->base/uploads/" . $client[0]->client_logo_link ?>" title="Client/Company name logo" alt="Client/Company name logo" />
			<fieldset class="client-logo-upload">
				<legend class="client-logo-title">Upload Client Logo</legend>
				<header class="client-logo-header">
					<h1 class="client-logo-title">Upload Client Logo</h1>
				</header>
				<img style="display:none" id="loader" src="/time_tracker/ci/uploads/ajax-loader.gif" alt="Loading...." title="Loading...." />

		 <input id="uploadImage" type="file" accept="image/*" name="image" />
		 <input id="button" type="button" value="Upload">
			</fieldset>
		</figure>
		<section class="client-detail l-col-80">
        	<fieldset class="client-details-entry">
				<legend class="client-details-title">Enter client details:</legend>
				<header class="client-details-header">
					<h1 class="client-details-title">Enter client details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<?php echo validation_errors(); 
					//print_r($client);
				?>
				<ul class="entity-list entity-details-list client-details-list">
					<li class="entity-details-item name client">
						<label for="client-name" class="entity-details-label client required">Client's name:</label>
						<input id="client-name" name="client-name" class="client-contact-info-input" type="text" tabindex="1" value="<?php echo set_value('client-name', $client[0]->client_name);?>" />
					</li>
					<li class="entity-details-item phoneNum client">
						<label for="client-phone" class="entity-details-label client">Phone number:</label>
						<input id="client-phone" name="client-phone" class="client-contact-info-input" type="text" tabindex="2" value="<?php echo set_value('client-phone', $client[0]->client_phone); ?>" />
					</li>
					<li class="entity-details-item email client">
						<label for="client-email" class="entity-details-label client">Email address:</label>
						<input id="client-email" name="client-email" class="client-contact-info-input" type="text" tabindex="3" value="<?php echo set_value('client-email', $client[0]->client_email); ?>" />
					</li>
					<li class="entity-details-item fax client">
						<label for="client-fax" class="entity-details-label client">Fax number:</label>
						<input id="client-fax" name="client-fax" class="client-contact-info-input" type="text" tabindex="4" value="<?php echo set_value('client-fax', $client[0]->client_fax); ?>" />
					</li>
					<li class="entity-details-item info-sync client">
						<label for="contact-info-sync" class="entity-details-label client">Use same info for contact:</label>
						<input id="contact-info-sync" name="contact-info-sync" class="contact-info-sync-input" type="checkbox" tabindex="4" value="info-sync" />
					</li>
					<li class="entity-details-item address">
						<label for="client-streetAddress" class="entity-details-label client">Street Address:</label>
						<textarea id="client-streetAddress" name="client-streetAddress" class="streetAddress-input" tabindex="5"><?php echo set_value('client-streetAddress', $client[0]->client_address); ?></textarea><br />
						<label for="client-city" class="entity-details-label client">City</label>
						<input id="client-city" name="client-city" class="client-city-input" type="text" tabindex="6" value="<?php echo set_value('client-city', $client[0]->client_city); ?>" /><br />
						<!--handle state differently, as it is a select field.-->
						<label for="client-state"class="entity-details-label client">State:</label>	
				<?php 
						
						$state_list = array(
							'default' => 'Please select a state',
							'AL' => 'Alabama',
							'AK' => 'Alaska',
							'AZ' => 'Arizona',
							'AR' => 'Arkansas',
							'CA' => 'California',
							'CO' => 'Colorado',
							'CT' => 'Connecticut',
							'DE' => 'Delaware',
							'FL' => 'Florida',
							'GA' => 'Georgia',
							'HI' => 'Hawaii',
							'ID' => 'Idaho',
							'IL' => 'Illinois',
							'IN' => 'Indiana',
							'IA' => 'Iowa',
							'KS' => 'Kansas',
							'KY' => 'Kentucky',
							'LA' => 'Louisiana',
							'ME' => 'Maine',
							'MD' => 'Maryland',
							'MA' => 'Massachusetts',
							'MI' => 'Michigan',
							'MN' => 'Minnesota',
							'MS' => 'Mississippi',
							'MO' => 'Missouri',
							'MT' => 'Montana',
							'NE' => 'Nebraska',
							'NV' => 'Nevada',
							'NH' => 'New Hampshire',
							'NJ' => 'New Jersey',
							'NM' => 'New Mexico',
							'NY' => 'New York',
							'NC' => 'North Carolina',
							'ND' => 'North Dakota',
							'OH' => 'Ohio',
							'OK' => 'Oklahoma',
							'OR' => 'Oregon',
							'PA' => 'Pennsylvania',
							'RI' => 'Rhode Island',
							'SC' => 'South Carolina',
							'SD' => 'South Dakota',
							'TN' => 'Tennessee',
							'TX' => 'Texas',
							'UT' => 'Utah',
							'VT' => 'Vermont',
							'VA' => 'Virginia',
							'WA' => 'Washington',
							'WV' => 'West Virginia',
							'WI' => 'Wisconsin',
							'WY' => 'Wyoming',
							'DC' => 'Washington DC',
							'PR' => 'Puerto Rico',
							'VI' => 'U.S. Virgin Islands',
							'AS' => 'American Samoa',
							'GU' => 'Guam',
							'MP' => 'Northern Mariana Islands',
							);
							echo form_dropdown('client-state', $state_list, set_value('client-state', $client[0]->client_state));
							?>

						<label for="client-zip" class="client-details-label">Zip code:</label>
						<input id="client-zip" name="client-zip" class="client-zip-input" type="text" tabindex="8" value="<?php echo set_value('client-zip', $client[0]->client_zip); ?>"/><br />
						<label for="client-country" class="client-details-label">Client's country:</label>
						<select id="client-country" name="client-country" class="client-country-select" tabindex="9">
							<option value="">Select client's country...</option>
							<option selected="selected" value="US">United States of America</option>
						</select>
					</li>

					<li class="client-details-item currency">
						<label for="client-currency" class="client-details-label">Preferred currency:</label>   
						<?php
$options = array('1' => 'USD');
echo form_dropdown('client_currency_index', $options, '1');
?>
					</li>
					<li class="entity-details-item client-archive client">
						<label for="client-archive" class="entity-details-label client">Archive client?</label>
						<input id="client-archive" name="client-archive" class="client-archive-input" type="checkbox" tabindex="10" value="1" />
					</li>
					<li class="entity-details-item delete-btn client">
						<label for="client-delete-btn" class="entity-details-label client">Delete Client?</label>
						<input id="client-delete-btn" name="client-delete-btn" class="client-delete-btn" onclick="window.open('delete.php?client_id=<?php echo $client_id ?>','myWindow','width=200,height=200,left=250%,right=250%,scrollbars=no')" type="button" value="- Delete Client" tabindex="11" />
					</li>
					<li class="entity-details-item submit-btn client">
						<label for="client-save-btn" class="entity-details-label client">All done?</label>
						<input id="client-save-btn" name="client-save-btn" class="client-save-btn" type="submit" value="+ Save Changes" tabindex="11" /> or
						<a class="" href="#" tabindex="11"><a href="clients.php">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
		<input type="hidden" name="action" value="edit_client">
		<section id="contact-detail" class="contact-detail">
			<header class="details-header contact-details-header">
				<h1 class="client-details-title">Contacts</h1>
			</header>

				<?php
				$i = 0;
foreach ($contact as $contacts) {
					?>
					
			<fieldset id="contact-details" class="contact-details-entry">
				<!-- <legend class="contact-details-title">Edit contact details:</legend> -->
				<header class="contact-details-header">
					<h1 class="contact-details-title">Edit contact details:</h1>
					<h4 class="required">= Required</h4>
				</header>
				<!-- <h4 class="required">= Required</h4> -->
				<ul class="details-list client-details-list">
						<li class="client-details-item name">
						<label for="contact-name" class="contact-details-label required">Your contact's name:</label>
						<input id="contact-name" name="contact-name[]" class="contact-info-input" type="text" value="<?php echo set_value('contact-name', $contacts->contact_name) ?>"/><br />
						<label for="contact-id" class="contact-details-label">Contact ID:</label>
						<input id="contact-id" name="contact-id[]" class="contact-details-label" type="text" value="<?php echo set_value('contact-id', $contacts->contact_id) ?>"/><br />
						<label for="contact-primary" class="contact-details-label">This the primary contact: </label>

						<?php 
						//$check_data = array(
						//'name'	=>	'contact-primary[]',
						//'id' => 'contact-primary',
						//'class'	=>	'contact-details-label',
						//);
						
						//echo form_checkbox($check_data); ?>
						
						<!--input name="contact-primary[<?php echo $i?>]" value="on" type="checkbox" <?php echo set_checkbox("contact-primary[$i]", "on", $contacts->contact_primary == 1) ?> id="contact-primary" class="contact-info-input"/-->
						<?php echo form_checkbox("contact-primary[$i]", "on", set_checkbox("contact-primary[$i]", "on", $contacts->contact_primary == 1)); ?>
						

					</li>
					<li class="client-details-item phoneNum">
						<label for="contact-officePhone" class="contact-details-label">Office phone:</label>
						<input id="contact-officePhone" name="contact-officePhone[]" class="contact-info-input" type="text" value="<?php echo set_value('contact-officePhone', $contacts->contact_office_number)?>" /><br />
						<label for="contact-mobilePhone" class="contact-details-label">Mobile phone:</label>
						<input id="contact-mobilePhone" name="contact-mobilePhone[]" class="contact-info-input" type="text" value="<?php echo set_value('contact-mobilePhone', $contacts->contact_mobile_number) ?>" />
					</li>
					<li class="client-details-item email">
						<label for="contact-email" class="contact-details-label">Email:</label>
						<input id="contact-email" name="contact-email[]" class="contact-info-input" type="text" value="<?php echo set_value('contact-email', $contacts->contact_email)?>" />
					</li>
					<li class="client-details-item fax">
						<label for="contact-fax" class="contact-details-label">Fax:</label>
						<input id="contact-fax" name="contact-fax[]" class="contact-info-input" type="text" value="<?php echo set_value('client-fax', $contacts->contact_fax_number)?>" />
					</li>
					<li class="client-details-item cancel-additional">
						<label for="cancel-contact-link" class="contact-details-label">Need to remove contact?</label>
						<a id="cancel-contact-link" class="cancel-action-link" href="#" tabindex="19">Remove contact</a>
					</li>
				</ul>
			</fieldset>
			<?php 
			$i++;
			} 
			?>
			<fieldset id="contact-save" class="contact-details-entry">
				<ul class="details-list contact-details-submit">
					<li class="client-details-item add-additional">
						<label for="add-additional-link" class="contact-details-label">Need to add more contacts?</label>
						<a id="add-additional-link" href="#" class="">Add another contact</a>
					</li>
					<li class="client-details-item submit-contact">
						<label for="contact-save-btn" class="contact-details-label">All done?</label>
						<input id="contact-save-btn" name="contact-save-btn" class="contact-save-btn" type="submit" value="+ Save Contact" tabindex="11" /> or
						<a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
		<section class="client-projects">

			<header class="details-header client-projects-header">
				<h1 class="client-details-title">Projects</h1>
			</header>
			<h1 class="client-projects-title active">Active Projects</h1>
			<ul class="details-list client-projects-list active">
			<?php 
				//we'll use an existing function to work this magic. Get all the clients with
				//projects and then display them (active and archived) by name for a particular client.
				//PUT THIS BACK IN ONCE PROJECTS ARE IN CODEIGNITER
				//$clientProjects = Project::getClientsProjectsByStatus(1);
				//foreach($clientProjects as $clientProject) {
					//if ($client->getValueEncoded("client_id") == $clientProject->getValueEncoded("client_id")) {
						?> 
						<li class="client-projects-list-item"><?php //echo $clientProject->getValueEncoded("project_name") ?></li> <?php
					//}
				//}			
			?>
			</ul>
			<h1 class="client-projects-title archive">Archived Projects</h1>
			<ul class="details-list client-projects-list archive">
<?php 
				//we'll use an existing function to work this magic. Get all the clients with
				//projects and then display them (active and archived) by name for a particular client.
				//PUT THIS BACK IN ONCE PROJECTS ARE IN CODEIGNITER
				//$clientProjects = Project::getClientsProjectsByStatus(0);
				//foreach($clientProjects as $clientProject) {
					//if ($client_id == $clientProject->getValueEncoded("client_id")) {
						?> <li class="client-projects-list-item"><?php //echo $clientProject->getValueEncoded("project_name") ?></li> <?php
					//}
				//}			
			?>			</ul>
		</section>
	</section>
</section>
<!--END FORM-->
</form>
<footer id="site-footer" class="site-footer">

</footer>
<script src="../../../js/client-controls.js" type="text/javascript"></script>
</body>
</html>