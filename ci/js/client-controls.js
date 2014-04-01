
$(document).ready( function() {
	//this is the checkbox for client same as contact
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
		//alert(attr.charAt(attr.length - 1));
		//RRRRRRRRRRRRRRRRRRR
		//ALRIGHT, I GIVE UP ON THIS.
		//I HAVE NO IDEA HOW THIS IS SUPPOSED TO WORK, 
		//COME BACK LATER AND TRY TO COMMENT IT.
		//alert(parseInt( attr.charAt( attr.length - 1)));
		if ( !isNaN(parseInt( attr.charAt( attr.length - 1 ) ) ) ) {
			attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
			alert(attr);
		}
		return attr += "-" + contactCtr;
	};
	
	//get the primary contacts and select the one clicked, uncheck the rest. This is based on the .contact-details-class
	$( '#contact-primary' ).click( function( evt ) {
		alert("y");
		var $primeContacts = $( '.contact-details-entry input[type="checkbox"]' );
		$primeContacts.each( function( index ) {
			$( this ).prop( 'checked', false );
		})
		$( this ).prop( 'checked', true );
	});
	
	//look for the #contact_details ID.
	var $contactInputs = $( '#contact-details' );
	//grab the ids
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

	//use the .cancel_additional link
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
					//alert(attr);
					return attr;
				})
				.find( 'input, a' )
				.each( function( index ) {
					$( this ).attr( 'id', function (i, attr) {
						idNum = parseInt( attr.slice( attr.lastIndexOf( '-' ) + 1 ) ) - 1;
						attr = attr.substring( 0, attr.lastIndexOf( '-' ) );
						attr += "-" + idNum;
						//alert(attr);
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

	//add additional
	$( '#add-additional-link' ).click( function( evt ) {
	//alert("X");
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