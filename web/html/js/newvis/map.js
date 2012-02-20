var map = new function Map() {
				
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
	
	this.init = function(data) {
		
		this.inited = 1;
		this.measureField = "none";
		
		for( ses in data.sessions )
			prcntVis[ses] = 1;
			
		this.start(data);

	}
	
	this.draw = function(data, f) {
		
		var latField = null;
		var lonField = null;
		var markers = Array();
		var f = null;
		
		for(var field in data.fields) {
			if(data.fields[field].name.toLowerCase() == 'latitude')
				latField = field;
			if(data.fields[field].name.toLowerCase() == 'longitude')
				lonField = field;
		}


		if( latField != null && lonField != null )
			this.Options['center'] = new google.maps.LatLng(data.avgField('latitude'), data.avgField('longitude'));
		else
			this.Options['center'] = new google.maps.LatLng(data.sessions[0].meta['latitude'], data.sessions[0].meta['longitude']);
		
		this.map = new google.maps.Map(document.getElementById("map_canvas"), this.Options);


		
		var color = hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 );
		
		if( latField != null && lonField != null ) {
			for(var ses in data.sessions)
				if(data.sessions[ses].visibility)
					for(var dp in data.sessions[ses].data) {
						if( !(dp % prcntVis[ses]) ) {
							if( this.measureField == "none" ) {
								var tmp = new google.maps.LatLng(data.sessions[ses].data[dp][latField],
													 	 	data.sessions[ses].data[dp][lonField]);
								markers[markers.length] = new google.maps.Marker({
									position: tmp,
									map: this.map,
									title: data.sessions[ses].data[dp][0].toString(),
									icon: '/html/img/vis/v3icon.php?color=' + hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )
								});
							} else {
								var tmp = new google.maps.LatLng(data.sessions[ses].data[dp][latField],
													 	 	data.sessions[ses].data[dp][lonField]);
								var max, min, val;
								for( var field in data.fields )
									if( data.fields[field].name.toLowerCase() == this.measureField.toLowerCase() )
										break;

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
		} else {
			for(var ses in data.sessions) {

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
	
	this.end = function() {
		
		google.maps.event.clearInstanceListeners(this.map);
		$('#controldiv').children().unbind();
		$('#controldiv').empty();
		$('#map_canvas').hide();
		$('#viscanvas').show();
		
	}
	
	this.drawControls = function () {
		
		$('#controldiv').append('<div id="control_exp"><select id="measuredField"><option value="null">None</option></select></div>');
		
		for( var field in data.fields )
			$('#measuredField').append('<option value="'+field+'">'+data.fields[field].name+'</option>');
			
		for( var ses in data.sessions ) {
				
				$('#controldiv').append('<div id="control_'+ ses +'">Session '+ses+':&nbsp;&nbsp;<input type="checkbox" id="visible_'+ses+'"></div>');
				$('#control_'+ses).css('background-color', '#' + rgbToHex(hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )));				

				if( data.sessions[ses].visibility ) 
					$('#visible_'+ses).attr('checked','true');

				switch (prcntVis[ses]) {
					case '1':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '"><option value="1" selected >100%</option><option value="2">50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;
					case '2':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '"><option value="1">100%</option><option value="2" selected >50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;
					case '4':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '"><option value="1">100%</option><option value="2">50%</option><option value="4" selected >25%</option><option value="10">10%</option></select>');
						break;
					case '10':
						$('#control_' + ses).append('<select id="prcnt_' + ses + '"><option value="1">100%</option><option value="2">50%</option><option value="4">25%</option><option value="10" selected >10%</option></select>');
						break;
					default:
						$('#control_' + ses).append('<select id="prcnt_' + ses + '"><option value="1" selected >100%</option><option value="2">50%</option><option value="4">25%</option><option value="10">10%</option></select>');
						break;				
			}

		}
								
	}
		
	this.setListeners = function() {
		
		google.maps.event.addListener( this.map, 'zoom_changed', function() {
			map.Options['zoom'] = map.map.getZoom();
		});
		
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
		
		$('#measuredField').bind('change', function () {
			
			if($(this).children().eq((parseInt(this.value)+1)).text())
				map.measureField = $(this).children().eq((parseInt(this.value)+1)).text().toLowerCase();
			else
				map.measureField = "none";
				
			map.draw(data);			

		});
		
	}
	
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
		
}