
// Function descriptions are all above the function they describe
// because I like to know what a method/function does before I have
// to look at the code, so that makes sense to me.

var timeline = new function Timeline(){
	
	this.inited = 0;
	
	/*
	// Use: mytimeline.drawControls();
	//
	// This will populate controls (it's broken right now)
	*/
	
	this.drawControls = function(){
		
		var controls = '';
		
		controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div><button id="resetview" type="button">Reset View</button></div>';
		

        //console.log( buildSessionControls('timeline'));
        controls += buildSessionControls('timeline');
		
		// --- //
		
		controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
		
		for( var i in data.fields ){
			//check if field is time or text
			if( data.fields[i].type_id != 7 && data.fields[i].type_id != 37 ){ 
				
				controls += '<tr><td>';
			
				controls += '<div id="fieldvisiblediv' + i + '" style="font-size:14px;font-family:Arial;text-align:center;color:#ffffff;float:left;">';
			
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
		
		//controls += '<br><br>One To One: <input id="one2one" type="checkbox" ' + ( timeline.one2one ? 'checked' : '' ) + '></input>'; // bkmk

		// --- //
		
		this.controls.innerHTML = controls;
		
	}
	
	this.setListeners = function(){
		
		// Set listener for reset button
		
		$('button#resetview').click(function(e){
			
			timeline.hRangeLower = 0.0;
			timeline.hRangeUpper = 1.0;
			timeline.vRangeLower = 0.0;
			timeline.vRangeUpper = 1.0;
			
			timeline.drawflag = true;
			
		});
		// Set listener for sessions visibility checkboxes
		
		$('input.sessionvisible').click(function(e){
			
			var visible = data.sessions[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.sessions[$(e.target).val()].visibility = visible;
			
			timeline.drawflag = true;
			
		});
		
		// Set listener for one-to-one display checkbox
		
		$('input#one2one').click(function(e){
			
			var on = timeline.one2one;
			
			on = on == true ? false : true;
			
			timeline.one2one = on;
			
			timeline.draw();
			
		});
		
		// Set listener for field visibility checkboxes
		
		$('input[id^=fieldvisible]').click(function(e){
			
			var visible = data.fields[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.fields[$(e.target).val()].visibility = visible;
			
			timeline.drawflag = true;
			
		});
		
		bindMouseZoom(timeline);
		
	}
	
	/*
	// Use: mytimeline.clear();
	//
	// This clears the canvas and redraws the graph border.
	*/
	
	this.clear = function(){
		
		// Clears the canvas. This is fairly self-explanatory
		
		this.context.fillStyle = this.bgcolor;
	    this.context.fillRect(0, 0, this.canvaswidth, this.canvasheight);
	
		this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;
		this.context.strokeRect(0, 0 + this.yoff, this.drawwidth, this.drawheight);
		
	}
	
	// plot() plots some normalized data that you give it. Assumes that first point (points[i][0]) is mapped to the x axis
	// and second point (points[i][1]) is mapped to the y axis.
	
	this.plot = function(points){
		
		if( points.length > 1 ){
			/*
			var beginDiffH = points[1][0] - points[0][0];
		
			var endDiffH = points[points.length-1][0] - points[points.length-2][0];

			var beginDiffV = points[1][1] - points[0][1];
		
			var endDiffV = points[points.length-1][1] - points[points.length-2][1];
		
			var beginslope = beginDiffV/beginDiffH;
		
			var beginslope = endDiffV/endDiffH;
		
		
			if( beginDiffH > beginDiffV){
			
				var x1 = points[0][0];
				var y1 = 0;
				var x2 = points[0][0];
				var y2 = points[0][1];
			
				this.context.moveTo( this.xoff + (x1*this.drawwidth), this.drawheight + this.yoff - (y1*this.drawheight) );
				this.context.lineTo( this.xoff + (x2*this.drawwidth), this.drawheight + this.yoff - (y2*this.drawheight) );
			
				this.context.stroke();
			
			} else {
			
				var x1 = 0;
				var y1 = points[0][1];
				var x2 = points[0][0];
				var y2 = points[0][1];
			
				this.context.moveTo( this.xoff + (x1*this.drawwidth), this.drawheight + this.yoff - (y1*this.drawheight) );
				this.context.lineTo( this.xoff + (x2*this.drawwidth), this.drawheight + this.yoff - (y2*this.drawheight) );
			
				this.context.stroke();
			
			}*/
			/*
			var oldpoints = points;
			
			if( points.length < this.drawwidth ){
				
				for( var j = 0; j < points[0].length; j++ ){
				
					for( var i = 0; i < this.drawwidth; i++ ){
					
						if( !points[i] ) points[i] = new Array();
					
						points[i][j] = oldpoints[Math.floor(i*oldpoints.length/this.drawwidth)][j];
					
					}
					
				}
				
			}*/

			var t1 = points[0][0];
			var t0 = points[1][0];
		
			for( var i = 1; i < points.length; i++ ){
		
				var x1 = (points[i-1][0] - this.hRangeLower)/(this.hRangeUpper-this.hRangeLower);
				var y1 = (points[i-1][1] - this.vRangeLower)/(this.vRangeUpper-this.vRangeLower);
				var x2 = (points[i][0] - this.hRangeLower)/(this.hRangeUpper-this.hRangeLower);
				var y2 = (points[i][1] - this.vRangeLower)/(this.vRangeUpper-this.vRangeLower);
		
				this.context.beginPath();
		
				if( typeof(points[i-1][0])	== "number" &&
				 	typeof(points[i-1][1])	== "number" &&
					typeof(points[i][0])	== "number" &&
					typeof(points[i][1])	== "number" ){
			
					this.context.moveTo( this.xoff + (x1*this.drawwidth), this.drawheight + this.yoff - (y1*this.drawheight) );
					this.context.lineTo( this.xoff + (x2*this.drawwidth), this.drawheight + this.yoff - (y2*this.drawheight) );
		
				}
		
				this.context.stroke();
			
			}
		
		}
		
	}
	
	/*
	// Use: mytimeline.plotData*();
	//
	// These methods plot the lines that correspond to the session
	// data. They take into account X and Y axis zoom/range.
	*/
	
	this.plotDataNormal = function(){
		
		var hbounds = data.getVisibleTimeBounds();
        var hmin = hbounds[0];
        var hmax = hbounds[1];
        var hdif = hmax - hmin;
        
        var vbounds = data.getVisibleDataBounds(true);
        var vmin = vbounds[0];
        var vmax = vbounds[1];
        var vdif = vmax - vmin;
        
        vmin -= vdif * 0.05;
        vmax += vdif * 0.05;
        vdif = vmax - vmin;
        
        var timeField = 0;
        
        for (var f in data.fields) {
            if (data.fields[f].type_id == 7) {
                timeField = f;
                break;
            }
        }
        
	
		// --- Display point-by-point --- //
	
		for( var i = 0; i < data.sessions.length; i++ ){
		
			for( var j = 0; j < data.fields.length; j++ ){
				
				var displaydata = data.getDataFrom(i,j);
				
				var timedata = data.getDataFrom(i,timeField); // time
				
				var datalen = displaydata.length;
			
				var plotarray = new Array();
			
				var inc = 0;
				
				/*
				
				if( data.sessions[i].visibility == 1 && data.fields[j].visibility == 1 ){
			
					for( var k = 0; k < datalen; k++ ){						
			
						if( data.fields[j].type_id ){ // if j == 1 should actually check for not time bkmk
			
							if( (timedata[k]-hmin)/hdif >= this.hRangeLower && (timedata[k]-hmin)/hdif <= this.hRangeUpper && (displaydata[k]-vmin)/vdif >= this.vRangeLower && (displaydata[k]-vmin)/vdif <= this.vRangeUpper ){
				
								plotarray[inc] = new Array();
	
								plotarray[inc][0] = (timedata[k]-hmin)/hdif;
	
								plotarray[inc][1] = (displaydata[k]-vmin)/vdif;
	
								inc++;
					
							} else {
								
								//..//
								
								plotarray[inc] = new Array();
	
								plotarray[inc][0] = null;
	
								plotarray[inc][1] = null;
								
								inc++;
								
							}
			
						}
			
					}
				
				}
				
				*/ //bkmk for interpolation code
				
				if( data.sessions[i].visibility == 1 && data.fields[j].visibility == 1 ){
				
					var numpoints = this.minpoints > datalen ? this.minpoints : datalen;
				
					for( var k = 0; k < numpoints; k++ ){
						
						var pos		= k*datalen/numpoints;
						var floor 	= Math.floor(pos);
						var ceil	= Math.ceil(pos);
						
						var xdata	= timedata[floor]*(ceil-pos) + timedata[ceil]*(pos-floor);
						var ydata	= displaydata[floor]*(ceil-pos) + displaydata[ceil]*(pos-floor);
						
						//console.log( "(" + datalen + ", " + numpoints + ")" );
						
						//xdata = timedata[floor];
						//ydata = displaydata[floor];
						
						xdata = (xdata-hmin)/hdif;
						ydata = (ydata-vmin)/vdif;
						
						plotarray[k] = new Array();
						
						if( xdata >= this.hRangeLower && xdata <= this.hRangeUpper && ydata >= this.vRangeLower && ydata <= this.vRangeUpper ){
						
							plotarray[k][0] = xdata;
							plotarray[k][1] = ydata;
							
						} else {
							
							plotarray[k][0] = null;
							plotarray[k][1] = null;
							
						}
						
					}
					
				}
				
				var color = getFieldColor(j, i);

				this.context.strokeStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 0.825)";

			    this.context.lineWidth = 1 + (j/data.fields.length);
		
				if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 && data.fields[j].type_id != 37 ) this.plot(plotarray);
			
			}
		
		}
		
	}
	
	this.plotDataOne2One = function(){
		
		var max = data.getMax();
	
		var min = data.getMin();
	
		var diff = max - min;
	
		// --- Display point-by-point --- //
	
		for( var i = 0; i < data.sessions.length; i++ ){
		
			var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 1.0, 0.5 );

			this.context.strokeStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 0.825)";
		
		    this.context.lineWidth = 1.5;
	
			// --- //
		
			var hmin = 0;
		
			var hmax = data.getMaxDatapoints() - 1;
		
			var hdif = hmax - hmin;
		
			var vmin = data.getMin();
		
			var vmax = data.getMax();
		
			var vdif = vmax - vmin;
		
			// --- //
		
			for( var j = 0; j < data.fields.length; j++ ){
				
				var displaydata = data.getDataFrom(i,j);
			
				var datalen = displaydata.length;
			
				var plotarray = new Array();
			
				var inc = 0;
				
				if( data.sessions[i].visibility == 1 && data.fields[j].visibility == 1 && data.fields[j].type_id != 7 && data.fields[j].type_id != 19 && data.fields[j].type_id != 37 ){
				
					for( var k = 0; k < datalen; k++ ){					
			
						if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 && data.fields[j].type_id != 37 ){ // bkmk must add checks for lat/long
		
							if( (k-hmin)/hdif >= this.hRangeLower && (k-hmin)/hdif <= this.hRangeUpper ){
			
								if( (displaydata[k]-vmin)/vdif >= this.vRangeLower && (displaydata[k]-vmin)/vdif <= this.vRangeUpper ){
			
									plotarray[inc] = new Array();
		
									plotarray[inc][0] = (k-hmin)/hdif;
		
									plotarray[inc][1] = (displaydata[k]-vmin)/vdif;
		
									inc++;
								
								}
				
							}
		
						}
		
					}
				
				}
			
				if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 && data.fields[j].type_id != 37 ) this.plot(plotarray); //bkmk
			
			}
		
		}
		
	}

	// plotData calls the above methods
	
	this.plotData = function(){
		
		if( this.one2one ){
					
			this.plotDataOne2One();
			
		} else {
			
			this.plotDataNormal();
			
		}
		
	}

	/**
     * Applies the suggested zoom bounds. If the bounds are too small
     * (within floating point error for example) they are not used.
     * 
     * @param hLow Suggested horizontal lower bound.
     * @param hUp  Suggested horizontal upper bound.
     * @param vLow Suggested vertical lower bound.
     * @param vUp  Suggested vertical upper bound.
     */
	this.setBounds = function(hLow, hUp, vLow, vUp){
        
        var xbounds = data.getVisibleTimeBounds();
        var xdiff = xbounds[1] - xbounds[0];
        var xMinRange = 10 / xdiff;
        xMinRange = Math.max(xMinRange, 1e-14); //Clamp for FPEs
        
        if (hUp - hLow >= xMinRange || 
            (hUp >= this.hRangeUpper && hLow <= this.hRangeLower)) {
            this.hRangeLower = hLow;
            this.hRangeUpper = hUp;
        }
        
        var ybounds = data.getVisibleDataBounds(true);
        var ydiff = ybounds[1] - ybounds[0];
        var yMinRange = (1e-15) / xdiff;
        yMinRange = Math.max(yMinRange, 1e-14); //Clamp for FPEs
        
        if (vUp - vLow >= yMinRange ||
            (vUp >= this.vRangeUpper && vLow <= this.vRangeLower)) {
            this.vRangeLower = vLow;
            this.vRangeUpper = vUp;
        }
    }
	
	/*
	// Use: mytimeline.draw();
	//
	// This draws the timeline to the canvas.
	*/
	
	this.draw = function(){
        
        $("a[rel^='prettyPhoto']").prettyPhoto();

        fixFieldLabels();
		
        var xbounds = data.getVisibleTimeBounds();
		var xmin = xbounds[0];
		var xmax = xbounds[1];
		var xdiff = xmax - xmin;
		
		xmax = xdiff * this.hRangeUpper + xmin;
		xmin = xdiff * this.hRangeLower + xmin;
		// --- //
		var ybounds = data.getVisibleDataBounds(true);
		var ymin = ybounds[0];
		var ymax = ybounds[1];
		var ydiff = ymax - ymin;
                
                ymin -= ydiff * 0.05;
                ymax += ydiff * 0.05;
                ydiff = ymax - ymin;
		
		ymax = ydiff * this.vRangeUpper + ymin;
		ymin = ydiff * this.vRangeLower + ymin;
		// --- //
			
		this.clear();
        
		drawXAxis(xmin, xmax, this, "time");
		drawYAxis(ymin, ymax, this);
		
		this.plotData();
		
		// --- //
		
		if( timeline.mouseX >= 0 && timeline.mouseX < timeline.drawwidth && timeline.mouseY >= 0 && timeline.mouseY < timeline.drawheight ){
							
			timeline.context.strokeStyle = "rgb(0,0,255)";
			
			var localxoff = this.fontheight/4 + this.xoff;
			
			for( var i = 0; i < data.sessions.length; i++ ){
				
				var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 1.0, 0.4 );
				
				timeline.context.strokeStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 1.0)";

				for( var j = 0; j < data.fields.length; j++ ){

					var displaydata = data.getDataFrom(i,j);
					
					if( j == 1 ){
						/*						// bkmk
						if( this.one2one ){
						
							var label = displaydata[Math.floor(displaydata.length*timeline.mouseX/timeline.drawwidth)] + ( i != data.sessions.length-1 ? ", " : "" );
						
							this.context.fillStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 1.0)";

							this.context.fillText( label, localxoff, this.fontheight);
						
							localxoff += this.context.measureText(label).width;
						
							// --- //
						
							if( this.hRangeLower == 0 && this.hRangeUpper == 1 && this.vRangeLower == 0 && this.vRangeUpper == 1 ){
						
								var y = (displaydata[Math.floor(displaydata.length*timeline.mouseX/timeline.drawwidth)]-data.getMin())/(data.getMax()-data.getMin());
						
								var x = timeline.mouseX + timeline.xoff;
							
								timeline.context.beginPath();
						
								timeline.context.arc(	timeline.mouseX + timeline.xoff,
														timeline.drawheight - y*timeline.drawheight + timeline.yoff,
														2.5,
														0,
														Math.PI*2, false	);

								timeline.context.stroke();

								timeline.context.closePath();
							
							}
						
						}
						*/
					}
					
				}
				
			}
		
		}
		
		// --- //
		
		// still need to check # of points under selection

		var x = mouseClkX;
		var y = this.drawheight - mouseClkY;
		
		if(this.dragflag){
		
			this.context.fillStyle = "rgba(0,0,200, 0.25)";
		
			this.context.fillRect(	x + this.xoff,
									y + this.yoff,
									this.mouseX - x,
									this.mouseY - y);
										
			this.context.strokeStyle = "rgb(0,0,255)";

			this.context.strokeRect(	x + this.xoff,
										y + this.yoff,
										this.mouseX - x,
										this.mouseY - y);
											
		}
         
		
	}
	
	// Starts the vis back up after another vis has been active
	
	this.start = function(){
		
        this.drawControls();
        
		this.draw();
		
		this.inited = 1;
		
		this.setListeners();
		
	}
	
	/*
	// Use: mytimeline.init();
	//
	// This draws the timeline to the canvas and populates
	// the div that holds the timeline controls. Call only once.
	*/
	
	this.init = function(){

		this.canvas		= document.getElementById("viscanvas");
		this.controls	= document.getElementById("controldiv");
		this.context	= this.canvas.getContext('2d');

		this.canvaswidth	= this.canvas.width;
		this.canvasheight	= this.canvas.height;

		// RMS is a crappy way to calculate this. It should be done better.
		this.fontheight = Math.floor( Math.sqrt( this.canvasheight*this.canvasheight + this.canvaswidth*this.canvaswidth ) / 55 );

		this.context.font = this.fontheight + "px sans-serif";

		this.xlabelsize = Math.floor(this.fontheight*2);
		this.ylabelsize = this.context.measureText(getLargestLabel()).width;

		this.drawwidth	= Math.floor(this.canvaswidth	- (this.ylabelsize*1.2));
		this.drawheight	= Math.floor(this.canvasheight	- (this.xlabelsize*2.5));
		
		this.minpoints = this.drawwidth*2;

		this.xoff = 0;
		this.yoff = this.fontheight*3/2;

		this.hRangeLower = 0.0;
		this.hRangeUpper = 1.0;
		this.vRangeLower = 0.0;
		this.vRangeUpper = 1.0;

		this.one2one = false;

		this.animateTimeout = null;
		
		this.drawTimeout = null;

		this.mouseX = 0;
		this.mouseY = 0;

		this.bgcolor = "rgb(255,255,255)";
		this.gridcolor = "rgb(0,0,0)";
		this.bordercolor = "rgb(0,0,0)";
		this.textcolor = "rgb(0,0,0)";
		
		this.inited = true;
		
		this.drawflag = false;

		this.start();
		
	}
	
	// end gets this vis outa' there.
	
	this.end = function(){
		
		$('canvas#viscanvas').unbind();
		
		$('div#controldiv').find().unbind();
		
		$('div#controldiv').empty();
		
		clearTimeout(this.drawTimeout);
		
		clearTimeout(this.animateTimeout);
		
		this.clear();
		
	}
	
}
