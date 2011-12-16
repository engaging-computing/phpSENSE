
// Function descriptions are all above the function they describe

console.log(data);

var scatter = new function Scatter(){
	
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
	
	/*
	// Use: myscatter.drawControls();
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

		// --- //
		
		this.controls.innerHTML = controls;
		
	}
	
	this.setListeners = function(){
		
		$('button#resetview').click(function(e){
			
			scatter.hRangeLower = 0.0;
			scatter.hRangeUpper = 1.0;
			scatter.vRangeLower = 0.0;
			scatter.vRangeUpper = 1.0;
			
			scatter.drawflag = true;
			
		});
		
		$('input.sessionvisible').click(function(e){
			
			var visible = data.sessions[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.sessions[$(e.target).val()].visibility = visible;
			
			scatter.drawflag = true;
			
		});
		
		$('input.fieldvisible').click(function(e){
			
			var visible = data.fields[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.fields[$(e.target).val()].visibility = visible;
			
			scatter.drawflag = true;
			
		});
		
		$('#viscanvas').mousemove(function(e){
			
			scatter.mouseX = e.pageX - $('canvas#viscanvas').offset().left - scatter.xoff;
			scatter.mouseY = e.pageY - $('canvas#viscanvas').offset().top - scatter.yoff;
			
			scatter.drawflag = true;
			
		});
		
		$('#viscanvas').bind('mousewheel', function(e){
			
			if( scatter.mouseX > 0 && 
				scatter.mouseY > 0 && 
				scatter.mouseX <= scatter.drawwidth &&
				scatter.mouseY <= scatter.drawheight ){
			
				e.preventDefault();
				e.stopPropagation();
				
				var hdiff = scatter.hRangeUpper - scatter.hRangeLower;
				var vdiff = scatter.vRangeUpper - scatter.vRangeLower;
				
				var delta = (e.wheelDelta/2400);
					
				var ldx = delta*scatter.mouseX/scatter.drawwidth;
				var udx = delta*(1-scatter.mouseX/scatter.drawwidth);
				var ldy = delta*(1-scatter.mouseY/scatter.drawheight);
				var udy = delta*scatter.mouseY/scatter.drawheight;
				
				scatter.hRangeLower += ldx*hdiff;
				scatter.hRangeUpper -= udx*hdiff;
				scatter.vRangeLower += ldy*vdiff;
				scatter.vRangeUpper -= udy*vdiff;
				
				if( scatter.hRangeLower < 0 ) scatter.hRangeLower = 0;
				if( scatter.hRangeUpper > 1 ) scatter.hRangeUpper = 1;
				if( scatter.vRangeLower < 0 ) scatter.vRangeLower = 0;
				if( scatter.vRangeUpper > 1 ) scatter.vRangeUpper = 1;
				
				if( scatter.vRangeLower > scatter.vRangeUpper ){
					
					var temp = scatter.vRangeLower;
					
					scatter.vRangeLower = scatter.vRangeUpper;
					
					scatter.vRangeUpper = temp;
					
				}
				
				if( scatter.hRangeLower > scatter.hRangeUpper ){
					
					var temp = scatter.hRangeLower;
					
					scatter.hRangeLower = scatter.hRangeUpper;
					
					scatter.hRangeUpper = temp;
					
				}
				
				scatter.drawflag = true;
			
			}
			
		});
		
		// --- //
		
		$('#viscanvas').mousedown(function(e){
     
			var x = e.pageX - $('canvas#viscanvas').offset().left - scatter.xoff;
            var y = scatter.drawheight - ( e.pageY - $('canvas#viscanvas').offset().top - scatter.yoff );

			mouseClkX = x;
			mouseClkY = y;

			scatter.dragflag = true;

			scatter.drawflag = true;
                
        });

		$('#viscanvas').mouseup(function(e){
            
            // still need to check # of points under selection

            var x = e.pageX - $('canvas#viscanvas').offset().left - scatter.xoff;
            var y = e.pageY - $('canvas#viscanvas').offset().top - scatter.yoff;

            var hdiff = scatter.hRangeUpper - scatter.hRangeLower;
            var vdiff = scatter.vRangeUpper - scatter.vRangeLower;

            if( x != mouseClkX && y != mouseClkY ){

                if( x >= 0 && x < scatter.drawwidth && y >= 0 && y < scatter.drawheight ){
	
					var temp;

                    mouseRlsX = x;
                    mouseRlsY = ( scatter.drawheight ) - y;

                    var hrl = ( mouseClkX > mouseRlsX ? mouseRlsX : mouseClkX ) / scatter.drawwidth;
                    var hru = ( mouseClkX < mouseRlsX ? mouseRlsX : mouseClkX ) / scatter.drawwidth;

                    var vrl = ( mouseClkY > mouseRlsY ? mouseRlsY : mouseClkY ) / scatter.drawheight;
                    var vru = ( mouseClkY < mouseRlsY ? mouseRlsY : mouseClkY ) / scatter.drawheight;
					

                    scatter.hRangeUpper = scatter.hRangeLower + hru * hdiff;
                    scatter.hRangeLower = scatter.hRangeLower + hrl * hdiff;
                    
                    scatter.vRangeUpper = scatter.vRangeLower + vru * vdiff;
                    scatter.vRangeLower = scatter.vRangeLower + vrl * vdiff;

                    scatter.drawflag = true;
                
                }
            
            }

			scatter.dragflag = false;
			
			scatter.drawflag = true;

        });
		
		scatter.drawTimeout = setTimeout( function(){
			
			if(scatter.drawflag) scatter.draw();
			
			scatter.drawflag = false;
			
			scatter.drawTimeout = setTimeout(arguments.callee, 1000/15);
			
		}, 1000/15 );
		
	}
	
	/*
	// Use: myscatter.clear();
	//
	// This clears the canvas and redraws the graph border.
	*/
	
	this.clear = function(){
		
		this.context.fillStyle = this.bgcolor;
	    this.context.fillRect(0, 0, this.canvaswidth, this.canvasheight);
	
		this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;
		this.context.strokeRect(this.xoff, this.yoff, this.drawwidth, this.drawheight);
		
	}
	
	/*
	// Use: myscatter.drawGrid( xAxisDivisions, yAxisDivisions );
	//
	// This should be used after calling drawLabelsXAxis() and
	// drawLabelsYAxis(), as they return the optimal number of X
	// and Y axis divisions. The methods mentioned may be and
	// should be used as arguments to drawGrid().
	*/
	
	this.drawGrid = function(xdivs, ydivs){

	    this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;

      	this.context.beginPath();

	    for( var i = 1; i < xdivs; i++ ){
		
	        this.context.moveTo(i * this.drawwidth/xdivs + this.xoff, 0 + this.yoff);
	        this.context.lineTo(i * this.drawwidth/xdivs + this.xoff, this.drawheight + this.yoff);

	    }

	    for( var i = 1; i < ydivs; i++ ){
		
	        this.context.moveTo(this.xoff, i*this.drawheight/ydivs + this.yoff);
	        this.context.lineTo(this.drawwidth + this.xoff, i*this.drawheight/ydivs + this.yoff);

	    }
	
		this.context.closePath();
	
		this.context.stroke();

	}
	
	/*
	// Use: Don't. Really. You shouldn't be using this directly.
	//
	// This method plots the line that corresponds to the point
	// data. It takes into account X and Y axis zoom/range.
	*/
	
	this.plot = function(points){
		
		if( points.length ){
		
		}
		
	}
	
	this.drawPoint = function(x, y, color, xmin, xmax, ymin, ymax){
		
		var xdiff = xmax - xmin;
		var ydiff = ymax - ymin;
		
		var hrdiff = this.hRangeUpper - this.hRangeLower;
		var vrdiff = this.vRangeUpper - this.vRangeLower;
		
		xmax = xdiff * this.hRangeUpper + xmin;
		xmin = xdiff * this.hRangeLower + xmin;
		
		ymax = ydiff * this.vRangeUpper + ymin;
		ymin = ydiff * this.vRangeLower + ymin;
		
		x = ( x - xmin ) / ( xmax - xmin );
		y = ( y - ymin ) / ( ymax - ymin );
		
		this.context.strokeStyle = color;
	
	    this.context.lineWidth = 1.25;
		
		if( x <= 1 && x >= 0 && y <= 1 && y >= 0 ){
				
			this.context.beginPath();
		
			this.context.arc(	this.xoff+x*this.drawwidth,
								this.drawheight - y*this.drawheight + this.yoff,
								2.5,
								0,
								Math.PI*2, false	);
		
			this.context.stroke();
			
			this.context.closePath();
		
		}
		
	}
	
	/*
	// Use: myscatter.plotData();
	//
	// This method plots the lines that correspond to the session
	// data. It takes into account X and Y axis zoom/range.
	*/
		
	this.plotData = function( xdata, ydata, xmin, xmax, ymin, ymax, color ){
		/*
		var ydiff = ymax - ymin;
		
		ymax = ymin + ydiff*this.hRangeLower;
		
		ymax = ymin + ydiff*this.hRangeUpper;
		
		ydiff = ymax - ymin;
				
		var xdiff = xmax - xmin;
		
		xmax = xmin + xdiff*this.vRangeLower;
		
		xmax = xmin + xdiff*this.vRangeUpper;

		xdiff = xmax - xmin;
		*/
		// --- //
		
		for(var i = 0; i <= xdata.length; i++){

			this.drawPoint(xdata[i], ydata[i], color, xmin, xmax, ymin, ymax);
			
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
	// Use: myscatter.draw();
	//
	// This draws the scatter to the canvas.
	*/
	
	this.draw = function(){
		
		var numsessions = data.sessions.length;
		
		var numfields = data.fields.length;
		
		// --- //
		
		var time = null;
		
		for( var i = 0; i < numfields; i++ ){
			
			if( data.fields[i].type_id == 7 ){
				
				time = i;
				
			}
			
		}
		
		// --- //
		
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
		
		this.drawLabelsXAxis( xmin, xmax, xinc );
		
		this.drawLabelsYAxis( ymin, ymax, yinc );
		
		// --- //
		
		xmin = data.getFieldMin("time");
		xmax = data.getFieldMax("time");
		
		ymin = data.getMin();
		ymax = data.getMax();
		
		for( var i = 0; i < numsessions; i++ ){
			
			for( var j = 0; j < numfields; j++ ){
		
				if( data.sessions[i].visibility && data.fields[j].visibility && data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ){
			
					var color = hslToRgb( ( 0.6 + ( 1.0*i/numsessions ) ) % 1.0, 1.0, 0.125 + (0.75*j/data.fields.length)  );
			
					var color = 'rgb(' + color[0] + ',' + color[1] + ',' + color[2] + ')';
		
					this.plotData( data.getDataFrom(i,time), data.getDataFrom(i,j), xmin, xmax, ymin, ymax, color );
	
				}
			
			}
	
		}
		
		// --- //
		
		var x = this.mouseX - $('canvas#viscanvas').offset().left - this.xoff;
        var y = this.mouseY - $('canvas#viscanvas').offset().top - this.yoff;

        var hdiff = this.hRangeUpper - this.hRangeLower;
        var vdiff = this.vRangeUpper - this.vRangeLower;
/*
        if( x >= 0 && x < this.drawwidth && y >= 0 && y < this.drawheight ){

			this.context.strokeStyle = "rgb(0,0,255)";

			var localxoff = this.fontheight/4 + this.xoff;

			for( var i = 0; i < data.sessions.length; i++ ){

				var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 1.0, 0.4 );

				this.context.strokeStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 1.0)";

				for( var j = 0; j < data.fields.length; j++ ){

					var displaydata = data.getDataFrom(i,j);

					if( data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ){

						var label = displaydata[Math.floor(displaydata.length*this.mouseX/this.drawwidth)] + ( i != data.sessions.length-1 ? ", " : "" );

						this.context.fillStyle = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 1.0)";

						this.context.fillText( label, localxoff, this.fontheight);

						localxoff += this.context.measureText(label).width;
						
					}
					
				}
				
			}
				
		}*/
		
		x = mouseClkX;
		y = this.drawheight - mouseClkY;
		
		if(this.dragflag){
		
			this.context.fillStyle = "rgba(0,0,200, 0.25)";
		
			this.context.fillRect(	x + this.xoff,
									y + this.yoff,
									this.mouseX - x,
									this.mouseY - y );
										
			this.context.strokeStyle = "rgb(0,0,255)";

			this.context.strokeRect(	x + this.xoff,
										y + this.yoff,
										this.mouseX - x,
										this.mouseY - y );
											
		}
		
	}
	
	/*
	// Use: myscatter.init();
	//
	// This draws the scatter to the canvas and populates
	// the div that holds the scatter controls.
	*/
	
	this.start = function(){
			
		this.draw();
		
		this.drawControls();
		
		this.setListeners();
		
	}
	
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
	
	this.end = function(){
		
		$('canvas#viscanvas').unbind();
		
		$('div#controldiv').find().unbind();
		
		$('div#controldiv').empty();
		
		clearTimeout(this.drawTimeout);
		
		clearTimeout(this.animateTimeout);
		
		this.clear();
		
	}
	
}