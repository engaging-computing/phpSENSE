Date.format = 'yyyy-mm-dd 00:00:00';
$(document).ready(function(){
		//console.log('hiya!');
	/*
    $('.button').raptorize({
	    'enterOn' : 'timer'
	});*/
	
	
    runLightboxInit();
    if($('#feature_experiment').length > 0) {
        $('#feature_experiment').click(function(){
            $('#feature_experiment').hide();
            $('#loading_msg').show();
            if($(this).attr("checked")) {
                // Make Featured
                $.get('actions/experiments.php', { action:"addfeature", id:$(this).val() }, function(data){
                    $('#feature_experiment').show();
                    $('#loading_msg').hide();
                });
            }
            else {
                // Remove Feature
                $.get('actions/experiments.php', { action:"removefeature", id:$(this).val() }, function(data){
                    $('#feature_experiment').show();
                    $('#loading_msg').hide();
                });
            }
        });
    }


    
    if($('img.selectexpimage').length > 0){
        $('img.selectexpimage').click(function(){
            $('img.selectexpimage').css( 'border', '0px none #fff' );
            $('img.selectexpimage').css( 'margin', '5px' );
            $('img.selectexpimage').css( 'padding', '0px')
            $(this).css( 'border', '4px solid #2396e6' );
            $(this).css( 'padding', '1px' );
            $(this).css( 'margin', '0px' );
            $(this).css( 'background', '#fff');
            //alert( "URL: " + $(this).attr("src") + "\nEXP ID: " + $('#storedexpid').val() );
            $.get('actions/experiments.php', { action:"changeimage", purl:$(this).attr("src"), eid:$('#storedexpid').val() }, function(data){});
        });
    }
    
    if($('input.feature_experiment').length > 0) {
        $('input.feature_experiment').click(function(){
            //$('input.feature_experiment').hide();			This stuff is commented out because it
            //$('loading_msg').show();						hides/shows all boxes. It looks weird.
            if($(this).attr("checked")) {
				$('#pickimage_' + $(this).val()).show();
                // Make Featured
                $.get('actions/experiments.php', { action:"addfeature", id:$(this).val() }, function(data){
                    //$('input.feature_experiment').show();
                    //$('loading_msg').hide();
                });
            }
            else {
				$('#pickimage_' + $(this).val()).hide();
                // Remove Feature
                $.get('actions/experiments.php', { action:"removefeature", id:$(this).val() }, function(data){
                    //$('input.feature_experiment').show();
                    //$('loading_msg').hide();
                });
            }
        });
    }

	if($('input.feature_vis').length > 0){
		$('input.feature_vis').click(function(){
			//$('input.feature_vis').hide();				This stuff is commented out because it
            //$('loading_msg').show();						hides/shows all boxes. It looks weird.
			if($(this).attr("checked")) {
				$('#pickimage_' + $(this).val()).show();
				$.get('browse.php', { action:"addfeature", id:$(this).val() }, function(data){
                    //$('input.feature_vis').show();
                    //$('loading_msg').hide();
                });
			} else {
				$('#pickimage_' + $(this).val()).hide();
				$.get('browse.php', { action:"removefeature", id:$(this).val() }, function(data){
                   //$('input.feature_vis').show();
                   //$('loading_msg').hide();
                });
			}
		})
	}
	
	if($('img.selectvisimage').length > 0){
		$('img.selectvisimage').click(function(){
			$('img.selectvisimage').css( 'border', '0px none #fff' );
			$('img.selectvisimage').css( 'margin', '5px' );
			$('img.selectvisimage').css( 'padding', '0px')
			$(this).css( 'border', '4px solid #2396e6' );
			$(this).css( 'padding', '1px' );
			$(this).css( 'margin', '0px' );
			$(this).css( 'background', '#fff');
			//alert( "URL: " + $(this).attr("src") + "\nVIS ID: " + $('#storedvisid').val() );
			$.get('/actions/vis.php', { action:"changeimage", purl:$(this).attr("src"), vid:$('#storedvisid').val() }, function(data){});
		});
	}
	
	if($('img.selectexpimage').length > 0){
		$('img.selectexpimage').click(function(){
			$('img.selectexpimage').css( 'border', '0px none #fff' );
			$('img.selectexpimage').css( 'margin', '5px' );
			$('img.selectexpimage').css( 'padding', '0px')
			$(this).css( 'border', '4px solid #2396e6' );
			$(this).css( 'padding', '1px' );
			$(this).css( 'margin', '0px' );
			$(this).css( 'background', '#fff');
			//alert( "URL: " + $(this).attr("src") + "\nVIS ID: " + $('#storedexpid').val() );
			$.get('/actions/experiments.php', { action:"changeimage", purl:$(this).attr("src"), eid:$('#storedexpid').val() }, function(data){});
		});
	}
    
    if($('#hide_experiment').length > 0) {
        $('#hide_experiment').click(function(){
            $('#hide_experiment').hide();
            $('#hidden_loading_msg').show();
            if($(this).attr("checked")) {
                // Make Featured
                $.get('actions/experiments.php', { action:"hide", id:$(this).val() }, function(data){
                    $('#hide_experiment').show();
                    $('#hidden_loading_msg').hide();
                });
            }
            else {
                // Remove Feature
                $.get('actions/experiments.php', { action:"unhide", id:$(this).val() }, function(data){
                    $('#hide_experiment').show();
                    $('#hidden_loading_msg').hide();
                });
            }
        });
    }

    if($('#close_experiment').length>0){
        $('#close_experiment').click(function(){
            $('#close_experiment').hide();
            $('#close_loading_msg').show();
            
            if($(this).attr("checked")) {
                // Make closed
                $.get('actions/experiments.php', { action:"closeExp", id:$(this).val() }, function(data){
                    $('#close_experiment').show();
                    $('#close_loading_msg').hide();
                    $('#contribute').hide();
             
                });
            }
            else {
                // unclose
                $.get('actions/experiments.php', { action:"uncloseExp", id:$(this).val() }, function(data){
                    $('#close_experiment').show();
                    $('#close_loading_msg').hide();
                    $('#contribute').show();
                  
                });
            }
        });
    }

    if($('#recommend_experiment').length>0){
        $('#recommend_experiment').click(function(){
            $('#recommend_experiment').hide();
            $('#recommend_loading_msg').show();

            if($(this).attr("checked")) {
                // Recommend the experiment
                $.get('actions/experiments.php', { action:"recommend", id:$(this).val() }, function(data){
                    $('#recommend_experiment').show();
                    $('#recommend_loading_msg').hide();
                });
            }
            else {
                // Unrecommend the experiment
                $.get('actions/experiments.php', { action:"unrecommend", id:$(this).val() }, function(data){
                    $('#recommend_experiment').show();
                    $('#recommend_loading_msg').hide();
                });
            }
        });
    }
    
    if($('#session_hidden').length > 0) {
        /*$('#session_hidden').click(function(){
            if($(this).attr("checked")) {
                // Make Featured
                $.get('actions/experiments.php', { action:"hideSes", id:$(this).attr('name') }, function(data){
                    if (typeof console == "object") {
                        console.log(data);
                    }
                });
            } else {
                // Remove Feature
                $.get('actions/experiments.php', { action:"unhideSes", id:$(this).attr('name') }, function(data){
                    if (typeof console == "object") {
                        console.log(data);
                    }
                });
            }
        });*/
    }

	if($('.star').length > 0) {
	    for(var i = 1; i < 6; i++) {
    		if(!$('#star'+i).hasClass('unclickable')) {
    			$('#star'+i).click(function(){
    					var id = this.id;
    					var score = id.substring(4,5);

    					$.get('actions/experiments.php', { action:"rate", id:$('#feature_experiment').val(), value:score },
    								function(data) {
    									if($('div#rating_prompt').length > 0) {
    										$('div#rating_prompt').text('Thank you for rating!');
    									}
    								});
    			});
    		}
    	}
    }

	if($('#rating').length > 0) {
		var rate = parseInt($('#rating').val()) + 1;
		for(var i = 1; i < rate; i++) {
			$("#star"+i).addClass("star-rating-hover");
		}
	}

	$('#datetime').datepicker({
		altField:'#date'
	});
    
    if($('#experiment_tags').length) {
        $("#experiment_tags").autocomplete("actions/create.php", {multiple: true, autoFill: true});
    }
    
    if($('#start').length > 0) {
        $('#start').datepicker();
    }
    
    if($('#end').length > 0) {
        $('#end').datepicker();
    }
    
    if($('#file_upload').length > 0) {
        $('#file_upload').click(function(){
            $('#type_file').toggle();
            $('#type_manual').toggle();
        });
    }
    
    if($('#manual_upload').length > 0) {
        $('#manual_upload').click(function(){
            $('#type_file').toggle();
            $('#type_manual').toggle();
        });
    }
    
    if($('#custom_field_type_1').length > 0){
      $('#custom_field_type_1').change(filterUnits);
    }
    

		$('div.rating_canel').css('display', 'none');
});

