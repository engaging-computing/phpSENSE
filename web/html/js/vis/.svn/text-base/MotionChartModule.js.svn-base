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


    /*
     * Check the field in the iSENSE data structure (assuming it has time data), and check if
     * the data is in the unix time format. If not, try parsing it as a human readable date, for
     * example "April 21 2009, 12:47:05"
     */
    var getTimeData = function(session_id, field_id, datapoint) {
	    var unixts = Date.parse(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	    
	    if (isNaN(unixts)) {
	      unixts = parseInt(isenseData.sessions[session_id]['data'][field_id][datapoint].value);
	      return unixts * 1000;
	    }
	    
	    return unixts;
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
	      } else if(fieldType == FIELD_TYPE.STRING && textFieldIndex == -1) {
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
	    } catch(e) {
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
		        } else {
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
		        } else {
			        datatable.setValue(rowNum,1,new Date(parseInt(isenseData.sessions[ses]['date'] + (dp * 86400000))));
		        }
		    
		        for(var fld = 0; fld < isenseData.fields['count']; fld++) {
			        if(fldNum < datatable.getNumberOfColumns() && fld != timeFieldIndex) {
			          if(isenseData.fields['sensor'][fld] == FIELD_TYPE.STRING) {
				          datatable.setValue(rowNum,fldNum,isenseData.sessions[ses]['data'][fld][dp].value);
			          } else {
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
	
	    $('#'+name+'_viewpane').css({'width':'800px','height':'600px','margin-left':'50px'});
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
	    options = {width:800,height:600};

	    this.generateMetaData();
	    this.createTableGenerators();
	    this.createLegendGenerators();
	    for(var ses in sessions) {
	      this.registerSession(ses);
	    }
    }

    thisModule = this;
}
