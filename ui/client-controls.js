;
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
	
	var contactCtr = 0;
	var incrementIDs = function( i, attr ) {
		//console.log(!isNaN(parseInt( attr.charAt( attr.length - 1 ) )));
		if ( !isNaN(parseInt( attr.charAt( attr.length - 1 ) ) ) ) {
			attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
		}
		return attr += "-" + contactCtr;
	};
	
	$( '#contact-primary' ).click( function( evt ) {
		var $primeContacts = $( '.contact-details-entry input[type="checkbox"]' );
		$primeContacts.each( function( index ) {
			$( this ).prop( 'checked', false );
		})
		$( this ).prop( 'checked', true );
	});
	
	var $contactInputs = $( '#contact-details' );
	$contactInputs.attr( 'id', incrementIDs )
		.find( 'input, a' )
		.each( function( index ) {
			$( this ).attr( 'id', incrementIDs );
		})
		.end()
		.find( 'label' )
		.each( function( index ) {
			$( this ).attr( 'for', incrementIDs );
	});

	var $cancelContact = $( '.cancel-additional' )
		.click( function( evt ) {
			var $updatePrime;
			if ( $( this ).parents( '.contact-details-entry' ).siblings( '.contact-details-entry' ).not( '#contact-save' ).length < 2 ) {
				$( '.cancel-additional' )
					.addClass( 'disabled' )
					.parents( '.contact-details-entry' )
					.find( 'input[type=checkbox]' )
					.prop( 'checked', true );
			}
			if ( $( this ).parents( '.contact-details-entry' ).find( 'input[type="checkbox"]' ).prop( 'checked' ) ) {
				if ( $( this ).parents( '.contact-details-entry' ).prev( '.contact-details-entry' ).length > 0 ) {
					$updatePrime = $( this ).parents( '.contact-details-entry' ).prev( '.contact-details-entry' );
				} else if ( $( this ).parents( '.contact-details-entry' ).next( '.contact-details-entry' ).length > 0 ) {
					$updatePrime = $( this ).parents( '.contact-details-entry' ).next( '.contact-details-entry' );
				} else {
					$updatePrime = $( '.contact-details-entry' ).first();
				}
				$updatePrime.find( 'input[type=checkbox]' )
					.prop( 'checked', true );
			}
			$( this ).parents( '.contact-details-entry' ).nextAll().not( '#contact-save' )
				.attr( 'id', function (i, attr) {
					idNum = parseInt( attr.slice( attr.lastIndexOf( '-' ) + 1 ) ) - 1;
					attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
					attr += "-" + idNum;
					//console.log(attr);
					return attr;
				})
				.find( 'input, a' )
				.each( function( index ) {
					$( this ).attr( 'id', function (i, attr) {
						idNum = parseInt( attr.slice( attr.lastIndexOf( '-' ) + 1 ) ) - 1;
						attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
						attr += "-" + idNum;
						//console.log(attr);
						return attr;
					})
				})
				.end()
				.find( 'label' )
				.each( function( index ) {
					$( this ).attr( 'for', function (i, attr) {
						idNum = parseInt( attr.slice( attr.lastIndexOf( '-' ) + 1 ) ) - 1;
						attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
						attr += "-" + idNum;
						//console.log(attr);
						return attr;
					})
				})
			$( this ).parents( '.contact-details-entry' ).remove();
		});
		if ( $cancelContact.length <= 1 ) {
			$cancelContact.addClass( 'disabled' );
		}

	$( '#add-additional-link' ).click( function( evt ) {
		contactCtr = $( '.contact-details-entry' ).not( '#contact-save' ).length;
		$cancelContact.removeClass( 'disabled' );
		var $newContactDetailsForm = $( this )
			.parents( '.contact-details-entry' )
			.prev()
			.clone( true );
		
		$newContactDetailsForm
			.attr( 'id', incrementIDs )
			.find( 'input, a' )
			.each( function( index ) {
				$( this ).attr( 'id', incrementIDs );
			})
			.end()
			.find( 'label' )
			.each( function( index ) {
				$( this ).attr( 'for', incrementIDs );
				//console.log($(this).attr('for'));
			});
		
		$newContactDetailsForm
			.find( '[type="checkbox"]' )
			.prop( 'checked', false )
			.end()
			.find( '[type="text"]' )
			.val( '' );
		
		$( '.contact-details-entry' )
			.last()
			.before( $newContactDetailsForm );
		
		
		
		$( '#contact-save-btn' ).val( '+ Save Contacts' );
		
		evt.preventDefault();
	});
	
	$( '#client-info' ).submit( function( evt ) {
		console.log( $( this ).serializeArray() );
		evt.preventDefault();
	});
});