function createActivity(exp_id) {
    var sessionList = Array();
    $(document).find('input[name=sessions]:checked').each(function(i){ sessionList.push($(this).val()); });
    
    if(sessionList.length == 0) {
         alert("You did not select any sessions to add to your activity. Please select at least 1 session then click 'Activity'");
    }
    else {
      var url = 'http://' + window.location.host + '/create-activity.php?eid='+exp_id+'&sessions='+sessionList.join(',');
      window.location = url;
    }
}

function loadExport(exp_id) {
    var sessionList = Array();
    
    $(document).find('input[name=sessions]:checked').each(function(i){ sessionList.push($(this).val()); });
    
    if(sessionList.length == 0) {
        alert("You did not select any sessions to Export. Please select at least 1 session then click 'Export'");
    }
    else {
        var url = 'http://' + window.location.host + '/actions/package.php?eid=' + exp_id + '&sessions=' + sessionList.join(',');
        window.open(url, 'Download');
    }
}

function loadVis(exp_id, is_activity) {
    var sessionList = Array();
    
    $(document).find('input[name=sessions]:checked').each(function(i){ sessionList.push($(this).val()); });
    
    if(sessionList.length == 0) {
        alert("You did not select any sessions to visualize. Please select at least 1 session then click 'Visualize'");
    }
    else {
        var url = 'vis.php?sessions='+sessionList.join('+');
        
        if(is_activity == true) {
            url = url + '&aid='+exp_id;
        }
        
        window.location.href = url;
    }
}

