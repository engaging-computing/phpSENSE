/*  visaxiscommon.js
 *   
 *  This file serves as a place to store code for dealing with graph/chart axis.
 *  
 *  Created by mmcguinn 2/23/2012
 *  
 */

var timeSizes = {
    milisecond:1,
    second:1000,
    minute:1000*60,
    hour:  1000*60*60,
    day:   1000*60*60*24,
    month: 1000*60*60*24*365.25 / 12,
    year:  1000*60*60*24*365.25
};
timeSizes.resolutions = 
     [[timeSizes.month * 6,  [1, 6]],
     [timeSizes.month  * 3,  [1, 3]],
     [timeSizes.month  * 2,  [1, 2]],
     [timeSizes.month  * 1,  [1, 1]],
     [timeSizes.day    * 14, [2, 14]],
     [timeSizes.day    * 7,  [2, 7]],
     [timeSizes.day    * 3,  [2, 3]],
     [timeSizes.day    * 1,  [2, 1]],
     [timeSizes.hour   * 12, [3, 12]],
     [timeSizes.hour   * 6,  [3, 6]],
     [timeSizes.hour   * 3,  [3, 3]],
     [timeSizes.hour   * 2,  [3, 2]],
     [timeSizes.hour   * 1,  [3, 1]],
     [timeSizes.minute * 30, [4, 30]],
     [timeSizes.minute * 20, [4, 20]],
     [timeSizes.minute * 10, [4, 10]],
     [timeSizes.minute * 5,  [4, 5]],
     [timeSizes.minute * 2,  [4, 2]],
     [timeSizes.minute * 1,  [4, 1]],
     [timeSizes.second * 30, [5, 1]],
     [timeSizes.second * 20, [5, 1]],
     [timeSizes.second * 10, [5, 1]],
     [timeSizes.second * 5,  [5, 1]],
     [timeSizes.second * 2,  [5, 1]],
     [timeSizes.second * 1,  [5, 1]]];

/*
 * Generates a time incrment value for an axis.
 * 
 * @param minDiv Minimum number of divisions on the screen.
 * @param maxDiv Maximum number of divisions on the screen.
 * @param diff   Range of time (ms) on-screen.
 * 
 * @return The calculated incrment
 */
function getTimeIncrement(minDiv, maxDiv, timeDiff){
    
    if (timeDiff / timeSizes.year > minDiv){
        //x years
        return [0, getDataIncrement(minDiv, maxDiv, timeDiff / timeSizes.year)];
    }
    
    var base;
    for (res in timeSizes.resolutions){
        base = timeSizes.resolutions[res][0];
        
        if (timeDiff / base > minDiv){
            return timeSizes.resolutions[res][1];
        }
    }
    
    //x miliseconds
    return [6, getDataIncrement(minDiv, maxDiv, timeDiff)];
}

/**
 * Gets the next time increment after the current.
 * If the current increment is null, then the first
 * increment on screen in generated.
 * 
 * @param min The smallest time value visible.
 * @param inc The increment level.
 * @param cur The current time increment (ms).
 * 
 * @return The next time increment.
 */
function getNextTimeIncrement(min, inc, cur){
    var d;
    
    if (cur === null){
        d = new Date(min);
        
        switch (inc[0]){
            case 0:
                d.setUTCFullYear(Math.floor(d.getUTCFullYear() / inc[1]) * inc[1]);
                d.setUTCMonth(0);
                d.setUTCDate(1);
                d.setUTCHours(0);
                d.setUTCMinutes(0);
                d.setUTCSeconds(0);
                d.setUTCMilliseconds(0);
                break;
            case 1:
                d.setUTCMonth(Math.floor(d.getUTCMonth() / inc[1]) * inc[1]);
                d.setUTCDate(1);
                d.setUTCHours(0);
                d.setUTCMinutes(0);
                d.setUTCSeconds(0);
                d.setUTCMilliseconds(0);
                break;
            case 2:
                d.setUTCDate(Math.floor(d.getUTCDate() / inc[1]) * inc[1]);
                d.setUTCHours(0);
                d.setUTCMinutes(0);
                d.setUTCSeconds(0);
                d.setUTCMilliseconds(0);
                break;
            case 3:
                d.setUTCHours(Math.floor(d.getUTCHours() / inc[1]) * inc[1]);
                d.setUTCMinutes(0);
                d.setUTCSeconds(0);
                d.setUTCMilliseconds(0);
                break;
            case 4:
                d.setUTCMinutes(Math.floor(d.getUTCMinutes() / inc[1]) * inc[1]);
                d.setUTCSeconds(0);
                d.setUTCMilliseconds(0);
                break;
            case 5:
                d.setUTCSeconds(Math.floor(d.getUTCSeconds() / inc[1]) * inc[1]);
                d.setUTCMilliseconds(0);
                break;
            case 6:
                d.setUTCMilliseconds(Math.floor(d.getUTCMilliseconds() / inc[1]) * inc[1]);
                break;
        }
    }
    else{
        d = new Date(cur);
    }
    
    switch (inc[0]){
        case 0:
            d.setUTCFullYear(d.getUTCFullYear() + inc[1]);
            break;
        case 1:
            d.setUTCMonth(d.getUTCMonth() + inc[1]);
            break;
        case 2:
            d.setUTCDate(d.getUTCDate() + inc[1]);
            break;
        case 3:
            d.setUTCHours(d.getUTCHours() + inc[1]);
            break;
        case 4:
            d.setUTCMinutes(d.getUTCMinutes() + inc[1]);
            break;
        case 5:
            d.setUTCSeconds(d.getUTCSeconds() + inc[1]);
            break;
        case 6:
            d.setUTCMilliseconds(d.getUTCMilliseconds() + inc[1]);
            break;
    }
    
    return d.getTime();
}

