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

  var div = targetDiv;
  var data;
  var opts;
  var map;
  var points = []; // Array of arrays of objects with properties .location and .text
  var markers = [];
  var markerInfo = [];
  var markerBounds = [];
  var polylines = [];
  var selectedElem;
  var errorno = 0;
  var errormsg = ["errno is set to 0, this should not be displayed.", "Shoot the Programmer error.", "Google Maps is unable to run in your browser.", "Malformed datatable error."];

  var colorsToUse = ["#4684ee", "#dc3912", "#ff9900", "#008000", "#666666", "#4942cc", "#cb4ac5", "#d6ae00", "#336699", "#dd4477", "#aaaa11", "#66aa00", "#888888", "#994499", "#dd5511", "#22aa99", "#999999", "#705770", "#109618", "#a32929"];

  //
  // Option variables
  //
  var opt_useOptionalValues = true;
  var opt_showLines;
  var opt_lineWidth;
  var opt_mapType;
  var opt_enableWheel;
  var opt_showTips;
  var opt_fillTypes = ["tfill", "bfill"];
  var opt_fillSelect = 1;
  var opt_smallMap = false;

  // PolyLine vars
  var showPolyLines = false;
  var Gpoints = new Array();

/* * * * * * * * * *
  * Initialization  *
  *                 *
  * * * * * * * * * *
  * Check to make sure Google Maps has been initialize, is comptable, and
  * there exists a div object to add the map to.
  */
  if (typeof GMap2 == 'undefined') {
    errorno = 1;
    throw new Error("Google Map object not found. Please add appropriate scripts to page.");
  }

  if (!GBrowserIsCompatible()) {
    errorno = 2;
  }

  if (typeof div == 'string') {
    if (typeof document.getElementById(div) == 'undefined') {
      errorno = 1;
      throw new Error("Invalid argument: target DIV element not found.");
    }
    else {
      div = document.getElementById(div);
    }
  }

  if (typeof div == 'object' && typeof div.nodeName != 'undefined' && div.nodeName != 'DIV') {
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

  this.draw = function (datatable, options) {
    //
    // Check arguments
    //
    if (typeof options != "object" || typeof datatable != "object") {
      errorno = 1;
      throw new Error("draw() - Invalid argument: arguments must be objects.");
    }

    //
    // Clear old information, if it exists.
    //
    if (typeof map != "undefined") {
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
    if (errorno != 0) {
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

    drawPoints();

    addEventHandlers();
  }

  this.getSelection = function () {
    if (selectedElem) {
      return {
        object: selectedElem,
        session: selectedElem.isenseSession,
        index: selectedElem.isenseIndex
      };
    } else {
      return null;
    }
  }

/*
  * Selects and zooms to marker indicated by selection.
  * selection should contain at least a session number (to zoom to an entire
  * path) and possibly an index for a particular marker.
  */
  this.setSelection = function (selection) {
    if (!selection || !! selection.session) {
      return;
    }

    var mark = null;
    for (var ses in markers) {
      if (selection.session == markers[ses][0].isenseSession) {
        mark = markers[ses];
        break;
      }
    }

    if (mark) {
      if (selection.index && typeof selection.index == 'number' && selection.index < markers[ses].length && selection.index >= 0) {
        map.setCenter(points[ses][selection.index], 5);
      } else {
        map.setCenter(points[ses][Math.floor(points[ses].length / 2)], map.getBoundsZoomLevel(markerBounds[ses]) - 1);
      }
    }

    return;
  }

  this.remove = function () {
    deleteMap();
  }

/* * * * * * * * * *
  * Private Methods *
  *                 *
  * * * * * * * * * *
  */
  var addEventHandlers = function () {
    GEvent.bind(map, "maptypechanged", this, function () {
      opt_mapType = map.getCurrentMapType();
      //alert("changed to: " + opt_mapType.getName());
    });

    GEvent.bind(map, "click", this, function (overlay, latlng, olatlng) {
      if (overlay == null) {
        return;
      }

      if (overlay.isenseType == "GMarker") {
        selectedElem = overlay;
        $("#" + div.id).trigger("click", {
          type: "custommapmarker",
          session: overlay.isenseSession,
          index: overlay.isenseIndex
        });
      }
      else if (overlay.isenseType == "GPolyLine") {
        selectedElem = overlay;
        $("#" + div.id).trigger("click", {
          type: "custommapline",
          session: overlay.isenseSession
        });
      }
    });
  }

  var handleOptions = function (options) {
    //
    // Polyline paths between points
    //
    if (options.showLines) {
      opt_showLines = true;
      if (options.lineWidth) {
        opt_lineWidth = options.lineWidth;
      } else {
        opt_lineWidth = 4;
      }
    } else {
      opt_showLines = false;
      opt_lineWidth = 0;
    }

    //
    // Set the starting map type
    //
    if (!opt_mapType) {
      if (options.mapType == "satellite") {
        opt_mapType = G_SATELLITE_MAP;
      } else if (options.mapType == "normal") {
        opt_mapType = G_NORMAL_MAP;
      } else {
        opt_mapType = G_HYBRID_MAP;
      }
    }

    //
    // Set scroll wheel usage
    //
    if (options.enableScrollWheel) {
      opt_enableWheel = true;
    }
    else {
      opt_enableWheel = false;
    }

    //
    // Set use of info window
    //
    if (typeof options.showTip == "undefined" || options.showTip) {
      opt_showTips = true;
    } else {
      opt_showTips = false;
    }

    //
    // Set marker style (colored Google marker or value-bar custom marker)
    //
    if (options.useFilledMarkers) {
      opt_useOptionalValues = true;
    } else {
      opt_useOptionalValues = false;
    }

    if (options.smallMap) {
      opt_smallMap = true;
    }
  }

  var enforceOptions = function () {
    //
    // To keep the map type from changing when it is reloaded,
    // set it to the last map type used.
    //
    map.setMapType(opt_mapType);

    //
    // Scroll wheel zoom
    //
    if (opt_enableWheel) {
      map.enableScrollWheelZoom();
    } else {
      map.disableScrollWheelZoom();
    }

    if (opt_smallMap) {
      map.addControl(new GSmallZoomControl());
    } else {
      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
    }
  }

/*
  * parseTable - check the datatable to see which parsing function should be used.
  *              If string addresses are passed, we need to convert the addresses to lat/lon.
  *              Otherwise, use lat/lon data in table to add points to map.
  */
  var parseTable = function (datatable) {
    if (typeof datatable.getNumberOfColumns != "function") {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable is not a Google DataTable object.");
    }
    if (datatable.getNumberOfColumns() < 3) {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable does not have the minimum number of columns.");
    }
    if (datatable.getColumnType(0) != "string") {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable column 0 should be a string; it is a " + data.getColumnType(0));
    }

    //
    // Is it an address table or a gps table?
    //Always gps now, geocoding done pre-vis
    if (datatable.getColumnType(1) == "number") {
      geocoderRunning = false;
      parseGPSTable(datatable);
    } else {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable column 1 should be a number, it is a " + datatable.getColumnType(1));
    }
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
  var parseGPSTable = function (datatable) {
    data = datatable;

    //
    // Check to ensure table is valid and properly constructed.
    //
    if (data.getNumberOfColumns() < 4) {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable does not have the minimum number of columns.");
    }

    if (data.getColumnType(1) != "number") {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable column 1 should be a number; it is a " + data.getColumnType(1));
    }

    if (data.getColumnType(2) != "number") {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable column 2 should be a number; it is a " + data.getColumnType(2));
    }

    if (data.getColumnType(3) != "string") {
      errorno = 4;
      throw new Error("parseTable() - Invalid argument: datatable column 3 should be a string; it is a " + data.getColumnType(3));
    }

    //
    // Clear existing data.
    //
    for (var arr in points) {
      delete points[arr];
    }
    delete points;
    points = new Array();

    for (var arr in markers) {
      delete markers[arr];
    }
    delete markers;
    markers = new Array();

    for (var arr in markerInfo) {
      delete markerInfo[arr];
    }
    delete markerInfo;
    markerInfo = new Array();

    for (var arr in polylines) {
      delete polylines[arr];
    }
    delete polylines;
    polylines = new Array();

    for (var arr in markerBounds) {
      delete markerBounds[arr];
    }
    delete markerBounds;
    markerBounds = new Array();

    //
    // Data looks ok. Create assocative arrays for points and markers
    // based on ID (column 0).
    //
    var sessionCells = new Array();
    var sessionColor = new Array();
    var rowLimit = data.getNumberOfRows();
    var colCount = data.getNumberOfColumns();
    var row;
    var minLon_t = 1000;
    var minLon_s = 1000;
    var maxLon_t = -1000;
    var maxLon_s = -1000;
    var minLat_t = 1000;
    var minLat_s = 1000;
    var maxLat_t = -1000;
    var maxLat_s = -1000;
    var minOpVal;
    var maxOpVal;
    var sesColorIndex = 0;

    for (row = 0; row < rowLimit; row++) {
      var id = data.getValue(row, 0);
      var lat = data.getValue(row, 1);
      var lon = data.getValue(row, 2);
      var info = data.getValue(row, 3);
      var opval;

      if (colCount >= 5) {
        opval = data.getValue(row, 4);
        if ((opval != undefined && !isNaN(opval)) && (minOpVal == undefined || minOpVal > opval)) {
          minOpVal = opval;
        }
        if ((opval != undefined && !isNaN(opval)) && (maxOpVal == undefined || maxOpVal < opval)) {
          maxOpVal = opval;
        }
      }

      //
      // If the session id contains "bnd", it is a helper marker used to provide
      // additional information on the maximum or minimum values of the measured
      // field (which has been accounted for above). Do not add it as a marker.
      //
      if (id.indexOf("bnd") != -1) {
        continue;
      }

      if (!sessionCells[id]) {
        sessionCells[id] = new Array();
        sessionColor[id] = sesColorIndex;

        if (sesColorIndex + 1 < colorsToUse.length) {
          ++sesColorIndex;
        }
      }

      //
      // Ignore bad waypoints as well (NaN lat-lon values)
      //
      if (isNaN(lat) || isNaN(lon) || lat == 200 || lon == 200) {
        continue;
      }

      //
      // Push good point to it's sessions array.
      //
      sessionCells[id].push({
        c_id: id,
        c_lat: lat,
        c_lon: lon,
        c_info: info,
        c_opval: opval
      });
    }
    sesColorIndex = 0;


    var i;
    for (var ses in sessionCells) {
      minLon_s = minLat_s = 1000;
      maxLon_s = maxLat_s = -1000;

      for (i = 0; i < sessionCells[ses].length; ++i) {
        id = sessionCells[ses][i].c_id;
        lat = sessionCells[ses][i].c_lat;
        lon = sessionCells[ses][i].c_lon;
        info = sessionCells[ses][i].c_info;
        opval = sessionCells[ses][i].c_opval;

        // Check max/min to get boundaries for default zoom level
        //
        if (lat < minLat_t) minLat_t = lat;
        if (lat < minLat_s) minLat_s = lat;
        if (lat > maxLat_t) maxLat_t = lat;
        if (lat > maxLat_s) maxLat_s = lat;
        if (lon < minLon_t) minLon_t = lon;
        if (lon < minLon_s) minLon_s = lon;
        if (lon > maxLon_t) maxLon_t = lon;
        if (lon > maxLon_s) maxLon_s = lon;

        // Create arrays for ID if it does not exist.
        //
        if (typeof points[id] == "undefined") {
          points[id] = new Array();
          markers[id] = new Array();
          markerInfo[id] = new Array();
        }

        // Build new points and markers, add to array
        //
        var mTitle = "Session " + parseInt(id) + " - Datapoint " + (markers[id].length + 1);
        var mIcon = new GIcon(G_DEFAULT_ICON);
        //alert(mIcon.imageMap.length);
        //alert(mIcon.imageMap);
        //alert(mIcon.iconSize.toString());
        //alert(mIcon.iconAnchor.toString());
        var mPath = location.href;
        mPath = mPath.slice(0, mPath.lastIndexOf("/"));

        if (opt_useOptionalValues) {

          // Use 'filled' markers. mOV should be an integer between 0 and 16 inclusive.
          //
          var mOV;
          if (opval != undefined && !isNaN(opval)) {
            mOV = Math.round(((opval - minOpVal) / (maxOpVal - minOpVal)) * 16.0);
          } else {
            mOV = 'x';
          }

          mIcon.shadow = mPath + "/html/img/vis/custommap/ricon_shad_ses" + String.fromCharCode(65 + sessionColor[id]) + ".png";
          mIcon.image = mPath + "/html/img/vis/custommap/ricon_" + opt_fillTypes[opt_fillSelect] + "_" + mOV + "_of16.png";
          mIcon.iconSize = new GSize(30, 64);
          mIcon.shadowSize = new GSize(30, 64);
          mIcon.iconAnchor = new GPoint(15, 64);
          mIcon.infoWindowAnchor = new GPoint(30, 0);
          mIcon.imageMap = [0, 0, 0, 62, 13, 68, 17, 68, 30, 62, 30, 0];

/* --------------------------------------------------------------------
            * This was used for the 10 image resolution, with Google style markers
            * --------------------------------------------------------------------
            * mIcon.shadow = mPath + "/html/img/vis/custommap/icon" + 
            *                colorsToUse[sessionColor[id]].substr(1) + "_shadow.png";
            * var mOV = Math.floor(((opval - opMinVal) / (opMaxVal - opMinVal)) * 10) * 10;
            *
            * //document.getElementById("Visualization_debug").innerHTML += mOV + "<br/>";
            * mIcon.image  = mPath + "/html/img/vis/custommap/iconvalpct" + mOV + ".png";
            * mIcon.shadowSize = mIcon.iconSize;
            */
        } else {

          mIcon.image = mPath + "/html/img/vis/custommap/icon" + colorsToUse[sessionColor[id]].substr(1) + ".png";
/*if (opt_smallMap) {
              mIcon.iconSize         = new GSize(15,24);
              mIcon.shadowSize       = new GSize(15,24);
              mIcon.iconAnchor       = new GPoint(7,24);
              mIcon.infoWindowAnchor = new GPoint(15,0);
              mIcon.imageMap         = [0,0,0,62,13,68,17,68,30,62,30,0];
            }*/
        }
        var nPoint = new GLatLng(lat, lon);
        var nMarker = new GMarker(nPoint, {
          icon: mIcon,
          title: mTitle
        });
        nMarker.isenseType = "GMarker";
        nMarker.isenseSession = id;
        nMarker.isenseIndex = markers[id].length;

        points[id].push(nPoint);
        markers[id].push(nMarker);
        markerInfo[id].push(info);
      }

      // Create the session specific boundary object
      //
      markerBounds[id] = new GLatLngBounds(new GLatLng(minLat_s, minLon_s), new GLatLng(maxLat_s, maxLon_s));
    }

    // Create the boundary object (south-west corner, north-east corner)
    //
    markerBounds["all"] = new GLatLngBounds(new GLatLng(minLat_t, minLon_t), new GLatLng(maxLat_t, maxLon_t));
  }

  var drawPoints = function () {
    //
    // Draw markers
    //
    //var sesColorIndex = 0;
    for (var ses in markers) {

      //
      // Before any markers can be added, the map must be initialized
      // with setCenter. Set zoom so that all markers will fit.
      //
      if (!map.isLoaded()) {
        map.setCenter(points[ses][0], map.getBoundsZoomLevel(markerBounds["all"]) - 1);
      }

      //
      // Setup the GPolyline object now, set it's visibility 
      // according to user options.
      //
      if (showPolyLines) {
        for (row = 0; row < data.getNumberOfRows(); row++) {
          var lat = data.getValue(row, 1);
          var lon = data.getValue(row, 2);
          if (lat <= 90 && lat >= -90 && lon <= 180 && lon >= -180) Gpoints[Gpoints.length] = new GLatLng(lat, lon);
        }
        for (var i = 0; i < Gpoints.length - 1; i++) {
          var polyline = new GPolyline([Gpoints[i], Gpoints[i + 1]], "#ff0000", 10);
          map.addOverlay(polyline);
        }
      }



      for (var m = 0; m < markers[ses].length; ++m) {
        if (opt_showLines) {
          polylines[ses].insertVertex(m, points[ses][m]);
          markerInfo[ses][m] += "<br/><br/>Distance from start: " + polylines[ses].getLength() + " meters";
        }

        if (opt_showTips) {
          markers[ses][m].bindInfoWindowHtml("<div style='font-size:80%;'>" + markerInfo[ses][m] + "<br/></div>");
        }
        map.addOverlay(markers[ses][m]);
      }

      //++sesColorIndex;
    }
  }

/*
  * Cleanly delete map object.
  */
  var deleteMap = function () {

    delete map;
    div.innerHTML = "";
  }
}
