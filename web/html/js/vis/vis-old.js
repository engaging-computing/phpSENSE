/* The idea to get vis stuff running for the June 2009 demonstration
 * is to translate the DATA json object on the page into the 'isenseData'
 * format used by the older version of the vis. Since the DATA object will
 * remain constant, the isenseData object can be safetly modified for data
 * filtering capabilities. The idea is that in the future, the isenseData
 * object could be replaced with the Google DataView object to provide
 * a safetly mutable view of the data. Note that in either situation, each
 * vis container will have its own 'view' of the data, such that modifications
 * in one vis will not effect the other. Also note that using the DataView
 * object will require all the datatable generator functions (which are still
 * necessary, as each visualization needs a datatable with specific formatting)
 * to be modified or rewritten for the new organization of the data view.
 *
 * We still want to keep the ability to have multiple vis containers per page,
 * in the instance that multiple DATA objects can be added to the page.
 * 
 * 
 */


/*
 * Function passed to sort() array method to sort numbers.
 */
function SortNumbers(a,b) {
    return a - b;
}

/*
 * Converts milliseconds to a clock-like string
 */
function millisToClockString(ms) {
    
    var retstr = "";
    var timeleft = parseInt(ms);
    if(timeleft < 0) {
	retstr += "-";
	timeleft = -timeleft;
    }
    var print = false;
    
    var days = Math.floor(timeleft / 86400000);
    timeleft = timeleft % 86400000;
    if(days > 0) {
	retstr += days + "::";
	print = true;
    }

    var hours = Math.floor(timeleft / 3600000);
    timeleft = timeleft % 3600000;
    if(print || hours > 0) {
	if(print && hours < 10) {
	    retstr += "0";
	}
	retstr += hours + ":";
	print = true;
    }
    
    var minutes = Math.floor(timeleft / 60000);
    timeleft = timeleft % 60000;
    if(print && minutes < 10) {
	retstr += "0";
    }
    retstr += minutes + ":";
    
    var seconds = Math.floor(timeleft / 1000);
    timeleft = timeleft % 1000;
    if(seconds < 10) {
	retstr += '0';
    }
    retstr += seconds;

    return retstr;
}

/*
 * This method will return a form element given the form id and element name.
 * I had to write this because the document.getElementById() method would not update
 * the value of text fields when they were changed by the user.
 */
function findInputElement(form_id,elem_name) {
    
    var form = null;
    var elem = null;
    
    for(var i = 0; i < document.forms.length && !form; ++i){
	if(document.forms[i].id == form_id) {
	    form = document.forms[i];
	}
    }
    
    if(!form) {
	throw new Error("findInputElement() failed: the form " + form_id + " could not be found.");
	return null;
    }
    
    for(var i = 0; i < form.elements.length && !elem; ++i) {
	var en = form.elements[i].name;
	if(en == elem_name) {
	    elem = form.elements[i];
	}
    }
    
    if(!elem) {
	throw new Error("findInputElement() failed: the element " + elem_name + " could not be found.");
    }
    
    return elem;
}

/*
 * Custom Google Map visualization. To be inserted into the google.visualization object
 * after visualization has been initialized.
 */
function isenseMap(targetDiv) {

    /* Configuration Options from Standard Map Viz
     * - enableScrollWheel
     * - showTip
     * - showLine
     * - lineColor (do not use)
     * - lineWidth
     * - mapType
     */

    var div          = targetDiv;
    var data;
    var opts;
    var map;
    var points       = []; // Array of arrays of objects with properties .location and .text
    var markers      = [];
    var markerInfo   = [];
    var markerBounds = [];
    var polylines    = [];   
    var selectedElem;
    var errorno      = 0;
    var errormsg     = ["errno is set to 0, this should not be displayed.",
			"Shoot the Programmer error.",
			"Google Maps is unable to run in your browser.",
			"Malformed datatable error."];

    var colorsToUse  = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		        ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
			,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
			,"#22aa99","#999999","#705770","#109618","#a32929"];
    
    //
    // For address conversion
    //
    var addressCache = new google.maps.GeocodeCache();
    var geocoder = new google.maps.ClientGeocoder(addressCache);
    var convertTable;
    var convertStep;
    var addressList;
    var geocoderRunning = false;
    
    //
    // Option variables
    //
    var opt_useOptionalValues = true;
    var opt_showLines;
    var opt_lineWidth;
    var opt_mapType;
    var opt_enableWheel;
    var opt_showTips;
    var opt_fillTypes         = ["tfill","bfill"];
    var opt_fillSelect        = 1;

    /* * * * * * * * * *
     * Initialization  *
     *                 *
     * * * * * * * * * *
     * Check to make sure Google Maps has been initialize, is comptable, and
     * there exists a div object to add the map to.
     */
    if(typeof GMap2 == 'undefined') {
	errorno = 1;
	throw new Error("Google Map object not found. Please add appropriate scripts to page.");
    }
    
    if(!GBrowserIsCompatible()) {
	errorno = 2;
    }

    if(typeof div == 'string') {
	if(typeof document.getElementById(div) == 'undefined') {
	    errorno = 1;
	    throw new Error("Invalid argument: target DIV element not found.");
	}
	else {
	    div = document.getElementById(div);
	}
    }
    
    if(typeof div == 'object' && typeof div.nodeName != 'undefined' && div.nodeName != 'DIV') {
	errorno = 1;
	throw new Error("Invalid argument: target is not a DIV element.");
    }

    /* 
     * Completed Initialization
     */

    /* * * * * * * * * *
     * Public Methods  *
     *                 *
     * * * * * * * * * *
     * The creation of a GMap object immediately draws it to whatever
     * DIV passed to it, so wait until the draw function to create it.
     */

    this.draw = function(datatable,options) {
	
	//
	// Check arguments
	//
	if(typeof options != "object" || typeof datatable != "object") {
	    errorno = 1;
	    throw new Error("draw() - Invalid argument: arguments must be objects.");
	}
	
	//
	// Clear old information, if it exists.
	//
	if(typeof map != "undefined") {
	    deleteMap();
	}
	
	//
	// Read options first to determine if anything special needs to be done.
	//
	handleOptions(options);
	
	//
	// Parse datatable to create points and markers
	//
	parseTable(datatable);
	
	//
	// Check errorno. If an error has been detected, display the associated
	// error message and abort.
	//
	if(errorno != 0) {	
	    // TODO
	    return;
	}
	
	//
	// Create the map, enforce options that require a GMap2 object to set, 
	// draw on it, then add event handlers (after the map has been
	// initialized by drawPoints).
	//
	map = new GMap2(div);
	enforceOptions();
	if(geocoderRunning) {
	    geocodeAddresses();
	}
	else {
	    drawPoints();
	}
	addEventHandlers();
    }
    
    this.getSelection = function() {
	if(selectedElem) {
	    return {object:selectedElem,
		    session:selectedElem.isenseSession,
		    index:selectedElem.isenseIndex};
	}
	else {
	    return null;
	}
    }
    
    /*
     * Selects and zooms to marker indicated by selection.
     * selection should contain at least a session number (to zoom to an entire
     * path) and possibly an index for a particular marker.
     */
    this.setSelection = function(selection) {
	
	if(!selection || !!selection.session) {
	    return;
	}

	var mark = null;
	for(var ses in markers) {

	    if(selection.session == markers[ses][0].isenseSession) {
		mark = markers[ses];
		break;
	    }
	}
	
	if(mark) {
	    if(selection.index && typeof selection.index == 'number' &&
	       selection.index < markers[ses].length && selection.index >= 0) {
		
		map.setCenter(points[ses][selection.index],5);
	    }
	    else {
		
		map.setCenter(points[ses][Math.floor(points[ses].length / 2)],
			      map.getBoundsZoomLevel(markerBounds[ses])-1);
	    }
	}

	return;
    }

    this.remove = function() {
	deleteMap();
    }
    
    /* * * * * * * * * *
     * Private Methods *
     *                 *
     * * * * * * * * * *
     */
    var addEventHandlers = function() {
	
	GEvent.bind(map, "maptypechanged", this, function() {
		opt_mapType = map.getCurrentMapType();
		//alert("changed to: " + opt_mapType.getName());
	    });

	GEvent.bind(map, "click", this, function(overlay, latlng, olatlng) {
		if(overlay == null) {
		    return;
		}

		if(overlay.isenseType == "GMarker") {
		    selectedElem = overlay;
		    $("#" + div.id).trigger("click",
					    {type:"custommapmarker",
					     session:overlay.isenseSession,
					     index:overlay.isenseIndex});
		}
		else if(overlay.isenseType == "GPolyLine") {
		    selectedElem = overlay;
		    $("#" + div.id).trigger("click",
					    {type:"custommapline",
					     session:overlay.isenseSession});
		}
	    });
    }
    
    var handleOptions = function(options) {
	
	//
	// Polyline paths between points
	//
	if(options.showLines) {
	    opt_showLines = true;
	    if(options.lineWidth) {
		opt_lineWidth = options.lineWidth;
	    }
	    else {
		opt_lineWidth = 4;
	    }
	}
	else {
	    opt_showLines = false;
	    opt_lineWidth = 0;
	}
	
	//
	// Set the starting map type
	//
	if(!opt_mapType) {
	    if(options.mapType == "satellite") {
		opt_mapType = G_SATELLITE_MAP;
	    }
	    else if(options.mapType == "normal") {
		opt_mapType = G_NORMAL_MAP;
	    }
	    else {
		opt_mapType = G_HYBRID_MAP;
	    }
	}

	//
	// Set scroll wheel usage
	//
	if(options.enableScrollWheel) {
	    opt_enableWheel = true;
	}
	else {
	    opt_enableWheel = false;
	}

	//
	// Set use of info window
	//
	if(typeof options.showTip == "undefined" || options.showTip) {
	    opt_showTips = true;
	}
	else {
	    opt_showTips = false;
	}

	//
	// Set marker style (colored Google marker or value-bar custom marker)
	//
	if(options.useFilledMarkers) {
	    opt_useOptionalValues = true;
	}
	else {
	    opt_useOptionalValues = false;
	}
    }
    
    var enforceOptions = function() {
	
	//
	// To keep the map type from changing when it is reloaded,
	// set it to the last map type used.
	//
	map.setMapType(opt_mapType);

	//
	// Scroll wheel zoom
	//
	if(opt_enableWheel) {
	    map.enableScrollWheelZoom();
	}
	else {
	    map.disableScrollWheelZoom();
	}
    }
    
    /*
     * parseTable - check the datatable to see which parsing function should be used.
     *              If string addresses are passed, we need to convert the addresses to lat/lon.
     *              Otherwise, use lat/lon data in table to add points to map.
     */
    var parseTable = function(datatable) {
    
	if(typeof datatable.getNumberOfColumns != "function") {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable is not a Google DataTable object.");
	}
	if(datatable.getNumberOfColumns() < 3) {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable does not have the minimum number of columns.");
	}
	if(datatable.getColumnType(0) != "string") {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable column 0 should be a string; it is a " + data.getColumnType(0));
	}

	//
	// Is it an address table or a gps table?
	//
	if(datatable.getColumnType(1) == "number") {
	    geocoderRunning = false;
	    parseGPSTable(datatable);
	}
	else if(datatable.getColumnType(1) == "string") {
	    parseAddressTable(datatable);
	}
	else {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable column 1 should be a string or number, it is a " + datatable.getColumnType(1));
	}
    }

    /*
     * parseAddressTable - Convert addresses to LatLng values, and add them to the map
     *
     * Doesn't really parse anything, just saves the table for later. The first idea was to
     * convert the address table into a gps table, and use the other parse method on it (which has
     * everything we need already), but it didn't work.
     */
    var geocodeAddressCallback = function(latlng) {
	
	var mTitle  = "Session " + parseInt(convertTable.getValue(convertStep,0));
	var mIcon   = new GIcon(G_DEFAULT_ICON);
	var mPath   = location.href;
	    mPath   = mPath.slice(0, mPath.lastIndexOf("/"));
	mIcon.image = mPath + "/images/viz/custommap/icon" + colorsToUse[convertStep].substr(1) + ".png";
	var nMarker = new GMarker(latlng,{icon:mIcon,title:mTitle});
	nMarker.isenseType    = "GMarker";
	nMarker.isenseSession = parseInt(convertTable.getValue(convertStep,0));
	nMarker.isenseIndex   = 0;

	//
	// Before any markers can be added, the map must be initialized
	// with setCenter. Set zoom so that all markers will fit.
	//
	if(!map.isLoaded()) {
	    map.setCenter(latlng,8);
	    
	    //
	    // Add visual controls to the map
	    //
	    map.addControl(new GLargeMapControl());
	    map.addControl(new GMapTypeControl());
	}
	else {
	    map.setCenter(latlng,8);
	}

	if(opt_showTips) {
	    nMarker.bindInfoWindowHtml(convertTable.getValue(convertStep,2));
	}
	map.addOverlay(nMarker);

	//
	// On to the next point
	//
	if(++convertStep < convertTable.getNumberOfRows() && geocoderRunning) {
	    geocoder.getLatLng(convertTable.getValue(convertStep,1), geocodeAddressCallback);
	}
	else {
	    geocoderRunning = false;
	}
    }

    var initializeDefaultMap = function() {
	if(!map.isLoaded()) {
	    var olsenHall = new GLatLng(42.6546,-71.3268);
	    map.setCenter(olsenHall, 9);
	    
	    map.addControl(new GLargeMapControl());
	    map.addControl(new GMapTypeControl());
	}
    }

    var parseAddressTable = function(datatable) {
	
	//
	// Save table for callback functions
	//
	convertTable = datatable;
	convertStep = 0;
	geocoderRunning = true;
	
	//
	// The real work will be done later, after the map is created
	//
    }

    var geocodeAddresses = function() {

	initializeDefaultMap();
	if(convertTable.getNumberOfRows() == 0) {
	    return;
	}
	geocoder.getLatLng(convertTable.getValue(0,1), geocodeAddressCallback);
    }

    /*
     * parseGPSTable - Convert data table into gps points and map markers.
     *              Do not modify the datatable.
     *
     * datatable format should be as follows...
     *     IconGraphValue is optional, and only used if the option to use icons to display values
     *     is enabled.
     * +-------------------------------------------------------------------------------------+
     * | ID(str) | Latitude(num) | Longitude(num) | Information(str) | [IconGraphValue(num)] |
     * +---------+---------------+----------------+------------------+-----------------------+
     * |         |               |                |                  |                       |
     */
    var parseGPSTable = function(datatable) {
	
	data = datatable;

	//
	// Check to ensure table is valid and properly constructed.
	//
	if(data.getNumberOfColumns() < 4) {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable does not have the minimum number of columns.");
	}

	if(data.getColumnType(1) != "number") {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable column 1 should be a number; it is a " + data.getColumnType(1));
	}

	if(data.getColumnType(2) != "number") {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable column 2 should be a number; it is a " + data.getColumnType(2));
	}

	if(data.getColumnType(3) != "string") {
	    errorno = 4;
	    throw new Error("parseTable() - Invalid argument: datatable column 3 should be a string; it is a " + data.getColumnType(3));
	}

	//
	// Clear existing data.
	//
	for(var arr in points) {
	    delete points[arr];
	}
	delete points;
	points = new Array();

	for(var arr in markers) {
	    delete markers[arr];
	}
	delete markers;
	markers = new Array();

	for(var arr in markerInfo) {
	    delete markerInfo[arr];
	}
	delete markerInfo;
	markerInfo = new Array();

	for(var arr in polylines) {
	    delete polylines[arr];
	}
	delete polylines;
	polylines = new Array();

	for(var arr in markerBounds) {
	    delete markerBounds[arr];
	}
	delete markerBounds;
	markerBounds = new Array();

	//
	// Data looks ok. Create assocative arrays for points and markers
	// based on ID (column 0).
	//
	var sessionCells  = new Array();
	var sessionColor  = new Array();
	var rowLimit      = data.getNumberOfRows();
	var colCount      = data.getNumberOfColumns();
	var row;
	var minLon_t      = 1000;
	var minLon_s      = 1000;
	var maxLon_t      = -1000;
	var maxLon_s      = -1000;
	var minLat_t      = 1000;
	var minLat_s      = 1000;
	var maxLat_t      = -1000;
	var maxLat_s      = -1000;
	var minOpVal;
	var maxOpVal;
	var sesColorIndex = 0;

	for(row = 0; row < rowLimit; row++) {
	    
	    var id = data.getValue(row,0);
	    var lat = data.getValue(row,1);
	    var lon = data.getValue(row,2);
	    var info = data.getValue(row,3);
	    var opval;
	    if(colCount >= 5) {
		opval = data.getValue(row,4);
		if(opval != undefined && !isNaN(opval) && !minOpVal || minOpVal > opval) {
		    minOpVal = opval;
		}
		if(opval != undefined && !isNaN(opval) && !maxOpVal || maxOpVal < opval) {
		    maxOpVal = opval;
		}
	    }
	    
	    if(!sessionCells[id]) {
		sessionCells[id] = new Array();
		sessionColor[id] = sesColorIndex;
		if(sesColorIndex+1 < colorsToUse.length) {
		    ++sesColorIndex;
		}
	    }

	    //
	    // If the session id contains "bnd", it is a helper marker used to provide
	    // additional information on the maximum or minimum values of the measured
	    // field (which has been accounted for above). Do not add it as a marker.
	    //
	    if(id.indexOf("bnd") != -1) {
		continue;
	    }

	    //
	    // Ignore bad waypoints as well (NaN lat-lon values)
	    //
	    if(isNaN(lat) || isNaN(lon)) {
		continue;
	    }

	    //
	    // Push good point to it's sessions array.
	    //
	    sessionCells[id].push({c_id:id,
			           c_lat:lat,
				   c_lon:lon,
				   c_info:info,
			           c_opval:opval});
	}

	var i;
	for(var ses in sessionCells) {

	    minLon_s = minLat_s = 1000;
	    maxLon_s = maxLat_s = -1000;
	    
	    for(i = 0; i < sessionCells[ses].length; ++i) {

		id    = sessionCells[ses][i].c_id;
		lat   = sessionCells[ses][i].c_lat;
		lon   = sessionCells[ses][i].c_lon;
		info  = sessionCells[ses][i].c_info;
		opval = sessionCells[ses][i].c_opval;

		// Check max/min to get boundaries for default zoom level
		//
		if(lat < minLat_t) minLat_t = lat;
		if(lat < minLat_s) minLat_s = lat;
		if(lat > maxLat_t) maxLat_t = lat;
		if(lat > maxLat_s) maxLat_s = lat;
		if(lon < minLon_t) minLon_t = lon;
		if(lon < minLon_s) minLon_s = lon;
		if(lon > maxLon_t) maxLon_t = lon;
		if(lon > maxLon_s) maxLon_s = lon;
		
		// Create arrays for ID if it does not exist.
		//
		if(typeof points[id] == "undefined") {
		    points[id] = new Array();
		    markers[id] = new Array();
		    markerInfo[id] = new Array();
		}
	    
		// Build new points and markers, add to array
		//
		var mTitle  = "Session " + parseInt(id) + " - Datapoint " + (markers[id].length+1);
		var mIcon   = new GIcon(G_DEFAULT_ICON);
		//alert(mIcon.imageMap.length);
		//alert(mIcon.imageMap);
		//alert(mIcon.iconSize.toString());
		//alert(mIcon.iconAnchor.toString());
		var mPath   = location.href;
		    mPath   = mPath.slice(0, mPath.lastIndexOf("/"));
		if(opt_useOptionalValues) {
		    // Use 'filled' markers. mOV should be an integer between 0 and 16 inclusive.
		    //
		    var mOV;
		    if(opval != undefined && !isNaN(opval)) {
			mOV = Math.round(((opval - minOpVal) / (maxOpVal - minOpVal)) * 16.0);
		    }
		    else {
			mOV = 'x';
		    }
		    mIcon.shadow = mPath + "/images/viz/custommap/ricon_shad_ses" + 
			           String.fromCharCode(65 + sessionColor[id]) + ".png";
		    mIcon.image  = mPath + "/images/viz/custommap/ricon_" + opt_fillTypes[opt_fillSelect] + "_" +
			           mOV + "_of16.png";
		    mIcon.iconSize         = new GSize(30,64);
		    mIcon.shadowSize       = new GSize(30,64);
		    mIcon.iconAnchor       = new GPoint(15,64);
		    mIcon.infoWindowAnchor = new GPoint(30,0);
		    mIcon.imageMap         = [0,0,0,62,13,68,17,68,30,62,30,0];

		    /* --------------------------------------------------------------------
		     * This was used for the 10 image resolution, with Google style markers
		     * --------------------------------------------------------------------
		     * mIcon.shadow = mPath + "/images/viz/custommap/icon" + 
		     *                colorsToUse[sessionColor[id]].substr(1) + "_shadow.png";
		     * var mOV = Math.floor(((opval - opMinVal) / (opMaxVal - opMinVal)) * 10) * 10;
		     *
		     * //document.getElementById("Visualization_debug").innerHTML += mOV + "<br/>";
		     * mIcon.image  = mPath + "/images/viz/custommap/iconvalpct" + mOV + ".png";
		     * mIcon.shadowSize = mIcon.iconSize;
		     */
		}
		else {
		    mIcon.image = mPath + "/images/viz/custommap/icon" + 
			          colorsToUse[sessionColor[id]].substr(1) + ".png";
		}
		var nPoint  = new GLatLng(lat,lon);
		var nMarker = new GMarker(nPoint,{icon:mIcon,title:mTitle});
		nMarker.isenseType    = "GMarker";
		nMarker.isenseSession = id;
		nMarker.isenseIndex   = markers[id].length;
		
		points[id].push(nPoint);
		markers[id].push(nMarker);
		markerInfo[id].push(info);
	    }

	    // Create the session specific boundary object
	    //
	    markerBounds[id] = new GLatLngBounds(new GLatLng(minLat_s, minLon_s),
						 new GLatLng(maxLat_s, maxLon_s));
	}

	// Create the boundary object (south-west corner, north-east corner)
	//
	markerBounds["all"] = new GLatLngBounds(new GLatLng(minLat_t, minLon_t),
						new GLatLng(maxLat_t, maxLon_t));

    }

    var drawPoints = function() {

	//
	// Draw markers
	//
	var sesColorIndex = 0;
	for(var ses in markers) {
	    
	    //
	    // Before any markers can be added, the map must be initialized
	    // with setCenter. Set zoom so that all markers will fit.
	    //
	    if(!map.isLoaded()) {
		map.setCenter(points[ses][0],
			      map.getBoundsZoomLevel(markerBounds["all"])-1);
	    }
	    
	    //
	    // Add visual controls to the map
	    //
	    map.addControl(new GLargeMapControl());
	    map.addControl(new GMapTypeControl());

	    //
	    // Setup the GPolyline object now, set it's visibility 
	    // according to user options.
	    //
	    if(!polylines[ses]) {
		polylines[ses] = new GPolyline([],
					       colorsToUse[sesColorIndex],
					       opt_lineWidth, 0.6, 
					       {clickable:false,geodesic:false});
		map.addOverlay(polylines[ses]);
		polylines[ses].isenseType = "GPolyLine";
		polylines[ses].isenseSession = ses;
	    }
	    
	    for(var m = 0; m < markers[ses].length; ++m) {
		if(opt_showLines) {
		    polylines[ses].insertVertex(m, points[ses][m]);
		    markerInfo[ses][m] += "<br/><br/>Distance from start: "
			+ polylines[ses].getLength() + " meters";
		}

		if(opt_showTips) {
		    markers[ses][m].bindInfoWindowHtml(markerInfo[ses][m]);
		}
		map.addOverlay(markers[ses][m]);
	    }

	    ++sesColorIndex;
	}
    }

    /*
     * Cleanly delete map object.
     */
    var deleteMap = function() {
	
	delete map;
	div.innerHTML = "";
    }
}