/**
 * Formats the given time, which is at the given incrment level.
 * 
 * @param time The time (Unix milis) to format.
 * @param inc  The increment level.
 * 
 * @return A formatted string representing time.
 */
function formatTime(time, inc){
    var d = new Date(time);
    
    var minStr = d.getUTCMinutes().toString()
    
    if (minStr.length < 2) {
        minStr = '0' + minStr;
    }
    
    var secStr = d.getUTCSeconds().toString()
    
    if (secStr.length < 2) {
        secStr = '0' + secStr;
    }
    
    var msStr = d.getUTCMilliseconds().toString();
    
    if (msStr.length == 1) {
        msStr = '00' + msStr;
    }
    else if (msStr.length == 2) {
        msStr = '0' + msStr;
    }
    
    switch (inc[0]){
        case 0:
            return d.getUTCFullYear();
        case 1:
        case 2:
            return (d.getUTCMonth() + 1) + '/' + d.getUTCDate() + '/' + d.getUTCFullYear();
        case 3:
        case 4:
            return d.getUTCHours() + ':' + minStr + ' ' + (d.getUTCMonth() + 1) + '/' + d.getUTCDate();
        case 5:
            return d.getUTCHours() + ':' + minStr + ':' + secStr;
        case 6:
            return d.getUTCSeconds() + '.' + msStr + 's';
    }
}


/*
 * Generates a data incrment value for an axis.
 * 
 * @param minDiv Minimum number of divisions on the screen.
 * @param maxDiv Maximum number of divisions on the screen.
 * @param diff   Range of data on-screen.
 * 
 * @return The calculated incrment
 */
function getDataIncrement(minDiv, maxDiv, diff){
    
    var base = 1;
    resolutions = [2,5,10]
    
    while (diff / base > maxDiv || diff / base < minDiv){
        
        if (diff / base > maxDiv){
            for (res in resolutions){
                if (diff / (base * resolutions[res]) <= maxDiv){
                    return base * resolutions[res];
                }
            }
            base *= resolutions.slice(-1)[0];
        }
        else if (diff / base < minDiv){
            
            for (res in resolutions){
                if (diff / (base / resolutions[res]) >= minDiv){
                    return base / resolutions[res];
                }
            }
            base /= resolutions.slice(-1)[0];
        }
    }
    
    return base;
}

/**
 * Gets the next data increment after the current.
 * If the current increment is null, then the first
 * increment on screen in generated.
 * 
 * @param min The smallest data value visible.
 * @param inc The increment level (number).
 * @param cur The current data increment.
 * 
 * @return The next data increment.
 */
function getNextDataIncrement(min, inc, cur){
    if (cur === null){
        return Math.floor(min / inc) * inc + inc;
    }
    else{
        return cur + inc;
    }
}

/**
 * Formats the given data, which is at the given incrment level.
 * 
 * @param data The data (number) to format.
 * @param inc  The increment level (number).
 * 
 * @return A formatted string representing data.
 */
function formatData(data, inc){
    
    if (Math.abs(data) < 1e-16){
        data = 0;
    }
    
    s = (Math.abs(data)).toPrecision(8).toString();
    
    var i;
    var len;
    if (s.search("\\.") === -1){
        len = i = s.length;
    }
    else{
        for (i = s.length - 1; i >= 0; i--){
            if (s[i] != '0'){
                if (s[i] === '.'){
                }
                else{
                    i++;
                }
                
                break
            }
        }
        
        len = s.length - (s.length - i - 1);
    }
    
    if (len > 10){
        return data.toExponential(5);
    }
    else{
        if (data >= 0){
            return s.substr(0, i)
        }
        else{
            return data.toPrecision(8).toString().substr(0, i + 1);
        }
    }
}