function loadVis2(exp_id, is_activity) {
    var sessionList = Array();
    
    $(document).find('input[name=sessions]:checked').each(function(i){ sessionList.push($(this).val()); });
    
    if(sessionList.length == 0) {
        alert("You did not select any sessions to visualize. Please select at least 1 session then click 'Visualize'");
    }
    else {
        var url = 'newvis.php?sessions='+sessionList.join('+');
        
        if(is_activity == true) {
            url = url + '&aid='+exp_id;
        }
        
        window.location.href = url;
    }
}

function readyUploadForm() {
    $('#error_msg_row').hide();
    $('#error_msg').text("");
    $('#manual_table').find('tr').each(function(i){
       if($(this).attr('id') != 'template' && $(this).attr('id') != 'error_msg_row' && $(this).attr('id') != 'header_row') {
           $(this).find('td > input').each(function(i){
                 if($(this).val() == "" && (!$('#error_msg_row').is(':visible'))) {
                     $('#error_msg_row').show();
                     $('#error_msg').append('Error: You did not fill out all of your data fields. Please check your data and try again.');
                 }
              });
       }
    });
    
    $('#upload_form').createAppend('input', { Type:'hidden', style:'display:none;', value:'work', name:'session_create', id:'session_create'}, []);
    
    if(!$('#error_msg_row').is(':visible')) {
        document.upload_form.submit();
    }
}