/*
 * The VisData data structure sits above the DATA object found on the page.
 * The DATA object will not be modified, so it is acceptable to modify the VisData object
 * inside the VisPanel (and re-parse the DATA object if the original state should be restored).
 */
function VisData() 
{
    this.user = 'Guest';
    this.experiment_id = 0;
    this.experiment_title = '';
    this.experiment_date = '';
    this.fields = new Array();
    this.fields['count'] = 0;
    this.fields['id'] = [];
    this.fields['title'] = [];
    this.fields['units'] = [];
    this.fields['unitType'] = [];
    this.fields['sensor'] = [];
    this.fields['sensorTitle'] = [];
    this.sessions = new Array();

    /* Session Structure
     * 
     * session is an associative array. Each session in the isense system is
     * identified by a *unique* id. We can convert this number to a string,
     * and use it an associative index for that sessions data. Searching the
     * array for a particular session is no longer necessary. ID refers to the
     * string representation of an example session's id number.
     *
     * sessions[ID]['id'] = number(original id value)
     * sessions[ID]['address'] = [string(address), 
     *                           number(latitude), 
     *                           number(longitude)]
     * sessions[ID]['date'] = string(created date)
     * sessions[ID]['data'] = [array(field 0 data),
     *                        array(field 1 data),
     *  		      ...]   
     *            fields should be matched to the order of the fields array
     */
}

var FIELD_TYPE = 
{
    STRING : -1,
    TIME : 7,
    GEOSPACIAL : 19,
    GEO_LAT : 57,
    GEO_LON : 58
};

function VisPanel(a_name,a_data,a_savestate) {
    
    var name          = a_name;        // name = div id for this panel
    var baseData      = a_data;
    var saveState     = a_savestate;
    var stateObject   = null;
    var isenseData;
    var visModules    = new Array(5);
    var currentModule = 0;

    var enableMap         = false;
    var enableTimeline    = false;
    var enableMotionChart = false;
    var enableBarChart    = true;

    /* ---------------- *
     * Public Functions *
     * ---------------- */

    /* parseBaseData
     * Convert the data from the DATA page object format to the old
     * isenseData format.
     */
    this.parseBaseData = function(bd) {
	
	isenseData = new VisData();
	var hasLat = false;
	var hasLon = false;
	var otherCount = 0;

	//
	// Add field information
	//
	for(var fld = 0; fld < bd[0]['fields'].length; ++fld) {
	    var bdfld = bd[0]['fields'][fld];

	    isenseData.fields['id'][fld]          = bdfld.field_id;
	    isenseData.fields['title'][fld]       = bdfld.field_name;
	    isenseData.fields['sensor'][fld]      = bdfld.type_id;
	    isenseData.fields['sensorTitle'][fld] = bdfld.type_name;
	    isenseData.fields['units'][fld]       = bdfld.unit_abbreviation;
	    isenseData.fields['unitType'][fld]    = bdfld.unit_id;
	    isenseData.fields['count']++;

	    if(bdfld.type_id == FIELD_TYPE.TIME) {
		enableTimeline = true;
	    }
	    else if(bdfld.unit_id == FIELD_TYPE.GEO_LAT) {
		hasLat = true;
	    }
	    else if(bdfld.unit_id == FIELD_TYPE.GEO_LON) {
		hasLon = true;
	    }
	    else {
		++otherCount;
	    }
	}
	var numflds = isenseData.fields['count'];

	if(hasLat && hasLon) {
	    enableMap = true;
	}
	
	if(enableTimeline && otherCount >= 2) {
	    enableMotionChart = true;
	}

	//
	// Add session information
	//
	for(var s in bd) {
	    
	    var ses = bd[s]['sessionId'] + 'S';
	    var meta = bd[s]['meta'][0];
	    var data = bd[s]['data'];

	    //
	    // Metadata
	    // 
	    isenseData.sessions[ses] = new Array();
	    isenseData.sessions[ses]['id'] = bd[s]['sessionId'];
	    isenseData.sessions[ses]['date'] = meta.timecreated;
	    isenseData.sessions[ses]['modified'] = meta.timemodified;
	    isenseData.sessions[ses]['address'] = meta.street+","+meta.city;
	    isenseData.sessions[ses]['title'] = meta.name;
	    isenseData.sessions[ses]['visible'] = true;
			
	    //
	    // Data
	    //
	    isenseData.sessions[ses]['data'] = new Array(numflds);
	    for(var i = 0; i < numflds; ++i) {
		isenseData.sessions[ses]['data'][i] = new Array(data.length-1);
		var peeklen = isenseData.sessions[ses]['data'][i].length;
		
		for(j = 1; j < data.length; ++j) {
		    var peekval = data[j][i];
		    isenseData.sessions[ses]['data'][i][j-1] = { value : data[j][i],
		                                                 row : j };
		}
	    }				   
	}
	isenseData.sessions.length = bd.length;
    }

    /* parseSavedState
     * Convert saved state info into a state object to pass to the modules.
     * It may be that the starting format of the saved state info is
     * sufficient to passthrough to modules, so this may be a simple checker
     * to configure this panel object.
     */
    this.parseSavedState = function(ss) {
	
    }

    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    this.eh_tabSelected = function(tabIndex) {
	
	visModules[tabIndex].redraw();
	currentModule = tabIndex;
    }

    //
    // As usual, MotionChart needs special treatment.
    //
    this.eh_tabPreSelect = function(tabIndex) {
	
	if(tabIndex == 3) { // MotionChart only
	    visModules[tabIndex].clean();
	}
    }

    /* -------------------- *
     * Panel Initialization *
     * -------------------- */

    this.parseBaseData(baseData);
    this.parseSavedState(saveState);

    //
    // Populate the panel DOM.
    //
    $("#"+name).createAppend(
	 'ul',{ id : name+'_tabs' }, [
	    'li', { id : name+'_tab_Map', display : 'none' }, [
	       'a', { href : '#'+name+'_Map' }, [
		  'span', { id : name+'_title_Map' }, 'Map']],
	    'li', { id : name+'_tab_Timeline', display : 'none' } , [
	       'a', { href : '#'+name+'_Timeline' }, [
		  'span', { id : name+'_title_Timeline' }, 'Timeline']],
	    'li', { id : name+'_tab_Scatter', display : 'none' }, [
	       'a', { href : '#'+name+'_Scatter'}, [
		  'span', { id : name+'_title_Scatter'}, 'Scatter Chart']],
	    'li', { id : name+'_tab_Motion', display : 'none' }, [
	       'a', { href : '#'+name+'_Motion' }, [
	          'span', { id : name+'_title_Motion' }, 'Motion Chart']],
	    'li', { id : name+'_tab_Bar', display : 'none' }, [
	       'a', { href : '#'+name+'_Bar' }, [
		  'span', { id : name+'_title_Bar' }, 'Bar Chart']]]);
    
    //
    // Add the appropriate modules based on clues found when 
    // parsing the data object.
    //
    if(enableMap) {
	$("#"+name).createAppend('div', { id : name+'_Map' }, []);
	visModules[0] = new MapModule(this, name+'_Map', isenseData, stateObject);
    }
    else {
	$("#"+name+"_title_Map").prepend('Session ');
	$("#"+name).createAppend('div', { id : name+'_Map' }, []);
	visModules[0] = new SessionMapModule(this, name+'_Map', isenseData, stateObject);
    }
    
    if(enableTimeline) {
	$("#"+name).createAppend('div', { id : name+'_Timeline' }, []);
	visModules[1] = new AnnotatedTimeLineModule(this, name+'_Timeline', isenseData, stateObject);
    }
    else {
	$("#"+name+"_tab_Timeline").hide();
	visModules[1] = null;
    }
 
    $("#"+name).createAppend('div', { id : name+'_Scatter' }, []);
    visModules[2] = new ScatterChartModule(this, name+'_Scatter', isenseData, stateObject);

    if(enableMotionChart) {
	$("#"+name).createAppend('div', { id : name+'_Motion' }, []);
	visModules[3] = new MotionChartModule(this, name+'_Motion', isenseData, stateObject);
    }
    else {
	$("#"+name+"_tab_Motion").hide();
	visModules[3] = null;
    }

    if(enableBarChart) {
	$("#"+name).createAppend('div', { id: name+'_Bar'}, []);
	visModules[4] = new ColumnChartModule(this, name+'_Bar', isenseData, stateObject);
    }
    else {
	$("#"+name+"_tab_Bar").hide();
	visModules[4] = null;
    }

    //
    // Bind a handler to the tab selected event, so that the associated
    // module will be updated when it's tab is clicked.
    //
    $("#"+name).bind('tabsshow', {scope:this}, 
		     function(evt,ui) {
			 evt.data.scope.eh_tabSelected(ui.index);
			 return true;
		     });
    $("#"+name).bind('tabsselect', {scope:this},
		     function(evt,ui) {
			 evt.data.scope.eh_tabPreSelect(ui.index);
			 return true;
		     });

    //
    // Initialize modules
    //
    for(i in visModules) {
	if(visModules[i]) {
	    visModules[i].init();
	}
    }

    //
    // Initialize the tab interface
    //
    $("#"+name).tabs();
}

/*this, name+'_Bar', isenseData, stateObject
 * TAG ANNOTATEDTIMELINE
 */
