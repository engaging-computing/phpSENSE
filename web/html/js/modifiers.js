//Quick sort for data object
quickSort = function(arr, key) {
 
  // return if array is unsortable
  if (arr.length <= 1){
    return arr;
  }
 
  var less = Array(), greater = Array();
 
  // select and remove a pivot value pivot from array
  // a pivot value closer to median of the dataset may result in better performance
  var pivotIndex = Math.floor(arr.length / 2);
  var pivot = arr.splice(pivotIndex, 1)[0];
 
  // step through all array elements
  for (var x = 0; x < arr.length; x++){
 
    // if (current value is less than pivot),
    // OR if (current value is the same as pivot AND this index is less than the index of the pivot in the original array)
    // then push onto end of less array
    if (
      (
        !key  // no object property name passed
        &&
        (
          (arr[x] < pivot)
          ||
          (arr[x] == pivot && x < pivotIndex)  // this maintains the original order of values equal to the pivot
        )
      )
      ||
      (
        key  // object property name passed
        &&
        (
          (arr[x][key] < pivot[key])
          ||
          (arr[x][key] == pivot[key] && x < pivotIndex)  // this maintains the original order of values equal to the pivot
        )
      )
    ){
      less.push(arr[x]);
    }
 
    // if (current value is greater than pivot),
    // OR if (current value is the same as pivot AND this index is greater than or equal to the index of the pivot in the original array)
    // then push onto end of greater array
    else {
      greater.push(arr[x]);
    }
  }
 
  // concatenate less+pivot+greater arrays
  return quickSort(less, key).concat([pivot], quickSort(greater, key));
};

for( var ses in data.sessions ) {
	
	data.sessions[ses].is_visible = function() {
		return this.visibility;
	}
	
	data.sessions[ses].set_visibility = function( on ) {
		this.visibility = on;
	}
	
	data.sessions[ses].getMaxVal = function( field ) {
		var max = this.data[0][field];
		for( var dP in this.data ) {
			if( this.data[dP][field] > max )
				max = this.data[dP][field];
		}
		
		return max;
	}
	
	data.sessions[ses].getMinVal = function( field ) {
		var min = this.data[0][field];
		for( var dP in this.data ) {
			if( this.data[dP][field] < min )
				min = this.data[dP][field];
		}
		
		return min;
	}
	
	data.sessions[ses].getMeanVal = function( field ) {
		var mean = 0;
		
		for( var dP in this.data ) {
			mean += this.data[dP][field];
		}
		
		return (mean / this.data[dP].length);
	}
	
	data.sessions[ses].getMedianVal = function( field ) {
		var sortedData = data;
		sortedData.qSort(field);
		
		if( this.data.length % 2 )
			return sortedData[this.data.length/2];
		else
			return ((sortedData[(this.data.length/2)-.5]+sortedData[(this.data.length/2)+.5])/2)
		
	}
	
	data.sessions[ses].getModeVal = function( field ) {
		var tmp = new Array();
		var max_count = 0;
		var max = 0;
		
		for( var dP in this.data ) {
			tmp[''+this.data[dp][field]+''] = 0;
		}	
		
		for( var dP in this.data ) {
			tmp[''+this.data[dp][field]+'']++;
		}
		
		for( var dP in tmp) {
			if( tmp[dP] > max_count )
				max = dP;
		}
		
		return max;
		
	}
	
}

for( var field in data.fields ) {
	
	data.fields[field].is_visible = function() {
		return this.visibility;
	}
	
	data.fields[field].set_visiblity = function( on ) {
		this.visibility = on;
	}
}

data.getSesMax = function ( field ) {

	var maxs = Array();
	for( var ses in data.sessions )
		maxs[maxs.length] = data.sessions[ses].getMaxVal(field);

	return maxs;

}

data.getSesMin = function ( field ) {
	var mins = Array();
	for( var ses in data.sessions ) 
		mins[mins.length] = data.sessions[ses].getMinVal(field);
	return mins;
}

//Gets all visible data and outputs it in an array
data.getVisData = function () {
	var output = Array();
	var sesi = 0;
	var fiei = 0;
	
	for( ses in data.sessions ) {
		if( data.sessions[ses].is_visible() ) {
			output[sesi] = Array();
			for( field in data.fields) {
				if( data.fields[field].is_visible() ) {
					output[sesi][fiei] = Array();
					var temp = Array();
					
					for( i = 0; i < this.sessions[ses].data.length; i++ ) {
						temp[temp.length] = this.sessions[ses].data[i][field];
					}
					
					output[sesi][fiei] = temp;
					fiei++;
				}
				
			}
			sesi++;
			fiei = 0;
		}
		
	}
	
	return output;
}

data.getDataFrom = function(session, field) {
	
	var tmp = Array();
	
	for( dp in data.sessions[session].data )
		tmp[tmp.length] = data.sessions[session].data[dp][field];
		
	return tmp;

}

