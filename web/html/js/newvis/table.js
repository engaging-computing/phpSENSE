
var table = new function Table() {
    
    this.inited = 0;
    
    this.data = Array();
    this.tmpData = Array();
    
    this.init = function () {
        
        this.inited = 1;
            
        this.formatter = {}

        
        this.start();

    }
    
   
    
    
    
    this.draw = function () {

        /*Set up the table headers*/
        $('#data_table').append('<thead><tr id=headers></tr></thead>');
        $('#headers').append('<td>Data Point</td>');
        $('#headers').append('<td>Session #</td>');
        for( var field in data.fields ) {
            var title = data.fields[field].name;
            $('#headers').append('<td>' + title + '</td>');
        }

        /*Add data to the table*/
        $('#data_table').append('<tbody id=data></tbody>');
        var dataPoint=0;
        for (var ses in data.sessions) {
            var dec = 0;
            for (var dp in data.sessions[ses].data) {
                var row_id = dp + '_'+ses;
                $('#data').append('<tr id=table_' + row_id + '></tr>');
                $('#table_'+row_id).append('<td>'+ dataPoint++ +'</td>');
                $('#table_'+row_id).append('<td style"background-color:red;">'+ses+'</td>');
                for (var field in data.fields) {  
                        var s = data.sessions[ses].data[dp][field].toString();
                        $('#table_'+row_id).append('<td>'+s+'</td>');
                       
                        var index = s.indexOf('.', 0);
                        
                        if (index != -1) {
                            dec = Math.max(s.length - (index + 1), dec);
                        }
                    }
                }
        }   

        /*Call to DataTables to build the table*/
        $('#data_table').dataTable( {
					"sScrollY": 400,
					"sScrollX": "100%",
					"sScrollXInner": "110%"
		} );
        
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
 
    }
    
    this.start = function () {
        
        $('#viscanvas').hide();
        $('#table_canvas').show();
        
        timer = new Date();
        
        this.draw();
        
        this.drawControls();
        this.setListeners();


    }
}