function AnnotatedTimeLineModule(a_parent, a_name, a_data, a_state) {
    
    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;

    //
    // Meta Data
    //
    var selectedGenerator      = 1;
    var fieldVisible           = new Array(isenseData.fields['count']);
    var startTimeOffset        = new Array();
    var offsetSliderLocation   = new Array();
    var offsetSliderScale      = new Array();
    
    //
    // These are the first twenty colors used by Google Visualization ScatterPlot.
    // They seem to be the same across other visualizations, although others often
    // have less range. It may be good to have a discussion on good color choices.
    //
    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];
    
    /*
     * Check the field in the iSENSE data structure (assuming it has time data), and check if
     * the data is in the unix time format. If not, try parsing it as a human readable date, for
     * example "April 21 2009, 12:47:05"
     */
    var getTimeData = function(session_id, field_id, datapoint) {

	var unixts = parseInt(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	
	if(isNaN(unixts)) {
	    unixts = Date.parse(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	    return unixts;
	}
	
	return unixts * 1000;
    }
    
    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    this.eh_toggleField = function(fld) {
	fieldVisible[fld] = !fieldVisible[fld];
	this.redraw();
    }

    this.eh_toggleSession = function(ses) {
	isenseData.sessions['visible'] = !isenseData.sessions['visible'];
	this.redraw();
    }

    this.eh_selectRecipe = function() {
	var selectedOp = parseInt(findInputElement("f_"+name+"_form",
						   "i_"+name+"_recipe_select").value);
	switch(selectedOp) {
	case 0:
	case 1:
	    selectedGenerator = selectedOp;
	    this.redraw();
	    break;
	    
	default:
	    break;
	}
    }

    this.eh_tsReset = function(ses) {
	offsetSliderLocation[ses] = 0;
	startTimeOffset[ses] = 0;
	this.redraw();
    }
    
    this.eh_tsUnitSelect = function(ses) {
	var selectedVal = parseInt(findInputElement("f_"+name+"_form",
						    "i_"+name+"_ts_unit_select_"+ses).value);
	if(isNaN(selectedVal)) {
	    selectedVal = 0;
	}
	
	offsetSliderScale[ses] = selectedVal;
	offsetSliderLocation[ses] = 0;
	this.redraw();
    }
    
    this.eh_tsSlideTick = function(ses, slideval) {
	//
	// Update the offset display text, but not the visualization
	//
	var changeVal = slideval - offsetSliderLocation[ses];
	var shiftScalar = 1000 * offsetSliderScale[ses];
	if(changeVal < 0) {
	    changeVal = Math.floor(changeVal);
	} else {
	    changeVal = Math.ceil(changeVal);
	}
	startTimeOffset[ses] += (changeVal * shiftScalar);
	offsetSliderLocation[ses] = slideval;
	
	var timeString = millisToClockString(startTimeOffset[ses]);
	$('#i_'+name+'_ts_offset_disp'+ses).html(timeString);
    }

    this.eh_tsSlideEnd = function(ses, slideval) {
	//
	// Slide complete, now the visualization can be updated
	//
	offsetSliderLocation[ses] = 0;
	this.redraw();
    }
    /* --- */
    
    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
	startTimeOffset[session_id]      = 0;
	offsetSliderLocation[session_id] = 0;
	offsetSliderScale[session_id]    = 1;
    }

    /*
     * Generate state and meta data.
     */
    this.generateMetaData = function() {
	for(var i = 0; i < fieldVisible.length; ++i) {
	    fieldVisible[i] = true;
	}
    }

    /*
     * Redraw the control panel and visualization.
     */
    this.redraw = function() {
	try {
	    tableGenerator[selectedGenerator]();
	    legendGenerator[selectedGenerator]();
	    //visObject = new google.visualization.AnnotatedTimeLine(viewPane);
	    visObject.draw(datatable, options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    this.clean = function() {
    }

    /*
     * Create HTML legend generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator.length = 2;

	//
	//
	//
	legendGenerator[0] = function(scope) {
	    
	    var table;
	    var fieldColor;

	    controlPane.empty();
	    fieldColor = 0;

	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {}, [
		    'td', { style : 'text-align:center;width:100%' , colSpan : 3 }, 'Timeline'],
		  'tr', {}, [
		    'td', { colSpan : 3 }, [
		      'select', { id : 'i_'+name+'_recipe_select', name : 'i_'+name+'_recipe_select', style : 'width:100%' }, [
		        'option', { id : 'i_'+name+'_recipe_opt_1', value : 1 }, 'Show Exact Time',
		        'option', { id : 'i_'+name+'_recipe_opt_0', value : 0 }, 'Side-by-Side Comparison']]],
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Fields']]]);

	    table = $('#f_'+name+'_table');

	    //
	    // Setup repice selection, which determines which datatable generator to use.
	    //
	    $('#i_'+name+'_recipe_opt_'+selectedGenerator).attr('selected','selected');	    
	    $('#i_'+name+'_recipe_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectRecipe();
			  return false;
		      });
	    
	    //
	    // Field Selection
	    //
	    for(var i = 0; i < isenseData.fields['count']; ++i) {
		
		if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME
		   || isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
		    continue;
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { style : 'width:16px' }, [
		      'input', { type : 'checkbox',
			         id : 'i_'+name+'_field_'+i+'_select',
			         name : 'i_'+name+'_field_'+i+'_select'
			       }],
		    'td', { columnspan : 2 }, isenseData.fields['title'][i]+' ('+isenseData.fields['units'][i]+')']);

		if(fieldVisible[i]) {
		    $('#i_'+name+'_field_'+i+'_select').attr('checked','checked');
		}
		
		$('#i_'+name+'_field_'+i+'_select')
		    .bind('click', { scope : thisModule, field : i },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleField(evt.data.field);
			      return false;
			  });
	    }

	    table.createAppend('tr', {}, [
			         'td', { colSpan : "3", style : 'text-decoration:underline;' }, 'Sessions']);

	    //
	    // Session Selection
	    //
	    for(var ses in sessions) {
		
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'checkbox',
			         id : 'i_'+name+'_session_'+ses+'_select'}, []],
		    'td', { colSpan : "2" }, isenseData.sessions[ses]['id']+' - '+isenseData.sessions[ses]['title']]);
		
		if(isenseData.sessions[ses]['visible']) {
		    $('#i_'+name+'_session_'+ses+'_select').attr('checked','checked');
		
		    //
		    // Field color distinction
		    //
		    var j = 1;
		    for(i = 0; i < isenseData.fields['count'];  ++i) {
			
			if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME
			   || isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
			    continue;
			}

			table.createAppend(
			  'tr', {}, [
			    'td', { id : 'f_'+name+'_colorfor_'+ses+'_'+i }, [],
			    'td', {}, j+' - '+isenseData.fields['title'][i]]);
			++j;

			if(fieldVisible[i]) {
			    $('#f_'+name+'_colorfor_'+ses+'_'+i)
				.css({'background-color' : colorsToUse[fieldColor++],
				      'border-width' : 'thin',
				      'border-color' : 'black'});
			}
			else {
			    $('#f_'+name+'_colorfor_'+ses+'_'+i).html('x');
			}
		    }

		    //
		    // Session start time offset slider - only works in sync'd start mode
		    // (only if there are more than 1 session)
		    if(sessions.length > 1 && selectedGenerator == 0) {
			
			table
			  .createAppend(
			    'tr', {}, [
			      'td', {}, [],
			      'td', {}, "Time shift:",
			      'td', {}, [
				'span', { id : 'i_'+name+'_ts_offset_disp'+ses }, []]]);
			table
			  .createAppend(
			    'tr', {}, [
			      'td', {}, [],
			      'td', { colSpan : "2" }, [
			        'div', { id : 'i_'+name+'_ts_slider_'+ses }, []]]);
			table
			  .createAppend(
			    'tr', {}, [
			      'td', { colSpan : "2" }, [
			        'input', { type : 'button', id : 'i_'+name+'_ts_reset_'+ses, value : 'Reset', style : 'width:20%' }, [],
				'select', { id : 'i_'+name+'_ts_unit_select_'+ses, name : 'i_'+name+'_ts_unit_select_'+ses }, [
				  'option', { id : 'i_'+name+'_ts_unit_opt_sec_'+ses, value : 1 }, 'Seconds',
				  'option', { id : 'i_'+name+'_ts_unit_opt_min_'+ses, value : 60 }, 'Minutes',
				  'option', { id : 'i_'+name+'_ts_unit_opt_hour_'+ses, value : 3600 }, 'Hours']]]);
		    
			if(offsetSliderScale[ses] == 1) {
			    $('#i_'+name+'_ts_unit_opt_sec_'+ses).attr('selected','selected');
			}
			else if(offsetSliderScale[ses] == 60) {
			    $('#i_'+name+'_ts_unit_opt_min_'+ses).attr('selected','selected');
			}
			else if(offsetSliderScale[ses] == 3600) {
			    $('#i_'+name+'_ts_unit_opt_hour_'+ses).attr('selected','selected');
			}

			$('#i_'+name+'_ts_reset_'+ses)
			    .bind('click', { scope : thisModule, session:ses },
				  function(evt, obj) {
				      evt.data.scope.eh_tsReset(evt.data.session);
				      return false;
				  });
			$('#i_'+name+'_ts_unit_select_'+ses)
			    .bind('select', { scope : thisModule, session:ses },
				  function(evt, obj) {
				      evt.data.scope.eh_tsUnitSelect(evt.data.session);
				      return false;
				  });
			$('#i_'+name+'_ts_slider_'+ses)
			    .slider({ max:60, min:-60, value:0, steps:120 });
			$('#i_'+name+'_ts_slider_'+ses)
			    .bind('slidestop', { scope : thisModule, session : ses },
				  function(evt, obj) {
				      evt.data.scope.eh_tsSlideEnd(evt.data.session, obj.value);
				  });
			$('#i_'+name+'_ts_slider_'+ses)
			    .bind('slide', { scope : thisModule, session : ses },
				  function(evt, obj) {
				      evt.data.scope.eh_tsSlideTick(evt.data.session, obj.value);
				  });
		    }
		}
		
		$('#i_'+name+'_session_'+ses+'_select')
		    .bind('click', { scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      return false;
			  });
		
	    }
	    // End session selection
	    //
	};
	
	//
	// The control panel need not be different in this case.
	//
	legendGenerator[1] = legendGenerator[0];
    }

    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {
	
	tableGenerator.length = 2;

	/* This needs to be finished to replace the original
	 * The original would not correctly offset the graph because of the way
	 *     it built up the rows (which aligned points on every plot to
	 *     time 'buckets', even if it wasn't an accurate location)
	 */
	tableGenerator[0] = function() {

	    delete datatable;
	    datatable = new google.visualization.DataTable();

	    var fieldCount         = 0;
	    var earliestTimestamp  = -1;
	    var timeField;
	    var sessionFieldCount  = new Array();
	    var sessionColumnStart = new Array();
	    var sessionDataPoint   = new Array();
	    var keepAddingPoints   = true;

	    //
	    // Add columns and find earliest timestamp
	    //
	    datatable.addColumn('datetime','Time');
	    for(var i in sessions) {
		if(!sessions[i].visible) {
		    continue;
		}
		
		var fieldTitleNumber = 1;
		sessionFieldCount[i] = 0;
		sessionColumnStart[i] = fieldCount + 1;
		sessionDataPoint[i] = 0;
		for(var j = 0; j < isenseData.fields['count']; ++j) {
		    var nextdata;
		    if(isenseData.fields['sensor'][j] == FIELD_TYPE.TIME) {
			
			timeField = j;
			nextdata = getTimeData(i,j,0);
			if(earliestTimestamp == -1 ||
			   earliestTimestamp > nextdata) {
			    earliestTimestamp = nextdata;
			}
		    }
		    else if(isenseData.fields['sensor'][j] != FIELD_TYPE.GEOSPACIAL &&
			    fieldVisible[j]) {

			var coltitle = "#" + sessions[i]['id'] + "-" + fieldTitleNumber + " (" + isenseData.fields['units'][j] + ")";
			datatable.addColumn('number',coltitle);
			++sessionFieldCount[i];
			++fieldCount;
			++fieldTitleNumber;
		    }
		}
	    }

	    if(datatable.getNumberOfColumns() <= 1) {
		delete datatable;
		datatable = new google.visualization.DataTable();
		datatable.addColumn('datetime','Time');
		datatable.addColumn('number','No Data');
	    }
	    
	    //
	    // Attempt to cluster sessions together in similar timestamps, without greatly
	    // reducing the resolution.
	    //
	    var row = 0;
	    while(keepAddingPoints) {
		var sessionsToAdd = [];
		var nextTimestamp = -1;
		keepAddingPoints = false;

		//
		// Go through the next datapoint that needs to be added for each session,
		// and get a set of sessions with the earliest timestamp that are close enough
		// to be grouped into a single timestamp.
		//
		for(var i in sessions) {
		    if(!sessions[i].visible || 
		       sessionDataPoint[i] >= isenseData.sessions[i]['data'][timeField].length) {
			continue;
		    }
		    keepAddingPoints = true;
		    var sesTimestamp = earliestTimestamp + startTimeOffset[i] +
			getTimeData(i,timeField,sessionDataPoint[i]) - getTimeData(i,timeField,0);
		                // (parseInt(isenseData.sessions[i]['data'][timeField][sessionDataPoint[i]].value) -
		                // parseInt(isenseData.sessions[i]['data'][timeField][0].value));
		    //
		    // Set resolution here: 100 = tenth of second
		    sesTimestamp -= (sesTimestamp % 100);
		    
		    //
		    // If a session timestamp is less than nextTimestamp (the value of
		    // the next timestamp to be added), clear sessionsToAdd, since there
		    // is an early occuring datapoint that should be added before others.
		    //
		    if(nextTimestamp == -1 ||
		       nextTimestamp > sesTimestamp) {
			delete sessionsToAdd;
			sessionsToAdd = [];
			sessionsToAdd.push(i);
			nextTimestamp = sesTimestamp;
		    }
		    else if(nextTimestamp == sesTimestamp) {
			sessionsToAdd.push(i);
		    }
		}
		
		//
		// Add the set of sessions to the table
		//
		datatable.addRow();
		datatable.setValue(row, 0, new Date(nextTimestamp));
		for(var i = 0; i < sessionsToAdd.length; ++i) {
		    var ses = sessionsToAdd[i];

		    var fldadd = 0;
		    for(var j = 0; j < isenseData.fields['count']; ++j) {
			if(fieldVisible[j]) {
			    datatable.setValue(row, sessionColumnStart[ses] + fldadd,
					       parseFloat(isenseData.sessions[ses]['data'][j][sessionDataPoint[ses]]));
			    ++fldadd;
			}
		    }
		    ++sessionDataPoint[ses];

		    //
		    // Error check number of columns added
		    //
		    if(fldadd != sessionFieldCount[ses]) {
			delete datatable;
			datatable = new google.visualization.DataTable();
			datatable.addColumn('datetime','Time');
			datatable.addColumn('number','No Data - Session ' + ses + ' only added ' + 
					    fldadd + ' fields out of ' + sessionFieldCount[ses]);
		    }
		}

		//
		// Prepare for next round
		//
		++row;
		nextTimestamp = 1;
	    }

	    //
	    // Final error checking
	    //
	    if(datatable.getNumberOfRows() == 0) {
		delete datatable;
		datatable = new google.visualization.DataTable();
		datatable.addColumn('datetime','Time');
		datatable.addColumn('number','No Data - 0 rows added');
	    }
	    
	}
	/* Below is the flawed original*/

	tableGenerator[0] = function(scope) {
	    
	    //
	    // Refresh Table
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Generate Columns
	    //
	    var fldcnt = 0;
	    var fldTitleNumber;
	    var sessionFieldCount = new Array();
	    var sessionRowPosition = new Array();
	    var sessionIntervalLength = new Array();
	    var smallestInterval = -1;
	    var largestInterval = 0;
	    var startTime = -1;
	    var latestStartTime = 0;
	    datatable.addColumn('datetime','Time');
	    for(var i in sessions) {
		if(!sessions[i].visible) {
		    continue;
		}

		sessionFieldCount[i] = 0;
		sessionRowPosition[i] = 0;
		fldTitleNumber = 1;
		for(var j = 0; j < isenseData.fields['count']; j++) {
		    if(isenseData.fields['sensor'][j] == FIELD_TYPE.TIME) {
			// Timestamp
			// TODO: Check the field precision to decide how
			//       to truncate the interval, i.e. data
			//       if all data is taken at one second
			//       intervals, this should zero out the
			//       last three digits.
			
			var first_t = getTimeData(i,j,0);
			if(isenseData.sessions[i]['data'][j].length > 1) {
			    var interval = getTimeData(i,j,1) - first_t;
			    interval -= (interval % 100); // Eliminate tiny differences
			    sessionIntervalLength[i] = interval;
			    
			    if(interval < smallestInterval || smallestInterval == -1) {
				smallestInterval = interval;
			    }
			    
			    if(interval > largestInterval) {
				largestInterval = interval;
			    }
			    
			    if((first_t + startTimeOffset[i]) < startTime || startTime == -1) {
				startTime = first_t + startTimeOffset[i];
			    }
			    
			    if((first_t + startTimeOffset[i]) > latestStartTime) {
				latestStartTime = first_t + startTimeOffset[i];
			    }
			}
			else if(isenseData.sessions[i]['data'][j].length == 1) {
			    sessionIntervalLength[i] = 1000;
			    
			    if((first_t + startTimeOffset[i]) < startTime || startTime == -1) {
				
				startTime = (first_t + startTimeOffset[i]);
			    }
			    
			    if((first_t + startTimeOffset[i]) > latestStartTime) {
				
				latestStartTime = first_t + startTimeOffset[i];
			    }
			}
		    }
		    else if(isenseData.fields['sensor'][j] != FIELD_TYPE.GEOSPACIAL) {
			// Ignore Latitude and Longitude
			
			if(fieldVisible[j]) {
			    var coltitle = "#" + sessions[i]['id'] + "-" + fldTitleNumber + " (" + isenseData.fields['units'][j] + ")";
			    datatable.addColumn('number',coltitle);
			    sessionFieldCount[i]++;
			    fldcnt++;
			    fldTitleNumber++;
			}
		    }
		}
	    }
	    
	    var tzOffset = (new Date().getTimezoneOffset()) - (new Date(startTime).getTimezoneOffset());
	    tzOffset *= 60000;
	    
	    //
	    // Add dummy data columns which display an error message in the vis if
	    // there was a problem generating the columns.
	    //
	    if(fldcnt == 0) {
		datatable.addColumn('number','No Data');
		return;
	    }
	    else if(startTime == -1) {
		delete datatable;
		datatable = new google.visualization.DataTable();
		datatable.addColumn('datetime','Time');
		datatable.addColumn('number','Data cannot be graphed - invalid time data');
		return;
	    }
	    
	    //
	    // Get the GCD of sessionIntervals
	    //
	    var workInterval = smallestInterval;
	    for(var i = 2; workInterval > 1 && !GCDFound; i++) {
		var GCDFound = true;
		for(var ses in sessionIntervalLength) {
		    if(!sessions[ses].visible) {
			continue;
		    }
		    
		    if(sessionIntervalLength[ses] % workInterval != 0) {
			GCDFound = false;
			break;
		    }
		}
		if(!GCDFound) {
		    workInterval = smallestInterval / i;
		}
	    }
	    smallestInterval = workInterval;
	    
	    //
	    // Convert session intervals to a "add every X rows" format,
	    // and calculate how many starting intervals to skip based on
	    // the shifted start time (make sure all are positive).
	    // 
	    var currentInterval = new Array();
	    var smallestIntervalOffset = 1000000;
	    var largestIntervalOffset = 0;
	    var startIntervalOffset = new Array();
	    var creditedIntervalOffset = new Array();
	    for(var ses in sessionIntervalLength) {
		currentInterval[ses] = 1;
		creditedIntervalOffset[ses] = 0;
		sessionIntervalLength[ses] /= smallestInterval;
		startIntervalOffset[ses] = startTimeOffset[ses] / smallestInterval;
		if(startIntervalOffset[ses] < 0) {
		    startIntervalOffset[ses] = Math.floor(startIntervalOffset[ses]);
		} else {
		    startIntervalOffset[ses] = Math.ceil(startIntervalOffset[ses]);
		}
		if(startIntervalOffset[ses] < smallestIntervalOffset) {
		    smallestIntervalOffset = startIntervalOffset[ses];
		}
	    }
	    largestInterval /= smallestInterval;
	    
	    for(var ses in startIntervalOffset) {
		startIntervalOffset[ses] -= smallestIntervalOffset;
		if(startIntervalOffset[ses] > largestIntervalOffset) {
		    largestIntervalOffset = startIntervalOffset[ses];
		}
	    }
	    
	    //
	    // Create data rows and then add data.
	    //
	    var maxLength = 0;
	    var max_i = [0,0];
	    for(var i in sessions) {
		if(!sessions[i].visible) {
		    continue;
		}
		
		if(max_i[0] == 0) {
		    max_i[0] = i;
		}	
		for(var j = 0; j < isenseData.fields['count']; j++) {
		    if(fieldVisible[j] && 
		       (isenseData.sessions[i]['data'][j].length * sessionIntervalLength[i]) + largestIntervalOffset > maxLength) {
			maxLength = (isenseData.sessions[i]['data'][j].length * sessionIntervalLength[ses]) + largestIntervalOffset;
			max_i = [i,j];
		    }
		}
	    }
	    datatable.addRows(maxLength);
	    
	    var tot_added = 0;
	    var limit = isenseData.sessions[max_i[0]]['data'][max_i[1]].length;
	    for(var i = 0; i < maxLength; i++) {
		var convertedTime = (startTime + (smallestInterval * i)) + tzOffset;
		datatable.setValue(i,0,new Date(convertedTime));
		var cpos = 1;
		
		for(var j in sessions) {
		    if(!sessions[j].visible) {
			continue;
		    }

		    //
		    // Check to see if adding data should be delayed for a start point shift
		    //
		    if(startIntervalOffset[j] > creditedIntervalOffset[j]) {
			creditedIntervalOffset[j]++;
			sessionRowPosition[j]++;
			cpos += sessionFieldCount[j];
		    }
		    else {
			for(var k = 0; k < isenseData.fields['count']; k++) {
			    var sensorType = isenseData.fields['sensor'][k];
			    if(fieldVisible[k] && sensorType != FIELD_TYPE.TIME && sensorType != FIELD_TYPE.GEOSPACIAL) {
				
				if(isenseData.sessions[j]['data'][k].length > (i - startIntervalOffset[j]) && 
				   currentInterval[j] <= sessionIntervalLength[j]) {
				    
				    datatable.setValue(sessionRowPosition[j],cpos,
						       parseFloat(isenseData.sessions[j]['data'][k][(i-startIntervalOffset[j])].value));
				    tot_added++;
				}
				cpos++;
			    }
			}
			
			if(currentInterval[j] <= sessionIntervalLength[j]) {
			    sessionRowPosition[j]++;
			}
			
			if(++currentInterval[j] > largestInterval) {
			    currentInterval[j] = 1;
			}
		    }
		}
	    }
	    
	    //
	    // If no data has been added, the table should be set
	    // to a totally empty table for the visualization to
	    // behave correctly
	    //
	    if(tot_added == 0) {
		delete datatable;
		datatable = new google.visualization.DataTable();
		datatable.addColumn('datetime','Time');
		datatable.addColumn('number','No Data');
	    }
	};
	/* */

	/*
	 * This generator places points in the time dimension
	 * at the exact time they were actually taken.
	 */
	tableGenerator[1] = function(scope) {
	    
	    //
	    // Refresh Table
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Generate Columns. Get the total number of data points that
	    // will be added.
	    //
	    var time_fld = -1;
	    var fld_i = [];
	    var fld_cnt = 0;
	    var fldTitleNumber;
	    var data_cnt = 0;
	    var row = 0;
	    datatable.addColumn('datetime','Time');
	    for(var i in sessions) {
		if(!sessions[i].visible) {
		    continue;
		}
		
		fld_i[i] = [];
		fldTitleNumber = 1;
		for(var j = 0; j < isenseData.fields['count']; j++) {
		    var sensorType = isenseData.fields['sensor'][j];
		    if(sensorType == FIELD_TYPE.TIME) {
			time_fld = j;
		    }
		    else if(fieldVisible[j] &&
			    sensorType != FIELD_TYPE.GEOSPACIAL) {
			var coltitle = "#" + isenseData.sessions[i]['id'] + "-" + isenseData.fields['title'][j] + " (" + isenseData.fields['units'][j] + ")";
			datatable.addColumn('number',coltitle);
			fld_i[i][j] = {cnt: 0,col: (fld_cnt + 1)};
			fld_cnt++;
			fldTitleNumber++;
			data_cnt += isenseData.sessions[i]['data'][j].length;
		    }
		}
	    }
	    
	    //
	    // Add dummy data columns which display an error message in the vis if
	    // there was a problem generating the columns.
	    //
	    if(fld_cnt == 0) {
		datatable.addColumn('number','No Data');
		return;
	    }
	    if(time_fld == -1) {
		delete datatable;
		datatable = new google.visualization.DataTable();
		datatable.addColumn('datetime','Time');
		datatable.addColumn('number','Data cannot be graphed - invalid time data');
		return;
	    }
	    
	    //
	    // Add all the points.
	    //
	    var d = 0;
	    try {
		while(d < data_cnt) {
		    //
		    // Find the data point with the earliest timestamp
		    //
		    var selected_data = [];
		    var early_ts = null;
		    for(var i in sessions) {
			if(!sessions[i].visible) {
			    continue;
			}

			for(var j = 0; j < isenseData.fields['count']; j++) {
			    var sensorType = isenseData.fields['sensor'][j];
			    if(fieldVisible[j] && sensorType != FIELD_TYPE.TIME && sensorType != FIELD_TYPE.GEOSPACIAL) {
				var k = fld_i[i][j].cnt;
				if(k < isenseData.sessions[i]['data'][j].length) {
				    var new_ts = getTimeData(i,time_fld,k);
				    if(early_ts == null || new_ts < early_ts) {
					early_ts = new_ts;
					delete selected_data;
					selected_data = [];
					selected_data.push([i,j]);
				    }
				    else if(new_ts == early_ts){
					selected_data.push([i,j]);
				    }
				}
			    }
			}
		    }
		    
		    //
		    // Add the selected data to a new row.
		    //
		    var old_d = d;
		    var tzOffset = (new Date().getTimezoneOffset()) - (new Date(early_ts).getTimezoneOffset());
		    tzOffset *= 60000;
		    var convertedTime = early_ts + tzOffset;
		    datatable.addRow();
		    datatable.setValue(row,0,new Date(convertedTime));
		    for(var s = 0; s < selected_data.length; s++) {
			var i = selected_data[s][0];
			var j = selected_data[s][1];
			var k = fld_i[i][j].cnt++;
			var col = fld_i[i][j].col;
			datatable.setValue(row,col,parseFloat(isenseData.sessions[i]['data'][j][k].value));
			d++;
		    }
		    row++;
		    
		    if(old_d == d) {
			alert("tried to add new points, but could not!");
			break;
		    }
		}
		
	    }catch(e){
		alert("AnnotatedGen1:" + e);
	    }
	};
    }
    
    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */    
    this.init = function(panelDiv) {
	//
	// Create DIVs for vis and control
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});
	
	controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create the Google visualization object
	//
	visObject = new google.visualization.AnnotatedTimeLine(viewPane);

	//
	// Check Google options to ensure good defaults exist
	//
	options = { colors : colorsToUse,
		    legendPosition : "newRow" };

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}

/*
 * TAG SCATTERCHART
 */
function ScatterChartModule(a_parent, a_name, a_data, a_state) {
    
    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();;
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;

    //
    // Meta Data
    //
    var selectedGenerator = 0;
    var axisField         = -1;
    var fieldVisible      = new Array(isenseData.fields['count']);
    var startTimeOffset   = new Array();
        
    //
    // These are the first twenty colors used by Google Visualization ScatterPlot.
    // They seem to be the same across other visualizations, although others often
    // have less range. It may be good to have a discussion on good color choices.
    //
    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];
    
    /*
     * Check the field in the iSENSE data structure (assuming it has time data), and check if
     * the data is in the unix time format. If not, try parsing it as a human readable date, for
     * example "April 21 2009, 12:47:05"
     */
    var getTimeData = function(session_id, field_id, datapoint) {

	var unixts = parseInt(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	
	if(isNaN(unixts)) {
	    unixts = Date.parse(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	    return unixts;
	}
	
	return unixts * 1000;
    }

    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    this.eh_toggleSession = function(ses) {
	sessions[ses]['visible'] = !sessions[ses]['visible'];
	this.redraw();
    }

    this.eh_toggleField = function(fld) {
	fieldVisible[fld] = !fieldVisible[fld];
	this.redraw();
    }

    this.eh_selectAxis = function() {
	var selectedId = parseInt(findInputElement("f_"+name+"_form",
						   "i_"+name+"_axis_select").value);
	axisField = selectedId;
	this.redraw();
    }

    this.eh_toggleLines = function() {
	if(options.lineSize > 0) {
	    options.lineSize = 0;
	}
	else {
	    options.lineSize = 1;
	}
	this.redraw();
    }
    /* --- */

    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
	startTimeOffset[session_id] = 0;
    }
    
    /*
     * Generate state and meta data.
     * TODO: change for new fieldType values
     */
    this.generateMetaData = function() {
	var i;
	var fieldType;
	for(i = 0; i < isenseData.fields['count']; i++) {
	    fieldType = isenseData.fields['sensor'][i];
	    if(fieldType == FIELD_TYPE.TIME) {
		axisField = i;
	    }
	}

	for(var i = 0; i < fieldVisible.length; ++i) {
	    fieldVisible[i] = true;
	}
    }
    
    /*
     * Redraw controls and visualization with current state.
     */
    this.redraw = function() {
	try {
	    tableGenerator[selectedGenerator]();
	    legendGenerator[selectedGenerator]();
	    visObject.draw(datatable,options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    this.clean = function() {
    }
    
    /*
     * Create control pane generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator.length = 1;
	legendGenerator[0] = function() {

	    var table;
	    var fieldColor;

	    controlPane.empty();
	    fieldColor = 0;

	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Scatter Chart'],
		  'tr', {}, [
		    'td', { colSpan : 3 }, 'X Axis:'],
		  'tr', {}, [
		    'td', {}, '',
		    'td', { colSpan : 2 }, [
		      'select', { id : 'i_'+name+'_axis_select', name : 'i_'+name+'_axis_select' }, [
		        'option', { value : -1 }, 'Datapoint #']]],
		  'tr', {}, [
		    'td', { style : "width:16px" }, [
		      'input', { type : 'checkbox', id : 'i_'+name+'_toggle_drawline'}, []],
		    'td', { colSpan : 2 }, 'Draw lines through points'],
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Fields']]]);

	    table = $('#f_'+name+'_table');
	    if(options.lineSize > 0) {
		$('#i_'+name+'_toggle_drawline').attr('checked','checked');
	    }
	    $('#i_'+name+'_axis_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectAxis();
			  return false;
		      });
	    $('#i_'+name+'_toggle_drawline')
	        .bind('click', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_toggleLines();
			  return false;
		      });
	    
	    //
	    // Add fields to list and field axis selection
	    //
	    for(var i = 0; i < isenseData.fields['count']; ++i) {
		
		if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
		    continue;
		}
		
		

		var sel = "";
		if(i == axisField) {
		    sel = "selected";
		}

		$('#i_'+name+'_axis_select').createAppend(
		  'option', { value : i, selected : sel }, isenseData.fields['title'][i]);

		table.createAppend('tr', { id : 'f_'+name+'_field_row_'+i }, []);

    if (isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
      continue;
    }

		if(i == axisField) {
		    $('#f_'+name+'_field_row_'+i).createAppend('td', {}, 'X');
		}
		else {
		    $('#f_'+name+'_field_row_'+i).createAppend(
		      'td', {}, [
		        'input', { type : 'checkbox',
				   id : 'i_'+name+'_field_'+i+'_select',
				   name : 'i_name'+name+'_field_'+i+'_select'
				 }, []]);
		    
		    if(fieldVisible[i]) {
			$('#i_'+name+'_field_'+i+'_select').attr('checked','checked');
		    }
		}

		$('#f_'+name+'_field_row_'+i).createAppend(
		  'td', { colSpan : 2 }, isenseData.fields['title'][i]+' ('+isenseData.fields['units'][i]+')');
		$('#i_'+name+'_field_'+i+'_select')
		    .bind('click', { scope : thisModule, field : i },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleField(evt.data.field);
			      return false;
			  });
	    }

	    table.createAppend(
	      'tr', {}, [
	        'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);

	    //
	    // Session selection and information.
	    //
	    for(var ses in sessions) {
		
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'checkbox',
			         id : 'i_'+name+'_session_'+ses+'_select' }, []],
		    'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		if(sessions[ses]['visible']) {
		    $('#i_'+name+'_session_'+ses+'_select').attr('checked','checked');

		    var j = 1;
		    for(i = 0; i < isenseData.fields['count']; ++i) {
			if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL
			   || i == axisField) {
			    continue;
			}

			table.createAppend(
			  'tr', {}, [
			    'td', { id : 'f_'+name+'_colorfor_'+ses+'_'+i }, [],
			    'td', {}, j+' - '+isenseData.fields['title'][i]]);
			++j;
		
			if(fieldVisible[i]) {
			    $('#f_'+name+'_colorfor_'+ses+'_'+i)
				.css({'background-color' : colorsToUse[fieldColor++],
				      'border-width' : 'thin',
				      'border-color' : 'black'});
			}
			else {
			    $('#f_'+name+'_colorfor_'+ses+'_'+i).html('x');
			}
		    }
		}

		$('#i_'+name+'_session_'+ses+'_select')
		    .bind('click', { scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			  });
	    }

	    //
	    // Modify options to show correct axis title in visualization
	    //
	    var axisTitle;
	    if(axisField == -1) {
		axisTitle = "Datapoint Number";
	    }
	    else {
		axisTitle = isenseData.fields['title'][axisField];
	    }
	    
	    if (isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
	      axisTitle += ' (s)';
	    } else {
	      axisTitle += ' ('+isenseData.fields['units'][axisField]+')';
	    }
	    options.titleX = axisTitle;
	};
    }

    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {
	
	tableGenerator.length = 1;
	tableGenerator[0] = function() {
	
	    //
	    // Refresh datatable.
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Add columns, starting with the field the user chose
	    // as the x axis, or a generic "datapoint number" scheme where
	    // the x value for each datapoint is it's position in the array.
	    //
	    var foundTimestampField = -1;
	    var axisFieldTitle = isenseData.fields['title'][axisField] + " (";
	    if(axisField == -1) {
		axisFieldTitle = "Datapoint Number";
	    }
	    else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
	        axisFieldTitle += "s)";
	    }
	    else {
	        axisFieldTitle += isenseData.fields['units'][axisField] + ")";
	    }
	    datatable.addColumn('number',axisFieldTitle);
	    options.titleX = axisFieldTitle;
	    for(var ses in sessions) {
		if(!sessions[ses].visible) {
		    continue;
		}
		
		for(var i = 0; i < isenseData.fields['count']; i++) {
		    var fieldType = isenseData.fields['sensor'][i];
		    if(fieldType == FIELD_TYPE.TIME) {
			foundTimestampField = i;
		    }
		    
		    if(i != axisField && fieldVisible[i] && fieldType != FIELD_TYPE.GEOSPACIAL) {
			if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
			  continue;
			    //axisFieldTitle = '#' + sessions[ses]['id'] + "-" + isenseData.fields['title'][i] + " (s)";
			}
			else {
			    axisFieldTitle = '#' + sessions[ses]['id'] + "-" + isenseData.fields['title'][i] + " (" + isenseData.fields['units'][i] + ")";
			}
			datatable.addColumn('number',axisFieldTitle);
		    }
		}
	    }
	    
	    //
	    // If any of the fields is a timestamp, extra work needs to be done
	    // to convert the unix timestamp into elapsed time.
	    //
	    var firstTimestamp = new Array();
	    if(foundTimestampField != -1) {
		for(var ses in sessions) {
		    if(sessions[ses].visible) {
			firstTimestamp[ses] = getTimeData(ses,foundTimestampField,0); 
			    //parseInt(isenseData.sessions[ses]['data'][foundTimestampField][0].value);
		    }
		}
	    }
	    
	    //
	    // Check to see if any columns have been added.
	    //
	    if(datatable.getNumberOfColumns() <= 1) {
		datatable.addColumn('number','No data');
		return;
	    }
	    
	    //
	    // Group the data based on values of the chosen axis field.
	    // These get converted into a string to be used as an index to an associative array.
	    // Numeric data of other fields are put into these 'bins', and then later moved
	    // into the actual datatable object.
	    //
	    var orgData = new Array();
	    for(var ses in sessions) {
		if(!sessions[ses].visible) {
		    continue;
		}
		
		var datapointNumber = 1;
		var dataArrayLength = 0;
		//
		// Use the length of the largest data array when using datapoint numbers
		// for the x axis.
		//
		if(axisField == -1) {
		    for(var i = 0; i < isenseData.sessions[ses]['data'].length; i++) {
			if (isenseData.sessions[ses]['data'][i].length > dataArrayLength) {
			    dataArrayLength = isenseData.sessions[ses]['data'][i].length;
			}
		    }
		}
		else {
		    dataArrayLength = isenseData.sessions[ses]['data'][axisField].length;
		}
		for(var i = 0; i < dataArrayLength; i++) {
		    var valueId;
		    if(axisField == -1) {
			valueId = datapointNumber + "#";
			datapointNumber++;
		    }
		    else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
			valueId = getTimeData(ses,axisField,i) - firstTimestamp[ses];
			    //(parseInt(isenseData.sessions[ses]['data'][axisField][i].value) - firstTimestamp[ses]);
			valueId -= (valueId % 100);
			valueId = valueId + "#";
		    }
		    else {
			valueId = (parseFloat(isenseData.sessions[ses]['data'][axisField][i].value)) + "#";
		    }
		    
		    if(typeof orgData[valueId] == 'undefined') {
			orgData[valueId] = new Array();
			orgData[valueId]['maxIndex'] = 0;
		    }
		    
		    for(var j = 0; j < isenseData.fields['count']; j++) {
			if(j != axisField && fieldVisible[j]) {
			    
			    if(typeof orgData[valueId][ses] == 'undefined') {
				orgData[valueId][ses] = new Array();
			    }
			    
			    var fieldId = j + "#";
			    
			    if(typeof orgData[valueId][ses][fieldId] == 'undefined') {
				orgData[valueId][ses][fieldId] = new Array();
			    }
			    
			    if(i < isenseData.sessions[ses]['data'][j].length) {
				orgData[valueId][ses][fieldId].push(isenseData.sessions[ses]['data'][j][i].value);
				if(orgData[valueId][ses][fieldId].length > orgData[valueId]['maxIndex']) {
				    orgData[valueId]['maxIndex'] = orgData[valueId][ses][fieldId].length;
				}
			    }
			}
		    }
		}
	    }
	    
	    //
	    // Move organized data into a consise datatable.
	    //
	    var curRow = 0;
	    var curCol = 0;
	    var i;
	    for(i in orgData) {
		
		if(i == "NaN#") {
		    continue;
		}
		
		datatable.addRows(orgData[i]['maxIndex']);
		
		for(var ses in sessions) {
		    if(!sessions[ses].visible) {
			continue;
		    }
		    
		    var curVal;
		    if(curCol == 0) {
			
			if(axisField == -1) {
			    curVal = parseInt(i);
			}
			else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
			    curVal = parseFloat(i) / 1000.0
			}
			else {
			    curVal = parseFloat(i);
			}
			
			for(var k = curRow; k < datatable.getNumberOfRows(); k++) {
			    datatable.setValue(k,0,curVal);
			}
			curCol++;
		    }
		    
		    for(var j = 0; j < isenseData.fields['count']; j++) {
			
			var fieldId = j + "#";
			var fieldType = isenseData.fields['sensor'][j];
			
			if(curCol == datatable.getNumberOfColumns()) {
			    break;
			}
			
			if(axisField != j && fieldVisible[j] &&
			   fieldType != FIELD_TYPE.GEOSPACIAL) {
			    if(typeof orgData[i][ses] != 'undefined' && isNaN(orgData[i][ses][fieldId][k])) {
				if(isenseData.fields['sensor'][j] == FIELD_TYPE.TIME) {
				    continue;
				    for(var k = 0; k < orgData[i][ses][fieldId].length; k++) {
					curVal = parseInt(orgData[i][ses][fieldId][k]) - firstTimestamp[ses];
					curVal = (curVal - (curVal % 100)) / 1000.0;
					if(!isNaN(curVal)) {
					    datatable.setValue(k+curRow,curCol,curVal);
					}
				    }
				}
				else {
				    
				    for(var k = 0; k < orgData[i][ses][fieldId].length; k++) {
					curVal = parseFloat(orgData[i][ses][fieldId][k]);
					if(!isNaN(curVal)) {
					    datatable.setValue(k+curRow,curCol,curVal);
					}
				    }
				}
				
				delete orgData[i][ses][fieldId];
			    }
			    curCol++;
			}
		    }
		    //delete orgData[i][ses];
		}
		curCol = 0;
		curRow = datatable.getNumberOfRows();
		delete orgData[i];
	    }
	};
    }
	
    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */
    this.init = function(panelDiv) {
	//
	// Create DIVs for controls and visualization
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});

	controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create Google visualization object
	//
	visObject = new google.visualization.ScatterChart(viewPane);

	//
	// Check Google options to ensure good defaults exist
	//
	options = { legend : "top",
		    titleY : "Value",
		    colors : colorsToUse,
		    titleX : "Datapoint Number" };

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}

/*
 * TAG MAP
 */
function MapModule(a_parent, a_name, a_data, a_state) {
    
    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var custom          = true;
    var thisModule;

    //
    // Remember Lat and Lon fields
    //
    var F_LAT;
    var F_LON;

    //
    // Meta Data
    //
    var selectedGenerator    = 0;
    var showLines            = true;
    var pointSkip            = 0;
    var pointStart           = new Array();
    var pointCount           = new Array();
    var pointChange          = new Array();
    var pointMove            = new Array();
    //var measuredField        = new Array();
    var measuredField        = -1;
    var maxPointsCollapsed   = new Array();
    var shownPointsCollapsed = new Array();
    //var sortByHighestDensity = new Array();
    var sortByHighestDensity = false;
    var cm_pointStart        = new Array();
    var cm_pointMove         = new Array();
    var cm_pointChange       = new Array();


    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];

    /*
     * Sorting function for collapsing datatable generator
     */
    var markerSort = function(a,b) {
	return b.count - a.count;
    }

    /*
     * Find Lat and Lon fields
     */
    for(var sfield = 0; sfield < isenseData.fields['count']; ++sfield) {
	if(F_LAT == undefined && isenseData.fields['unitType'][sfield] == FIELD_TYPE.GEO_LAT) {
	    F_LAT = sfield;
	}
	else if(F_LON == undefined && isenseData.fields['unitType'][sfield] == FIELD_TYPE.GEO_LON) {
	    F_LON = sfield;
	}
    }

    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    //
    //
    //
    this.eh_selectRecipe = function() {
	var recipe = parseInt(findInputElement("f_" + name + "_form",
					       "i_"+name+"_recipe_select").value);
	selectedGenerator = recipe;
	this.redraw();
    }

    //
    //
    //
    this.eh_selectMeasuredField = function(fld) {
	measuredField = fld;
	if(measuredField == -1) {
	    options.useFilledMarkers = false;
	}
	else {
	    options.useFilledMarkers = true;
	}
	this.redraw();
    }
    
    
    //
    //
    //
    this.eh_toggleSession = function(ses) {
	sessions[ses]['visible'] = !sessions[ses]['visible'];
	this.redraw();
    }
    
    //
    //
    //
    this.eh_skipPointSelect = function(ses) {
	var sp = parseInt(findInputElement("f_" + name + "_form",
					       "i_"+name+"_skip_point_select").value);
	skipPoint = sp;
	this.redraw();
    }

    //
    //
    //
    this.eh_sortCollapsed = function() {
	sortByHighestDensity = !sortByHighestDensity;
	this.redraw();
    }

    //
    //
    //
    this.eh_movePointEnd = function(ses, val) {
	
	var endPoint = pointStart[ses] + (pointCount[ses] * (pointSkip+1)) - (pointSkip+1);
	var oflow = endPoint - isenseData.sessions[ses]['data'][0].length;
	if(oflow > 0) {
	    pointStart[ses] -= (oflow+1);
	}
	this.redraw();
    }
    
    //
    //
    //
    this.eh_cm_movePointEnd = function(ses, val) {
	
	var endPoint = cm_pointStart[ses] - (pointSkip + 1)
	             + (shownPointsCollapsed[ses] * (pointSkip + 1));
	var oflow = endPoint - maxPointsCollapsed[ses];
	if(oflow > 0) {
	    cm_pointStart[ses] -= (oflow+1);
	}
    }
    
    //
    //
    //
    this.eh_movePointTick = function(ses, val) {
	pointStart[ses] = val;
	
	$('#f_'+name+'_session_'+ses+'_interval_info').empty() 
	    .append('Will display points '+(pointStart[ses]+1)+' through '+(pointStart[ses] + (pointCount[ses] * (pointSkip+1)) - (pointSkip)));
    }

    //
    //
    //
    this.eh_cm_movePointTick = function(ses, val) {
	cm_pointStart[ses] = val;
	
	$('#f_'+name+'_session_'+ses+'_interval_info').empty() 
	    .append('Will display points '+(cm_pointStart[ses]+1)+' through '+(cm_pointStart[ses] + shownPointsCollapsed[ses]+1));
    }

    //
    //
    //
    this.eh_enterPointCount = function(ses) {
	var textin = findInputElement("f_" + name + "_form",
				      "i_"+name+"_session_"+ses+"_point_count");
	var modp   = parseInt(textin.value);
	
	if(!isNaN(modp)) {
	    if(modp < 0) {
		modp = 0;
	    }
	    
	    if(modp > sessions[ses]['data'][0].length) {
		pointCount[ses] = sessions[ses]['data'][0].length;
	    }
	    else {
		pointCount[ses] = modp;
	    }
	}
	else if(textin.value.toLowerCase() == "all") {
	    pointCount[ses] = sessions[ses]['data'][0].length;
	}
	else {
	    textin.value = pointCount[ses];
	}

	if(pointCount[ses] == sessions[ses]['data'][0].length) {
	    textin.value = "all";
	}

	//
	// Call eh_movePointEnd to ensure that the new size of the interval
	// does not go out of bounds for the maximum number of points.
	// It will call redraw.
	//
	this.eh_movePointEnd(ses, pointStart[ses]);
    }
    
    //
    //
    //
    this.eh_cm_enterPointCount = function(ses) {
	var textin = findInputElement("f_" + name + "_form",
				      "i_"+name+"_session_"+ses+"_point_count");
	var modp   = parseInt(textin.value);
	
	if(!isNaN(modp)) {
	    if(modp > maxPointsCollapsed[ses]) {
		shownPointsCollapsed[ses] = maxPointsCollapsed[ses];
	    }
	    else {
		shownPointsCollapsed[ses] = modp;
	    }
	}
	else if(textin.value.toLowerCase() == "all") {
	    shownPointsCollapsed[ses] = maxPointsCollapsed[ses];
	}
	else {
	    textin.value = shownPointsCollapsed[ses];
	}

	if(shownPointsCollapsed[ses] == maxPointsCollapsed[ses]) {
	    textin.value = "all";
	}

	//
	// Call eh_cm_movePointEnd to ensure that the new size of the interval
	// does not go out of bounds for the maximum number of points.
	// It will call redraw.
	//
	this.eh_cm_movePointEnd(ses, cm_pointStart[ses]);
    }

    /* --- */

    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
	pointStart[session_id]           =  0;
	pointCount[session_id]           =  1;
	pointChange[session_id]          =  1;
	pointMove[session_id]            =  1;
	maxPointsCollapsed[session_id]   =  0;
	shownPointsCollapsed[session_id] =  1;
	cm_pointStart[session_id]        =  0;
	cm_pointMove[session_id]         =  1;
	cm_pointChange[session_id]       =  1;

	//
	// Find the first point with good gps
	//
	var dp = 0;
	if(F_LAT == undefined || F_LON == undefined) {
	    pointStart[session_id] = 0;
	    return;
	}
	
	while(isNaN(parseFloat(isenseData.sessions[session_id]['data'][F_LAT][dp].value)) ||
	      isNaN(parseFloat(isenseData.sessions[session_id]['data'][F_LON][dp].value))) {
	    if(dp < isenseData.sessions[session_id]['data'][F_LAT].length - 1) {
		++dp;
	    }
	    else {
		break;
	    }
	}

	pointStart[session_id] = dp;
    }

    /*
     * Generate state and meta data.
     */
    this.generateMetaData = function() {
	//
	// Nothing special yet.
	//
    }

    this.redraw = function() {
	try {
	    tableGenerator[selectedGenerator]();
	    legendGenerator[selectedGenerator]();
	    visObject.draw(datatable,options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    this.clean = function() {
    }

    /*
     * Create HTML legend generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator.length = 2;
	
	legendGenerator[0] = function() {
	    
	    var table;
	    var sessionColor = 0;
	    
	    controlPane.empty();
	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Map'],
		  'tr', {}, [
		    'td', { colSpan : 3 }, [
		      'select', { id : 'i_'+name+'_recipe_select',
			          name : 'i_'+name+'_recipe_select',
			          style : 'width:100%' }, [
			'option', { value : 0, selected:'selected' }, 'Individual Points',
			'option', { value : 1 }, 'Average Point']]],
		  'tr', {}, [
		    'td', { colSpan : 3, id : 'f_'+name+'_skip_point_row'}, ' '],
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Measured Field'],
		  'tr', {}, [
		    'td', { style : "width:16px" }, [
		      'input', { type : 'radio', id : 'i_'+name+'_field_select_-1'}, []],
		    'td', { colSpan : 2 }, 'No Measurement']]]);
	    table = $('#f_'+name+'_table');
	    
	    $('#i_'+name+'_recipe_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectRecipe();
			  return false;
		      });
	    
	    //
	    // Input to scale number of points displayed (skip points)
	    //
	    $('#f_'+name+'_skip_point_row').append('Use ');
	    $('#f_'+name+'_skip_point_row').createAppend(
	        'select', { id : 'i_'+name+'_skip_point_select',
			    name : 'i_'+name+'_skip_point_select'}, [
		  'option', { id : 'i_'+name+'_skip_opt0', value : 0 }, '100%', 
		  'option', { id : 'i_'+name+'_skip_opt1', value : 1 }, '50%',
		  'option', { id : 'i_'+name+'_skip_opt3', value : 3 }, '25%',
		  'option', { id : 'i_'+name+'_skip_opt9', value : 9 }, '10%']);
	    $('#f_'+name+'_skip_point_row').append('of data points');
	    
	    $('#i_'+name+'_skip_opt'+pointSkip).attr('selected','selected');
	    $('#i_'+name+'_skip_point_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_skipPointSelect();
			  return false;
		      });

	    //
	    // Add fields to select which will be measured (including no measurement)
	    //
	    $('#i_'+name+'_field_select_-1')
	        .bind('click', { scope : thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectMeasuredField(-1);
			  return false;
		      });

	    for(var fld = 0; fld < isenseData.fields['count']; ++fld) {
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'radio', id : 'i_'+name+'_field_select_'+fld }, []],
		    'td', { colSpan : 2 }, isenseData.fields['title'][fld]+' ('+isenseData.fields['units'][fld]+')']);
		if(measuredField == fld) {
		    $('#i_'+name+'_field_select_'+fld).attr('checked','checked');
		}

		$('#i_'+name+'_field_select_'+fld)
		    .bind('click', { scope : thisModule, field:fld },
			  function(evt, obj) {
			      evt.data.scope.eh_selectMeasuredField(evt.data.field);
			      return false;
			  });
	    }

	    if(measuredField == -1) {
		$('#i_'+name+'_field_select_-1').attr('checked','checked');
	    }

	    //
	    // Add session controls
	    //
	    table.createAppend(
	      'tr', {}, [
	        'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);
	    
	    for(var ses in sessions) {
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'checkbox',
			         id : 'i_'+name+'_session_select_'+ses }, []],
		    'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		$('#i_'+name+'_session_select_'+ses)
		    .bind('click',{ scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      return false;
			  });
		
		if(!sessions[ses]['visible']) {
		    continue;
		}
		else {
		    $('#i_'+name+'_session_select_'+ses).attr('checked','checked');
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_color_bar', rowSpan : 4 }, [],
		    'td', { id : 'f_'+name+'_session_'+ses+'_info', colSpan : 2 }, []]);
		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_point_choice',
			    colSpan : 2 }, []]);
		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_interval_info',
			    colSpan : 2 }, 'Displaying from point '+(pointStart[ses]+1)+' to '+(pointStart[ses] + (pointCount[ses]* (pointSkip+1)) - (pointSkip))]);
		table.createAppend(
		  'tr', {}, [
		    'td', { colSpan : 2 }, [
		      'div', { id : 'i_'+name+'_session_'+ses+'_move_slider' }]]);

		//
		// Vertical bar filled with same color as associated map markers,
		// or the correct 'tag' number
		//
		if(measuredField == -1) {
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.css({'background-color' : colorsToUse[sessionColor++],
			      'border-width' : 'thin',
			      'border-color' : 'black'});
		}
		else {
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.css({'border-width' : 'thin',
			      'border-color' : 'black'});
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.html(String.fromCharCode(65 + sessionColor));
		    ++sessionColor;
		}

		//
		// Text input to determine how many points to display
		//
		$('#f_'+name+'_session_'+ses+'_point_choice').append('Display ');
		$('#f_'+name+'_session_'+ses+'_point_choice').createAppend(
		    'input', { type : 'text',
			       id : 'i_'+name+'_session_'+ses+'_point_count',
			       name : 'i_'+name+'_session_'+ses+'_point_count',
			       size : 4, maxlength : 4, value : pointCount[ses]}, []);
		$('#f_'+name+'_session_'+ses+'_point_choice').append(' points');
		
		$('#i_'+name+'_session_'+ses+'_point_count')
		    .bind('change',{ scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_enterPointCount(evt.data.session);
			      return false;
			  });

		//
		// Add total point information
		//
		$('#f_'+name+'_session_'+ses+'_info')
		    .append('Total point count: '+sessions[ses]['data'][0].length);

		//
		// Create a slider to control the interval of points displayed
		//
		var peeklen = sessions[ses]['data'][0].length;
		var max_clicks = Math.max(
		    peeklen - pointCount[ses] * ((pointSkip + 1))+(pointSkip + 1),
		    0);
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .slider({ min:0, 
			      max:(max_clicks-1),
			      value:pointStart[ses], 
			      steps:max_clicks});
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .bind('slidestop', { scope : thisModule, session : ses },
			  function(evt, obj) {
			      evt.data.scope.eh_movePointEnd(evt.data.session, obj.value);
			  });
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .bind('slide', { scope : thisModule, session : ses },
			  function(evt, obj) {
			      evt.data.scope.eh_movePointTick(evt.data.session, obj.value);
			  });
	    }
	}
	
	legendGenerator[1] = function() {
	    
	    var table;
	    var sessionColor = 0;
	    
	    controlPane.empty();
	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-align:center;' }, 'Map'],
		  'tr', {}, [
		    'td', { colSpan : 3 }, [
		      'select', { id : 'i_'+name+'_recipe_select', name : 'i_'+name+'_recipe_select', style : 'width:100%' }, [
			'option', { value : 0 }, 'Individual Points',
			'option', { value : 1, selected:'selected' }, 'Average Point']]],
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_skip_point_row', colSpan : 3 }, ''],
		  'tr', {}, [
		    'td', { style : "width:16px" }, [
		      'input', { type : 'checkbox', id : 'i_'+name+'_sort_collapsed' }, []],
		    'td', { colSpan : 2 }, 'Show most collapsed points first'],
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Measured Field'],
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'radio', id : 'i_'+name+'_field_select_-1'}, []],
		    'td', { colSpan : 2 }, 'No Measurement']]]);

	    table = $('#f_'+name+'_table');

	    $('#i_'+name+'_recipe_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectRecipe();
			  return false;
		      });

	    //
	    // Control for sorting by density of collapse points
	    //
	    $('#i_'+name+'_sort_collapsed')
	        .bind('click', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_sortCollapsed();
			  return false;
		      });
	    if(sortByHighestDensity) {
		$('#i_'+name+'_sort_collapsed').attr('checked','checked');
	    }

	    //
	    // Input to scale number of points displayed (skip points)
	    //
	    $('#f_'+name+'_skip_point_row').append('Use ');
	    $('#f_'+name+'_skip_point_row')
	        .createAppend('select', { id : 'i_'+name+'_skip_point_select',
			                  name : 'i_'+name+'_skip_point_select'}, [
				'option', { id : 'i_'+name+'_skip_opt0', value : 0 }, '100%', 
				'option', { id : 'i_'+name+'_skip_opt1', value : 1 }, '50%',
				'option', { id : 'i_'+name+'_skip_opt3', value : 3 }, '25%',
				'option', { id : 'i_'+name+'_skip_opt9', value : 9 }, '10%']);
	    $('#f_'+name+'_skip_point_row').append('of data points');
	    
	    $('#i_'+name+'_skip_opt'+pointSkip).attr('selected','selected');
	    $('#i_'+name+'_skip_point_select')
	        .bind('change', { scope:thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_skipPointSelect();
			  return false;
		      });
	    
	    //
	    // Add fields to select which will be measured (including no measurement)
	    //
	    $('#i_'+name+'_field_select_-1')
	        .bind('click', { scope : thisModule },
		      function(evt, obj) {
			  evt.data.scope.eh_selectMeasuredField(-1);
			  return false;
		      });

	    for(var fld = 0; fld < isenseData.fields['count']; ++fld) {
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'radio', id : 'i_'+name+'_field_select_'+fld }, []],
		    'td', { colSpan : 2 }, isenseData.fields['title'][fld]+' ('+isenseData.fields['units'][fld]+')']);
		if(measuredField == fld) {
		    $('#i_'+name+'_field_select_'+fld).attr('checked','checked');
		}

		$('#i_'+name+'_field_select_'+fld)
		    .bind('click', { scope : thisModule, field:fld },
			  function(evt, obj) {
			      evt.data.scope.eh_selectMeasuredField(evt.data.field);
			      return false;
			  });
	    }
	    if(measuredField == -1) {
		$('#i_'+name+'_field_select_-1').attr('checked','checked');
	    }

	    //
	    // Add session controls
	    //
	    table.createAppend(
	      'tr', {}, [
	        'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);
	    
	    for(var ses in sessions) {
		table.createAppend(
		  'tr', {}, [
		    'td', {}, [
		      'input', { type : 'checkbox', id : 'i_'+name+'_session_select_'+ses }, []],
		    'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		$('#i_'+name+'_session_select_'+ses)
		    .bind('click',{ scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      return false;
			  });
		
		if(!sessions[ses]['visible']) {
		    continue;
		}
		else {
		    $('#i_'+name+'_session_select_'+ses).attr('checked','checked');
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_color_bar', rowSpan : 4 }, [],
		    'td', { id : 'f_'+name+'_session_'+ses+'_info', colSpan : 2 }, []]);
		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_point_choice',
			    colSpan : 2 }, []]);
		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_interval_info',
			    colSpan : 2 }, 'Displaying from point '+(cm_pointStart[ses]+1)+' to '+(cm_pointStart[ses] + shownPointsCollapsed[ses] + 1)]);
		table.createAppend(
		  'tr', {}, [
		    'td', { colSpan : 2 }, [
		      'div', { id : 'i_'+name+'_session_'+ses+'_move_slider' }]]);
		
		//
		// Vertical bar filled with same color as associated map markers,
		// or the correct 'tag' number
		//
		if(measuredField == -1) {
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.css({'background-color' : colorsToUse[sessionColor++],
			      'border-width' : 'thin',
			      'border-color' : 'black'});
		}
		else {
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.css({'border-width' : 'thin',
			      'border-color' : 'black'});
		    $('#f_'+name+'_session_'+ses+'_color_bar')
			.html(String.fromCharCode(65 + sessionColor));
		    ++sessionColor;
		}
		
		//
		// Text input to determine how many points to display
		//
		$('#f_'+name+'_session_'+ses+'_point_choice').append('Display ');
		$('#f_'+name+'_session_'+ses+'_point_choice')
		    .createAppend('input', { type : 'text',
			 	             id : 'i_'+name+'_session_'+ses+'_point_count',
				             name : 'i_'+name+'_session_'+ses+'_point_count',
				             size : 4, maxlength : 4, value : shownPointsCollapsed[ses]}, []);
		$('#f_'+name+'_session_'+ses+'_point_choice').append(' points');
		
		$('#i_'+name+'_session_'+ses+'_point_count')
		    .bind('change',{ scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_cm_enterPointCount(evt.data.session);
			      return false;
			  });

		//
		// Add total point information
		//
		$('#f_'+name+'_session_'+ses+'_info')
		    .append('Total point count: '+maxPointsCollapsed[ses]);
		
		//
		// Create a slider to control the interval of points displayed
		//
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .slider({ min:0, 
			      max:(maxPointsCollapsed[ses] - shownPointsCollapsed[ses]), 
			      value:cm_pointStart[ses], 
			      steps:(maxPointsCollapsed[ses] - shownPointsCollapsed[ses])});
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .bind('slidestop', { scope : thisModule, session : ses },
			  function(evt, obj) {
			      evt.data.scope.eh_cm_movePointEnd(evt.data.session, obj.value);
			  });
		$('#i_'+name+'_session_'+ses+'_move_slider')
		    .bind('slide', { scope : thisModule, session : ses },
			  function(evt, obj) {
			      evt.data.scope.eh_cm_movePointTick(evt.data.session, obj.value);
			  });
	    }
	};
    }


    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {

	tableGenerator.length = 2;
	tableGenerator[0] = function(scope) {
	    
	    //
	    // Refresh table.
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Generate Columns.
	    //
	    if(custom) {
		datatable.addColumn('string','Id');
	    }
	    datatable.addColumn('number','Lat');
	    datatable.addColumn('number','Lon');
	    datatable.addColumn('string','Data');
	    if(custom) {
		datatable.addColumn('number','Measurement');
	    }
	    
	    //
	    // Add points to map.
	    //
	    
	    var totadded = 0;
	    for(var ses in sessions) {
		
		//
		// Check if this session is visible, skip it if not.
		//
		if(!sessions[ses].visible) {
		    continue;
		}
	       
		var max_meas = undefined;
		var min_meas = undefined;
		//
		// This is a little hackish, but go through all the points just to
		// find the max and min measured value, if available.
		//
		if(custom && measuredField/*[ses]*/ > -1) {
		    for(var dp = 0; dp < isenseData.sessions[ses]['data'][measuredField/*[ses]*/].length; ++dp) {
			var val = parseFloat(isenseData.sessions[ses]['data'][measuredField/*[ses]*/][dp].value);
			if(max_meas == undefined || val > max_meas) {
			    max_meas = val;
			}
			if(min_meas == undefined || val < min_meas) {
			    min_meas = val;
			}
		    }
		}

		var pos = pointStart[ses];
		var addednow = 0;
		var limit = pointCount[ses] * (1 + pointSkip);
		datatable.addRows(pointCount[ses]);
		for(var i = 0; i < limit; i += 1 + pointSkip) {
		    if(custom) {
			datatable.setValue(addednow+totadded,0,ses);
		    }
		    var dataStr = "#" + sessions[ses]['id'] + ", Datapoint #" + (i+pos+1) + "<br/>";
		    var measureStr = "";
		    var colsel;
		    for(var j = 0; j < isenseData.fields['count']; j++) {
			if(isenseData.fields['unitType'][j] == FIELD_TYPE.GEO_LAT) {
			    // Latitude
			    if(custom) {
				colsel = 1;
			    } else {
				colsel = 0;
			    }
			    var latval = isenseData.sessions[ses]['data'][j][pos+i].value;
			    datatable.setValue(addednow+totadded,colsel,
					       parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
			}
			else if(isenseData.fields['unitType'][j] == FIELD_TYPE.GEO_LON) {
			    // Longitude
			    if(custom) {
				colsel = 2;
			    } else { 
				colsel = 1;
			    }
			    datatable.setValue(addednow+totadded,colsel,
					       parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
			}
			else if(isenseData.fields['sensor'][j] == FIELD_TYPE.TIME) {
			    // Time
			    var curTime = parseInt(isenseData.sessions[ses]['data'][j][pos+i].value);
			    var tzOffset = (new Date().getTimezoneOffset()) - (new Date(curTime).getTimezoneOffset());
			    tzOffset *= 60000;
			    var convertedTime = curTime + tzOffset;
			    dataStr += "Time: " + new Date(convertedTime) + "<br/>";
			}
			else {
			    // Other Data
			    if(custom) {
				if(j == measuredField/*[ses]*/) {
				    measureStr = "Measured Field:<br/>" + isenseData.fields["title"][j] + ": "
					+ isenseData.sessions[ses]['data'][j][pos+i].value + " " 
					+ isenseData.fields['units'][j] + "<br/>";
				    datatable.setValue(addednow+totadded,4,
						       parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
				}
				else {
				    dataStr += isenseData.fields['title'][j] + ": " 
					+ isenseData.sessions[ses]['data'][j][pos+i].value + " "
					+ isenseData.fields['units'][j] + "<br/>";
				}
			    }
			    else {
				dataStr += isenseData.fields["title"][j] + ": " 
				    + isenseData.sessions[ses]['data'][j][pos+i].value + " " 
				    + isenseData.fields['units'][j] + "<br/>";
			    }
			}
		    }
		    
		    if(custom) {
			colsel = 3;
		    } else {
			colsel = 2;
		    }
		    datatable.setValue(addednow+totadded,colsel,dataStr + measureStr);
		    ++addednow;
		}

		//
		// Add max and min values found for the measured field using dummy points,
		// so that the markers that are displayed are relative to the entire data set,
		// not just the points displayed.
		//
		if(custom && measuredField/*[ses]*/ > -1) {
		    if(min_meas != undefined) {
			datatable.addRow();
			datatable.setValue(addednow+totadded,0,ses + "bnd");
			datatable.setValue(addednow+totadded,1,0);
			datatable.setValue(addednow+totadded,2,0);
			datatable.setValue(addednow+totadded,3,"");
			datatable.setValue(addednow+totadded,4,min_meas);
			++addednow;
		    }
		    if(max_meas != undefined) {
			datatable.addRow();
			datatable.setValue(addednow+totadded,0,ses + "bnd");
			datatable.setValue(addednow+totadded,1,0);
			datatable.setValue(addednow+totadded,2,0);
			datatable.setValue(addednow+totadded,3,"");
			datatable.setValue(addednow+totadded,4,max_meas);
			++addednow
		    }
		}
		totadded += addednow; //pointCount[ses];
	    }		
	};

	//
	// From a collection of data, find the most commonly occuring area and collapse to
	// a single point. Average data values.
	//
	tableGenerator[1] = function(scope) {
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    var latField;
	    var lonField;
	    var timeField;
	    var numberFields = new Array();
	    var textFields   = new Array();
	    var numAdded     = 0;
	    var numToAdd;
	    var numToSkip;

	    //
	    // Add required columns to the datatable
	    //
	    if(custom) {
		datatable.addColumn('string','Id');
	    }
	    datatable.addColumn('number','Lat');
	    datatable.addColumn('number','Lon');
	    datatable.addColumn('string','Data');
	    if(custom) {
		datatable.addColumn('number','Measurement');
	    }

	    //
	    // Get the indexes of important data fields.
	    //
	    for(var fld = 0; fld < isenseData.fields['count']; ++fld) {
		
		if(isenseData.fields['unitType'][fld] == FIELD_TYPE.GEO_LAT && !latField) {
		    latField = fld;
		}
		else if(isenseData.fields['unitType'][fld] == FIELD_TYPE.GEO_LON && !lonField) {
		    lonField = fld;
		}
		else if(isenseData.fields['sensor'][fld] == FIELD_TYPE.TIME && !timeField) {
		    timeField = fld;
		}
		/* NO LONGER HAS TEXT FIELDS
		else if(isenseData.fields['sensor'][fld] == FIELD_TYPE.TEXT) {
		    textFields.push(fld);
		}
		*/
		else {
		    numberFields.push(fld);
		}
	    }
	    
	    //
	    // Go through each session, searching for lat-lon pairs that are
	    // very close, and accumulate them. The most common area will be
	    // represented as a single marker on the Google map.
	    //
	    for(var ses in sessions) {
		
		if(!sessions[ses].visible) {
		    continue;
		}
		//var com_count    = 0;
		//var com_lat      = 1001.0;
		//var com_lon      = 1001.0;
		//var com_datAccum = new Array();
		//var com_startTime;
		//var com_endTime;
		var com_array    = new Array();
		var com_text     = "";
		var com_mtext    = "";
		var cur_count    = 0;
		var cur_lat      = 1001.0; //parseFloat(isenseData.sessions[ses]['data'][latField][0].value);
		var cur_lon      = 1001.0; //parseFloat(isenseData.sessions[ses]['data'][lonField][0].value);
		var cur_datAccum = new Array();
		var cur_startTime;
		var cur_endTime;
		var measField    = measuredField/*[ses]*/;
		var min_measVal;
		var max_measVal;
		var last_pos     = -1;
		
		//
		// Further initialization
		//
		if(timeField) {
		    cur_startTime = parseInt(isenseData.sessions[ses]['data'][timeField][0].value);
		}
		
		for(var dp = 0; dp < isenseData.sessions[ses]['data'][latField].length; ++dp) {
		    var n_lat  = parseFloat(isenseData.sessions[ses]['data'][latField][dp].value);
		    var n_lon  = parseFloat(isenseData.sessions[ses]['data'][lonField][dp].value);
		    if(measField >= 0) {
			var n_mfld = parseFloat(isenseData.sessions[ses]['data'][measField][dp].value);
		    }

		    //
		    // If a measured field exists, find the minimum and maximum for all points,
		    // to allow the marker graphics to have more accurate fills when small
		    // numbers of markers are added.
		    //
		    if(measField >= 0 && !isNaN(n_mfld) && !min_measVal || n_mfld < min_measVal) {
			min_measVal = n_mfld;
		    }
		    if(measField >= 0 && !isNaN(n_mfld) && !max_measVal || n_mfld > max_measVal) {
			max_measVal = n_mfld;
		    }
		    
		    if(isNaN(n_lat) || isNaN(n_lon)) {
			continue;
		    }
		    
		    //
		    // Check to see if the next data point can be collapsed into the 
		    // currently accumulating area. The current values of 0.00005 
		    // would collapse and area about the size of the circular sitting area
		    // in front of Olsen, with the origin at the center of the garden.
		    //
		    if(n_lat <= cur_lat + 0.00005 && n_lat >= cur_lat - 0.00005 &&
		       n_lon <= cur_lon + 0.00005 && n_lon >= cur_lon - 0.00005) {
			
			++cur_count;
			
			for(var c = 0; c < numberFields.length; ++c) {
			    var cfld = numberFields[c];
			    cur_datAccum[cfld].push(parseFloat(isenseData.sessions[ses]['data'][cfld][dp].value));
			}
		    }
		    //
		    // Otherwise, add the currently accumulated point into the array of collapsed points
		    // and start on the next accumulated point.
		    //
		    else {
			//
			// Don't accumulate the first uninitialized area.
			//
			if(cur_count > 0) {
			    //cur_endTime = parseInt(isenseData.sessions[ses]['data'][timeField][dp].value);
			    if(last_pos < 0 || last_pos >= com_array.length) {
				com_array.push({count:cur_count,
						lat:cur_lat,
						lon:cur_lon,
						//start_time:cur_startTime,
						//end_time:cur_endTime,
						data:cur_datAccum});
			    }
			    else {
				com_array.splice(last_pos,0,{count:cur_count,
							     lat:cur_lat,
							     lon:cur_lon,
							     //start_time:cur_startTime,
							     //end_time:cur_endTime,
							     data:cur_datAccum});
			    }
			}
			
			//
			// See if this can fit into any of the other accumulated points
			//
			var ac;
			var lookback = false;
			for(ac = 0; ac < com_array.length; ++ac) {
			    if(n_lat <= com_array[ac].lat + 0.00005 && n_lat >= com_array[ac].lon - 0.00005 &&
			       n_lon <= com_array[ac].lon + 0.00005 && n_lon >= com_array[ac].lon - 0.00005) {
				lookback = true;
				break;
			    }
			}
			
			//
			// If the new point fits in a previous accumulation, remove it and set up the cur_
			// variables with it's information, as well as it's previous position in the array.
			// Otherwise, create a new area.
			//
			if(lookback) {
			    cur_count    = com_array[ac].count + 1;
			    cur_lat      = com_array[ac].lat;
			    cur_lon      = com_array[ac].lon;
			    //cur_startTime = com_array[ac].start_time;
			    cur_datAccum = com_array[ac].data;
			    
			    for(var c = 0; c < numberFields.length; ++c) {
				var cfld = numberFields[c];
				cur_datAccum[cfld].push(parseFloat(isenseData.sessions[ses]['data'][cfld][dp].value));
			    }
			    
			    last_pos     = ac;
			    com_array.splice(ac,1);
			}
			else { 
			    cur_count     = 1;
			    cur_lat       = n_lat;
			    cur_lon       = n_lon;
			    //cur_startTime = parseInt(isenseData.sessions[ses]['data'][timeField][dp].value);
			    cur_datAccum  = new Array();
			    
			    for(var c = 0; c < numberFields.length; ++c) {
				var cfld = numberFields[c];
				cur_datAccum[cfld] = new Array();
				cur_datAccum[cfld].push(parseFloat(isenseData.sessions[ses]['data'][cfld][dp].value));
			    }
			}
		    }
		}
		//
		// Make sure to collect the last collapsed area
		//
		if(cur_count > 0) {
		    //cur_endTime = parseInt(isenseData.sessions[ses]['data'][timeField][dp-1].value);
		    com_array.push({count:cur_count,
				    lat:cur_lat,
				    lon:cur_lon,
				    //start_time:cur_startTime,
				    //end_time:cur_endTime,
				    data:cur_datAccum});
		}

		//
		// Sort the array by decending count.
		//
		if(sortByHighestDensity/*[ses]*/) {
		    com_array.sort(markerSort);
		}
		
		//
		// Create the number of collapsed markers specified by the user.
		// 0 means to add all collapsed areas.
		//
		numToAdd = shownPointsCollapsed[ses];
		maxPointsCollapsed[ses] = com_array.length;

		numToSkip = cm_pointStart[ses];
		while(numToAdd > 0 && com_array.length > 0) {
		    var elem = com_array.shift();
		    if(numToSkip > 0) {
			--numToSkip;
			continue;
		    }
		    if(elem.count == 0) {
			continue;
		    }
		    datatable.addRow();
		    datatable.setValue(numAdded,0,ses);
		    datatable.setValue(numAdded,1,elem.lat);
		    datatable.setValue(numAdded,2,elem.lon);
		    com_text = "Session: " + ses + " (Average Values)<br/>";
		    for(var c = 0; c < numberFields.length; ++c) {
			var cfld = numberFields[c];
			var ccnt = 0;
			var cacc = 0;

			//
			// Find the average for each field.
			//
			while(elem.data[cfld].length > 0) {
			    if(isNaN(elem.data[cfld][0])) {
				//
				// Ignore data that could not be parsed correctly.
				//
				elem.data[cfld].shift();
			    }
			    else if(!isFinite(cacc + elem.data[cfld][0])) {
				if(ccnt < 1) {
				    //
				    // A single value is astronomically high, ignore it.
				    //
				    elem.data[cfld].shift();
				}
				else {
				    //
				    // Since adding one more would overflow, take the current average
				    // and push it back into the array of data.
				    //
				    cacc = cacc / ccnt;
				    elem.data[cfld].push(cacc);
				    cacc = 0;
				    ccnt = 0;
				}
			    }
			    else {
				cacc += elem.data[cfld][0];
				++ccnt;
				elem.data[cfld].shift();
			    }
			}
			if(cacc > 1) {
			    cacc = cacc / ccnt;
			}
			
			if(cfld == measuredField/*[ses]*/) {
			    datatable.setValue(numAdded,4,cacc);
			    com_mtext = "Measured Field:<br/>" 
				      + isenseData.fields["title"][cfld] + ": " + cacc + " " 
				      + isenseData.fields['units'][cfld] + "<br/>";
			}
			else {
			    com_text += isenseData.fields['title'][cfld] + ": " + cacc 
				     + " " + isenseData.fields['units'][cfld] + "<br/>";
			}
		    }
		    com_text += "<br/>" + com_mtext + "<br/>Points Collapsed: " + elem.count;
		    datatable.setValue(numAdded,3,com_text);
		    ++numAdded;
		    --numToAdd;
		    if(pointSkip > 0) {
			numToSkip = pointSkip;
		    }
		}

		//
		// If there is a measured field, add in the max and min boundary values,
		// to prevent situations where a single marker would have a maximum fill
		// even if it does not represent the maximum value.
		//
		if(measField >= 0) {
		    if(min_measVal) {
			datatable.addRow();
			datatable.setValue(numAdded,0,ses + "bnd");
			datatable.setValue(numAdded,1,0);
			datatable.setValue(numAdded,2,0);
			datatable.setValue(numAdded,3,"");
			datatable.setValue(numAdded,4,min_measVal);
			++numAdded;
		    }
		    if(max_measVal) {
			datatable.addRow();
			datatable.setValue(numAdded,0,ses + "bnd");
			datatable.setValue(numAdded,1,0);
			datatable.setValue(numAdded,2,0);
			datatable.setValue(numAdded,3,"");
			datatable.setValue(numAdded,4,max_measVal);
			++numAdded;
		    }
		}
	    }
	};
    }
    

    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */    
    this.init = function(panelDiv) {
	
	//
	// Create control and vis DIVs
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});

	controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create Google visualization object
	//
	visObject = new isenseMap(viewPane);
	
	//
	// Check Google options to ensure good defaults exist
	//
	options = { enableScrollWheel : false,
		    showTip : true,
		    showLines : false };

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}

/*
 * TAG SESSIONMAP
 */
function SessionMapModule(a_parent, a_name, a_data, a_state) {
    
    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;

    //
    // Meta Data
    //

    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];

    /*
     * Sorting function for collapsing datatable generator
     */
    var markerSort = function(a,b) {
	return b.count - a.count;
    }

    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    //
    //
    //
    this.eh_toggleSession = function(ses) {
	sessions[ses]['visible'] = !sessions[ses]['visible'];
	this.redraw();
    }
    
    /* --- */

    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
	//
	// Nothing special yet.
	//
    }

    /*
     * Generate state and meta data.
     */
    this.generateMetaData = function() {
	//
	// Nothing special yet.
	//
    }

    this.redraw = function() {
	try {
	    tableGenerator();
	    legendGenerator();
	    visObject.draw(datatable,options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    this.clean = function() {
    }

    /*
     * Create HTML legend generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator = function() {
	    
	    var table;
	    var sessionColor = 0;
	    
	    controlPane.empty();
	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {} [
		    'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Session Map']]]);

	    table = $('#f_'+name+'_table');
	    
	    //
	    // Add session controls
	    //
	    table.createAppend(
	      'tr', {}, [
	        'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);
	    
		  for(var ses in sessions) {
		table.createAppend(
		  'tr', {}, [
		    'td', { style : 'background-color:'+colorsToUse[sessionColor++], rowSpan : 2 }, [
		      'input', { type : 'checkbox',
			         id : 'i_'+name+'_session_select_'+ses }, []],
		    'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		$('#i_'+name+'_session_select_'+ses)
		    .bind('click',{ scope:thisModule, session:ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      return false;
			  });
		
		if(!sessions[ses]['visible']) {
		    continue;
		}
		else {
		    $('#i_'+name+'_session_select_'+ses).attr('checked','checked');
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_info', colSpan : 2 }, 'Rating:']);

	    }
	};
    }
    
    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {

	tableGenerator = function(scope) {
	    
	    //
	    // Refresh table.
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Generate Columns.
	    //
	    
	    datatable.addColumn('string','Id');
	    datatable.addColumn('string','Address');
	    datatable.addColumn('string','Data');
	    //datatable.addColumn('number','Measurement');
	    
	    //
	    // Add points to map.
	    //
	    var totadded = 0;
	    for(var ses in sessions) {
	      if (!sessions[ses]['visible']) {
	        continue;
	      }
		
		datatable.addRow();
		datatable.setValue(totadded,0,ses);
		datatable.setValue(totadded,1,sessions[ses]['address']);

		var infostr = "Session " + sessions[ses]['id'] + "<br/>"
		    + sessions[ses]['title'] + "<br/>"
		    + sessions[ses]['address'] + "<br/>"
		    + sessions[ses]['date'] + "<br/>"
		    + sessions[ses]['data'][0].length + " rows of data.";

		datatable.setValue(totadded,2,infostr);
		totadded = totadded + 1;

		//
		// Perhaps put session rating into isenseData object and display it as the bar scale
		// TODO
	    }
	};
    }
    

    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */    
    this.init = function(panelDiv) {
	
	//
	// Create control and vis DIVs
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});

	controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create Google visualization object
	//
	visObject = new isenseMap(viewPane);
	
	//
	// Check Google options to ensure good defaults exist
	//
	options = { enableScrollWheel : false,
		    showTip : true,
		    showLines : false };

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}

/*
 * TAG MOTIONCHART
 */
function MotionChartModule(a_parent, a_name, a_data, a_state) {

    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;
    var isDrawn = false;
    var savedHTML;

    //
    // Meta Data
    //

    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];

    //
    // Meta Data
    //
    var timeFieldIndex    = -1;
    var textFieldIndex    = -1;

    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
	//
	// Nothing special yet.
	//
    }

    var getTimeData = function(session_id, field_id, datapoint) {

	var datastr = isenseData.sessions[session_id]['data'][field_id][datapoint].value;
	var unixts = parseInt(datastr);
	
	if(isNaN(unixts)) {
	    unixts = Date.parse(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	    return unixts;
	}
	
	var chkSlashes = datastr.indexOf("/",0);
	var chkDashes = datastr.indexOf("-",0);
	var chkColons = datastr.indexOf(":",0);
	
	if(chkSlashes >= 0 || chkDashes >= 0 || chkColons >= 0) {
	    unixts = Date.parse(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	    return unixts;
	}

	return unixts * 1000;
    }

    /*
     * Generate state and meta data.
     */
    this.generateMetaData = function() {
	var preselected = 0;
	var fieldType;
	for(var i = 0; i < isenseData.fields['count']; ++i) {
	    fieldType = isenseData.fields['sensor'][i];
	    //
	    // Use first string as possible title
	    if(fieldType == FIELD_TYPE.TIME && timeFieldIndex == -1) {
		timeFieldIndex = i;
	    }
	    else if(fieldType == FIELD_TYPE.STRING && textFieldIndex == -1) {
		textFieldIndex = i;
	    }
	}
    }

    this.redraw = function() {
	try {
	    isDrawn = true;
	    tableGenerator();
	    //legendGenerator();
	    visObject.draw(datatable,options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    /*
     * Need to refresh the div to the original state of MotionChart
     */
    this.clean = function() {
	if(isDrawn) {
	    $('#'+name+'_viewpane').empty();
	    visObject = new google.visualization.MotionChart(viewPane);
	}
    }

    /*
     * Create HTML legend generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator = function() {
	    
	};
    }
    
    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {

	tableGenerator = function(scope) {

	    //
	    // Refresh Table
	    //
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    //
	    // Create Columns. Only four data columns can be created (plus
	    // title and time columns for six total), so use selected
	    // fields from meta data.
	    //
	    // Assume that the experiment has already been checked to make
	    // sure that there are appropriate fields for this viz.
	    //
	    datatable.addColumn('string','Session');
	    datatable.addColumn('datetime','Time');
	    var colCount = 2;
	    for(var saxis = 0; saxis < isenseData.fields['count']; ++saxis) {
		if(saxis != timeFieldIndex) {
		    var title = "" + isenseData.fields['title'][saxis] + " (" + isenseData.fields['units'][saxis] + ")";
		    if(isenseData.fields['sensor'][saxis] == FIELD_TYPE.STRING) {
			datatable.addColumn('string',title);
		    }
		    else {
			datatable.addColumn('number',title);
		    }
		    colCount++;
		}
	    }
	    
	    //
	    // Add data for each session. At this point, don't worry about
	    // time alignment.
	    //
	    var rowNum = 0;
	    for(var ses in sessions) {
		if(!sessions[ses].visible) {
		    continue;
		}
		
		datatable.addRows(isenseData.sessions[ses]['data'][0].length);
		for(var dp = 0; dp < isenseData.sessions[ses]['data'][0].length; ++dp) {
		    var fldNum = 2;
		    datatable.setValue(rowNum,0,ses + " " + isenseData.sessions[ses]['title']);
		    if(timeFieldIndex != -1) {
			
			datatable.setValue(rowNum,1,new Date(getTimeData(ses,timeFieldIndex,dp)));
		    } 
		    else {
			datatable.setValue(rowNum,1,new Date(parseInt(isenseData.sessions[ses]['date'] + (dp * 86400000))));
		    }
		    
		    for(var fld = 0; fld < isenseData.fields['count']; fld++) {
			if(fldNum < datatable.getNumberOfColumns() && fld != timeFieldIndex) {
			    if(isenseData.fields['sensor'][fld] == FIELD_TYPE.STRING) {
				datatable.setValue(rowNum,fldNum,isenseData.sessions[ses]['data'][fld][dp].value);
			    }
			    else {
				datatable.setValue(rowNum,fldNum,parseFloat(isenseData.sessions[ses]['data'][fld][dp].value));
			    }
			    ++fldNum;
			}
		    }
		    ++rowNum;
		}
	    }
	};
    }
    

    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */    
    this.init = function(panelDiv) {
	
	//
	// Create control and vis DIVs
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	//$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'width':'1024px','height':'600px'});
	//$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	//$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});

	//controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create Google visualization object
	//
	visObject = new google.visualization.MotionChart(viewPane);
	savedHTML = $('#'+name+'_viewpane').html();

	//
	// Check Google options to ensure good defaults exist
	//
	options = {width:1024,height:600};

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}


/*
 * TAG COLUMNCHART
 */
function ColumnChartModule(a_parent, a_name, a_data, a_state) {
    
    var name            = a_name;
    var parent          = a_parent;
    var stateObject     = a_state;
    var isenseData      = a_data;
    var sessions        = isenseData.sessions;
    var options;
    var tableGenerator  = new Array();
    var legendGenerator = new Array();;
    var datatable;
    var visObject;
    var viewPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;

    //
    // Meta Data
    //
    var fieldVisible         = new Array();
    var showField            = new Array();
    var selectedGenerator    = 0; 
    var analysisTypes;
    var selectedAnalysisType = new Array();
        
    //
    // These are the first twenty colors used by Google Visualization ScatterPlot.
    // They seem to be the same across other visualizations, although others often
    // have less range. It may be good to have a discussion on good color choices.
    //
    var colorsToUse = ["#4684ee","#dc3912","#ff9900","#008000","#666666"
		       ,"#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477"
		       ,"#aaaa11","#66aa00","#888888","#994499","#dd5511"
		       ,"#22aa99","#999999","#705770","#109618","#a32929"];
    
    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

    this.eh_toggleSession = function(ses) {
	sessions[ses]['visible'] = !sessions[ses]['visible'];
	this.redraw();
    }

    this.eh_toggleField = function(fld) {
	fieldVisible[fld] = !fieldVisible[fld];
	this.redraw();
    }

    this.eh_selectAnalysisType = function(field) {
	var anTypeIndex = parseInt(findInputElement("f_"+name+"_form","i_"+name+"_anal_select_"+field).value);
	selectedAnalysisType[field] = analysisTypes[anTypeIndex];
	this.redraw();
    }
    /* --- */

    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {

    }
    
    /*
     * Generate state and meta data.
     * TODO: change for new fieldType values
     */
    this.generateMetaData = function() {
	for(var i = 0; i < isenseData.fields['count']; ++i) {
	    fieldVisible[i] = true;
	}

	analysisTypes = ['mean','max','min','total'];//,'point'];
	for(var i = 0; i < isenseData.fields['count']; ++i) {
	    selectedAnalysisType[i] = 'mean';
	}
    }
    
    /*
     * Redraw controls and visualization with current state.
     */
    this.redraw = function() {
	try {
	    tableGenerator[selectedGenerator]();
	    legendGenerator[selectedGenerator]();
	    visObject.draw(datatable,options);
	    return true;
	}
	catch(e) {
	    alert(e);
	    return false;
	}
    }

    this.clean = function() {
    }
    
    /*
     * Create control pane generators.
     */
    this.createLegendGenerators = function() {
	
	legendGenerator.length = 1;
	legendGenerator[0] = function() {

	    var table;
	    var fieldColor;

	    controlPane.empty();
	    fieldColor = 0;

	    controlPane.createAppend(
	      'form', { id : 'f_'+name+'_form' }, [
		'table', { id : 'f_'+name+'_table', style : 'width:100%' }, [
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Column Chart'],
		  'tr', {}, [
		    'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Fields']]]);

	    table = $('#f_'+name+'_table');
	    
	    for(var i = 0; i < isenseData.fields['count']; ++i) {
		if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL ||
		   isenseData.fields['sensor'][i] == FIELD_TYPE.TIME ||
		   isenseData.fields['sensor'][i] == FIELD_TYPE.STRING) {
		    continue;
		}
		
		var fchk = "";
		if(fieldVisible[i]) {
		    fchk = "checked";
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { rowSpan : 2, style : 'width:16px' }, [
		      'input', { type : "checkbox", id : 'i_'+name+'_field_toggle_'+i, checked : fchk }, ''],
		    'td', { colSpan : 2 }, isenseData.fields['title'][i]+' ('+isenseData.fields['units'][i]+')']);
		
		$('#i_'+name+'_field_toggle_'+i)
			.bind('click', { scope : thisModule, field : i },
			      function(evt, obj) {
				  evt.data.scope.eh_toggleField(evt.data.field);
				  return false;
			      });
		if(fieldVisible[i]) {
		    table.createAppend(
		      'tr', {}, [
			'td', {}, 'Compute:',
		        'td', { colSpan : 1 }, [
			  'select', { id : 'i_'+name+'_anal_select_'+i, name : 'i_'+name+'_anal_select_'+i }, []]]);
		
		    for(var j = 0; j < analysisTypes.length; ++j) {
			var sel = "";
			if(selectedAnalysisType[i] == analysisTypes[j]) {
			    sel = 'selected';
			}

			$('#i_'+name+'_anal_select_'+i).createAppend(
			  'option', { value : j, selected : sel }, analysisTypes[j]);
		    }
		    
		    $('#i_'+name+'_anal_select_'+i)
			.bind('change', { scope : thisModule, field : i },
			      function(evt, obj) {
				  evt.data.scope.eh_selectAnalysisType(evt.data.field);
				  return false;
			      });
		}
	    }
	    
	    var sessionColor = 0;
	    table.createAppend(
	      'tr', {}, [
	        'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);
	    
	    for(var ses in sessions) {
		var chk = "";
		if (sessions[ses]['visible']) {
		    chk = 'checked';
		}

		table.createAppend(
		  'tr', {}, [
		    'td', { id : 'f_'+name+'_session_'+ses+'_color' }, [
		      'input', { id : 'i_'+name+'_session_'+ses+'_select' ,type : 'checkbox', checked : chk }, []],
		    'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		$('#i_'+name+'_session_'+ses+'_select')
		    .bind('click', { scope : thisModule, session : ses },
			  function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      return false;
			  });

		if(sessions[ses]['visible']) {
		    $('#f_'+name+'_session_'+ses+'_color')
			.css({'background-color' : colorsToUse[fieldColor++]});
		}
	    }
	};
    }

    /*
     * Create table generators.
     */
    this.createTableGenerators = function() {
	
		tableGenerator.length = 1;
	tableGenerator[0] = function() {
	    
	    delete datatable;
	    datatable = new google.visualization.DataTable();
	    
	    datatable.addColumn('string','Field');
	    for(var i in sessions) {
		if(sessions[i].visible) {
		    datatable.addColumn('number','#' + sessions[i]['id'] + ": " + isenseData.sessions[i]['title']);
		}
	    }
	    
	    var row = 0;
	    var col;
	    for(var j = 0; j < isenseData.fields['count']; ++j) {
		var fldType = isenseData.fields['sensor'][j];
		showField[j] = false;

		if(fldType == FIELD_TYPE.TIME || fldType == FIELD_TYPE.GEOSPACIAL || fldType == FIELD_TYPE.STRING) {
		    continue;
		}
		
		for(var i in sessions) {
		    if(sessions[i].visible && fieldVisible[j]) {
			showField[j] = true;
			break;
		    }
		}

		if(!showField[j]) {
		    continue;
		}
		
		col = 0;
		datatable.addRow();
		datatable.setValue(row, 0, isenseData.fields['title'][j] + " (" 
				         + isenseData.fields['units'][j] + ")");
		for(var i in sessions) {
		    if(!sessions[i].visible) {
			continue;
		    }
		    
		    ++col;
		    
		    if(!fieldVisible[j]) {
			continue;
		    }

		    if(selectedAnalysisType[j] == "mean") {
			var value = 0;
			for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
			    value += parseFloat(isenseData.sessions[i]['data'][j][k].value);
			}
			value = value / isenseData.sessions[i]['data'][j].length;
			datatable.setValue(row, col, value)
		    }
		    else if(selectedAnalysisType[j] == "max") {
			var value = "undefined";
			for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
			    if(value == "undefined" || parseFloat(isenseData.sessions[i]['data'][j][k].value) > value) {
				value = parseFloat(isenseData.sessions[i]['data'][j][k].value);
			    }
			}
			datatable.setValue(row, col, value);
		    }
		    else if(selectedAnalysisType[j] == "min") {
			var value = "undefined";
			for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
			    if(value == "undefined" || parseFloat(isenseData.sessions[i]['data'][j][k].value) < value) {
				value = parseFloat(isenseData.sessions[i]['data'][j][k].value);
			    }
			}
			datatable.setValue(row, col, value);
		    }
		    else if(selectedAnalysisType[j] == "total") {
			var value = 0;
			for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
			    value += parseFloat(isenseData.sessions[i]['data'][j][k].value);
			}
			datatable.setValue(row, col, value);
		    }
		    else if(selectedAnalysisType[j] == "point") {
			
		    }
		}
		++row;
	    }
	    
	    if(datatable.getNumberOfColumns() < 2) {
		datatable.addColumn('number','No Data');
		return;
	    }
	};
    }
	
    /*
     * Initialize this visualization object. Called from VizWrapper.
     * Pass the DIV visualization will be created in since some visualizations
     * may need to do special initializations with it.
     */
    this.init = function(panelDiv) {
	//
	// Create DIVs for controls and visualization
	//
	$('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	$('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	
	$('#'+name+'_viewpane').css({'float':'left','width':'740px','height':'600px','margin-right':'4px'});
	$('#'+name+'_cntlpane').css({'float':'right','width':'280px','height':'600px'});

	controlPane = $('#'+name+'_cntlpane');
	viewPane = document.getElementById(name+'_viewpane');

	//
	// Create Google visualization object
	//
	visObject = new google.visualization.ColumnChart(viewPane);

	//
	// Check Google options to ensure good defaults exist
	//
	options = { legend : "top",
		    titleY : "Value",
		    colors : colorsToUse,
		    titleX : "Fields" };

	this.generateMetaData();
	this.createTableGenerators();
	this.createLegendGenerators();
	for(var ses in sessions) {
	    this.registerSession(ses);
	}
    }

    thisModule = this;
}


var vPanels;

function startUp() {
    $("#container").css('width','1075px');//1024
    $("#header").css('position','relative');
    $("#header").css('left','87px');//62
    $("#pagetitle").css('width','1075px');
    $("#vis").css({'width':'1075px','height':'800px','clear':'both'});
   
    vPanels = new VisPanel('vis',DATA,{});
}

google.load("visualization","1",
	    {packages:['annotatedtimeline','table','scatterchart','motionchart','columnchart']});
google.setOnLoadCallback(function() { $(document).ready(startUp); });

function testStartUp() {

    var mainDiv = $("#vis");
    
    mainDiv.append("Here it is! Let's look at the data...<br/>");

    var push = (window.innerWidth - 1024) / 2;
    push = Math.max(push,0) * -1;
    push = '0px';

    for(var prop in DATA[0]) {
	mainDiv.append("" + prop + ":" + DATA[0][prop] + " (" + typeof(DATA[0][prop]) + ")<br/>");
    }
    mainDiv.append("<br/>data.time = " + (DATA[0]['data']).time + "<br/>");
    mainDiv.append("<br/>data['time'] = " + DATA[0]['data']['time'] + "<br/>");
    mainDiv.append("<br/>data[0] = " + DATA[0]['data'][0] + "<br/>");
    mainDiv.append("<br/>data[0][0] = " + DATA[0]['data'][0][0] + "<br/>");
    mainDiv.append("<br/>In Meta:<br/>");
    
    for(var prop in DATA[0]['meta'][0]) {
	mainDiv.append("" + prop + ":" + DATA[0]['meta'][0][prop] + "<br/>");
    }

    mainDiv.append("<br/>In Fields:<br/>");
    for(var prop in DATA[0]['fields'][0]) {
	mainDiv.append("" + prop + ":" + DATA[0]['fields'][0][prop] + "<br/>");
    }

    mainDiv.append('<div id="adiv">AAA</div><div id="bdiv">BBB</div><div id="vis-left"></div><div id="vis-right"></div>');
    mainDiv.createPrepend('div',{id:'TT'},['ul',{},[
					  'li',{id:"LI1"},['a',{href:'#TD1'},['span',{},'Uno']],
				          'li',{},['a',{href:'#TD2'},['span',{},'Dos']],
					  'li',{},['a',{href:'#TD3'},['span',{},'Tres']]],
					  'div',{id:'TD1'},'One!',
					   'div',{id:'TD2'},'Two!',
					   'div',{id:'TD3'},'Three!']);
   
					  
    //$("#adiv").hide();
    //$("#bdiv").hide();
    //$("#tab-top").append('<div id="adiv">A</div><div id="bdiv">B</div>');
    /*$("#tab-top").append('<ul><li onclick="javascript:alert(1);return false;"><a href="#adiv"><span>A</span></a></li>'+
			 '<li onclick="javascript:alert(2);return false;"><a href="#bdiv"><span>B</span></a></li>' +
			 '<li><a href="#cdiv"><span onclick="javascript:alert(3);return false;">C</span></a></li></ul><div id="adiv">Viz A</div><div id="bdiv">Viz B</div><div id="cdiv">Viz C</div>');
    */
    $("#container").css('width','1024px');
    $("#header").css('position','relative');
    $("#header").css('left','62px');
    $("#pagetitle").css('width','1024px');
    $("#vis").css({'width':'1024px','clear':'both'});
    $("#tab-top").css('width','1024px');
    $("#tab-top").css('clear','both');
    $("#vis-left").css({'float':'left','background-color':'blue','width':'740px','height':'600px','margin-right':'4px'});
    $("#vis-right").css({'float':'right','background-color':'red','width':'280px','height':'600px'});

    $("#tab-top").tabs();
    $("#TT").tabs();
    $("#LI1").hide();
    $("#TT").tabs('select',2);
    $("#TT").tabs('disable',0);
    //$("#tab-top").tabs('add','#adiv','Left Side');
    //$("#tab-top").tabs('add','#bdiv','Right Side');

    mainDiv.append("So how does that look?");
    mainDiv.createAppend('div',{ id : 'slidertest' }, []);
    mainDiv.createAppend('div',{ id : 'slideres' }, []);
    $('#slidertest').slider({ max:60, min:-60, value:0, steps:120 })
	.bind('slidestop', {}, function(evt, obj) { 
		$('#slideres').append(obj.value + '!<br/>');
	    })
	.bind('slide', {}, function(evt, obj) { $('#slideres').append('.'); });

    mainDiv.createAppend('select', { id : 'selectest' }, [
			   'option', { value : 0 }, 'False',
			   'option', { value : 1 }, 'True',
			   'option', { value : "yes" }, 'Yes',
			   'option', { value : "no" }, 'No']);
    mainDiv.createAppend('div', {id : 'selectres'}, '');
    $('#selectest').bind('change', {},
			 function(evt, obj) {
			     $('#selectres').append('EVENT TARGET<br/>');
			     for(var elm in evt.originalEvent) {
				 $('#selectres').append('*'+elm+':'+evt[elm]+'<br/>');
			     }
			     
			     $('#selectres').append(obj.value);
			     $('#selectres').append($('#selectest').attr('selectedIndex')+'<br/>');
			     $('#selectres').append($('#selectest').attr('options'));
			 });
		
}