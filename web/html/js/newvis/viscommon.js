/*  viscommon.js
    
    This file serves as a place to store code currently common or that may be useful to
    other visualization features.
    
    Created by cadorett 2/18/2012
    
*/


function clip(input, lbound, ubound){
		
    input = input > ubound ? ubound : input;
		
    input = input < lbound ? lbound : input;
		
    return input;
		
}

/*
    hslToRGB convers Hue/Saturation/Lightness values
    to 8bit RGB values. This is for generating unique
    colors for sessions/fields. I copy/pasted this from
    the interwebs because I'm a classy programmer.
    
    										- Eric F.
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

/*
    TODO: Validate this comment
    
    Assuming this function simply converts from rgb color representation
    to hex representation.
*/
function rgbToHex(hex){
    return toHex(hex[0])+toHex(hex[1])+toHex(hex[2]);
}

/*
    TODO: Validate this comment
    
    Assuming this function simply converts from decimal color representation
    to hexadecimal representation
*/
function toHex(n){
    n = parseInt(n, 10);
    if( isNaN(n) ) return '00';
    n = Math.max(0, Math.min(n, 255));
    return '0123456789ABCDEF'.charAt( (n-n%16) / 16 ) + '0123456789ABCDEF'.charAt(n%16);
} 

/* 
    TODO: Describe global mouseevent functions 
*/

// Update vis mouseover coordinates


function bindMouseZoom(visObject) {
	$('#viscanvas').mousemove(function(e){
		
		visObject.mouseX = e.pageX - $('canvas#viscanvas').offset().left - visObject.xoff;
		visObject.mouseY = e.pageY - $('canvas#viscanvas').offset().top - visObject.yoff;
		
		visObject.mouseX = clip( visObject.mouseX, 0, visObject.drawwidth );
		visObject.mouseY = clip( visObject.mouseY, 0, visObject.drawheight );
		
		visObject.drawflag = true;
		
	});   
    
    $('#viscanvas').bind('mousewheel', function(e, deltaraw){
    
        // Chris A Interprets this if as making sure the the zoom occurs inside the canvas area.
    	if( visObject.mouseX > 0 && 
    		visObject.mouseY > 0 && 
    		visObject.mouseX <= visObject.drawwidth && 
    		visObject.mouseY <= visObject.drawheight ){
    	
    		e.preventDefault();
    		e.stopPropagation();
    		
    		//console.log("Working with x and y: " + visObject.mouseX + " " + visObject.mouseY);
    		
    		var hdiff = visObject.hRangeUpper - visObject.hRangeLower;
    		var vdiff = visObject.vRangeUpper - visObject.vRangeLower;
    		
    		//console.log("hdiff, vdiff : " + hdiff + " " + vdiff);
    		
    		var delta = (deltaraw/120);		
    			
    		var ldx = delta*visObject.mouseX/visObject.drawwidth;
    		var udx = delta*(1-visObject.mouseX/visObject.drawwidth);
    		var ldy = delta*(1-visObject.mouseY/visObject.drawheight);
    		var udy = delta*visObject.mouseY/visObject.drawheight;
    		
    		visObject.hRangeLower += ldx*hdiff;
    		visObject.hRangeUpper -= udx*hdiff;
    		visObject.vRangeLower += ldy*vdiff;
    		visObject.vRangeUpper -= udy*vdiff;
    		
    		if( visObject.hRangeLower < 0 ) visObject.hRangeLower = 0;
    		if( visObject.hRangeUpper > 1 ) visObject.hRangeUpper = 1;
    		if( visObject.vRangeLower < 0 ) visObject.vRangeLower = 0;
    		if( visObject.vRangeUpper > 1 ) visObject.vRangeUpper = 1;
    		
    		if( visObject.vRangeLower > visObject.vRangeUpper ){
    			
    			var temp = visObject.vRangeLower;
    			
    			visObject.vRangeLower = visObject.vRangeUpper;
    			
    			visObject.vRangeUpper = temp;
    			
    		}
    		
    		if( visObject.hRangeLower > visObject.hRangeUpper ){
    			
    			var temp = visObject.hRangeLower;
    			
    			visObject.hRangeLower = visObject.hRangeUpper;
    			
    			visObject.hRangeUpper = temp;
    			
    		}
    		
    		visObject.drawflag = true;
    	     
    	}
    	
    });
    
    // Record click for drag selecting data range
    
    $('#viscanvas').mousedown(function(e){
    
    	var x = e.pageX - $('canvas#viscanvas').offset().left - visObject.xoff;
        var y = visObject.drawheight - ( e.pageY - $('canvas#viscanvas').offset().top - visObject.yoff );
    
    	x = clip(x, 0, visObject.drawwidth);
    	y = clip(y, 0, visObject.drawheight);
    
    	mouseClkX = x;
    	mouseClkY = y;
    
    	visObject.dragflag = true;
    
    	visObject.drawflag = true;
            
    });
    
    // Calculate new zoom level on mouse release is mouse position has changed since last click
    
    $('#viscanvas').mouseup(function(e){
        
        // still need to check # of points under selection
    
        var x = e.pageX - $('canvas#viscanvas').offset().left - visObject.xoff;
        var y = e.pageY - $('canvas#viscanvas').offset().top - visObject.yoff;
    
    	x = clip(x, 0, visObject.drawwidth);
    	y = clip(y, 0, visObject.drawheight);
    
        var hdiff = visObject.hRangeUpper - visObject.hRangeLower;
        var vdiff = visObject.vRangeUpper - visObject.vRangeLower;
    
        if( x != mouseClkX && y != mouseClkY ){
    
    		var temp;
    
            mouseRlsX = x;
            mouseRlsY = ( visObject.drawheight ) - y;
    
            var hrl = ( mouseClkX > mouseRlsX ? mouseRlsX : mouseClkX ) / visObject.drawwidth;
            var hru = ( mouseClkX < mouseRlsX ? mouseRlsX : mouseClkX ) / visObject.drawwidth;
    
            var vrl = ( mouseClkY > mouseRlsY ? mouseRlsY : mouseClkY ) / visObject.drawheight;
            var vru = ( mouseClkY < mouseRlsY ? mouseRlsY : mouseClkY ) / visObject.drawheight;
    
    
            visObject.hRangeUpper = visObject.hRangeLower + hru * hdiff;
            visObject.hRangeLower = visObject.hRangeLower + hrl * hdiff;
            
            visObject.vRangeUpper = visObject.vRangeLower + vru * vdiff;
            visObject.vRangeLower = visObject.vRangeLower + vrl * vdiff;
    
            visObject.drawflag = true;
        
        }
    
    	visObject.dragflag = false;
    	
    	visObject.drawflag = true;
    
    });
    
    visObject.drawTimeout = setTimeout( function(){
		
		if(visObject.drawflag) visObject.draw();
		
		visObject.drawflag = false;
		
		visObject.drawTimeout = setTimeout(arguments.callee, 1000/15);
		
	}, 1000/15 );

}