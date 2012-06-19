
// Function descriptions are all above the function they describe

var bar = new function Bar(){
    
    /*
     *	// Use: mybar.drawControls();
     *	//
     *	// This will populate controls (it's broken right now)
     */
    
    this.drawControls = function(){
        
        var controls = '';
        
        controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div></div>';
        
        controls += buildSessionControls('bar');
        
        // --- //
        
        controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
        
        controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
        
        for( var i in data.fields ){
            
            // Should properly check if field is time
            if( data.fields[i].type_id != 7 && data.fields[i].type_id != 19 ){ 
                
                var color = Math.floor(((0.75*i/data.fields.length)) * 256);
                
                controls += '<td><div style="font-size:14px;font-family:Arial;text-align:center;color:#' + color.toString(16) + color.toString(16) + color.toString(16) + ';float:left;">';
                
                controls += data.fields[i].name + '&nbsp;';
                
                controls += '<input class="fieldvisible" type="checkbox" value="' + i + '" ' + ( data.fields[i].visibility ? 'checked' : '' ) + '></input>&nbsp;';
                
                controls += '<select id="' + i + '" class="fieldcalc"><option>Max</option><option>Min</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                
                // <option>Mean</option><option>Median</option><option>Mode</option>
                
                controls += '</div></td>';
                
            }
            
        }
        
        controls += '</table>'
        
        controls += '</div>';
        
        controls += '<div style="clear:both;"></div>';
        
        // --- //
        
        controls += '';
        
        // --- //
        
        this.controls.innerHTML = controls;
        
    }
    
    this.setListeners = function(){
        
        $('select.fieldcalc').change(function(){
            
            var i = $(this).attr('id');
            
            data.fields[i].calc = $(this).val();
            
            bar.draw();
            
        });
        
        $('input.sessionvisible').click(function(e){
            
            var visible = data.sessions[$(this).val()].visibility;
            
            visible = visible > 0 ? 0 : 1;
            
            data.sessions[$(e.target).val()].visibility = visible;
            
            bar.draw();
            
        });
        
        $('input.fieldvisible').click(function(e){
            
            var visible = data.fields[$(e.target).val()].visibility;
            
            visible = visible > 0 ? 0 : 1;
            
            data.fields[$(e.target).val()].visibility = visible;
            
            bar.draw();
            
        });
        
        $('#viscanvas').mousemove(function(e){
            
            bar.mouseX = e.pageX - $('canvas#viscanvas').offset().left;
            bar.mouseY = e.pageY - $('canvas#viscanvas').offset().top;
            
        });
        
    }
    
    
    this.draw = function(){
        
        $("a[rel^='prettyPhoto']").prettyPhoto();
    }
    
    
    /*
     *	// Use: mybar.init();
     *	//
     *	// This draws the bar to the canvas and populates
     *	// the div that holds the bar controls.
     */
    
    this.start = function(){
        
        $('#viscanvas').hide();
        $('#bar_canvas').show();
        
        this.draw();
        
        this.drawControls();
        
        this.setListeners();
        
    }
    
    this.init = function(){
        
        this.controls	= document.getElementById("controldiv");
        
        this.inited = true;
        
        this.chart = new Highcharts.Chart({
            
            chart: {
                renderTo: 'bar_canvas',
                type: 'column'
            },
            title: {
                text: 'Monthly Average Rainfall'
            },
            subtitle: {
                text: 'Source: WorldClimate.com'
            },
            xAxis: {
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rainfall (mm)'
                }
            },
            legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
            },
            tooltip: {
                formatter: function() {
                    return ''+
                    this.x +': '+ this.y +' mm';
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Tokyo',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
            }, {
                name: 'New York',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]
            }, {
                name: 'London',
                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]
            }, {
                name: 'Berlin',
                data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]
                
            }]
        });
        
        this.start();
        
    }
    
    this.end = function(){
        
        $('#bar_canvas').hide();
        $('#viscanvas').show();
    }
    
}
