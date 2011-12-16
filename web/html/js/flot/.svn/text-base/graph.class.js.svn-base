function Graph(values, sessions, options) {
	this.values = values;
	this.sessions = sessions;
	this.options = options;

	this.JSON = function() {
	
	}
	
	this.graph = function() {
		alert(this.values);
	};

}

function Data() {
	this.values = new Array();
	this.sessions = new Array();
	this.fields = new Array();
	this.visibility = new Array();

	this.setValues = function() {
		for( var field in this.fields ) {
			this.values[this.fields[field]] = new Array();
			for( var ses in this.sessions ) {
				for( var data in this.sessions[ses].data ) {
					this.values[this.fields[field]][this.values[this.fields[field]].length] = this.sessions[ses].data[data][field];
				}
			}		
		}
	};

	this.getAxis = function( field ) {
		return this.values[field];
	};

	this.setVisibility = function( index, value ) {
		this.visibility[index] = value;
	};

}
