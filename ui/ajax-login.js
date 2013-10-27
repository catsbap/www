$( document ).ready( function() {
	var $message = $( '<p></p>' )
		.insertBefore( '#user-login' )
		.hide();


	$( '#user-login' ).submit( function( evt ) {
		$message.hide()
			.removeClass();
			
		if ( ( $( '#username' ).val() != '' ) && ( $( '#password' ).val() != '' ) ) {
			$.ajax({
				type: 'POST',
				url: 'check_login.php',
				data: "username=" + $( '#username' ).val() + "&password=" + $( '#password' ).val( ),
				success: function( response ) {
					console.log(response);
					if ( response == '1' ) {
						$message.html( "Login sucessful!" )
							.removeClass()
							.addClass( 'success' )
							.fadeIn();
							
						setTimeout( function() {
							self.location = 'index.php?login=1&data=' + $( '#username' ).val( );
						}, 1000 );
					} else if ( response == '2' ) {
						$message.html( "Login unsucessful. There was an error with the user name or password." )
							.removeClass()
							.addClass( 'error' )
							.fadeIn();
					}
				}
			});
		} else {
			$message.html( "Please enter a user name and a password to login to Time Tracker." )
				.removeClass()
				.addClass( 'error' )
				.fadeIn();
		}
		evt.preventDefault();
	});

/*
function check_login(){
    $.ajax({
        type:'POST',
        url:'check_login.php',
        data:"username="+$('#username').val()+"&password="+$('#password').val(),
        success:function(response){
            //if(response=='1'){$('#error').css({'color':'#0c0','display':'block'}).html('CORRECT!')}
            if(response=='1'){self.location='index.php?login=1&data='+$('#username').val()}
            else if(response=='2'){$('#error').css({'color':'red','display':'block'}).html('Login credentials incorrect!')}
            else if(response=='3'){$('#error').css({'color':'red','display':'block'}).html('Please fill in all fields')}
        }
    });
*/
});