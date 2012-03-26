
var table = new function Table() {
    
    this.inited = 0;
    
    this.data = Array();
    this.tmpData = Array();
    
    this.init = function () {
        
        this.inited = 1;
        
        this.lastClicked = 'Data Point';
        this.lastField = 'Data Point';
        this.fieldOrder = 'asc';
        this.sessionOrder = 'asc';
        
        this.formatter = {}
        
        for (var field in data.fields) {
            if (data.fields[field].type_id == 7) {
                //time
                this.formatter[field] = function (time) {
                    var d = new Date(time)
                    
                    var s = d.getUTCHours() + ':' + d.getUTCMinutes() + ':' + d.getUTCSeconds() + '.' + d.getUTCMilliseconds()
                    return s + ' ' + (d.getUTCMonth() + 1) + '/' + d.getUTCDate() + '/' + d.getUTCFullYear();
                };
            }
            else if (data.fields[field].type_id == 37) {
                //text
                this.formatter[field] = function (text) {
                    return text;
                };
            }
            else {
                //data
                var dec = 0;
                
                for (var ses in data.sessions) {
                    for (var dp in data.sessions[ses].data) {
                        var s = data.sessions[ses].data[dp][field].toString();
                        var index = s.indexOf('.', 0);
                        
                        if (index != -1) {
                            dec = Math.max(s.length - (index + 1), dec);
                        }
                    }
                }
                
                dec = Math.min(dec, 20);
                
                function gen(prec) {
                    return function(data) {
                        return Number(data).toFixed(prec);
                    };
                }
                
                this.formatter[field] = gen(dec);
            }
        }
        
        this.start();
    }
    
    this.tabularize = function (sortOrder) {
        
        var count = 0;
        var meta;
        
        if( this.data.length ) {
            this.data.length = 0;
        }
        
        
        for( var i in sortOrder ){
            meta = {count:sortOrder[i].count, 
                    sid:data.sessions[sortOrder[i].session].sid, 
                    visibility:data.sessions[sortOrder[i].session].visibility,
                    session:sortOrder[i].session,
                    data:data.sessions[sortOrder[i].session].data[sortOrder[i].dataPoint]};
                    
            this.data.push(meta);
        }
    }
    
    this.sortField = function (field) {
        
        var tmpSort = Array();
        var cnt = 0;
        
        if (field == 'Data Point') {            
            for(var ses in data.sessions) {
                for(var dp in data.sessions[ses].data) {
                    tmpSort.push({session:ses, count:cnt, dataPoint:dp, data:dp});
                    cnt++;
                }
            }
        }
        else {
            for(var ses in data.sessions) {
                for(var dp in data.sessions[ses].data) {
                    tmpSort.push({session:ses, count:cnt, dataPoint:dp, data:data.sessions[ses].data[dp][field]});
                    cnt++;
                }
            }
        }
            
        
        var fieldCmp;
        if (this.fieldOrder === 'asc') {
            fieldCmp = function(a, b) {return a.data - b.data};
        }
        else {
            fieldCmp = function(a, b) {return b.data - a.data};
        }
        
        var sessionCmp;
        if (this.sessionOrder === 'asc') {
            sessionCmp = function(a, b) {return a.session - b.session};
        }
        else {
            sessionCmp = function(a, b) {return b.session - a.session};
        }
        
        
        tmpSort.sort((function(a, b) {
            if (!sessionCmp(a, b)) {
                return fieldCmp(a, b);
            }
            
            return sessionCmp(a, b);
        }));
        
        return tmpSort;
        
    }
    
    this.draw = function () {
        
        //var sortName = sort.substr(6, sort.length).replace('~', ' ');
        var sortOrder;
        
        if(this.lastField != null) {
            
            if (this.lastField == 'Data Point') {
                sortOrder = this.sortField('Data Point');
            }
            else {
                for( var field in data.fields ) {
                    if( data.fields[field].name.toLowerCase() == this.lastField.toLowerCase() ) {
                        sortOrder = this.sortField(parseInt(field));
                    }
                }
            }
        }
        
        this.tabularize(sortOrder);
        
        var count = 0;
        
        $('#table_canvas').append('<table id="data_table" border="1px"><tr id="data_table_label"></tr></table>');
        $('#data_table_label').append('<td id="label_Data Point" class="data_table_label">Data Point</td>');
        $('#data_table_label').append('<td id="label_Session #" class="data_table_label">Session #</td>');
        
        for( var field in data.fields ) {
            var id = 'label_' + data.fields[field].name;
            $('#data_table_label').append('<td id="' + id + '" class="data_table_label">' + data.fields[field].name + '</td>');
        }
        
        for( var dp in this.data ) {
            
            var idRoot = this.data[dp].count + '_' + this.data[dp].count
            
            $('#data_table').append('<tr id="table_' + idRoot +'" class="data_table_data"></tr>');
            $('#table_' + idRoot).append('<td class="data_table_data">' + this.data[dp].count + '</td>');
            $('#table_' + idRoot).append('<td class="data_table_data" style="background-color:#' + rgbToHex(getSessionColor(this.data[dp].session)) + '">'+this.data[dp].sid + '</td>');
            
            for( var i = 0; i < this.data[dp].data.length; i++ ) {
                $('#table_' + idRoot).append('<td class="data_table_data">' + this.formatter[i](this.data[dp].data[i]) + '</td>');
            }
        }
        
        if( this.fieldOrder != "desc") {
            $('#' + jqISC('label_' + this.lastField)).append('<img src="/html/img/vis/up-tri.png" height="14px" />');
        }
        else {
            $('#' + jqISC('label_' + this.lastField)).append('<img src="/html/img/vis/down-tri.png" height="14px" />');
        }
        
        if( this.sessionOrder != "desc") {
            $('#'+jqISC('label_Session #')).append('<img src="/html/img/vis/up-tri.png" height="14px" />');
        }
        else {
            $('#'+jqISC('label_Session #')).append('<img src="/html/img/vis/down-tri.png" height="14px" />');
        }
        
        
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
        
        for( var field in data.fields ) {
            
            $('#' + jqISC('label_' + data.fields[field].name)).bind('click', function(evt) {
                
                if( table.lastClicked == $(this).text() ) {
                    if(table.fieldOrder == "asc")
                        table.fieldOrder = "desc";
                    else
                        table.fieldOrder = "asc";
                } else {
                    table.lastField = evt['target']['id'].substr(6, evt['target']['id'].length);
                    table.fieldOrder = "asc";
                }
                
                table.lastClicked = $(this).text();				
                $('#table_canvas').find('*').unbind();
                $('#table_canvas').empty();
                table.start();
            });}
            
            $('#' + jqISC('label_Data Point')).bind('click', function() {
                
                if( table.lastClicked == $(this).text() ) {
                    if(table.fieldOrder == "asc")
                        table.fieldOrder = "desc";
                    else
                        table.fieldOrder = "asc";
                } else {
                    table.lastField = 'Data Point';
                    table.fieldOrder = "asc";
                }
                
                table.lastClicked = $(this).text();
                $('#table_canvas').find('*').unbind();
                $('#table_canvas').empty();
                table.start();
            });
            
            $('#' + jqISC('label_Session #')).bind('click', function() {    
                
                if(table.sessionOrder == "asc") {
                    table.sessionOrder = "desc";
                }
                else {
                    table.sessionOrder = "asc";
                }
                
                table.lastClicked = $(this).text();				
                $('#table_canvas').find('*').unbind();
                $('#table_canvas').empty();
                table.start();
            });
            
            
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