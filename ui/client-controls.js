$(document).ready( function() {
	var $infoSyncBtn = $( '#contact-info-sync' );

	$infoSyncBtn.click( function() {
		
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
	
	
	/*
var $cancelContact = $( '<li class="client-details-item cancel-additional"></li>' );
	var $cancelContactLabel = $( '<label for="cancel-contact-link" class="client-details-label">Need to remove contact?</label>' )
		.appendTo( $cancelContact );
	var $cancelContactLink = $( '<a id="cancel-contact-link" class="cancel-action-link" href="#" tabindex="19">Cancel</a>' )
		.click( function( evt ) {
			console.log('cancel');
			//$( '.contact-details-list' ).find( '[type="checkbox"]' ).prop( 'checked', false );
			//$ ( this ).prop( 'checked', true )
			//if ( $( this ).parent() )
			/*
	if ( $( this ).parent().next().has( '#add-additional-link' ) ) {
				var addNewContactLink = $( this ).parent().next( ).detach();
				$( this ).parents( '.contact-details-entry' ).remove();
				$( '#contact-detail' ).last().find( '.contact-details-list' ).append( addNewContactLink );
			}
	*/
			
/*			evt.preventDefault();
		})
		.appendTo( $cancelContact );
*/
	
/*

	$( '#add-additional-link' ).click( function( evt ) {
		var $newContactDetailsForm = $( '.contact-details-entry' )
			.last()
			.clone( true );

		$( this )
			.parent()
			.replaceWith( $cancelContact );
		
		$newContactDetailsForm
			.find( '[type="checkbox"]' )
			.prop( 'checked', false )
			.end()
			.find( '[type="text"]' )
			.val( '' );
		
		$( '.contact-details-entry' )
			.last()
			.after( $newContactDetailsForm );
		
		evt.preventDefault();
	});
*/
	
	
	
});