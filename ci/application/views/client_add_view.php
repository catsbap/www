<!DOCTYPE html>
<html lang="en">
<?php echo $library_src;?>
<script type="text/javascript" src="/time_tracker/ui/libraries/jquery.form.js"></script>

<script>
$(document).ready(function() {
	//this uses jquery form to upload the image immediately.
	var f = $('form');
	var l = $('#loader'); // loder.gif image
	var b = $('#button'); // upload button
	var i = $('#uploadImage');
	var imager = $('#image');
	

	b.click(function(){
		//alert("X");
		//f.ajaxForm({
    	//	beforeSend: function(){
				l.show();
				b.attr('disabled', 'disabled');
		//	},
		//	success: function(e){
				l.hide();
				b.removeAttr('disabled');
				var file = i.val().split('\\').pop();
				imager.attr('src','/time_tracker/ci/uploads/' + file);
		//	},
		//	error: function(e){
				b.removeAttr('disabled');
		//	}
		//});
	});
});
</script>
<section id="page-content" class="page-content">
	<header class="page-header">
		<h1 class="page-title">Add New Client</h1>
		<nav class="page-controls-nav">
			<ul class="page-controls-list client">
				<li class="page-controls-item"><a class="view-all-link" href="view_clients">View All</a></li>
			</ul>
		</nav>
	</header>