data.getMax = function () {

	var max;

	var temp = Array();
	for( var field in data.fields )
		if( data.fields[field].name !='Time' && data.fields[field].name != 'time' && data.fields[field].type_id != 37 )
			temp[temp.length] = data.getSesMax(field);
	
	max = temp[0][0];
	
//	for( var tmp in temp.length ) Good job Hassan, no wonder the vises wouldn't display properly (This was supposed to be funny, but it sounds kind of mean, sorry)
	for( var tmp in temp )
		for( var tp in temp[tmp] )
			if( temp[tmp][tp] > max )
				max = temp[tmp][tp];

	return max;
	
}

data.getMin = function () {
	
	var temp = Array();
	for( var field in data.fields )
		if( data.fields[field].name !='Time' && data.fields[field].name != 'time' && data.fields[field].type_id != 37 )
			temp[temp.length] = data.getSesMin(field);
	
	min = temp[0][0];
	
	for( var tmp in temp )
		for( var tp in temp[tmp] )
			if( temp[tmp][tp] < min )
				min = temp[tmp][tp];

	return min;
	
}

data.getFieldMax = function (fieldName) {
	
	for(var f in this.fields) {
		if( this.fields[f].name.toLowerCase() == fieldName.toLowerCase())
			var field = f;
	}
	
	var max = data.sessions[0].data[0][field];
		
	for(var ses in this.sessions) {
		for(var dp in this.sessions[ses].data) {
			if( dp != this.sessions[ses].data.length )
				if( max < this.sessions[ses].data[dp][field] )
					max = this.sessions[ses].data[dp][field];
		}
	}
	
	return max;
	
}

data.getFieldMin = function (fieldName) {
		
	for(var f in this.fields) {
		if( this.fields[f].name.toLowerCase() == fieldName.toLowerCase())
			var field = f;
	}
	
	var min = this.sessions[0].data[0][field];
	
	for(var ses in this.sessions) {
		for(var dp in this.sessions[ses].data) {
			if( dp != this.sessions[ses].data.length )
				if( min > this.sessions[ses].data[dp][field] )
					min = this.sessions[ses].data[dp][field];
		}
	}
	
	return min;
}

data.getMaxDatapoints = function () {
	var max = 0;
		
	for( var ses in data.sessions )
		if( data.sessions[ses].data.length > max )
			max = data.sessions[ses].data.length;
						
	return max;
}

data.qSort = function( fieldName ) {
	
	for( field in data.fields )
		if( data.fields[field].name.toLowerCase() == fieldName.toLowerCase() )
			for( ses in data.sessions )
				data.sessions[ses].data = quickSort(data.sessions[ses].data, field);
	
}

data.avgField = function( fieldName ) {
	
	var avg = 0;
	var count = 0;
	
	for(var field in this.fields )
		if( this.fields[field].name.toLowerCase() == fieldName.toLowerCase() )
			for(var ses in this.sessions ) {
				for(var dp in this.sessions[ses].data) {
					avg += this.sessions[ses].data[dp][field];
					count++;
				}
			}
	
	return (avg/count);
	
}

data.fullSort = function( fieldName ) {

	var returnData = Array();

	for( var ses in data.sessions )
		for( var dp in data.sessions[ses].data)
			returnData.push(data.sessions[ses].data[dp]);

	for( var field in data.fields )
		if( data.fields[field].name.toLowerCase() == fieldName.toLowerCase() )
			return quickSort(returnData, field);

}

/*
 * Gets the minimum and maximum values for visible non-time data.
 * 
 * @return an array of [min, max]
 */
data.getVisibleDataBounds = function() {
    var max = 0;
    var min = 0;
    
    for (var i = 0; i < data.sessions.length; i++) {
        for (var j = 0; j < data.fields.length; j++) {
            if (data.fields[j].visibility === 1 && data.sessions[i].visibility === 1 &&
                data.fields[j].name.toLowerCase() != "time") {
                max = Math.max(max, Math.max.apply(null, data.getDataFrom(i, j)));
                min = Math.min(min, Math.min.apply(null, data.getDataFrom(i, j)));
            }
        }
    }
    
    return [min, max];
}

/*
 * Gets the minimum and maximum values for visible data of the given fields.
 * 
 * @param fields an array of field names, eg. ['time', 'temperature'].
 * 
 * @return an array of [min, max]
 */
data.getVisibleFieldBounds = function(fields) {
    var max = 0;
    var min = Number.POSITIVE_INFINITY;
    
    for (var i = 0; i < data.sessions.length; i++) {
        for (var j = 0; j < data.fields.length; j++) {
            for (f in fields) {
                if (data.fields[j].visibility === 1 && data.sessions[i].visibility === 1 &&
                    data.fields[j].name.toLowerCase() === fields[f].toLowerCase()) {
                    max = Math.max(max, Math.max.apply(null, data.getDataFrom(i, j)));
                    min = Math.min(min, Math.min.apply(null, data.getDataFrom(i, j)));
                }
            }
        }
    }
    
    return [min, max];
}

