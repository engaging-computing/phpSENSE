
// Function descriptions are all above the function they describe
// because I like to know what a method/function does before I have
// to look at the code, so that makes sense to me.

var timeline = new function Timeline(){
    
    this.inited = 0;
    
    /*
     *	// Use: mytimeline.drawControls();
     *	//
     *	// This will populate controls (it's broken right now)
     */
    
    this.drawControls = function(){
        
        var controls = '';
        
        // --- //
        
        this.controls.innerHTML = controls;
        
    }
    
    
    
    this.draw = function(){
        
        $("a[rel^='prettyPhoto']").prettyPhoto();
    }
    
    // Starts the vis back up after another vis has been active
    
    this.start = function(){
        
        $('#viscanvas').hide();
        $('#timeline_canvas').show();
        
        this.drawControls();
        
        this.draw();
    }
    
    /*
     *	// Use: mytimeline.init();
     *	//
     *	// This draws the timeline to the canvas and populates
     *	// the div that holds the timeline controls. Call only once.
     */
    
    this.init = function(){
        
        this.controls	= document.getElementById("controldiv");
        
        this.inited = true;
        
        this.drawflag = false;
        
        this.chart = new Highcharts.Chart({
            
            chart: {
                renderTo: 'timeline_canvas',
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
            }]
        });
        
        this.start();
    }
    
    // end gets this vis outa' there.
    this.end = function(){
        
        $('#timeline_canvas').hide();
        $('#viscanvas').show();
    }
    
}