<!-- preview action or error msgs -->
		<form action="<?php echo site_url('client_add_controller/insert_client')?>" id="form" method = "post" style="margin-bottom:50px;" enctype="multipart/form-data">
		 
		
		
		<figure class="client-logo l-col-20">
			<img class="client-logo-img small" id="image" src="/time_tracker/ci/uploads/default.jpg" title="Client/Company name logo" alt="Client/Company name logo" />
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
				<?php echo validation_errors(); ?>
				<ul class="details-list client-details-list">
		   			<li class="client-details-item name">
						<label for="client_name" class="client-details-label">Client's name:</label>
						<input id="client-name" name="client-name" class="client-name-input" type="text" tabindex="1" value="<?php echo set_value('client-name'); ?>"/><br />
						<label for="contact-info-sync" class="client-details-label">Client and contact the same:</label>
						<input id="contact-info-sync" name="contact-info-sync" class="contact-info-sync-input" type="checkbox" tabindex="11" value="info-sync" />
					</li>
					<li class="client-details-item phoneNum">
						<label for="client-phone" class="client-details-label">Phone number:</label>
						<input id="client-phone" name="client-phone" class="client-phone-input" type="text" tabindex="2" value="<?php echo set_value('client-phone'); ?>"/>
					</li>
					<li class="client-details-item email">
						<label for="client-email" class="client-details-label">Email address:</label>
						<input id="client-email" name="client-email" class="client-email-input" type="text" tabindex="3" value="<?php echo set_value('client-email'); ?>" />
					</li>
					<li class="client-details-item fax">
						<label for="client-fax" class="client-details-label">Fax number:</label>
						<input id="client-fax" name="client-fax" class="client-fax-input" type="text" tabindex="4" value="" value="<?php echo set_value('client-fax'); ?>"/>
					</li>
					<li class="client-details-item address">
						<label for="client-streetAddress" class="client-details-label">Street Address:</label>
						<textarea id="client-streetAddress" name="client-streetAddress" class="client-streetAddress-input" tabindex="5" value="<?php echo set_value('client-streetAddress'); ?>"></textarea><br />
						<label for="client-city" class="client-details-label" >City:</label>
						<input id="client-city" name="client-city" class="client-city-input" type="text" tabindex="6" value="<?php echo set_value('client-city'); ?>"/><br />
						<label for="client-state" class="client-details-label">State:</label>
						<select id="client-state" name="client-state" class="client-state-select" tabindex="7" value="<?php echo set_value('client-state	'); ?>">
							<option selected="selected" value="default">Select state</option>
							<option value="AL">Alabama</option>
							<option value="AK">Alaska</option>
							<option value="AZ">Arizona</option>
							<option value="AR">Arkansas</option>
							<option value="CA">California</option>
							<option value="CO">Colorado</option>
							<option value="CT">Connecticut</option>
							<option value="DE">Delaware</option>
							<option value="FL">Florida</option>
							<option value="GA">Georgia</option>
							<option value="HI">Hawaii</option>
							<option value="ID">Idaho</option>
							<option value="IL">Illinois</option>
							<option value="IN">Indiana</option>
							<option value="IA">Iowa</option>
							<option value="KS">Kansas</option>
							<option value="KY">Kentucky</option>
							<option value="LA">Louisiana</option>
							<option value="ME">Maine</option>
							<option value="MD">Maryland</option>
							<option value="MA">Massachusetts</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MS">Mississippi</option>
							<option value="MO">Missouri</option>
							<option value="MT">Montana</option>
							<option value="NE">Nebraska</option>
							<option value="NV">Nevada</option>
							<option value="NH">New Hampshire</option>
							<option value="NJ">New Jersey</option>
							<option value="NM">New Mexico</option>
							<option value="NY">New York</option>
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="OH">Ohio</option>
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VT">Vermont</option>
							<option value="VA">Virginia</option>
							<option value="WA">Washington</option>
							<option value="WV">West Virginia</option>
							<option value="WI">Wisconsin</option>
							<option value="WY">Wyoming</option>
							<option value="DC">Washington DC</option>
							<option value="PR">Puerto Rico</option>
							<option value="VI">U.S. Virgin Islands</option>
							<option value="AS">American Samoa</option>
							<option value="GU">Guam</option>
							<option value="MP">Northern Mariana Islands</option>
						</select><br />
						<label for="client-zip" class="client-details-label">Zip code:</label>
						<input id="client-zip" name="client-zip" class="client-zip-input" type="text" tabindex="8" /><br />
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
				</ul>
			</fieldset>
			<fieldset class="contact-details-entry">
				<legend class="contact-details-title">Enter contact details:</legend>
				<h4 class="required">= Required</h4>
				<ul class="details-list client-details-list">
					<li class="client-details-item name">
						<label for="contact-name" class="client-details-label">Your contact's name:</label>
						<input id="contact-name" name="contact-name" class="contact-contact-info-input" type="text" tabindex="12" /><br />
						<label for="contact-primary" class="client-details-label">This the primary contact: </label>
						<input id="contact-primary" name="contact-primary" class="contact-info-input" type="checkbox" checked="checked" tabindex="13" value="1" />
					</li>
					<li class="client-details-item phoneNum">
						<label for="contact-officePhone" class="client-details-label">Office phone:</label>
						<input id="contact-officePhone" name="contact-officePhone" class="contact-contact-info-input" type="text" tabindex="14" value="" /><br />
						<label for="contact-mobilePhone" class="client-details-label">Mobile phone:</label>
						<input id="contact-mobilePhone" name="contact-mobilePhone" class="contact-info-input" type="text" tabindex="15" value="" />
					</li>
					<li class="client-details-item email">
						<label for="contact-email" class="client-details-label">Email:</label>
						<input id="contact-email" name="contact-email" class="contact-contact-info-input" type="text" tabindex="16" value="" />
					</li>
					<li class="client-details-item fax">
						<label for="contact-fax" class="client-details-label">Fax:</label>
						<input id="contact-fax" name="contact-fax" class="contact-contact-info-input" type="text" tabindex="17" value="" />
					</li>
				</ul>
			</fieldset>
			<fieldset class="client-details-entry">
				<ul class="details-list client-details-submit">
					<li class="client-details-item submit-client">
						<label for="client-add-btn" class="client-details-label">All done?</label>
						<!--modified field to be of type submit instead of button-->
                        <input id="client-add-btn" name="client-add-btn" class="client-add-btn" type="submit" value="+ Add Client" tabindex="11"/> 
						<!--input id="client-add-btn" name="client-add-btn" class="client-add-btn" type="button" value="+Add Client" tabindex="11" /-->
						<!--end change--> 
						 or <a class="" href="#" tabindex="11">Cancel</a>
					</li>
				</ul>
			</fieldset>
		</section>
	</section>
</section>
</form>
		
</html>