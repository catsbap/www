$(document).ready( function() {
	var infoSyncBtn = $( '#contact-info-sync' );

	infoSyncBtn.click( function() {
		
		var clientInfo = $( '.client-contact-info-input' );
		var contactInfo = $( '.contact-contact-info-input' );
		var contactData = [];
		if (this.checked) {
			clientInfo.each( function( index ) {
				contactData.push( $( this ).val() );
			});
			contactInfo.each( function( index ) {
				$( this ).val( contactData[index] );
			});
		} else {
			contactInfo.each( function( index) {
				$( this ).val( "" );
			});
		}
	});
	

	$( '#add-additional-link' ).click( function( evt ) {
		var addNewContactLink = $( this ).parent().detach();
		var newContactDetailsForm = $( '.contact-details-entry' ).last().clone( true );
		
		newContactDetailsForm.find( '[type="checkbox"]' ).prop( 'checked', false );
		newContactDetailsForm.children('.contact-details-list').append( addNewContactLink );
		newContactDetailsForm.appendTo('#contact-detail').slideDown( 'slow' );
		//$( '#contact-details' ).append('<p>');
		//$('.page-title').clone().appendTo('.page-header');
		
		evt.preventDefault();
	});
	
	$( '#cancel-add-contact-link' ).click( function( evt ) {
		console.log('cancel');
		//$( '.contact-details-list' ).find( '[type="checkbox"]' ).prop( 'checked', false );
		//$ ( this ).prop( 'checked', true )
		if ( $( this ).parent() )
		if ( $( this ).parent().next().has( '#add-additional-link' ) ) {
			var addNewContactLink = $( this ).parent().next( ).detach();
			$( this ).parents( '.contact-details-entry' ).remove();
			$( '#contact-detail' ).last().find( '.contact-details-list' ).append( addNewContactLink );
		}
		
		evt.preventDefault();
	});
	
});