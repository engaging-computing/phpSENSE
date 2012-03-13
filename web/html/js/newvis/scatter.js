
// Function descriptions are all above the function they describe
console.log(data);
var scatter = new function Scatter(){
	
	/*
	// Use: myscatter.drawControls();
	//
	// This will populate controls (it's broken right now)
	*/
	
	this.drawControls = function(){
		
		var controls = '';
		
		controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div><button id="resetview" type="button">Reset View</button></div>';
		
		controls += '<div id="sessioncontrols" style="float:left;margin:10px;">';
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Sessions:</tr></td>';
		
		for( var i in data.sessions ){
			
			var color = hslToRgb((0.6 + (1.0 * i/data.sessions.length)) % 1.0, 0.825, 0.425 );
			
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
		
		controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">X Axis:</tr></td>';
		
        for( var i in data.fields ){
            //check if field is text
            if (data.fields[i].type_id != 37){ 
                controls += '<tr><td>';
                
                controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#000000;float:left;">';
                
                controls += '<input class="xaxis" type="radio" name="xaxisselect" value="' + i + '" ' + ( i === '0' ? 'checked' : '' ) + '></input>&nbsp;';
                
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
			
				var color = Math.floor(((0.75*i/data.fields.length)) * 256).toString(16);
                if (color.length === 1){
                    color = '0' + color;
                }
                color = color + color + color;
			
				controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#' + color + ';float:left;">';
			
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
        
        $('input.xaxis').click(function(e){
            
            scatter.xAxis = $(e.target).val();
            
            scatter.drawflag = true;
            
        });
		
        bindMouseZoom(scatter);		
		
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
	
	this.drawPoint = function(x, y, color, xmin, xmax, ymin, ymax){
		
		var xdiff = xmax - xmin;
		var ydiff = ymax - ymin;
		
		/*var hrdiff = this.hRangeUpper - this.hRangeLower;
		var vrdiff = this.vRangeUpper - this.vRangeLower;
		
		xmax = xdiff * this.hRangeUpper + xmin;
		xmin = xdiff * this.hRangeLower + xmin;
		
		ymax = ydiff * this.vRangeUpper + ymin;
		ymin = ydiff * this.vRangeLower + ymin;*/
		
		x = ( x - xmin ) / ( xmax - xmin );
		y = ( y - ymin ) / ( ymax - ymin );
		
		this.context.strokeStyle = color;
	
	    this.context.lineWidth = 1.25;
		
		if( x <= 1 && x >= 0 && y <= 1 && y >= 0 ){
				
			this.context.beginPath();
		
			this.context.arc(this.xoff+x*this.drawwidth,
							this.drawheight - y*this.drawheight + this.yoff,
							2.0,
							0,
							Math.PI*2, false);
		
			this.context.stroke();
			
			this.context.closePath();
            
            this.context.fillStyle = color;
            this.context.fill();
		}
		
	}
	
	/*
	// Use: myscatter.plotData();
	//
	// This method plots the lines that correspond to the session
	// data. It takes into account X and Y axis zoom/range.
	*/
		
	this.plotData = function( xdata, ydata, xmin, xmax, ymin, ymax, color ){
		
		for(var i = 0; i <= xdata.length; i++){

			this.drawPoint(xdata[i], ydata[i], color, xmin, xmax, ymin, ymax);
			
		}
	}
	
	/*
     * Stub for zooming bounds - needs to be set up like the timeline one
     * to actually consider... anything.
     */
	this.setBounds = function(hLow, hUp, vLow, vUp){
        this.hRangeLower = hLow;
        this.hRangeUpper = hUp;
        this.vRangeLower = vLow;
        this.vRangeUpper = vUp;
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
		var xbounds;
		if (data.fields[this.xAxis].type_id == 7){
            xbounds = data.getVisibleTimeBounds();
        }
        else{
            xbounds = data.getFieldBounds([data.fields[this.xAxis].name], false);
        }
        var xmin = xbounds[0];
        var xmax = xbounds[1];
        var xdiff = xmax - xmin;
		
		xmax = xdiff * this.hRangeUpper + xmin;
		xmin = xdiff * this.hRangeLower + xmin;

		xdiff = xmax - xmin;
		
		// --- //
		
		var ybounds = data.getVisibleDataBounds(true);
        var ymin = ybounds[0];
        var ymax = ybounds[1];
        var ydiff = ymax - ymin;
		
		ymax = ydiff * this.vRangeUpper + ymin;
		ymin = ydiff * this.vRangeLower + ymin;

		ydiff = ymax - ymin;
		
		// --- //
		
		this.clear();
		
		drawXAxis(xmin, xmax, this, data.fields[this.xAxis].name.toLowerCase());
        drawYAxis(ymin, ymax, this);
		
		// --- //
		
		for( var i = 0; i < numsessions; i++ ){
			
			for( var j = 0; j < numfields; j++ ){
		
				if( data.sessions[i].visibility && data.fields[j].visibility && data.fields[j].type_id != 7){
			
					var color = hslToRgb( ( 0.6 + ( 1.0*i/numsessions ) ) % 1.0, 1.0, 0.125 + (0.75*j/data.fields.length)  );
					var color = 'rgb(' + color[0] + ',' + color[1] + ',' + color[2] + ')';
		
					this.plotData( data.getDataFrom(i,this.xAxis), data.getDataFrom(i,j), xmin, xmax, ymin, ymax, color );
	
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
        
        this.xAxis = 0;

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