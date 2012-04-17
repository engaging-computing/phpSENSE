
// Function descriptions are all above the function they describe

var bar = new function Bar(){
		
	/*
	// Use: mybar.drawControls();
	//
	// This will populate controls (it's broken right now)
	*/
	
	this.drawControls = function(){
		
		var controls = '';
		
		controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div></div>';
		
		controls += buildSessionControls('bar');
		
		// --- //
		
		controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
		
		for( var i in data.fields ){
			
			if( data.fields[i].type_id != 7 && data.fields[i].type_id != 19 ){ // Should properly check if field is time
			
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
	
	/*
	// Use: mybar.clear();
	//
	// This clears the canvas and redraws the graph border.
	*/
	
	this.clear = function(){
		
		this.context.fillStyle = this.bgcolor;
	    this.context.fillRect (0, 0, this.canvaswidth, this.canvasheight);
	
		this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;
		this.context.rect(0, 0 + this.fontheight/2, this.drawwidth, this.drawheight);
	    this.context.stroke();
		
	}
	
	/*
	// Use: mybar.drawGrid( xAxisDivisions, yAxisDivisions );
	//
	// This should be used after calling drawLabelsXAxis() and
	// drawLabelsYAxis(), as they return the optimal number of X
	// and Y axis divisions. The methods mentioned may be and
	// should be used as arguments to drawGrid().
	*/
	
	this.drawGrid = function(xdivs, ydivs){

	    this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;

	    for( var i = 1; i < ydivs; i++ ){

	        this.context.beginPath();
	        this.context.moveTo(0, i*this.drawheight/ydivs + this.fontheight/2);
	        this.context.lineTo(this.drawwidth, i*this.drawheight/ydivs + this.fontheight/2);
	        this.context.stroke();

	    }

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
	
	/*
	// Use: mybar.plotData();
	//
	// This method plots the lines that correspond to the session
	// data. It takes into account X and Y axis zoom/range.
	*/
	
	this.plotDataNormal = function(){

	}
	
	this.plotData = function(){
		
	}
	
	/*
	// Use: mybar.drawLabelsXAxis();
	//
	// This draws the labels for the X axis of the graph.
	*/
	
	this.drawLabelsXAxis = function(){
		
		var divs = 10;
		
		return divs;
		
	}
	
	/*
	// Use: mybar.drawLabelsYAxis();
	//
	// This draws the labels for the Y axis of the graph.
	*/	

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
	// Use: mybar.draw();
	//
	// This draws the bar to the canvas.
	*/
	
	this.getHue = function( index, numpoints ){
		
		var out = 0;
		
		switch( index % 3 ){
			
			case 0:
			
			out = index/(numpoints*3);
			
			break;
			case 1:
			
			out = index/(numpoints*3) + (numpoints/3);
			
			break;
			
			case 2:
			
			out = index/(numpoints*3) + (numpoints*2/3);
			
			break;
			
		}
		
		return out;
		
	}

	
	this.draw = function(){
		
		this.clear();
		$("a[rel^='prettyPhoto']").prettyPhoto();
		// -- //
		
		var ymin = data.getMin();
		var ymax = data.getMax();
		
		var ydiff = ymax - ymin;

		var yinc = ydiff/(this.drawheight/(this.fontheight*3/2));
		
		// -- //
		
		this.drawGrid( this.drawLabelsXAxis(), this.drawLabelsYAxis(ymin, ymax, yinc) );
		
		// --- //
		
		var divs = 0;
		
		var barcolors = new Array();
		
		var barvals = new Array();
		
		ymax = data.getMax();
		
		ymin = data.getMin();
		
		if( ymin > 0 ) ymin = 0;
		
		ydif = ymax - ymin;
		
		for( var j = 0; j < data.fields.length; j++ ){
		
			if( data.fields[j].visibility && data.fields[j].type_id != 7 && data.fields[j].type_id != 19 ){
		
				for( var i = 0; i < data.sessions.length; i++ ){
			
					if( data.sessions[i].visibility ){
				
						var color = hslToRgb( ( 0.6 + i/data.sessions.length ) % 1.0, 1.0, 0.125 + (0.75*j/data.fields.length) );
						
						var val = 0;

						barcolors[divs] = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 1.0)";
					
						switch(data.fields[j].calc){
				
							default:
							val = data.sessions[i].getMaxVal(j);
							break;
							case 'Max':
		 					val = data.sessions[i].getMaxVal(j);
							break;
							case 'Min':
							val = data.sessions[i].getMinVal(j);
							break;
							case 'Mean':
							val = data.sessions[i].getMeanVal(data.fields[j].name);
							break;
							case 'Median':
							val = data.sessions[i].getMedianVal(data.fields[j].name);
							break;
							case 'Mode':
							val = data.sessions[i].getModeVal(data.fields[j].name);
							break;
		
						}
						
						barvals[divs] = (parseFloat(val))/ydif;
						
						divs++;
				
					}
			
				}
				
				if( j < data.fields.length - 1 ){

					barvals[divs] = null;

					divs++;

				}
			
			}
		
		}
		
		
		for( var i = 0; i < divs; i++ ){
			
			var height = barvals[i];
		
			if( height != null ){
		
				var color = hslToRgb( ( 0.6 + i/divs ) % 1.0, 1.0, 0.4 );
		
				this.context.fillStyle = barcolors[i];
	
				this.context.fillRect( this.drawwidth*i/divs, this.drawheight - (this.drawheight*(-ymin)/ydif) + this.yoff, this.drawwidth/divs, -height*this.drawheight );

				var linewidth = 0.5;
			
				this.context.lineWidth = linewidth;
	
				this.context.strokeStyle = "rgba( 0,0,0,1.0)";
	
				this.context.strokeRect( this.drawwidth*i/divs, this.drawheight - (this.drawheight*(-ymin)/ydif) + this.yoff, this.drawwidth/divs, -height*this.drawheight );
			
			}
			
		}
		
	}
	

	/*
	// Use: mybar.init();
	//
	// This draws the bar to the canvas and populates
	// the div that holds the bar controls.
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
		this.drawheight	= Math.floor(this.canvasheight	- (this.xlabelsize*1.5));

		this.xoff = 0;
		this.yoff = this.fontheight/2;

		this.one2one = false;

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
