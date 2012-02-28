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
     [[timeSizes.month  * 6,  [1, 6]],
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

function formatTime(time, inc){
    var d = new Date(time);
    
    switch (inc[0]){
        case 0:
            return d.getUTCFullYear();
        case 1:
        case 2:
            return (d.getUTCMonth() + 1) + '/' + d.getUTCDate() + '/' + d.getUTCFullYear();
        case 3:
        case 4:
            return d.getUTCHours() + ':' + d.getUTCMinutes() + ' ' + (d.getUTCMonth() + 1) + '/' + d.getUTCDate();
        case 5:
            return d.getUTCHours() + ':' + d.getUTCMinutes() + ':' + d.getUTCSeconds();
        case 6:
            return d.getUTCSeconds() + ':' + d.getUTCMilliseconds() + 'ms';
    }
}


/*
 * Generates a data incrment value for an axis given a minimum.
 * 
 * @param minInc The minimum increment. This is a somwhat arbitrary value,
 * in timeline it is (valueRange) * (fontHeight / drawHeight) * 3/2.
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

function getNextDataIncrement(min, inc, cur){
    if (cur === null){
        return Math.floor(min / inc) * inc + inc;
    }
    else{
        return cur + inc;
    }
}

function formatData(data, inc){
    var n = Math.floor(Math.log(1/inc)/Math.log(10));
    n = n > 0 ? n : 0;
    
    if (n < 10){
        return data.toFixed(n);
    }   
}