/* This file contains all of the functionality needed */
/* to draw the map visualization.                     */

var map = new function Map() {
	
    /* Variable declarations */			
	var prcntVis = Array();
	var Options = Array();
	
	var inited;
	var measureField;
	
	this.Options = {
		zoom: 8,
		center: new google.maps.LatLng(0,0),
		mapTypeId: google.maps.MapTypeId.HYBRID
	};
	
	this.map = null;
	

    /* Initialization Function */
	this.init = function(data) {
		
		this.inited = 1;
		this.measureField = "none";
		
		for( ses in data.sessions )
			prcntVis[ses] = 1;
			
        this.controls = document.getElementById("controldiv");


		this.start(data);
        $("a[rel^='prettyPhoto']").prettyPhoto();

	}
	
    this.addInfoWindow = function(marker){



    }


    /* Draw function */
	this.draw = function(data, f) {
		$("a[rel^='prettyPhoto']").prettyPhoto();
		var latField = null;
		var lonField = null;
		var markers = Array();
		var f = null;
		
        /* Find the latitude and longitude fields in the current session (if they exist) */
		for(var field in data.fields) {
			if(data.fields[field].name.toLowerCase() == 'latitude'){
				latField = field;
            }
			
            if(data.fields[field].name.toLowerCase() == 'longitude'){
				lonField = field;
            }
		}

        /* If there is data in the session use it, else display the session map */
		if( latField != null && lonField != null ){
			this.Options['center'] = new google.maps.LatLng(data.avgField('latitude'), data.avgField('longitude'));      
        }
		else {
			this.Options['center'] = new google.maps.LatLng(data.sessions[0].meta['latitude'], data.sessions[0].meta['longitude']);
		}    
       
        /* Create the new map */
        //this.Options['zoom'] = 15;
		this.map = new google.maps.Map(document.getElementById("map_canvas"), this.Options);

		var color = hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 );
		

        /* If there is lat/lon in the experiment start adding those points */
		if( latField != null && lonField != null ) {
			for(var ses in data.sessions) {
				if(data.sessions[ses].visibility) {
					for(var dp in data.sessions[ses].data) {
                        
						if( !(dp % prcntVis[ses]) ) {
                          
                            /* Draw regular points on the map */
							if( this.measureField == "none" ) {
								var tmp = new google.maps.LatLng(data.sessions[ses].data[dp][latField], data.sessions[ses].data[dp][lonField]);
                                markers[markers.length]= new google.maps.Marker({
									position: tmp,
									map: this.map,
                                    title: data.sessions[ses].meta["name"].toString(),									
									icon: '/html/img/vis/v3icon.php?color=' + hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 ),
                                    clickable: true                        
								});

                            map.addInfoWindow(markers[markers.length-1]);

                                
                                        
                            /* Draw bars on the map corresponding to the field selected */
							} else {
								var tmp = new google.maps.LatLng(data.sessions[ses].data[dp][latField],
													 	 	data.sessions[ses].data[dp][lonField]);
								var max, min, val;

   								for( var field in data.fields ){
									if( data.fields[field].name.toLowerCase() == this.measureField.toLowerCase() ){
										break;
                                    }
                                }

								max = data.getFieldMax(map.measureField);
								min = data.getFieldMin(map.measureField);
								val = data.sessions[ses].data[dp][field];
								
								markers[markers.length] = new google.maps.Marker({
									position: tmp,
									map: this.map,
									title: data.sessions[ses].data[dp][0].toString(),
									icon: '/html/img/vis/measured.php?color=' + hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )
									 	+ '&value=' + Math.floor( ( val - min ) / ( max - min ) * 20 )
								});
							}
						}
					}
				}
			}

        /* If there is no lat/lon in the experiment start drawing the session map */
		} else {
		    for(var ses in data.sessions) {
                if(data.sessions[ses].visibility) {
			        var tmp = new google.maps.LatLng(data.sessions[ses].meta['latitude'],
					    data.sessions[ses].meta['longitude']);
				    markers[markers.length] = new google.maps.Marker({
					    position: tmp,
					    map: this.map,
					    title: 'Session #: ' + eval(ses + 1) ,
					    icon: '/html/img/vis/v3icon.php?color=' + hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )
				    });
			    }
			}
		}			
	}

    /* Draw the controls under the map. */
	this.drawControls = function () {
      
        /* Add the table of selectable sessions to the controls. */
        $('#controldiv').append('<div id="sessionControls" style="float:left;margin:10px;"></div>');
        $('#sessionControls').append('<table id="sessionTable" style="border:1px solid grey;padding:5px;"></table>');        
        $('#sessionTable').append('<thead><tr><td></td><td style="text-align:center;text-decoration:underline;padding-bottom:5px;display:block" colspan="3">Sessions:</td><td></td></tr></thead>');
		for( var ses in data.sessions ) {
				var session_name = data.sessions[ses].meta["name"];
				$('#sessionTable').append('<tr id="row_' + ses + '"></tr>'); 


                if(data.sessions[ses].pictures[0] != null){
                    for(var i in data.sessions[ses].pictures){                
                            var link = data.sessions[ses].pictures[i]['provider_url'];
                            var description = data.sessions[ses].pictures[i]['description'];
                            if(i==0){ 
                                $('#row_' + ses).append('<td style="width:20px"> <input type="checkbox" id="visible_'+ses+ '"/></td>'+
                                    '<td id="pic_'+ses+'"><a id="link_'+ses+'"rel="prettyPhoto[gallery'+ses+']" href="'+ link + '" title="'+description+'"> ' + session_name +'</a>'+':&nbsp;&nbsp;</td> ');
                            } else {
                               $('#pic_'+ses).append('<a rel="prettyPhoto[gallery'+ses+']" href="'+ link + '" title="'+description+'"></a>');
                            }
                            $('#link_'+ses).css('color', '#' + rgbToHex(hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )));
                    }
				   				
                } else {
                    $('#row_' + ses).append('<td style="width:20px"> <input type="checkbox" id="visible_'+ses+ '"/></td><td>'+ session_name +':&nbsp;&nbsp;</td> ');
                    $('#row_'+ses).css('color', '#' + rgbToHex(hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )));
                }

                
				                
                $('#row_' + ses).append('<td id="control_'+ ses +'"> </td>');
						

				if( data.sessions[ses].visibility ) 
					$('#visible_'+ses).attr('checked','true');


                /* percent of data */
				switch (prcntVis[ses]) {
					case '1':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '" ><option value="1" selected >100%</option><option value="2">50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;
					case '2':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '" ><option value="1">100%</option><option value="2" selected >50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;
					case '4':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '" ><option value="1">100%</option><option value="2">50%</option><option value="4" selected >25%</option><option value="10">10%</option></select>');
						break;
					case '10':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '" ><option value="1">100%</option><option value="2">50%</option><option value="4">25%</option><option value="10" selected >10%</option></select>');
						break;
					default:
						$('#control_' + ses).append('<select id="prcnt_' + ses + '" ><option value="1" selected >100%</option><option value="2">50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;				
			    }

		}

        /* Add the table of selectable fields to the controls */
        $('#controldiv').append('<div id="fieldControls" style="float:left;margin:10px;"></div>');
        $('#fieldControls').append('<table id="fieldTable" style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;></table>');
        $('#fieldTable').append('<tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>');
        $('#fieldTable').append('<div id="fieldControls"><select id="measuredField"><option value="null">None</option></select></div>');
        for( var field in data.fields ){
			$('#measuredField').append('<option value="'+field+'">'+data.fields[field].name+'</option>');
        }
		
	}
		

    /* Set up listeners for different actions */
	this.setListeners = function() {
		
        /* Not sure this actually effects anything */
		google.maps.event.addListener( this.map, 'zoom_changed', function() {
			map.Options['zoom'] = map.map.getZoom();
		});
		
        
        /* Handelers for percent of data shown and whether a session is visible*/
		for( var ses in data.sessions ) {
			$('#prcnt_'+ses).bind('change', function () {
				$ses = $(this).attr('id').split('_');
				prcntVis[$ses[1]] = this.value;
				map.draw(data);
			});				

			$('#visible_'+ses).bind('change', function () {
				var $ses = $(this).attr('id').split('_');
				$('#visible_'+$ses[1]).attr('checked') ? data.sessions[$ses[1]].visibility = 1 : data.sessions[$ses[1]].visibility = 0 ;
				map.draw(data);
			});
		}
		
        /* Redraw the map with bars representing a field */
		$('#measuredField').bind('change', function () {
			
			if($(this).children().eq((parseInt(this.value)+1)).text())
				map.measureField = $(this).children().eq((parseInt(this.value)+1)).text().toLowerCase();
			else
				map.measureField = "none";
				
			map.draw(data);			

		});
		
	}
	

    /* Start the map and hide the previous vis */
	this.start = function (data) {
		
		var isiPad = navigator.userAgent.match(/iPad/i) != null;	
		height = (400*isiPad)+400;
		
		$('#viscanvas').hide();
		$('#map_canvas').show();
		$('#map_canvas').css('height', height);
		
		this.draw(data);
		this.drawControls();
		this.setListeners();
		
	}


    /* Stop the map */
	this.end = function() {		
		google.maps.event.clearInstanceListeners(this.map);
		$('#controldiv').children().unbind();
		$('#controldiv').empty();
		$('#map_canvas').hide();
		$('#viscanvas').show();
		
	}
		
}
