/******************************************************************************
 * Validate.js                                                                *
 *****************************************************************************/

// When document loads, excecute anonamous function
$(document).ready( function() {
	
	//Hide the _validated and _failed images (checkbox and x'ed out circle)
	$('#session_name_validated').hide();
	$('#session_description_validated').hide();
	$('#session_street_validated').hide();
	$('#session_citystate_validated').hide();
	$('#session_name_failed').hide();
	$('#session_description_failed').hide();
	$('#session_street_failed').hide();
	$('#session_citystate_failed').hide();
	
	//Create a new validator for the upload form
	var validator = $('#upload_form').validate({

		//Overwrite default submit action
		submitHandler: function() {
			
			srchtable = $(this.currentForm).find('table');
			
			rows = srchtable.children().children();
			
			oneperrow = new Array();

			rows.each( function(index, dom) {
				
				if( index > 1 )
					$(dom).find('input').each(function( i, key ) {
						if( $(key).val().length )
						oneperrow[index - 2] = 1;
					});
				
			});
			
			var fail = 0;
			
			for( x = 0; x < oneperrow.length; x++) {
				if( oneperrow[x] == undefined )
					fail = 1;
			}
			
			if( !fail )
				readyUploadForm();
			else
				$('#error_rows').text('Please fill out at least one value from each row').show().css('color', 'red');
		},

		//Overwrite default error function
		errorPlacement: function( error, element ) {
			$(element).css('background-color', '#ff6666');
			$('#' + $(element).attr('id') + '_validated').hide();
			$('#' + $(element).attr('id') + '_failed').show();
			$('#' + $(element).attr('id') + '_hint').text(error.text());
		},
		
		//Overwrite default success function
		success: function ( evt ) {
			$('#' + evt.attr('for')).css('background-color', '#ccff99');
			$('#' + evt.attr('for') + '_failed').hide();
			$('#' + evt.attr('for') + '_validated').show();
		}
		
	})
});