function gotoPage(page, tag) {
	if(tag != "" && tag != undefined) {
		window.location.href = 'browse.php?page='+page+'&tag=' + tag;
	}
	else {
		window.location.href = 'browse.php?page='+page;
	}
}

function checkAll(target) {
    $(target).find('input:checkbox').each(function(i){
        $(this).attr('checked', true);
    });
}

function uncheckAll(target) {
    $(target).find('input:checkbox').each(function(i){
        $(this).attr('checked', false);
    });
}

function justMySessions() {
    $('#mystuff > div.experiment').each(function(i){ $(this).hide(); });
    $('#mystuff > div.visualization').each(function(i){ $(this).hide(); });
    $('#mystuff > div.session').each(function(i){ $(this).show(); });
}

function justMyExperiments() {
    $('#mystuff > div.session').each(function(i){ $(this).hide() });
    $('#mystuff > div.visualization').each(function(i){ $(this).hide(); });
    $('#mystuff > div.experiment').each(function(i){ $(this).show(); });
}

function justMyVisualizations() {
    $('#mystuff > div.session').each(function(i){ $(this).hide() });
    $('#mystuff > div.experiment').each(function(i){ $(this).hide(); });
    $('#mystuff > div.visualization').each(function(i){ $(this).show(); });
}

function allMyStuff() {
    $('#mystuff > div').each(function(i){ $(this).show(); });
}

function filterProfile() {
    var filter = $('#filter').val();
    if(filter == "all") {
        $('#results > div:hidden').each(function(i){
           $(this).show(); 
        });
    }
    else {
        $('#results > div.result').each(function(i){
           if(!$(this).hasClass(filter)) {
               $(this).hide();
           }
           else {
               $(this).show();
           }
        });
    }
}

function editUserProfile() {
    if($('#edit_link').text() == 'Edit Profile') {
        $('#profile_pic').show();
        $('#edit_link').text('Save Profile');
        $('#profile').find('tr').each(function(i){
           $(this).find('td.value').hide();
           $(this).find('td.editable').show();
        });
    }
    else {
        $('#profile_form').submit();
    }
}

function removefield(parent) {
    
}

var filterUnits = function(event) {

  var type = $(event.target).find('option:selected').val();

  var target = "#" + $(event.target).attr("id").replace("type", "unit");

  $(target).find('option').each(function(i) {
    if($(this).val() != "-1") {
      $(this).remove();
    }
  });
  
  $('#custom_field_unit_xxx > option').each(function(i){
      if($(this).attr("ref") == type) {
        var x = $(this).clone();
        $(target).append(x);
      }
  });
}

function addField() {
    /*
    var org = $('tr#template').clone();
    org.attr('id', ($('#customFields').length + 1));
    org.css('display', '');
    */
    var new_row_count = parseInt($('#row_count').val()) + 1;
    $('#row_count').val(new_row_count);
    var org = $('tr#template').clone();
    org.attr('id', $('#row_count').val());
    
    org.find('td > select').each(function(i){
       var id =  $(this).attr('id');
       var name = $(this).attr('name');
       
       id = id.replace(/(xxx)/g, $('#row_count').val());
       name = name.replace(/(xxx)/g, $('#row_count').val());
       
       $(this).attr('id', id);
       $(this).attr('name', name);
    });
    
    org.find('td > input').each(function(i){
       var id =  $(this).attr('id');
       var name = $(this).attr('name');
       
       id = id.replace(/(xxx)/g, $('#row_count').val());
       name = name.replace(/(xxx)/g, $('#row_count').val());
       
       $(this).attr('id', id);
       $(this).attr('name', name);
    });
    
    org.css('display', 'table-row');
    $('#customFields').append(org);
    
    $("#custom_field_type_" + new_row_count).change(filterUnits);
}
$(document).ready( function() {
    $("#addManualRowButton").click(
        function addManualDataRow(e) {
            $('#row_count').val(parseInt($('#row_count').val()) + 1);
            var org = $('tr#template').clone();
            org.attr('id', $('#row_count').val());
            
            org.find('td > input').each(function(i){
                var id =  $(this).attr('id');
            var name = $(this).attr('name');
            
            //console.log(id);
            
            if( id == 'Time_xxx' || id == 'time_xxx' )
                $(this).attr('class', 'time');
            
            id = id.replace(/(xxx)/g, $('#row_count').val());
            name = name.replace(/(xxx)/g, $('#row_count').val());
            
            $(this).attr('id', id);
            $(this).attr('name', name);
            $(this).addClass('required');
            //$(this).addClass('numeric');
            });
            
            org.css('display', '');
            $('#manual_table').append(org);
            
            $("#removeManualRowButton").removeAttr('disabled');
        });
        
        $("#removeManualRowButton").click(
            function removeManualDataRow(e) {
                if ($('#row_count').val() > 1)
                {
                    $('#row_count').val(parseInt($('#row_count').val()) - 1);
                    
                    $('#manual_table tr:last').remove();
                    
                    if ($('#row_count').val() == 1)
                    {
                        $("#removeManualRowButton").attr('disabled', 'disabled');
                    }
                }
            }
		);
		
		$(".checkboxformsubmitter").click(function(){
			
			$("#browseform").submit();
			
		});
		
		$(".selectformsubmitter").change(function(){
			
			$("#browseform").submit();
			
		});
		
});

