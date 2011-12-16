
// Function descriptions are all above the function they describe
// because I like to know what a method/function does before I have
// to look at the code, so that makes sense to me.

var timeline = new function Timeline(){
	
	/*
	// hslToRGB convers Hue/Saturation/Lightness values
	// to 8bit RGB values. This is for generating unique
	// colors for sessions/fields. I copy/pasted this from
	// the interwebs because I'm a classy programmer.
	//
	//										- Eric F.
	*/ 
	
	function hslToRgb(h, s, l){
	    var r, g, b;

	    if(s == 0){
	        r = g = b = l; // achromatic
	    }else{
	        function hue2rgb(p, q, t){
	            if(t < 0) t += 1;
	            if(t > 1) t -= 1;
	            if(t < 1/6) return p + (q - p) * 6 * t;
	            if(t < 1/2) return q;
	            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
	            return p;
	        }

	        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
	        var p = 2 * l - q;
	        r = Math.floor( hue2rgb(p, q, h + 1/3) * 255 );
	        g = Math.floor( hue2rgb(p, q, h) * 255 );
	        b = Math.floor( hue2rgb(p, q, h - 1/3) * 255 );
	    }

	    return [r, g, b];
	}

	function rgbToHex(hex){
		return toHex(hex[0])+toHex(hex[1])+toHex(hex[2]);
	}

	function toHex(n){
		n = parseInt(n, 10);
		if( isNaN(n) ) return '00';
		n = Math.max(0, Math.min(n, 255));
		return '0123456789ABCDEF'.charAt( (n-n%16) / 16 ) + '0123456789ABCDEF'.charAt(n%16);
	}
	
	this.inited = 0;
	
	/*
	// Use: mytimeline.drawControls();
	//
	// This will populate controls (it's broken right now)
	*/
	
	this.drawControls = function(){
		
		var controls = '<div id="sessioncontrols">';
		
		controls += '<button id="resetview" type="button">Reset View</button><br><br>';
		
		for( var i in data.sessions ){
			
			var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 0.825, 0.425 );
			
			controls += '<div style="font-size:14px;font-family:Arial;text-align:center;height:100px;width:150px;color:#' + (color[0]>>4).toString(16) + (color[1]>>4).toString(16) + (color[2]>>4).toString(16) + ';float:left;">';
			
			controls += data.sessions[i].meta.name + '&nbsp;';
			
			controls += '<input class="sessionvisible" type="checkbox" value="' + i + '" ' + ( data.sessions[i].visibility ? 'checked' : '' ) + '></input>';
			
			controls += '</div>';
			
		}
		
		controls += '</div>';
		
		// --- //
		
		controls += '<br><div id="fieldcontrols">';
		
		for( var i in data.fields ){
			
			if( data.fields[i].type_id != 7 && data.fields[i].type_id != 19 ){ // Should properly check if field is time
			
				var color = Math.floor(((0.75*i/data.fields.length)) * 256);
			
				controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#' + color.toString(16) + color.toString(16) + color.toString(16) + ';float:left;">';
			
				controls += data.fields[i].name + '&nbsp;';
			
				controls += '<input class="fieldvisible" type="checkbox" value="' + i + '" ' + ( data.fields[i].visibility ? 'checked' : '' ) + '></input>&nbsp;';
			
				controls += '</div>';
			
			}
			
		}
		
		controls += '</div>';
		
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
		
		$('input.fieldvisible').click(function(e){
			
			var visible = data.fields[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.fields[$(e.target).val()].visibility = visible;
			
			timeline.drawflag = true;
			
		});
		
		// Update vis mouseover coordinates
		
		$('#viscanvas').mousemove(function(e){
			
			timeline.mouseX = e.pageX - $('canvas#viscanvas').offset().left - timeline.xoff;
			timeline.mouseY = e.pageY - $('canvas#viscanvas').offset().top - timeline.yoff;
			
			timeline.drawflag = true;
			
		});
		
		// Recalculate vis scale parameters to zoom in/out with mouse wheel
		
		$('#viscanvas').bind('mousewheel', function(e){
			
			if( timeline.mouseX > 0 && 
				timeline.mouseY > 0 && 
				timeline.mouseX <= timeline.drawwidth && 
				timeline.mouseY <= timeline.drawheight ){
			
				e.preventDefault();
				e.stopPropagation();
				
				var hdiff = timeline.hRangeUpper - timeline.hRangeLower;
				var vdiff = timeline.vRangeUpper - timeline.vRangeLower;
				
				var delta = (e.wheelDelta/2400);
					
				var ldx = delta*timeline.mouseX/timeline.drawwidth;
				var udx = delta*(1-timeline.mouseX/timeline.drawwidth);
				var ldy = delta*(1-timeline.mouseY/timeline.drawheight);
				var udy = delta*timeline.mouseY/timeline.drawheight;
				
				timeline.hRangeLower += ldx*hdiff;
				timeline.hRangeUpper -= udx*hdiff;
				timeline.vRangeLower += ldy*vdiff;
				timeline.vRangeUpper -= udy*vdiff;
				
				if( timeline.hRangeLower < 0 ) timeline.hRangeLower = 0;
				if( timeline.hRangeUpper > 1 ) timeline.hRangeUpper = 1;
				if( timeline.vRangeLower < 0 ) timeline.vRangeLower = 0;
				if( timeline.vRangeUpper > 1 ) timeline.vRangeUpper = 1;
				
				if( timeline.vRangeLower > timeline.vRangeUpper ){
					
					var temp = timeline.vRangeLower;
					
					timeline.vRangeLower = timeline.vRangeUpper;
					
					timeline.vRangeUpper = temp;
					
				}
				
				if( timeline.hRangeLower > timeline.hRangeUpper ){
					
					var temp = timeline.hRangeLower;
					
					timeline.hRangeLower = timeline.hRangeUpper;
					
					timeline.hRangeUpper = temp;
					
				}
				
				timeline.drawflag = true;
			
			}
			
		});
		
		// Record click for drag selecting data range
		
		$('#viscanvas').mousedown(function(e){
     
			var x = e.pageX - $('canvas#viscanvas').offset().left - timeline.xoff;
            var y = timeline.drawheight - ( e.pageY - $('canvas#viscanvas').offset().top - timeline.yoff );

			mouseClkX = x;
			mouseClkY = y;

			timeline.dragflag = true;

			timeline.drawflag = true;
                
        });

		// Calculate new zoom level on mouse release is mouse position has changed since last click

		$('#viscanvas').mouseup(function(e){
            
            // still need to check # of points under selection

            var x = e.pageX - $('canvas#viscanvas').offset().left - timeline.xoff;
            var y = e.pageY - $('canvas#viscanvas').offset().top - timeline.yoff;

            var hdiff = timeline.hRangeUpper - timeline.hRangeLower;
            var vdiff = timeline.vRangeUpper - timeline.vRangeLower;

            if( x != mouseClkX && y != mouseClkY ){

                if( x >= 0 && x < timeline.drawwidth && y >= 0 && y < timeline.drawheight ){
	
					var temp;

                    mouseRlsX = x;
                    mouseRlsY = ( timeline.drawheight ) - y;

                    var hrl = ( mouseClkX > mouseRlsX ? mouseRlsX : mouseClkX ) / timeline.drawwidth;
                    var hru = ( mouseClkX < mouseRlsX ? mouseRlsX : mouseClkX ) / timeline.drawwidth;

                    var vrl = ( mouseClkY > mouseRlsY ? mouseRlsY : mouseClkY ) / timeline.drawheight;
                    var vru = ( mouseClkY < mouseRlsY ? mouseRlsY : mouseClkY ) / timeline.drawheight;
					

                    timeline.hRangeUpper = timeline.hRangeLower + hru * hdiff;
                    timeline.hRangeLower = timeline.hRangeLower + hrl * hdiff;
                    
                    timeline.vRangeUpper = timeline.vRangeLower + vru * vdiff;
                    timeline.vRangeLower = timeline.vRangeLower + vrl * vdiff;

                    timeline.drawflag = true;
                
                }
            
            }

			timeline.dragflag = false;
			
			timeline.drawflag = true;

        });
		
		// THIS IS THE MAIN ANIMATION LOOP, DUDE // <-- Did I write this? XD
		
		setTimeout( function(){

			if(timeline.drawflag) timeline.draw();

			timeline.drawflag = false;
			
			setTimeout(arguments.callee, 1000/15);
			
		}, 1000/15 );
		
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
		
		var max = data.getMax();
	
		var min = data.getMin();//data.getMin() >= 0 ? 0 : data.getMin();
	
		var diff = max - min;
	
		// --- Display point-by-point --- //
	
		for( var i = 0; i < data.sessions.length; i++ ){
		
			var hmin = data.getFieldMin('time');
		
			var hmax = data.getFieldMax('time');
		
			var hdif = hmax - hmin;
		
			var vmin = data.getMin();
			
			var vmax = data.getMax();
				
			var vdif = vmax - vmin;
		
			// --- //
		
			for( var j = 0; j < data.fields.length; j++ ){
				
				var displaydata = data.getDataFrom(i,j);
				
				var timedata = data.getDataFrom(i,0); // time
				
				var datalen = displaydata.length;
			
				var plotarray = new Array();
			
				var inc = 0;
				
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
				
				var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 1.0, 0.125 + (0.75*j/data.fields.length) );

				this.context.strokeStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 0.825)";

			    this.context.lineWidth = 1 + (j/data.fields.length);
		
				if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ) this.plot(plotarray);
			
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
				
				if( data.sessions[i].visibility == 1 && data.fields[j].visibility == 1 && data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ){
				
					for( var k = 0; k < datalen; k++ ){					
			
						if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19  ){ // bkmk must add checks for lat/long
		
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
			
				if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ) this.plot(plotarray); //bkmk
			
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

	// Below are some helper functions for drawLabelsXAxis()
	
	this.getResolution = function(timediff){
		
		var threshold = 5;
		
		if( ( timediff /= 1000 ) < threshold ) return 0;
		
		if( ( timediff /= 60 ) < threshold ) return 1;
		
		if( ( timediff /= 60 ) < threshold ) return 2;
		
		if( ( timediff /= 24 ) < threshold ) return 3;
		
		if( ( timediff /= 30 ) < threshold ) return 4;
		
		if( ( timediff /= 12 ) < threshold ) return 5;
		
		return 6;
		
	}
	
	this.getIncX = function(resolution){
		
		switch(resolution){
			
			case 0:
			return 1;
			break;
			case 1:
			return 1000;
			break;
			case 2:
			return 1000*60;
			break;
			case 3:
			return 1000*60*60;
			break;
			case 4:
			return 1000*60*60*24;
			break;
			case 5:
			return 1000*60*60*24*365/12;
			break;
			case 6:
			return 1000*60*60*24*365;
			break;
			
		}
		
	}
	
	this.formatDate = function(startdate, dateoffset, resolution){
		
		var date = new Date(startdate + dateoffset);
		
		switch(resolution){
			
			case 0: // Milliseconds
			
			return Math.floor(dateoffset) + "ms";
			
			break;
			
			case 1: // Seconds
			
			return Math.floor(dateoffset/1000) + "s";
			
			break;
			
			case 2: // Minutes
			
			return Math.floor(dateoffset/(1000*60)) + "m";
			
			break;
			
			case 3: // Hours
			
			return ( ( date.getMonth() + 1 ) + '/' + date.getDate() + '/' + date.getFullYear() ) + ": " + date.getHours() + "h";
			
			break;
			
			case 4: // Days
			
			return ( date.getMonth() + 1 ) + '/' + date.getDate() + '/' + date.getFullYear();
			
			break;
			
			case 5: // Months
			
			return ( date.getMonth() + 1 ) + '/' + date.getDate() + '/' + date.getFullYear();
			
			break;
			
			case 6: // Years
			
			return date.getFullYear();
			
			break;
			
		}
		
		return "";
		
	}

	/*
	// Use: mytimeline.drawLabelsXAxis();
	//
	// This draws the labels for the X axis of the graph.
	*/

	this.drawLabelsXAxis = function(xmin,xmax,inc){
		
		/*
		var divs = 10;
		
		if( this.one2one ){

			var lower = 0;
			var upper = data.getMaxDatapoints();
			
			var lower = upper * this.hRangeLower;
			var upper = upper * this.hRangeUpper;
			
			var diff = upper - lower;
		
			var maxtextwidth = this.context.measureText( upper ).width;
		
			divs = Math.floor(this.drawwidth/maxtextwidth*4/5);
		
		
			if( this.fontheight > 4 ){

				this.context.font = this.fontheight + "px sans-serif";
		
				this.context.fillStyle = "rgb(0,0,0)";
		
				for( var i = 1; i < divs; i++ ){
				
					var text = Math.floor(lower + (i*diff/divs));
			
					//text = diff;
			
					var textwidth = Math.floor( this.context.measureText( text ).width );
			
					this.context.fillText( text, (i*this.drawwidth/divs) - (textwidth*3/5), this.drawheight + this.fontheight + this.yoff);
					
					this.context.strokeStyle = this.gridcolor;
					this.context.lineWidth = 0.25;
					this.context.beginPath();
			        this.context.moveTo(i*this.drawwidth/divs, 0 + this.yoff);
			        this.context.lineTo(i*this.drawwidth/divs, this.drawheight + this.yoff);
			        this.context.stroke();
					this.context.closePath();
			
				}
		
			}

		} else {
			
			var divs = 5;

			var xdiff = xmax - xmin;

			var hrdiff = this.hRangeUpper - this.hRangeLower;

			xmax = xdiff * this.hRangeUpper + xmin;
			xmin = xdiff * this.hRangeLower + xmin;

			xdiff = xmax - xmin;

			this.context.font = this.fontheight + "px sans-serif";

			this.context.fillStyle = "rgb(0,0,0)";

			for( var i = 1; i < divs; i++ ){

				var label = this.formatDate(xmin*1000, Math.floor(i*(xdiff*1000)/divs), this.getResolution(xdiff*1000));

				var textwidth = Math.floor( this.context.measureText( label ).width );

				this.context.fillText( label.toString(), (i*this.drawwidth/divs) - (textwidth*3/5), this.drawheight + this.fontheight + this.yoff );
				
				this.context.strokeStyle = this.gridcolor;
				this.context.lineWidth = 0.25;
				this.context.beginPath();
		        this.context.moveTo(i*this.drawwidth/divs, 0 + this.yoff);
		        this.context.lineTo(i*this.drawwidth/divs, this.drawheight + this.yoff);
		        this.context.stroke();
				this.context.closePath();

			}

			
			if( this.fontheight > 4 ){

				this.context.font = this.fontheight + "px sans-serif";

				this.context.fillStyle = "rgb(0,0,0)";

				for( var i = 0; i <= divs; i++ ){

					var label = Math.round( min + (i*diff/divs) );//diff >= 10 ? Math.floor(((((i*(this.vRangeUpper-this.vRangeLower))+this.vRangeLower)*diff/divs) + min)) : ((i*diff/divs) + min);

					this.context.fillText(  label.toString(), this.drawwidth + this.fontheight/2, this.drawheight - (i*this.drawheight/divs-this.fontheight/3) + this.fontheight/2);

				}

			}
			
		}

		return divs;
		*/
		
		if( this.one2one ){
			
			
			
		} else {

			xmin = xmin; // Used to be * 1000
			
			xmax = xmax; // Used to be * 1000

			var xdiff = xmax - xmin;
			
			inc = this.getIncX(this.getResolution(xdiff));
			
			this.context.font = this.fontheight + "px sans-serif";

			this.context.fillStyle = "rgb(0,0,0)";
			
			var labels = new Array();
			
			var xpos = 0;
			
			for( var i = this.getOffset(xmin, inc); i < xmax; i += inc ){
				
				if( (i-xmin)*this.drawwidth/xdiff >= xpos ){
				
					var label = this.formatDate(xmin,i-xmin,this.getResolution(xdiff));
				
					labels[label] = new Array();
				
					labels[label]['label'] = label;
				
					labels[label]['xpos'] = (i-xmin)*this.drawwidth/xdiff;
				
					xpos = ((i-xmin)*this.drawwidth/xdiff) + (this.context.measureText(label).width*4/3);
					
				}
				
			}
			
			for( i in labels ){
				
				if( this.context.measureText(labels[i]['label']).width + labels[i]['xpos'] < this.drawwidth )
				
				this.context.fillText( labels[i]['label'], labels[i]['xpos'] + this.xoff, this.drawheight + this.yoff + this.fontheight );

				this.context.strokeStyle = this.gridcolor;
				this.context.lineWidth = 0.25;
				this.context.beginPath();
		        this.context.moveTo(labels[i]['xpos'] + this.xoff, 0 + this.yoff);
		        this.context.lineTo(labels[i]['xpos'] + this.xoff, this.drawheight + this.yoff);
		        this.context.stroke();
				this.context.closePath();
				
			}
			
		}
		
	}

	// Below are some helper functions for drawLabelsYAxis()

	this.getIncrement = function(mininc){
		
		var out = 1;
		
		var minincint = mininc;
		
		var divtonormalize = 1;
		/*
		while( minincint != Math.floor(minincint) ){
			
			minincint *= 10;
			
			divtonormalize *= 10;
			
		}*/
		
		var i = 1;
		
		if( minincint >= 1 ){
		
			while( out < minincint ){
			
				out = Math.pow(10, i);
			
				i++;
			
			}
		
		} else {
			
			while( out/Math.pow(10, i) > minincint ){
			
				out = out/Math.pow(10, i);
			
				i++;
			
			}
			
		}

		return out/divtonormalize;
		
	}
	
	this.getOffset = function(min, inc){
		
		return (Math.floor(min/inc)*inc) + inc;
		
	}
	
	/*
	// Use: mytimeline.drawLabelsYAxis();
	//
	// This draws the labels for the Y axis of the graph.
	*/
	
	this.drawLabelsYAxis = function(ymin, ymax, inc){

		var ydiff = ymax - ymin;

		this.context.font = this.fontheight + "px sans-serif";

		this.context.fillStyle = "rgb(0,0,0)";
		
		// --- //
		
		var iinc = this.getIncrement(inc);
		
		var istart = this.getOffset(ymin, iinc);
		
		var n = Math.floor(Math.log(1/iinc)/Math.log(10));
		
		n = n > 0 ? n : 0;
		
		// --- //
		
		var label = ymin.toFixed(n);

		this.context.fillText( label.toString(), this.drawwidth + this.fontheight/2, this.drawheight + this.fontheight*1/3 + this.yoff );

		var label = (ymin + ydiff).toFixed(n);

		this.context.fillText( label.toString(), this.drawwidth + this.fontheight/2, this.fontheight*1/3 + this.yoff );
		
		// --- //
					
		for( var i = istart; i < ymax; i += iinc ){
			
			var y = this.drawheight - ((i-ymin)*this.drawheight/ydiff-this.fontheight/3) + this.yoff;
			
			var gridy =  this.drawheight - ((i-ymin)*this.drawheight/ydiff) + this.yoff;
			
			var n = Math.floor(Math.log(1/iinc)/Math.log(10));
			
			n = n > 0 ? n : 0;
			
			label = (i.toFixed(n)).toString();
			
			if( y > this.fontheight + this.yoff && y < this.yoff + this.drawheight - this.fontheight/2 )
		
				this.context.fillText( label.toString(), this.drawwidth + this.fontheight/2, y );
						
			this.context.strokeStyle = this.gridcolor;
			this.context.lineWidth = 0.25;		
			this.context.beginPath();
	        this.context.moveTo(0, gridy);
	        this.context.lineTo(this.drawwidth, gridy);
	        this.context.stroke();
			this.context.closePath();
			
		}

		return 0;

	}
	
	/*
	// Use: mytimeline.draw();
	//
	// This draws the timeline to the canvas.
	*/
	
	this.draw = function(){
		
		var xmin = data.getFieldMin("time");
		var xmax = data.getFieldMax("time");
		
		var xdiff = xmax - xmin;
		
		xmax = xdiff * this.hRangeUpper + xmin;
		xmin = xdiff * this.hRangeLower + xmin;

		xdiff = xmax - xmin;
		
		var xinc = xdiff/(this.drawwidth/(this.fontheight*5));
		
		// --- //
		
		var ymin = data.getMin();
		var ymax = data.getMax();
		
		var ydiff = ymax - ymin;
		
		ymax = ydiff * this.vRangeUpper + ymin;
		ymin = ydiff * this.vRangeLower + ymin;

		ydiff = ymax - ymin;
		
		var yinc = ydiff/(this.drawheight/(this.fontheight*3/2));
		
		// --- //
			
		this.clear();
		
		this.drawLabelsXAxis(xmin,xmax,xinc);
		this.drawLabelsYAxis(ymin,ymax,yinc);
		
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
			
		this.draw();
		
		this.inited = 1;
		
		this.drawControls();
		
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
		this.ylabelsize = this.context.measureText( data.getMax() + "" ).width + this.fontheight/2;

		this.drawwidth	= Math.floor(this.canvaswidth	- (this.ylabelsize*1.5));
		this.drawheight	= Math.floor(this.canvasheight	- (this.xlabelsize*1.5));

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
		this.gridcolor = "rgb(128,128,128)";
		this.bordercolor = "rgb(0,0,0)";
		this.textcolor = "rgb(0,0,0)";
		
		this.inited = true;
		
		this.drawflag = false;
		
		this.dragflag = false;

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
