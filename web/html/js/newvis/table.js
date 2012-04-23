
var table = new function Table() {
    
    this.inited = 0;
    
    this.data = Array();
    this.tmpData = Array();
    
    this.init = function () {
        
        this.inited = 1;
            
        this.formatter = {}
        
        this.start();
     

    }
    

    this.fmtTime = function(rawTime){
        
        //Pull out the millis to append
        var millis = rawTime % 1000;   

        //Even though the date object takes millis it cant handle anything but 000 at the end.     
        var seconds = Math.floor(rawTime/1000)*1000;

        //Create a new date from the seconds (with 000 at the end instead of the actual millis)
        var d = new Date(seconds);

        //Build up the time string.
        var s = d.getUTCHours() + ':' + d.getUTCMinutes() + ':' + d.getUTCSeconds() + '.' + millis + ' ' + (d.getUTCMonth() + 1) + '/' 
            + d.getUTCDate() + '/' + d.getUTCFullYear();

        return s;
    }


    this.draw = function () {

        $('#table_canvas').append('<table id=data_table></table>');

        /*Set up the table headers*/
        $('#data_table').append('<thead><tr id=headers></tr></thead>'); 
        $('#headers').append('<td >Session</td>');
        $('#headers').append('<td>Data Point</td>');
        $('#headers').append('<td>Session #</td>');
       
        for( var field in data.fields ) {
            var title = data.fields[field].name;
            var type_id = data.fields[field].type_id;
            
            /* Do not display units for geospacial/text/numeric/custom */
            if(type_id == 19 || type_id == 37 || type_id == 21 || type_id == 22){
                $('#headers').append('<td><b>' + title  + '</b></td>');
            } else {
                var unit = data.fields[field].unit_abb;
                $('#headers').append('<td><b>' + title + '</b> (' + unit + ')</td>');
            }
            
        }

        /* Add data to the table */
        $('#data_table').append('<tbody id=data></tbody>');
        
        for (var ses in data.sessions) {
            var dataPoint=0;
            if(data.sessions[ses].visibility) {
                var dec = 0;
                for (var dp in data.sessions[ses].data) {
                    var row_id = dp + '_'+ses;
                    $('#data').append('<tr id=table_' + row_id + '></tr>');
                    $('#table_'+row_id).append('<td> ' + data.sessions[ses].meta['name']+'</td>');
                    $('#table_'+row_id).append('<td>'+ dataPoint++ +'</td>');
                    $('#table_'+row_id).append('<td>'+ data.sessions[ses].sid +'</td>');
                     
                
                    for (var field in data.fields) { 
                        var s = data.sessions[ses].data[dp][field].toString();
                    
                        /* Format time correctly in UTC */
                        if(data.fields[field].type_id==7){
                            var rawTime = data.sessions[ses].data[dp][field];
                            var formattedTime = table.fmtTime(rawTime);
                            $('#table_'+row_id).append('<td>'+formattedTime+'</td>');        

                        /* Otherwize just throw it in the table */
                        } else {
                            $('#table_'+row_id).append('<td>'+s+'</td>');   
                            var index = s.indexOf('.', 0);
                        
                            if (index != -1) {
                                dec = Math.max(s.length - (index + 1), dec);
                            }
                        }
                    }
                }
            }
        }   

        /* Call to DataTables to build the table */
        var atable = $('#data_table').dataTable( {
		    "sScrollY": 400,
			"sScrollX": "100%",
            "iDisplayLength": -1,
            "bDeferRender":true,
            "aaSorting": [[2,'asc'] ,[1,'asc']],
            "oLanguage": {
			    "sLengthMenu": 'Display <select>'   +
			             '<option value="10">10</option>' +
			             '<option value="25">25</option>' +
			             '<option value="50">50</option>' +
			             '<option value="100">100</option>' +
			             '<option value="-1">All</option>'+
			             '</select> records'
			},
            "aoColumnDefs": [ {
		           "aTargets": [0],
		           "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
		               var color = getSessionColor(oData[2]);
                        
                       var paint = '#' + (color[0]>>4).toString(16) + (color[1]>>4).toString(16) + (color[2]>>4).toString(16);                  
		               $(nTd).css('color', paint);	             
		           }
		         } ]
		} );
        

    }
    

    
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
                                    '<td id="pic_'+ses+'"><a id="link_'+ses+'"rel="prettyPhoto[gallery'+ses+']" href="'+ link + '" title="'+description+'"> ' + session_name +'</a>'+'</td> ');
                            } else {
                               $('#pic_'+ses).append('<a rel="prettyPhoto[gallery'+ses+']" href="'+ link + '" title="'+description+'"></a>');
                            }
                    }
				   	 $('#link_'+ses).css('color', '#' + rgbToHex(hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )));			
                } else {
                    $('#row_' + ses).append('<td style="width:20px"> <input type="checkbox" id="visible_'+ses+ '"/></td><td>'+ session_name +'</td> ');
                    $('#row_'+ses).css('color', '#' + rgbToHex(hslToRgb( ( 0.6 + ( 1.0*ses/data.sessions.length ) ) % 1.0, 1.0, 0.5 )));	
                }

               
				
                if( data.sessions[ses].visibility ){ 
					$('#visible_'+ses).attr('checked','true');
                }
		}
    }
    
    this.setListeners = function () {
        for( var ses in data.sessions ) {
			$('#visible_'+ses).bind('change', function () {
				var $ses = $(this).attr('id').split('_');
				$('#visible_'+$ses[1]).attr('checked') ? data.sessions[$ses[1]].visibility = 1 : data.sessions[$ses[1]].visibility = 0 ;
                $('#table_canvas').empty();
				table.draw(data);
			});
		}
    }
    
    this.start = function () {
        
        $('#viscanvas').hide();
        $('#table_canvas').show();
        
        timer = new Date();
        
        this.draw();
        
        this.drawControls();
        this.setListeners();
        $("a[rel^='prettyPhoto']").prettyPhoto();


    }    

    this.end = function () {
        $('#controldiv').children().unbind();
		$('#controldiv').empty();        
        $('#table_canvas').children().unbind();
        $('#table_canvas').empty();
        $('#table_canvas').hide();
        $('#viscanvas').show();
    }
}
