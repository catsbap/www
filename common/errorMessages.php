<?php
//this file can be internationalized later, it currently uses client_currency_index (1).
//once it is internationalized, don't overload this field like this, use a language preference instead.
function getErrorMessage($client_currency_index, $errorMessage, $errorType) {
	$errorArray = array();
	
	switch($client_currency_index) {
		case 1:
			//echo $errorMessage;
			//echo $errorType;
			//errorType "missing"
			$errorArray["client_name"]["required"] = "You did not fill out the client name field.";
			$errorArray["client_phone"]["required"] = "You did not fill out the client phone number field.";
			$errorArray["client_email"]["required"] = "You did not fill out the client email field.";
			$errorArray["client_address"]["required"] = "You did not fill out the client address field.";
			$errorArray["client_city"]["required"] = "You did not fill out the client city field.";
			$errorArray["client_zip"]["required"] = "You did not fill out the client zip field.";
			$errorArray["client_logo_link"]["required"] = "You didn't upload a file.";
<<<<<<< HEAD
=======
			$errorArray["contact_name"]["required"] = "Please enter a contact for this client. All clients must have at least one contact.";
>>>>>>> upstream/master
			//errorType "invalid input"
			$errorArray["client_email"]["invalid_input"] = "Please check you entered a valid email address.";
			$errorArray["client_phone"]["invalid_input"] = "Please check you entered a valid phone number in format XXX-XXX-XXXX.";
			$errorArray["client_zip"]["invalid_input"] = "Please check you entered a valid zip code in format XXXXX.";
			//errors for file upload
			$errorArray["client_logo_link"]["invalid_file"] = "You may only upload JPEG images.";
			$errorArray["client_logo_link"]["upload_problem"] = "Something went wrong uploading your image.";
			return $errorArray[$errorMessage][$errorType];
			break;
		default:
			return "You entered an invalid language index.";
	}
}
?>