function addLinkRow() {
    $('#row_count').val(parseInt($('#row_count').val()) + 1)
    var org = $('tr#template').clone();
    org.attr('id', $('#row_count').val());
    
    org.find('td > span > input').each(function(i){
       
       var id =  $(this).attr('id');
       var name = $(this).attr('name');
       
       id = id.replace(/(xxx)/g, $('#row_count').val());
       name = name.replace(/(xxx)/g, $('#row_count').val());
       
       $(this).attr('id', id);
       $(this).attr('name', name);
       
       $('#'+id).blur(function(){
           alert('hi!');
          $.get('actions/upload-links.php', { url:$(this).value(), div:$('#'+id) }, function(data){
              alert(data);
          });
       });
       
    });
    
    org.find('td.heading').text('Link ' + $('#row_count').val() + ':');
    
    org.css('display', '');
    $('#links_table').append(org);
}

function addImageRow() {
    var new_row_count = parseInt($('#row_count').val()) + 1;
    $('#row_count').val(new_row_count);
    
    var org = $('tr#template').clone();
    org.attr('id', $('#row_count').val());
    
    org.find('td > input').each(function(i){
       
       var id =  $(this).attr('id');
       var name = $(this).attr('name');
       
       id = id.replace(/(xxx)/g, $('#row_count').val());
       name = name.replace(/(xxx)/g, $('#row_count').val());
       
       $(this).attr('id', id);
       $(this).attr('name', name);        
    });
          
    org.css('display', '');
    $('#picture_table').append(org);
}

function submitImageUpload() {
    $("#picture_create").val("Please wait...");
}

function handle_follow(res) {
    
    if($('.start_following').length > 0) {
        $('#graph_status_wrapper').html('');
        $('#graph_status_wrapper').createAppend('button', { className:'stop_following'}, 'Unfollow');
        var b = $('#graph_status_wrapper > button').get(0);
        $(b).click(function(){stop_following(follower, followee);});
    }
    else {
         $('#graph_status_wrapper').html('');
         $('#graph_status_wrapper').createAppend('button', { className:'start_following'}, 'Follow');
         var b = $('#graph_status_wrapper > button').get(0);
         $(b).click(function(){start_following(follower, followee);});
    }

    
}

function stop_following(follower, followee) {
    
    $.ajax({
        url:'/actions/graph.php',
        data:'action=unfollow&follower='+follower+'&followee='+followee,
        success:handle_follow
    })
    
}

function start_following(follower, followee) {
    
    $.ajax({
        url:'/actions/graph.php',
        data:'action=follow&follower='+follower+'&followee='+followee,
        success:handle_follow
    })
    
}

