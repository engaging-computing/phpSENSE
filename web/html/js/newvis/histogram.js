
// Function descriptions are all above the function they describe

var histogram = new function Histogram(){
	
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
		
		var controls = '<div id="sessioncontrols">';
		
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
			
			if( i != 0 ){ // Should properly check if field is time
			
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
		this.context.rect(0, 0 + this.fontheight/2, this.drawwidth, this.drawheight);
	    this.context.stroke();
		
	}
	
	/*
	// Use: myhistogram.drawGrid( xAxisDivisions, yAxisDivisions );
	//
	// This should be used after calling drawLabelsXAxis() and
	// drawLabelsYAxis(), as they return the optimal number of X
	// and Y axis divisions. The methods mentioned may be and
	// should be used as arguments to drawGrid().
	*/
	
	this.drawGrid = function(xdivs, ydivs){

	    this.context.strokeStyle = this.gridcolor;
	    this.context.lineWidth = 0.25;

	    for( var i = 1; i < xdivs; i++ ){

	        this.context.beginPath();
	        this.context.moveTo(i * this.drawwidth/xdivs, 0 + this.fontheight/2);
	        this.context.lineTo(i * this.drawwidth/xdivs, this.drawheight + this.fontheight/2);
	        this.context.stroke();

	    }

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
	// Use: myhistogram.plotData();
	//
	// This method plots the lines that correspond to the session
	// data. It takes into account X and Y axis zoom/range.
	*/
	
	this.plotDataNormal = function(){

	}
	
	this.plotData = function(){
		
	}
	
	/*
	// Use: myhistogram.drawLabelsXAxis();
	//
	// This draws the labels for the X axis of the graph.
	*/
	
	this.drawLabelsXAxis = function(){
		
		var divs = 10;
		
		return divs;
		
	}
	
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
	
	this.drawLabelsYAxis = function(){
		
		var ymax = data.getMax();
		
		var ymin = data.getMin();
		
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
		
		var numbins = 5;
		
		this.drawGrid( 0, this.drawLabelsYAxis() );
		
		var alldata = new Array();
		
		var bins = new Array();
		
		var field = 3;
		
		var max = data.getFieldMax(data.fields[field].name);
		
		var min = data.getFieldMin(data.fields[field].name);
		
		for( i in data.sessions ){
				
			alldata = alldata.concat(data.getDataFrom(i,field));
			
		}
		
		console.log(alldata);
		
		for( i in alldata ){
			
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
		
		var binmax = ArrayMax(bins);
		
		var binmin = ArrayMin(bins);
		
		for( i in bins ){
			
			var hue = hslToRgb( 0.6, 0.75, 0.5 );
			
			var color = "rgb("+hue[0]+","+hue[1]+","+hue[2]+")";
			
			this.context.fillStyle = color;
			
			this.context.fillRect( this.drawwidth*i/numbins + this.xoff, this.drawheight + this.yoff, this.drawwidth/numbins, (-this.drawheight)*bins[i]/binmax );
			
			var linewidth = 0.5;
		
			this.context.lineWidth = linewidth;

			this.context.strokeStyle = "rgba( 0,0,0,1.0)";

			this.context.strokeRect( this.drawwidth*i/numbins + this.xoff, this.drawheight + this.yoff, this.drawwidth/numbins, (-this.drawheight)*bins[i]/binmax );
			
		}
		
	}
	
	/*
	// Use: myhistogram.init();
	//
	// This draws the histogram to the canvas and populates
	// the div that holds the histogram controls.
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
		this.yoff = this.fontheight/2;

		this.hRangeLower = 0.0;
		this.hRangeUpper = 1.0;
		this.vRangeLower = 0.0;
		this.vRangeUpper = 1.0;

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