/******************************************************************************
 * Validate.js                                                                *
 *****************************************************************************/

// When document loads, excecute anonamous function
$(document).ready( function() {
	
	//Hide the _validated and _failed images (checkbox and x'ed out circle)
	$('.failed').hide();
	$('.validated').hide();
	
	//Create a new validator for the upload form
	var validator = $('#upload_form').validate({

		//Overwrite default submit action
		submitHandler: function() {
		    
		    var data = new Array();
			
			if($('#team').val() !='Choose...' && $('#testType').val() != 'Choose...' && $('#sessionLoc').val() != 'Choose...') {
			    if( $('#pH').val() != '' || $('#temp').val() != '' || $('#disox').val() != '' 
			        || $('#vernierClarity').val() != '' || $('#secchiClarity').val() != '' || $('#airTemp').val() != '' ||
			         $('#copper').val() != '' || $('#phosphorus').val() != '' ) {
			             $('input').each(function() {
			                data[data.length] = $(this).attr('id');
			             });

			             var poppers = 0;
			             
			             for( emptys in data ) {
			                if( data[emptys] == undefined )
			                    poppers++;
			             }
			             
			             for( var i = data.length; i > (data.length - poppers); i--) {
			                data.pop();
			                poppers--;
		                }
			                
			            var dataString = '';
			                
			            for(keys in data) { 
			                if( $('#'+data[keys]).val() != '' )
			                    dataString += data[keys] + '=' + $('#'+data[keys]).val() + '&';
			                else
			                    dataString += data[keys] + '= &';
			            }
			            
			            dataString += 'team=' + $('#team').val() + '&';
			            dataString += 'testType=' + $('#testType').val() + '&';
			            dataString += 'sessionLoc=' + $('#sessionLoc').val();
			            
			            $.ajax({
			                type: 'POST',
			                url: '/tsor.php',
			                data: dataString,
			                success: function(msg) {
			                    alert('Successfully added data');
			                }
			            });
			            
			         }
		    }			    
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