/******************************************************************************
 * Validate.js                                                                *
 *****************************************************************************/

// When document loads, excecute anonamous function
$(document).ready( function() {
	
	//Hide the _validated and _failed images (checkbox and x'ed out circle)
	$('.failed').hide();
	$('.validated').hide();
	
	// We can assume the first form on a given page is the one we are after.
	// Grab the ID of this form and store it in formID.
	var formID = "#" + $(document).find('form')[0].name;
	var validator = $(formID).validate({
		
		// Override the default submit action
		submitHandler: function() {
			var validationFailed = 0;
			// We operate under the assumption that we only want to validate the
			// length of the "required" fields to be non zero.
			// To do this, every field we wish to validate should be a member of the 
			// css class "required".
			// The following blurb will find all such elements and verify there length
			// to be 0.
			// WARNING: Only attach the tag to fields that contain a value with a 
			// length property.
			$(document).find('.required').each(function( i, key ) {
				if( $(key).attr('value').length == 0)
					validationFailed = 1;
			});
		
			if( validationFailed ) {
				$('#error_rows').text('Please fill out at least one value from each row').show().css('color', 'red');
			}		
			// Only do the following if we are on the Upload page. Otherwise
			// this doesn't make sense.
			else if(formID == "#upload_form")
				readyUploadForm();
			else 
				$(document).find('form')[0].submit();
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
			
		
	});
});