/**
 * Draws an X axis (with grid marks) on the given visObject.
 * 
 * @param xmin Minimum value visible on screen.
 * @param xmax Maximum value visible on screen.
 * @param visObject The vis object itself (eg. timeline)
 * @param type The type of data being displayed, if
 *             type is === "time" then time data is shown, 
 *             otherwise data is shown.
 */
function drawXAxis(xmin, xmax, visObject, type){
    
    var getIncrement, getNextIncrement, formatter;
    
    if (type.toLowerCase() === "time"){
        getIncrement = getTimeIncrement;
        getNextIncrement = getNextTimeIncrement;
        formatter = formatTime;
    }
    else{
        getIncrement = getDataIncrement;
        getNextIncrement = getNextDataIncrement;
        formatter = formatData;
    }
    
    var xdiff = xmax - xmin;
    
    inc = getIncrement(3, 7, xdiff);
    
    visObject.context.font = visObject.fontheight + "px sans-serif";
    visObject.context.fillStyle = "rgb(0,0,0)";
    visObject.context.textAlign = 'center';
    
    var labels = new Array();
    
    var xpos = 0;
    var i = null;
    
    while ((i = getNextIncrement(xmin, inc, i)) <= xmax){
        
        if( (i-xmin)*visObject.drawwidth/xdiff >= xpos ){
            
            var label = formatter(i, inc);
            
            labels[label] = new Array();
            labels[label]['label'] = label;
            labels[label]['xpos'] = (i-xmin)*visObject.drawwidth/xdiff;
            
            xpos = ((i-xmin)*visObject.drawwidth/xdiff) + (visObject.context.measureText(label).width*4/3);
            
        }
        
    }
    
    for( i in labels ){
        
        if (labels[i]['xpos'] + visObject.context.measureText(labels[i]['label']).width / 2 < visObject.drawwidth &&
            labels[i]['xpos'] - visObject.context.measureText(labels[i]['label']).width / 2 > 0) {
            
            visObject.context.fillText(labels[i]['label'], labels[i]['xpos'] + visObject.xoff, visObject.drawheight + visObject.yoff + visObject.fontheight);
        }
        
        visObject.context.strokeStyle = visObject.gridcolor;
        visObject.context.lineWidth = 0.25;
        visObject.context.beginPath();
        visObject.context.moveTo(labels[i]['xpos'] + visObject.xoff, 0 + visObject.yoff);
        visObject.context.lineTo(labels[i]['xpos'] + visObject.xoff, visObject.drawheight + visObject.yoff);
        
        visObject.context.closePath();
        visObject.context.stroke();
        
    }
    
    visObject.context.fillText(String(type).toCapitalize(), visObject.drawwidth / 2.0, visObject.drawheight + visObject.yoff + visObject.fontheight * 3.0);
    
    visObject.context.textAlign = 'left';
    
    //bkmk
    if (type === "time"){
        visObject.context.fillText("Starting: " + (new Date((xmin+(xdiff*visObject.hRangeLower)))).toUTCString(), visObject.xoff, visObject.fontheight);
    }
        
}

/**
 * Draws a Y axis (with grid marks) on the given visObject.
 * 
 * @param ymin Minimum value visible on screen.
 * @param ymax Maximum value visible on screen.
 * @param visObject The vis object itself (eg. timeline)
 */
function drawYAxis(ymin, ymax, visObject){
    
    visObject.context.font = visObject.fontheight + "px sans-serif";
    visObject.context.fillStyle = "rgb(0,0,0)";
    
    // --- //
    var ydiff = ymax - ymin;
    var inc = getDataIncrement(3, (visObject.drawheight / visObject.fontheight) * 0.66, ydiff);
    
    var i = null;
    while ((i = getNextDataIncrement(ymin, inc, i)) <= ymax){
        var y = visObject.drawheight - ((i-ymin)*visObject.drawheight/ydiff-visObject.fontheight/3) + visObject.yoff;
        
        var gridy =  visObject.drawheight - ((i-ymin)*visObject.drawheight/ydiff) + visObject.yoff;
        
        label = (formatData(i, inc));//.toString();//(i.toFixed(n)).toString();
        
        //if( y > visObject.fontheight + visObject.yoff && y < visObject.yoff + visObject.drawheight - visObject.fontheight/2 )
        
        visObject.context.fillText( label.toString(), visObject.drawwidth + visObject.fontheight/2, y );
        
        visObject.context.strokeStyle = visObject.gridcolor;
        visObject.context.lineWidth = 0.25;      
        visObject.context.beginPath();
        visObject.context.moveTo(0, gridy);
        visObject.context.lineTo(visObject.drawwidth, gridy);
        visObject.context.stroke();
        visObject.context.closePath();
        
    }
    
    return 0;
    
}

/**
 * Returns a JQuery-safe version of a given string 
 * (escapes special characters)
 * 
 * @param str The string to make safe.
 * 
 * @return The safe string.
 */
function jqISC(str) {
    return str.replace(/([;&,\.\ \+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
}