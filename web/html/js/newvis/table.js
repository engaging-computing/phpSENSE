var rawData = data;

var table = new function Table() {

	this.inited = 0;
	this.order = null;
	this.lastClicked = null;
	
	this.data = Array();
	this.tmpData = Array();
	
	this.init = function (data) {
		
		this.inited = 1;
		
		this.start(data);
		
	}
	
	this.tabularize = function (data, sortOrder) {
		
		var count = 0;
		var meta = Array();
		
		if( this.data.length )
			this.data.length = 0;
		
		if( sortOrder == null || sortOrder == "Data Point") {
		
			this.numSes = data.sessions.length;
		
			for( var ses in data.sessions )
				for( var dp in data.sessions[ses].data ) {
					meta = [count, data.sessions[ses].sid, data.sessions[ses].visibility];
					this.data.push(meta.concat(data.sessions[ses].data[dp]))
					++count;
				}
		} else {
						
			for( var i in sortOrder ){
				meta = [sortOrder[i].count, data.sessions[sortOrder[i].session].sid, data.sessions[sortOrder[i].session].visibility];
				this.data.push(meta.concat(data.sessions[sortOrder[i].session].data[sortOrder[i].dataPoint]));
			}
			
		}

	}
	
	this.sortField = function ( field, order ) {

		var tmpSort = Array();
		var cnt = 0;
		
		for(var ses in data.sessions) {
			for(var dp in data.sessions[ses].data) {
				tmpSort.push({session:ses, count:cnt, dataPoint:dp, data:data.sessions[ses].data[dp][field]});
				cnt++;
			}
		}
						
		tmpSort.sort( (function(a, b) {
			return a.data - b.data;
		}));
		
		if( order != "asc" )
			tmpSort.reverse();
			
		return tmpSort;

	}

	this.sortSes = function () {

		var tmpSort = Array();
		var cnt = 0;
		
		for(var ses in data.sessions) {
			for(var dp in data.sessions[ses].data) {
				tmpSort.push({session:ses, count:cnt, dataPoint:dp});
				cnt++;
			}
		}
						
		tmpSort.sort( (function(a, b) {
			return a.session - b.session;
		}));
		
			
		return tmpSort;

	}

	this.draw = function (data, sort) {

		var sortOrder;

		if( sort != null ){	
			if( sort != 'Session #' ) {		
				for( var field in data.fields ) {				
					if( data.fields[field].name.toLowerCase() == sort.toLowerCase() ) {
						sortOrder = this.sortField(parseInt(field));
					}
				}	
			
				this.tabularize(data, sortOrder);
			} else {
			
			sortOrder = this.sortSes();
			this.tabularize(data, sortOrder);
			
			}
			
		} else { this.tabularize(data); }

		if( this.order == "asc" && sort != "Data Point" )
			this.data.reverse();
		else if( this.order == "desc" && sort == "Data Point")
			this.data.reverse();

		var count = 0;
		
		$('#table_canvas').append('<table id="data_table" border="1px" style="border-collapse:collapse;"><tr id="label"></tr></table>');
		$('#label').append('<td id="dP">Data Point</td>');
		$('#label').append('<td id="ses">Session #</td>');

		for( var field in data.fields )
			$('#label').append('<td id="label_'+field+'">' + data.fields[field].name + '</td>');

		for( var dp in this.data ) {
			if( this.data[dp][3]) {
				$('#data_table').append('<tr id="table_'+this.data[dp][1]+'_'+this.data[dp][0]+'" class="'+dp%2+'"></tr>');
				$('#table_'+this.data[dp][1]+'_'+this.data[dp][0]).append('<td>'+this.data[dp][0]+'</td><td style="background-color:#'+rgbToHex(hslToRgb( ( 0.6 + ( 1.0*this.data[dp][1]/this.numSes ) ) % 1.0, 1.0, 0.5 ))+'">'+this.data[dp][1]+'</td>')
			}

			for( var i = 3; i < this.data[dp].length; i++ ) {
				$('#table_'+this.data[dp][1]+'_'+this.data[dp][0]).append('<td>'+this.data[dp][i]+'</td>');
			}

		}
		
		$('#data_table').css('width','100%').children().children().css('text-align','center');
		$('#table_canvas').css('height', 'auto');
				
		if( this.lastClicked )
			if( this.order != "desc")
				$('table tr :contains("'+sort+'")').append('<img src="http://icons.mysitemyway.com/wp-content/gallery/glossy-black-3d-buttons-icons-media/thumbs/thumbs_002139-glossy-black-3d-button-icon-media-media2-arrow-up.png" height="10px" />');
			else
				$('table tr :contains("'+sort+'")').append('<img src="http://icons.mysitemyway.com/wp-content/gallery/glossy-black-3d-buttons-icons-media/002138-glossy-black-3d-button-icon-media-media2-arrow-down.png" height="10px" />');


	}
	
	this.end = function () {
		
		$('#table_canvas').children().unbind();
		$('#table_canvas').empty();
		$('#table_canvas').hide();
		$('#viscanvas').show();
		
	}
	
	this.drawControls = function () {
		
	}
	
	this.setListeners = function () {
		
		var labelField =  Array();
		
		for( var field in data.fields )
			$('#label_'+field).bind('click', function() {
								
				if( table.lastClicked == $(this).text() ) {
					if(table.order == "asc")
						table.order = "desc";
					else
						table.order = "asc";
				} else {
						table.order = "asc";
				}
				
				table.lastClicked = $(this).text();				
				$('#table_canvas').find('*').unbind();
				$('#table_canvas').empty();
				table.start( rawData, $(this).text() );
			});
			
		$('#dP').bind('click', function() {	
			
			if( table.lastClicked == $(this).text() ) {
				if(table.order == "asc")
					table.order = "desc";
				else
					table.order = "asc";
			} else {
					table.order = "asc";
			}
			
			table.lastClicked = $(this).text();
			$('#table_canvas').find('*').unbind();
			$('#table_canvas').empty();
			table.start(rawData, 'Data Point');
		});
		
		$('#ses').bind('click', function() {
			
			if( table.lastClicked == $(this).text() ) {
				if(table.order == "asc")
					table.order = "desc";
				else
					table.order = "asc";
			} else {
					table.order = "asc";
			}
			
			table.lastClicked = $(this).text();				
			$('#table_canvas').find('*').unbind();
			$('#table_canvas').empty();
			table.start( rawData, 'Session #' );
			
			
		})
			
		
	}
	
	this.start = function (data, sort) {
		
		$('#viscanvas').hide();
		$('#table_canvas').show();
		$('#table_canvas').css('height', '400px');
		
		timer = new Date();
		
		this.draw(data, sort);
		
		this.drawControls();
		this.setListeners();

	}
	
}