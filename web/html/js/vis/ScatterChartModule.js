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
    var infoPane;
    var width_px;
    var height_px;
    var thisModule;
    var svbutn;

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
    
   //function to create the session info
    this.createSessionInfoPane = function() { 
      var table;
      var sessionColor = 0;
      infoPane.empty();

      infoPane.createAppend(
        'form', {id : 'f_'+name+'_sessionInfoForm'}, [
        'table', {id : 'f_'+name+'_sessionInfoTable', style : 'width:100%;font-size:80%'}, []]);

      table = $('#f_'+name+'_sessionInfoTable');

      table.createAppend(
        'tr', {}, [
        'td', {style : 'text-align:center;width:100%;text-decoration:underline;padding-top:10px', colSpan : 3}, 'Session Descriptions']);

      for(var ses in sessions) {
        if (!sessions[ses]['visible']) continue;

        table.createAppend(
   		    'tr', {}, [
   		      'td', { id : 'f_'+name+'_sessionInfo_'+ses+'_color_bar', style:'width:3px;'}, [],
   		      'td', { }, sessions[ses]['id']+' - '+sessions[ses]['title']+' : by '+sessions[ses]['creator']]);

        table.createAppend(
          'tr', {}, [
            'td', {}, [],
            'td', {colSpan : 3, style:'text-align:left;padding-bottom:10px'}, 'Description: '+sessions[ses]['description']]);

        $('#f_'+name+'_sessionInfo_'+ses+'_color_bar').css({'background-color' : colorsToUse[sessionColor++],
         		'border-width' : 'thin',
         		'border-color' : 'black'});
         	
      }

    }
    
   this.parseSavedState = function() {
     if (stateObject[0] != name) return;
     
     /* Okay, we have a saved state
      * First we need to set every field off and every sessions off.
      */
      for (var ses in sessions) {
        sessions[ses]['visible'] = 0;
      }
      
      for(var i = 0; i < isenseData.fields['count']; ++i) {		
		      if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME || isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
		        continue;
		      }
		      
		      fieldVisible[i] = false;
		    }
		    
		  if (stateObject.length <= 2) return;
		    //Okay, now we actually want to start going through the state object
		    for (var i = 1; i < stateObject.length; i++) {
		      var field = stateObject[i].split(":")[0].split("i_"+name+"_")[1].split("_");
		      var value = stateObject[i].split(":")[1];
		      
		      if (field[0] == "toggle") {
		        options.lineSize = 1;
		      } else if (field[0] == "axis") {
		        axisField = value;
		      } else if (field[0] == "field") {
		        fieldVisible[field[1]] = true;
		      } else if (field[0] == "session") {
		        sessions[field[1]]['visible'] = 1;
		      }
		      
		    }
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

    /* ----------------------- *
     * Event Handler Functions *
     * ----------------------- */

   this.eh_saveState = function() {
      var serial = name+',' + $('#f_'+name+'_form').serialize().replace(/&/g, ",").replace(/=/g, ":");
      var url = window.location.href;
      url = url.replace(/#/g, "");
      //shortenURL(url + "&state=" + serial);
      var sessionids = new Array();
        for(ses in sessions) {
            sessionids.push(isenseData.sessions[ses]['id']);
        }

        openSaveVisDialog(sessionids, serial, isenseData.experiment_id);

	$('#TB_window').draggable();
    }

    this.eh_toggleSession = function(ses) {
	    sessions[ses]['visible'] = !sessions[ses]['visible'];
	    //this.redraw();
    }

    this.eh_toggleField = function(fld) {
	    fieldVisible[fld] = !fieldVisible[fld];
	    //this.redraw();
    }

    this.eh_selectAxis = function() {
	    var selectedId = parseInt(findInputElement("f_"+name+"_form",
			  "i_"+name+"_axis_select").value);
	    axisField = selectedId;
	    //this.redraw();
    }

    this.eh_toggleCheckbox = function(place) {
	if( $( place+':checked').val() == on ){
	    $(place).removeAttr('checked');
	} else {
	    $(place).attr('checked', 'checked');
	}
    }


    this.eh_toggleLines = function() {
	    if(options.lineSize > 0) {
	      options.lineSize = 0;
	    } else {
	      options.lineSize = 1;
	    }
	    //this.redraw();
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
	 /*   var fieldType;
	    for(i = 0; i < isenseData.fields['count']; i++) {
	      fieldType = isenseData.fields['sensor'][i];
	      if(fieldType == FIELD_TYPE.TIME) {
		      axisField = i;
	      }
	    }*/

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
	      this.createSessionInfoPane();
	      visObject.draw(datatable,options);
	      return true;
	    } catch(e) {
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

	      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form' }, [
		      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Scatter Chart'],
		        'tr', {}, [
		          'td', {colSpan : 2, style : 'text-align:center'}, [
	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn }, []]],
		        'tr', {}, [
		          'td', { colSpan : 3 }, 'X Axis:'],
		        'tr', {}, [
		          'td', {}, '',
		          'td', { colSpan : 2 }, [
		            'select', { id : 'i_'+name+'_axis_select', name : 'i_'+name+'_axis_select' }, [
		              'option', { value : -1 }, 'Datapoint#']]],
		        'tr', {}, [
		          'td', { style : "width:16px" }, [
		            'input', { type : 'checkbox', id : 'i_'+name+'_toggle_drawline', name : 'i_'+name+'_toggle_drawline'}, []],
		          'td', { colSpan : 2 }, 'Draw lines through points'],
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-decoration:underline;' }, ['Fields']],
			'tr', {}, [
		   	  'td', { colSpan : 3, style : 'text-align:center; width:100%' }, [
		     	    'input', { type : 'button', value : 'Reload', id: 'ReloadScatterChart' }, []]]]] );

    if(!$('#profile_link').length > 0) {
	$('#i_vis_Scatter_save_state').hide();
    }

	      $('#ReloadScatterChart').bind( 'click', function() { thisModule.redraw(); });

	      table = $('#f_'+name+'_table');
	      
	      if(options.lineSize > 0) {
		      $('#i_'+name+'_toggle_drawline').attr('checked','checked');
	      }
	    
	      $('#i_'+name+'_axis_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_selectAxis();
			      evt.data.scope.eh_toggleCheckbox(this);
			      return false;
		      });
	    
	      $('#i_'+name+'_toggle_drawline').bind('click', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_toggleLines();
			      evt.data.scope.eh_toggleCheckbox(this);
			      return false;
		      });
	    
	      $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
    		  function(evt, obj) {
    		    evt.data.scope.eh_saveState();
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
		      } else {
		        $('#f_'+name+'_field_row_'+i).createAppend(
		          'td', {}, [
		            'input', { type : 'checkbox',
				          id : 'i_'+name+'_field_'+i+'_select',
				          name : 'i_'+name+'_field_'+i+'_select'}, []]);
		    
		        if(fieldVisible[i]) {
			        $('#i_'+name+'_field_'+i+'_select').attr('checked','checked');
		        }
		      }

		      $('#f_'+name+'_field_row_'+i).createAppend(
		        'td', { colSpan : 2 }, isenseData.fields['title'][i]+' ('+isenseData.fields['units'][i]+')');
		      
		      $('#i_'+name+'_field_'+i+'_select').bind('click', { scope : thisModule, field : i },
			      function(evt, obj) {
			        evt.data.scope.eh_toggleField(evt.data.field);
			        evt.data.scope.eh_toggleCheckbox(this);
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
			              id : 'i_'+name+'_session_'+ses+'_select',
			              name : 'i_'+name+'_session_'+ses+'_select' }, []],
		            'td', { colSpan : 2 }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		        if(sessions[ses]['visible']) {
		          $('#i_'+name+'_session_'+ses+'_select').attr('checked','checked');

		          var j = 1;
		          for(i = 0; i < isenseData.fields['count']; ++i) {
			          if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL || i == axisField || isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
			            continue;
			          }

			          table.createAppend(
			            'tr', {}, [
			              'td', { id : 'f_'+name+'_colorfor_'+ses+'_'+i }, [],
			              'td', {}, j+' - '+isenseData.fields['title'][i]]);
			        
			          ++j;
		
			          if(fieldVisible[i]) {
			            $('#f_'+name+'_colorfor_'+ses+'_'+i).css({'background-color' : colorsToUse[fieldColor++],
				            'border-width' : 'thin',
				            'border-color' : 'black'});
			          } else {
			            $('#f_'+name+'_colorfor_'+ses+'_'+i).html('x');
			          }
		          }
		        }

		        $('#i_'+name+'_session_'+ses+'_select').bind('click', { scope:thisModule, session:ses },
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
	          } else {
		          axisTitle = isenseData.fields['title'][axisField];
	          }
	    
	          if (isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
	            axisTitle += ' (s)';
	          } else {
	            axisTitle += ' ('+isenseData.fields['units'][axisField]+')';
	          }
	          options.titleX = axisTitle;

            var axisYTitle;
            var field;
	          var count = 0;

            for(i = 0; i < fieldVisible.length; i++){
              if(fieldVisible[i] == true && (isenseData.fields['sensor'][i] != FIELD_TYPE.TIME && isenseData.fields['sensor'][i] != FIELD_TYPE.GEOSPACIAL)){
                count = count + 1;
                field = i;   
              }       
            }
                  
	          if(count > 1){
		          axisYTitle = "Value";
	          } else {
		          axisYTitle = isenseData.fields['title'][field];
	          } 

	          if (isenseData.fields['sensor'][field] == FIELD_TYPE.TIME) {
	            axisYTitle += ' (s)';
	          } else {
	            axisYTitle += ' ('+isenseData.fields['units'][field]+ ')';
	          }

            options.titleY = axisYTitle;
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
  	    } else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
  	      axisFieldTitle += "s)";
  	    } else {
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
  			      } else {
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
  		    } else {
  		      dataArrayLength = isenseData.sessions[ses]['data'][axisField].length;
  		    }
		    
  		    for(var i = 0; i < dataArrayLength; i++) {
  		      var valueId;
  		      if(axisField == -1) {
  			      valueId = datapointNumber + "#";
  			      datapointNumber++;
  		      } else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
  			      valueId = getTimeData(ses,axisField,i) - firstTimestamp[ses];
  			      //(parseInt(isenseData.sessions[ses]['data'][axisField][i].value) - firstTimestamp[ses]);
  			      valueId -= (valueId % 100);
  			      valueId = valueId + "#";
  		      } else {
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
  			      } else if(isenseData.fields['sensor'][axisField] == FIELD_TYPE.TIME) {
  			        curVal = parseFloat(i) / 1000.0
  			      } else {
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
  				          } else {
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
	    $('#'+name).createAppend('div', { id : name+'_infopane' }, []);
	
	    $('#'+name+'_viewpane').css({'float':'left','width':'650px','height':'600px','margin-right':'4px'});
	    $('#'+name+'_cntlpane').css({'float':'right','width':'180px','height':'600px','overflow-y':'auto','position':'relative'});
      $('#'+name+'_infopane').css({'width':'830px','clear':'both'});
  
	    controlPane = $('#'+name+'_cntlpane');
	    viewPane = document.getElementById(name+'_viewpane');
      infoPane = $('#'+name+'_infopane');

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
	    this.parseSavedState();
	    this.createTableGenerators();
	    this.createLegendGenerators();
	    this.createSessionInfoPane();
	    for(var ses in sessions) {
	      this.registerSession(ses);
	    }
    }

    thisModule = this;
}