var createWizard = {
    fields:Array(),
    
    init:function() {
        createWizard._started = false;
    },
    
    write_field:function(namex, type, unit) {
        $('#number_of_fields').val(parseInt($('#number_of_fields').val()) + 1);
        var number_of_fields = $('#number_of_fields').val();
        
        $('#data_wrapper').createAppend('input', {  type:'hidden', 
                                                    id:'field_label_' + number_of_fields, 
                                                    name:'field_label_' + number_of_fields, 
                                                    value:namex });
                                                  
        $('#data_wrapper').createAppend('input', {  type:'hidden',
                                                    id:'field_type_' + number_of_fields, 
                                                    name:'field_type_' + number_of_fields,
                                                    value:type });
        
        $('#data_wrapper').createAppend('input', {  type:'hidden',
                                                    id:'field_unit_' + number_of_fields, 
                                                    name:'field_unit_' + number_of_fields,
                                                    value:unit });
        
        $('#data_wrapper').createAppend('input', {  type:'hidden',
                                                    id:'field_conversion_' + number_of_fields, 
                                                    name:'field_conversion_' + number_of_fields,
                                                    value:unit });
    },
    
    store_field:function(namey, type, unit) {
        var spaceFix = /(\s)/g;
        var newname = namey.replace(spaceFix, '_');
        var x = new Array(newname, type, unit);
        createWizard.fields.push(x);
    },
    
    clear_field:function() {
      createWizard.fields = new Array();  
    },
    
    next_step:function() {
        if(createWizard._started == true) {
            createWizard.next();
        }
    },
    
    prev_step:function(){
        if(createWizard._started == true) {
            createWizard.prev();
        }
    },
    
    step_start:function(pinpoint) {
        createWizard._started = true;
        $('#step_start').hide();
        if(pinpoint) {
            createWizard.step_pinpoint();
        }
        else {
            createWizard.step_custom();
        }
        $('#wizard_options').show();
    },
    
    step_restart:function() {
        createWizard._started = false;
        $('#step_pinpoint').hide();
        $('#step_custom').hide();
        $('#wizard_options').hide();
        $('#step_start').show();
    },
            
    step_pinpoint:function() {
        createWizard.next = createWizard.step_pinpoint_advance;
        createWizard.prev = createWizard.step_restart;
        $('#step_pinpoint').show();
    },

    step_pinpoint_advance:function() {
	
			/*
			var labels = Array("#external_label_A");
			var boxes = Array("#external_A");

	        for(i = 0; i < labels.length; i++) {
	            if(($(labels[i]).val() == "") && $(boxes[i]).attr('checked')) {
	                if(filled) {
	                    filled = false;
	                    $('#error').show();
	                    $('#error_msg').append('Error: You did not label one of your fields. Please label it and try again.');
	                }
	            }
	        }
			*/

    		$('#error_msg_custom').children().remove();
	
			// - Check to see if there are duplicate sensors - //
				
			var errors		= false;
			var errormessage = "";
			
			var portselectarray = [];
			var porttestarray = [false,false,false,false];
			
			var ports = ['A','B','C','D'];
			
			var sensortypes = {
				
				1:  {name: 'temperature probe', type_id: 1, unit_id: 2},
				2:  {name: 'voltage', type_id: 11, unit_id: 34},
				3:  {name: 'counts', type_id: 20, unit_id: 60},
				4:  {name: 'counts', type_id: 20, unit_id: 60},
				5:  {name: 'ph', type_id: 24, unit_id: 70},
				6:  {name: 'salinity', type_id: 23, unit_id: 69},
				7:  {name: 'co2 high', type_id: 22, unit_id: 67},
				8:  {name: 'dissolved oxygen', type_id: 30, unit_id: 77},
				9:  {name: 'anenometer', type_id: 31, unit_id: 45},
				10: {name: 'turbidity', type_id: 32, unit_id: 78},
				11: {name: 'flow rate', type_id: 33, unit_id: 45},
				12: {name: 'motor monitor', type_id: 34, unit_id: 34},
				13: {name: 'conductivity', type_id: 35, unit_id: 79}
				
			};
			
			portselectarray[portselectarray.length] = $('#external_port_A').val();
			portselectarray[portselectarray.length] = $('#external_port_B').val();
			portselectarray[portselectarray.length] = $('#external_port_C').val();
			portselectarray[portselectarray.length] = $('#external_port_D').val();
			
			for(i in portselectarray){
				
				var port = parseInt(portselectarray[i])-1;
				
				if(port>=0){
				
					var used = porttestarray[port];
				
					if(used){
						
						errors = true;
						
						errormessage += "Error: Multiple sensors cannot use the same port.<br>";
						
					}
				
					porttestarray[port] = true;
				
				}
				
			};
			
			for( i in ports ) if($('#external_type_' + ports[i]).val() == 0 && $('#external_' + ports[i]).attr('checked')){
				
				errors = true;
				
				errormessage += "Error: Please select a sensor type.<br>";

			}
			
			// - end - //

            /* Clean Up Form */
				
			if(!errors){
			
				/* Add Time to fields */
	            createWizard.store_field('time', 7, 22);

	            /* Add gps to fields */
	            if($('#gps').attr('checked')) {
	                createWizard.store_field('latitude', 19, 57);
	                createWizard.store_field('longitude', 19, 58);
	            }

	            /* Add acceleration to fields */
	            if($('#acceleration').attr('checked')) {
	                createWizard.store_field('acceleration', 25, 71);
	            }

	            /* Add light to fields */
	            if($('#light').attr('checked')) {
	                createWizard.store_field('light', 9, 31);
	            }

	            if($('#temperature').attr('checked')) {
	                createWizard.store_field('air temperature', 1, 2);
	            }

				if($('#pressure').attr('checked')) {
	                createWizard.store_field('pressure', 27, 75);
	            }

				if($('#humidity').attr('checked')) {
	                createWizard.store_field('humidity', 28, 77);
	            }

				/* Add Altitude to fields */
	            if($('#altitude').attr('checked')) {
	                createWizard.store_field('altitude', 3, 5);
	            }

				// - //

				for( i in ports ){

		            /* Add External */
		            if($('#external_' + ports[i]).attr('checked')) {

						var external_port = "";

						switch(parseInt($('#external_port_' + ports[i]).val())){
							case 0:
							external_port = "";
							break;
							case 1:
							external_port = "~BTA1";
							break;
							case 2:
							external_port = "~BTA2";
							break;
							case 3:
							external_port = "~MINI1";
							break;
							case 4:
							external_port = "~MINI2";
							break;
						}
						
						var sensortype = $('#external_type_' + ports[i]).val();
					
						createWizard.store_field(sensortypes[sensortype].name + external_port, sensortypes[sensortype].type_id, sensortypes[sensortype].unit_id);

		            }
				}
			
				// - //
			
				if(!errors){
			
					createWizard.next = createWizard.step_post_process;

		            $('#step_pinpoint').hide();
		            $('#step_done').show();
		            $('#create_advance').val('Done');
		            $('#create_previous').hide();
					$('div#wizard_error').hide();
				
				}
				
			} else {
				
				$('div#wizard_error').html(errormessage);

				$('div#wizard_error').show();
				
			}
				
	    },
        
    step_custom:function() {
        createWizard.next = createWizard.step_custom_advance;
        createWizard.prev = createWizard.step_restart;
        $('#step_custom').show();
		$('#create_advance').val('Done');
    },
    
    step_custom_advance:function() {
        
        var filled = true;
        var typed = true;
        var united = true;
        
        $('#error_msg_custom').children().remove();
        
        $('#customFields').find('tr').each(function(i) {
           if($(this).attr('id') != 'template' && $(this).attr('id') != 'theader' && $(this).attr('id') != 'error_custom') {
              
               var counter = 1;
               $('#customFields').find('tr').each(function(i) {
                    if($(this).attr('id') != 'template' && $(this).attr('id') != 'theader' && $(this).attr('id') != 'error_custom') {
                        var label = $(this).find("input[name='custom_field_label_"+counter+"']").val();
		                counter++;
                        if(label == "") {
                            if(filled) {
                                filled = false;
                                if(!$('#error_custom').is(':visible')) { 
                                    $('#error_custom').show(); 
                                }
                                $('#error_msg_custom').append('<p>Error: You did not label one of your fields. Please label it and try again.</p>');
                            }
                        }
                    }
               });

              var counter = 1;
              $('#customFields').find('tr').each(function(i) {
                   if($(this).attr('id') != 'template' && $(this).attr('id') != 'theader' && $(this).attr('id') != 'error_custom') {
                        var type = $(this).find('#custom_field_type_'+counter).val();
		                counter++;
                        if(type == "-1"){
                            if(typed) {
                                typed = false;
                                if(!$('#error_custom').is(':visible')) { 
                                    $('#error_custom').show(); 
                                }    
                                $('#error_msg_custom').append('<p>Error: You did not select a type for one of your fields.</p>');
                            }
                        }
                   }
              });
               
              var counter = 1;
              $('#customFields').find('tr').each(function(i) {
                   if($(this).attr('id') != 'template' && $(this).attr('id') != 'theader' && $(this).attr('id') != 'error_custom') {
                        var unit = $(this).find('#custom_field_unit_'+counter).val();
		                counter++;
                        if(unit == "-1") {
                            if(united) {
                                united = false;
                                if(!$('#error_custom').is(':visible')) { 
                                    $('#error_custom').show(); 
                                }
                                $('#error_msg_custom').append('<p>Error: You did not select a unit for one of your fields.</p>');
                            }
                      }
                  }
               });
               
           }
        });
        
        if(filled && typed && united) {
            createWizard.next = createWizard.step_post_process;
            var counter = 1;
            $('#customFields').find('tr').each(function(i) {
               if($(this).attr('id') != 'template' && $(this).attr('id') != 'theader' && $(this).attr('id') != 'error_custom') {
                   var label  = $("#custom_field_label_"+counter).val();
                   var type   = $("#custom_field_type_"+counter).val();
                   var unit   = $("#custom_field_unit_"+counter).val();
                   if(type == 7){
                        createWizard.store_field("time", type, unit);
                   } else {    
                        createWizard.store_field(label, type, unit);
                   }
                   counter++;
               }
            });
           /* 
            $('#step_custom').hide();
            $('#step_done').show();
            $('#create_advance').val('Done');
            $('#create_previous').hide();
	   */
	    createWizard.next_step();
        }
    },
        
    step_post_process:function() {
        
        /* Run through fields, add them to data wrapper */
        for(i = 0; i < createWizard.fields.length; i++) {
            var label = createWizard.fields[i][0];
            var type = createWizard.fields[i][1];
            var unit = createWizard.fields[i][2];
            var conversion = createWizard.fields[i][3];
            createWizard.write_field(label, type, unit, conversion);
            $('#fields_list').createAppend('div', { }, label);
        }
                
        /* Should only be done after real work is completed */
        $('#setup_button').hide();
        $('#setup_summary').show();
        $('#experiment_create').attr('disabled', false);
        tb_remove();
    },

   /* Ideally, this should reset (nearly) everything to it's *
    * start state so that a user can re-enter their data     *
    * without refreshing the page. However, this is done via *
    * my limited understanding of this mess of a class, so   *
    * it may be less than perfect.                           */
    reset_all:function() {

	createWizard.step_restart();
	createWizard.clear_field();

	$('#data_wrapper').html("");
	$('#fields_list').html("");
	$('#setup_summary').hide();
	$('#setup_button').show();
	$('#experiment_create').attr('disabled', false);
	$('#step_done').hide();
	$('#create_advance').val('Next');
	$('#create_previous').show();

	}
}

function removeField(){

	if( parseInt( $('#row_count').val() ) > 1 ){

		$('#customFields').children().children('*:last-child').remove();

		$('#row_count').val( parseInt( $('#row_count').val() ) - 1 );

	}

}

createWizard.init();
