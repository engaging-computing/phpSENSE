
// Function descriptions are all above the function they describe

var histogram = new function Histogram(){
	
	function ArrayMax( n ){
		
		var x = n[0];
		
		for( i in n ){
			
			if( n[i] > x ) x = n[i];
			
		}
		
		return x;
		
	}
	
	function ArrayMin( n ){
		
		var x = n[0];
		
		for( i in n ){
			
			if( n[i] < x ) x = n[i];
			
		}
		
		return x;
		
	}
	
	/*
	// Use: myhistogram.drawControls();
	//
	// This will populate controls (it's broken right now)
	*/
	
	this.drawControls = function(){
		
		var controls = '';
		
		controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div>';

		controls += '<button id="set_bins" type="button">Set #Bins:</button><input type="text" id="num_bins" value="' + this.numbins + '"></input>';
		
		controls += '</div>';
		
		controls += '<div id="sessioncontrols" style="float:left;margin:10px;">';
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Sessions:</tr></td>';
		
		for( var i in data.sessions ){
			
			var color = hslToRgb( ( 0.6 + ( 1.0*i/data.sessions.length ) ) % 1.0, 0.825, 0.425 );
			
			controls += '<tr><td>';
			
			controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#' + (color[0]>>4).toString(16) + (color[1]>>4).toString(16) + (color[2]>>4).toString(16) + ';float:left;">';
			
			controls += '<input class="sessionvisible" type="checkbox" value="' + i + '" ' + ( data.sessions[i].visibility ? 'checked' : '' ) + '></input>' + '&nbsp;';
			
			controls += data.sessions[i].meta.name;
			
			controls += '</div>';
			
			controls += '</td></tr>';
			
		}
		
		controls += '</table>'
		
		controls += '</div>';
		
		// --- //
		
		controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
		
		for( var i in data.fields ){
			
			if( data.fields[i].type_id != 7 && data.fields[i].type_id != 19 && data.fields[i].type_id != 37 ){ // Should properly check if field is time
				
				controls += '<tr><td>';
			
				var color = Math.floor(((0.75*i/data.fields.length)) * 256);
			
				controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#' + color.toString(16) + color.toString(16) + color.toString(16) + ';float:left;">';
			
				controls += '<input class="fieldvisible" type="checkbox" value="' + i + '" ' + ( data.fields[i].visibility ? 'checked' : '' ) + '></input>&nbsp;';

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
		
		$('input.sessionvisible').click(function(e){
			
			var visible = data.sessions[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.sessions[$(e.target).val()].visibility = visible;
			
			histogram.draw();
			
		});
		
		$('input.fieldvisible').click(function(e){
			
			var visible = data.fields[$(e.target).val()].visibility;
			
			visible = visible > 0 ? 0 : 1;
			
			data.fields[$(e.target).val()].visibility = visible;
			
			histogram.draw();
			
		});
		
		$('#viscanvas').mousemove(function(e){
			
			histogram.mouseX = e.pageX - $('canvas#viscanvas').offset().left;
			histogram.mouseY = e.pageY - $('canvas#viscanvas').offset().top;
			
		});
		
		$('button#set_bins').click(function(e){
			
			if($('input#num_bins').val() > 50) $('input#num_bins').val(50);
			
			histogram.numbins = $('input#num_bins').val();
			
			histogram.draw();
			
		});
		
	}
	
	/*
	// Use: myhistogram.clear();
	//
	// This clears the canvas and redraws the graph border.
	*/
	
	this.clear = function(){
		
		this.context.fillStyle = this.bgcolor;
	    this.context.fillRect (0, 0, this.canvaswidth, this.canvasheight);
		
		this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;
		this.context.strokeRect(0, 0 + this.fontheight/2, this.drawwidth, this.drawheight);
		
	}
	
	/*
	// Use: Don't. Really. You shouldn't be using this directly.
	//
	// This method plots the line that corresponds to the point
	// data. It takes into account X and Y axis zoom/range.
	*/
	
	/*
	// Use: myhistogram.drawLabelsYAxis();
	//
	// This draws the labels for the Y axis of the graph.
	*/
	
	this.getIncrement = function(mininc){
		
		var out = 1;
		
		var minincint = mininc;
		
		var divtonormalize = 1;
		
		while( minincint != Math.floor(minincint) ){
			
			minincint *= 10;
			
			divtonormalize *= 10;
			
		}
		
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
	
	this.drawLabelsYAxis = function(ymin, ymax){
		
		if( ymin > 0 ) ymin = 0;
		
		var ydiff = ymax - ymin;
		
		var inc = ydiff/(this.drawheight/(this.fontheight*3/2));

		this.context.font = this.fontheight + "px sans-serif";

		this.context.fillStyle = "rgb(0,0,0)";
		
		var label = Math.round( ymin );

		this.context.fillText( label.toString(), this.drawwidth + this.fontheight/2, this.drawheight + this.fontheight*1/3 + this.yoff );

		var label = Math.round( ymin + ydiff );

		this.context.fillText( label.toString(), this.drawwidth + this.fontheight/2, this.fontheight*1/3 + this.yoff );
		
		// --- //
		
		var iinc = this.getIncrement(inc);
		
		var istart = this.getOffset(ymin, iinc);
		
		// --- //
					
		for( var i = istart; i < ymax; i += iinc ){
			
			var y = this.drawheight - ((i-ymin)*this.drawheight/ydiff-this.fontheight/3) + this.yoff;
			
			var gridy =  this.drawheight - ((i-ymin)*this.drawheight/ydiff) + this.yoff;
			
			label = Math.floor(i/iinc)*iinc;
			
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
	// Use: myhistogram.draw();
	//
	// This draws the histogram to the canvas.
	*/
	
	this.draw = function(){
		
		this.clear();
		
		// --- //
		
		var numfields = 0;
		
		var inc = 0;
		
		for( var i in data.fields ){
			
			if( data.fields[i].visibility && data.fields[i].type_id != 7 && data.fields[i].type_id != 19 ) numfields++;
			
		}
		
		var binmax = null;
		
		var binmin = 0;
		
		var numbins = this.numbins;
		
		// --- //
		
		for( var field in data.fields ){
			
			var max = data.getFieldMax(data.fields[field].name);

			var min = data.getFieldMin(data.fields[field].name);
			
			// --- //
			
			if( data.fields[field].visibility && data.fields[field].type_id != 7 && data.fields[field].type_id != 19 /* && field == 3 */ ){
		
				var alldata = new Array();
			
				var bins = new Array();
			
				// --- //
			
				for( var i in data.sessions ){
		
					alldata = alldata.concat(data.getDataFrom(i,field));
		
				}
			
				// --- //
			
				for( var i in alldata ){
		
					var index = Math.floor( ((alldata[i]-min)/(max-min))*numbins );
		
					if( index != numbins ){
		
						if( bins[index] ){
			
							bins[index]++;
			
						} else {
			
							bins[index] = 1;
			
						}
			
					} else {
			
						if( bins[numbins-1] ){
			
							bins[numbins-1]++;
			
						} else {
			
							bins[numbins-1] = 1;
			
						}
			
					}
		
				}
				
				// --- //
			
				for( var session in data.sessions ){
				
					if( bimmax = null ){
					
						binmax = ArrayMax(bins);
					
					} else {
					
						if( ArrayMax(bins) > binmax ) binmax = ArrayMax(bins);
					
					}
				
				}
				
				// --- End --- //
			
			}
			
		}
		
		// --- //
		
		this.drawLabelsYAxis(binmin,binmax);
		
		// --- //
		
		for( var field in data.fields ){
			
			if( data.fields[field].visibility && data.fields[field].type_id != 7 && data.fields[field].type_id != 19 /* && field == 3 */ ){
		
				var alldata = new Array();
		
				var visibledata = new Array();
		
				var bins = new Array();
		
				var visiblebins = new Array();
		
				var max = data.getFieldMax(data.fields[field].name);
				
				if( isNaN(max) ) max = 0;
		
				var min = data.getFieldMin(data.fields[field].name);
				
				if( isNaN(min) ) min = 0;
				
				var diff = max - min;
		
				for( i in data.sessions ){
			
					if( data.sessions[i].visibility ) visibledata = visibledata.concat(data.getDataFrom(i,field));
			
					alldata = alldata.concat(data.getDataFrom(i,field));
			
				}
		
				for( var i in alldata ){
			
					var index = Math.floor( ((alldata[i]-min)/(max-min))*numbins );
			
					if( index != numbins ){
			
						if( bins[index] ){
				
							bins[index]++;
				
						} else {
				
							bins[index] = 1;
				
						}
				
					} else {
				
						if( bins[numbins-1] ){
				
							bins[numbins-1]++;
				
						} else {
				
							bins[numbins-1] = 1;
				
						}
				
					}
			
				}
		
				for( var i in visibledata ){
			
					var index = Math.floor( ((visibledata[i]-min)/(max-min))*numbins );
			
					if( index != numbins ){
			
						if( visiblebins[index] ){
				
							visiblebins[index]++;
				
						} else {
				
							visiblebins[index] = 1;
				
						}
				
					} else {
				
						if( visiblebins[numbins-1] ){
				
							visiblebins[numbins-1]++;
				
						} else {
				
							visiblebins[numbins-1] = 1;
				
						}
				
					}
			
				}
				
				if( bimmax = null ){
					
					binmax = ArrayMax(bins);
					
				} else {
					
					if( ArrayMax(bins) > binmax ) binmax = ArrayMax(bins);
					
				}
		
				for( var i = 0; i < numbins; i++ ){
			
					var hue = hslToRgb( 0.6, 0.75, 0.125 + (0.75*field/data.fields.length) );
			
					var color = "rgb("+hue[0]+","+hue[1]+","+hue[2]+")";
			
					this.context.fillStyle = color;
			
					this.context.fillRect( this.drawwidth*inc/(numbins*numfields+(numfields-1)) + this.xoff, this.drawheight + this.yoff, this.drawwidth/(numbins*numfields+(numfields-1)), (-this.drawheight)*visiblebins[i]/binmax );
			
					var linewidth = 0.5;
		
					this.context.lineWidth = linewidth;

					this.context.strokeStyle = "rgba( 0,0,0,1.0)";

					this.context.strokeRect( this.drawwidth*inc/(numbins*numfields+(numfields-1)) + this.xoff, this.drawheight + this.yoff, this.drawwidth/(numbins*numfields+(numfields-1)), (-this.drawheight)*visiblebins[i]/binmax );
					
					// Draw X axis stuff
					
					var textpos = ((this.drawwidth*inc/(numbins*numfields+(numfields-1))) + (this.drawwidth*(inc+1)/(numbins*numfields+(numfields-1))))/2;
					
					textpos = textpos - ((this.drawwidth/(numbins*numfields+(numfields-1))))*3/12;
					
					this.context.save();
					this.context.translate( textpos + this.xoff, this.fontheight/2 + this.drawheight + this.yoff);
					this.context.rotate(Math.PI*1/4);
					this.context.textAlign = "left";
					
					this.context.fillStyle = "rgb(0,0,0)";
					
					this.context.font = this.fontheight*2/3 + "px sans-serif";
					
					this.context.fillText( parseFloat((diff*i/numbins+min)).toFixed(3) + " to " + parseFloat((diff*(i+1)/numbins+min)).toFixed(3), 0, 0 );
					this.context.restore();
		
					inc++;
			
				}
			
				inc++;
				
			}
			
		}
		
	}
	
	/*
	// Use: myhistogram.init();
	//
	// This draws the histogram to the canvas and populates
	// the div that holds the histogram controls.
	*/
	
	this.start = function(){
			
		this.clear();
			
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
		this.drawheight	= Math.floor(this.canvasheight	- (this.xlabelsize*3));

		this.xoff = 0;
		this.yoff = this.fontheight/2;

		this.hRangeLower = 0.0;
		this.hRangeUpper = 1.0;
		this.vRangeLower = 0.0;
		this.vRangeUpper = 1.0;

		this.one2one = false;
		
		this.numbins = 10;

		this.animateTimeout = null;

		this.mouseX = 0;
		this.mouseY = 0;

		this.bgcolor = "rgb(255,255,255)";
		this.gridcolor = "rgb(128,128,128)";
		this.bordercolor = "rgb(0,0,0)";
		this.textcolor = "rgb(0,0,0)";
		
		this.inited = true;

		this.start();
		
	}
	
	this.end = function(){
		
		$('canvas#viscanvas').unbind();
		
		$('div#controldiv').find().unbind();
		
		$('div#controldiv').empty();
		
		this.clear();
		
	}
	
}