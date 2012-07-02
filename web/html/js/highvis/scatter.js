
// Function descriptions are all above the function they describe
console.log(data);
var scatter = new function Scatter(){
    
    /*
     *	// Use: myscatter.drawControls();
     *	//
     *	// This will populate controls
     */
    
    this.drawControls = function(){
        
        var controls = '';
        
        controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div><button id="resetview" type="button">Reset View</button></div>';
        
        //controls += buildSessionControls('scatter');
        
        // --- //
        
        controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
        
        controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">X Axis:</tr></td>';
        
        controls += '<tr><td>';
        
        controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#000000;float:left;">';
        
        controls += '<input class="xaxis" type="radio" name="xaxisselect" value="-1" checked></input>&nbsp;';
        
        controls += 'Datapoint #&nbsp;';
        
        controls += '</div>';
        controls += '</td></tr>';
        
        for( var i in data.fields ){
            //check if field is text
            if (data.fields[i].type_id != 37){ 
                controls += '<tr><td>';
                
                controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#000000;float:left;">';
                
                controls += '<input class="xaxis" type="radio" name="xaxisselect" value="' + i + '" ></input>&nbsp;';
                
                controls += data.fields[i].name + '&nbsp;';
                
                controls += '</div>';
                controls += '</td></tr>';
            }
        }
        
        controls += '</table>';  
        controls += '</div>';
        
        controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
        controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
        
        for( var i in data.fields ){
            //check if field is time or text
            if( data.fields[i].type_id != 7 && data.fields[i].type_id != 37 ){
                
                controls += '<tr><td>';
                
                controls += '<div id="fieldvisiblediv' + i + '" style="font-size:14px;font-family:Arial;text-align:center;color:#000000;float:left;">';
                
                controls += '<input id="fieldvisible' + i + '" type="checkbox" value="' + i + '" ' + ( data.fields[i].visibility ? 'checked' : '' ) + '></input>&nbsp;';
                
                controls += data.fields[i].name + '&nbsp;';
                
                controls += '</div>';
                controls += '</td></tr>';
                
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
        
        $('input.xaxis').click(function(e){
            
            scatter.xAxis = $(e.target).val();
            
            scatter.drawflag = true;
            
        });
    }
    
    /*
     *	// Use: myscatter.draw();
     *	//
     *	// This draws the scatter to the canvas.
     */
    
    this.draw = function(){
        
        $("a[rel^='prettyPhoto']").prettyPhoto();
    }
    
    /*
     *	// Use: myscatter.init();
     *	//
     *	// This draws the scatter to the canvas and populates
     *	// the div that holds the scatter controls.
     */
    
    this.start = function(){
        
        $('#viscanvas').hide();
        $('#scatter_canvas').show();
        
        this.drawControls();
        
        this.draw();
        
        this.setListeners();
        
    }
    
    this.init = function(){
        
        this.controls	= document.getElementById("controldiv");
        
        this.inited = true;
        
        this.dragflag = false;
        
        this.chart = new Highcharts.Chart({
            
            chart: {
                renderTo: 'scatter_canvas',
                zoomType: 'xy'
            },
            
            legend: {
                itemStyle: {
                    color: '#000000',
                    fontWeight: 'bold'
                }
            },
            
            xAxis: {
            },
            
            series: [{
                data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]        
            }, {
                data: [144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4, 29.9, 71.5, 106.4, 129.2]     
            },
            {
                data: [44.0, 76.0, 35.6, 48.5, 16.4, 94.1, 5.6, 4.4, 9.9, 1.5, 6.4, 29.2]     
            }]
        });
        
        this.start();
        
    }
    
    this.end = function(){
        
       $('#scatter_canvas').hide();
       $('#viscanvas').show();
        
    }
    
}
