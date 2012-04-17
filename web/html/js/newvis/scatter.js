
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
		
		controls += buildSessionControls('scatter');
		
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
		
		$('input[id^=fieldvisible]').click(function(e){
			
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
        
        var xbounds, xdiff, xMinRange;
        
        //Datapoint
        if (this.xAxis == -1) {
            xbounds = [-1,0];
            
            for (ses in data.sessions) {
                xbounds[1] = Math.max(xbounds[1], data.sessions[ses].data.length);
            }
            xdiff = xbounds[1] - xbounds[0];
            xMinRange = 4 / xdiff;
        }
        else if (data.fields[this.xAxis].type_id == 7){
            xbounds = data.getVisibleTimeBounds();
            xdiff = xbounds[1] - xbounds[0];
            xMinRange = 10 / xdiff;
        }
        else{
            xbounds = data.getFieldBounds([data.fields[this.xAxis].name], false);
            xdiff = xbounds[1] - xbounds[0];
            xMinRange = (1e-15) / xdiff;
        }
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
	// Use: myscatter.draw();
	//
	// This draws the scatter to the canvas.
	*/
	
	this.draw = function(){
        
		fixFieldLabels();
        
		// --- //
		
		var time = null;
		
		for( var i = 0; i < data.fields.length; i++ ){
			
			if( data.fields[i].type_id == 7 ){
				
				time = i;
				
			}
			
		}
		
		// --- //
		var xbounds;
        
        //Datapoint
        if (this.xAxis == -1) {
            xbounds = [-1,0];
            
            for (ses in data.sessions) {
                xbounds[1] = Math.max(xbounds[1], data.sessions[ses].data.length);
            }
        }
		else if (data.fields[this.xAxis].type_id == 7){
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
		
        var xAxisName;
        if (this.xAxis == -1) {
            xAxisName = 'Datapoint #';
        }
        else {
            xAxisName = data.fields[this.xAxis].name.toLowerCase();
        }
        
		drawXAxis(xmin, xmax, this, xAxisName);
        drawYAxis(ymin, ymax, this);
		
		// --- //
		
        var dpNum = 0;
        
        for( var i = 0; i < data.sessions.length; i++ ){
            
            for( var j = 0; j < data.fields.length; j++ ){
                
                if( data.sessions[i].visibility && data.fields[j].visibility && data.fields[j].type_id != 7){
                    
                    var color = getFieldColor(j, i);
                    var color = 'rgb(' + color[0] + ',' + color[1] + ',' + color[2] + ')';
                    
                    if (this.xAxis == -1) {
                        var dpArr = [];
                        
                        for (dp in data.sessions[ses].data) {
                            dpArr.push(Number(dpNum) + Number(dp));
                        }
                        
                        this.plotData(dpArr, data.getDataFrom(i,j), xmin, xmax, ymin, ymax, color);
                        //console.log([dpArr, data.getDataFrom(i,j), xmin, xmax, ymin, ymax]);
                    }
                    else {
                        this.plotData( data.getDataFrom(i,this.xAxis), data.getDataFrom(i,j), xmin, xmax, ymin, ymax, color );
                    }   
                }
            }
        }
        
		// --- //
		
		var x = this.mouseX - $('canvas#viscanvas').offset().left - this.xoff;
        var y = this.mouseY - $('canvas#viscanvas').offset().top - this.yoff;

        var hdiff = this.hRangeUpper - this.hRangeLower;
        var vdiff = this.vRangeUpper - this.vRangeLower;
        
		
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
			
        this.drawControls();
        
		this.draw();
		
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
		this.drawheight	= Math.floor(this.canvasheight	- (this.xlabelsize*2.5));

		this.xoff = 0;
		this.yoff = this.fontheight*3/2;

		this.hRangeLower = 0.0;
		this.hRangeUpper = 1.0;
		this.vRangeLower = 0.0;
		this.vRangeUpper = 1.0;
        
        this.xAxis = -1;

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
         $("a[rel^='prettyPhoto']").prettyPhoto();
		
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
