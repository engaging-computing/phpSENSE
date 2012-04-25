
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
			
				controls += '<tr><td><div style="font-size:14px;font-family:Arial;text-align:center;color:#' + color.toString(16) + color.toString(16) + color.toString(16) + ';float:left;">';
			
				controls += data.fields[i].name + '&nbsp;</div></td>';
			
				controls += '<td><input class="fieldvisible" type="checkbox" value="' + i + '" ' + ( data.fields[i].visibility ? 'checked' : '' ) + '></input>&nbsp;</td>';
			
				controls += '<td><select id="mode_' + i + '" class="fieldmode"><option>Max</option><option>Min</option><option>Mean</option><option>Median</option><option>Mode</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				
				// <option>Mean</option><option>Median</option><option>Mode</option>
			
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
		
		$('select.fieldmode').change(function(){
			
			var i = parseInt($(this).attr('id').replace(/mode_/,''));
			
			data.fields[i].mode = $(this).val(); // disabling this line removes side effects that randomly delete data. :<
			
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
		this.context.strokeRect(0, 0 + this.fontheight/2, this.drawwidth, this.drawheight);
		
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
		
		/*
		var ymin = data.getMin();
		var ymax = data.getMax();*/
		
		var ybounds = data.getVisibleDataBounds(true);
		var ymin = ybounds[0];
		var ymax = ybounds[1];
		
		var ydiff = ymax - ymin;

		var yinc = ydiff/(this.drawheight/(this.fontheight*3/2));
		
		// --- //
		
		var divs = 0;
		
		var barcolors = new Array();
		
		var barvals = new Array();
		
		var visiblesessions = 0;
		
		var visiblefields = 0;
		
		// --- //
		
		drawYAxis(ymin, ymax, this);
		
		for( var session in data.sessions ){
			
			if(data.sessions[session].visibility) visiblesessions++;
			
		}
		
		for( var field in data.fields ){
			
			if( data.fields[field].visibility && data.fields[field].type_id != 7 && data.fields[field].type_id != 19 && data.fields[field].type_id != 37 ){
			
				if(data.fields[field].visibility) visiblefields++;
				
			}
			
		}
		
		for( var j = 0; j < data.fields.length; j++ ){
		
			if( data.fields[j].visibility && data.fields[j].type_id != 7 && data.fields[j].type_id != 19 && data.fields[j].type_id != 37 ){
		
				for( var i = 0; i < data.sessions.length; i++ ){
			
					if( data.sessions[i].visibility ){
				
						var color = getSessionColor(i);
						
						var val = 0;

						barcolors[divs] = "rgba(" + color[0] + "," + color[1] + "," + color[2] + ", 0.85)";
					
						switch(data.fields[j].mode){
				
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
							val = data.sessions[i].getMeanVal(j);
							break;
							case 'Median':
							val = data.getMedianVal(j,i);
							break;
							case 'Mode':
							val = data.sessions[i].getModeVal(j);
							break;
		
						}
						
						barvals[divs] = (parseFloat(val))/ydiff;
						
						divs++;
				
					}
			
				}

				barvals[divs] = null;

				divs++;
			
			}
		
		}
		
		divs--;
		
		for( var i = 0; i < divs; i++ ){
			
			var height = barvals[i];
		
			if( height != null ){
		
				var color = getSessionColor(i);
		
				this.context.fillStyle = barcolors[i];
	
				this.context.fillRect( this.drawwidth*i/divs, this.drawheight - (this.drawheight*(-ymin)/ydiff) + this.yoff, this.drawwidth/divs, -height*this.drawheight );

				var linewidth = 0.5;
			
				this.context.lineWidth = linewidth;
	
				this.context.strokeStyle = "rgba( 0,0,0,1.0)";
	
				this.context.strokeRect( this.drawwidth*i/divs, this.drawheight - (this.drawheight*(-ymin)/ydiff) + this.yoff, this.drawwidth/divs, -height*this.drawheight );
			
			}
			
		}
		
		var fieldinc = 0;
		
		for(var i = 0; i < data.fields.length; i++){
			
			if( data.fields[i].visibility && data.fields[i].type_id != 7 && data.fields[i].type_id != 19 && data.fields[i].type_id != 37 ){
				
				var shade = Math.floor(((0.75*i/data.fields.length)) * 256);
			
				this.context.fillStyle = "rgb("+shade+","+shade+","+shade+")";
				
				this.context.textAlign = "center";
				
				this.context.fillText(	data.fields[i].name,
										((fieldinc*(visiblesessions+1))*(this.drawwidth/divs)) + ((visiblesessions)*(this.drawwidth/divs)/2),
										this.drawheight+this.fontheight+this.yoff,
										(visiblesessions)*(this.drawwidth/divs), 
										this.fontheight);
				
				if(data.fields[i].mode){
				
					this.context.fillText(	"("+data.fields[i].mode+")",
											((fieldinc*(visiblesessions+1))*(this.drawwidth/divs)) + ((visiblesessions)*(this.drawwidth/divs)/2),
											this.drawheight+(this.fontheight*2)+this.yoff,
											(visiblesessions)*(this.drawwidth/divs), 
											this.fontheight);
										
				} else {
					
					this.context.fillText(	"(Max)",
											((fieldinc*(visiblesessions+1))*(this.drawwidth/divs)) + ((visiblesessions)*(this.drawwidth/divs)/2),
											this.drawheight+(this.fontheight*2)+this.yoff,
											(visiblesessions)*(this.drawwidth/divs), 
											this.fontheight);
					
				}
				
				this.context.textAlign = "left";
				
				fieldinc++;
				
			}
			
		}
		
		// bkmk
		
		/*
		if(i%(data.sessions.length+1) == 0){
			
			var inc = i/(data.sessions.length+1);
			
			console.log(inc);
		
			this.context.fillText(data.fields[inc].name, (this.drawwidth*inc/divs*(data.sessions.length+1)) + (this.drawwidth/divs/2*(data.sessions.length+1)), this.drawheight + this.yoff + this.fontheight, this.drawwidth/divs*(data.sessions.length+1));
	
			this.context.textAlign = 'left';
		
		}
		*/
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
		this.ylabelsize = this.context.measureText(getLargestLabel()).width;
                
                this.drawwidth  = Math.floor(this.canvaswidth   - (this.ylabelsize*1.2));
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
