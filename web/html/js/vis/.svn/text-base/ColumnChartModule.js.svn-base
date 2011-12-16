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
    var infoPane;
    var controlPane;
    var width_px;
    var height_px;
    var thisModule;
    var svbutn;

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
       * First we need to set every field off and evevry sessions off.
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

          if (field[0] == "field") {
 		        fieldVisible[field[2]] = true;
 		      } else if (field[0] == "session") {
 		        sessions[field[1]]['visible'] = 1;
 		      } else if (field[0] == "anal" && fieldVisible[field[2]]) {
 		        selectedAnalysisType[field[2]] = analysisTypes[value];
 		      }
 		    }
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

    this.eh_selectAnalysisType = function(field) {
	    var anTypeIndex = parseInt(findInputElement("f_"+name+"_form","i_"+name+"_anal_select_"+field).value);
	    selectedAnalysisType[field] = analysisTypes[anTypeIndex];
	    //this.redraw();
    }

    this.eh_toggleCheckbox = function(place) {
	if( $( place+':checked').val() == on ){
	    $(place).removeAttr('checked');
	} else {
	    $(place).attr('checked', 'checked');
	}
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
		          'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Bar Chart'],
		        'tr', {}, [
	            'td', {colSpan : 2, style : 'text-align:center'}, [
	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn }, []]],
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-decoration:underline;' }, ['Fields']],
		    'tr', {}, [
		      'td', { colSpan : 3, style : 'text-align:center; width:100%' }, [
			 'input', { type : 'button', value : 'Reload', id: 'Reload' }, []]]]] );

	      $('#Reload').val('Reload').bind( 'click', function() { thisModule.redraw(); });

    if(!$('#profile_link').length > 0) {
	$('#i_vis_Bar_save_state').hide();
    }

	      table = $('#f_'+name+'_table');
	    
	      $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
  		    function(evt, obj) {
  		      evt.data.scope.eh_saveState();
  		      return false;
  	      });
	    
	      for(var i = 0; i < isenseData.fields['count']; ++i) {
		      if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL || isenseData.fields['sensor'][i] == FIELD_TYPE.TIME || isenseData.fields['sensor'][i] == FIELD_TYPE.STRING) {
		        continue;
		      }
		
		      var fchk = "";
		      if(fieldVisible[i]) {
		        fchk = "checked";
		      }

		      table.createAppend(
		        'tr', {}, [
		          'td', {}, [
		            'input', { type : "checkbox", id : 'i_'+name+'_field_toggle_'+i, name : 'i_'+name+'_field_toggle_'+i, checked : fchk }, ''],
		          'td', { colSpan : 2 }, isenseData.fields['title'][i]+ ' (' + isenseData.fields['units'][i]+')']);
		
		      $('#i_'+name+'_field_toggle_'+i).bind('click', { scope : thisModule, field : i },
			      function(evt, obj) {
				      evt.data.scope.eh_toggleField(evt.data.field);
				      evt.data.scope.eh_toggleCheckbox(this);
			      });
		
		      if(fieldVisible[i]) {
		        table.createAppend(
		          'tr', {}, [
			          'td', {colSpan : 2/*, style : 'padding-bottom:10px'*/}, [
			            'span', { }, 'Compute: ',
		              'select', { id : 'i_'+name+'_anal_select_'+i, name : 'i_'+name+'_anal_select_'+i }, []]]);
		
		        for(var j = 0; j < analysisTypes.length; ++j) {
			        var sel = "";
			        if(selectedAnalysisType[i] == analysisTypes[j]) {
			          sel = 'selected';
			        }

			        $('#i_'+name+'_anal_select_'+i).createAppend(
			          'option', { value : j, selected : sel }, analysisTypes[j]);
		        }
		    
		        $('#i_'+name+'_anal_select_'+i).bind('change', { scope : thisModule, field : i },
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
		          'td', { id : 'f_'+name+'_session_'+ses+'_color', style : 'width:3px;' }, [
		            'input', { id : 'i_'+name+'_session_'+ses+'_select', name : 'i_'+name+'_session_'+ses+'_select', type : 'checkbox', checked : chk }, []],
		          'td', {  }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		      $('#i_'+name+'_session_'+ses+'_select').bind('click', { scope : thisModule, session : ses },
			    function(evt, obj) {
			      evt.data.scope.eh_toggleSession(evt.data.session);
			      evt.data.scope.eh_toggleCheckbox(this);
			      return false;
			    });

		      if(sessions[ses]['visible']) {
		        $('#f_'+name+'_session_'+ses+'_color').css({'background-color' : colorsToUse[fieldColor++]});
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
		      datatable.setValue(row, 0, isenseData.fields['title'][j] + " (" + isenseData.fields['units'][j] + ")");
		    
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
					  if( !isNaN(isenseData.sessions[i]['data'][j][k].value) )
			          	value += parseFloat(isenseData.sessions[i]['data'][j][k].value);
			        }
			
			        value = value / isenseData.sessions[i]['data'][j].length;
					if( !isNaN(value) )
			        	datatable.setValue(row, col, value)
		        } else if(selectedAnalysisType[j] == "max") {
			        var value = "undefined";
			      
			        for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
					  if( !isNaN(isenseData.sessions[i]['data'][j][k].value))
			            if(value == "undefined" || parseFloat(isenseData.sessions[i]['data'][j][k].value) > value) {
				          value = parseFloat(isenseData.sessions[i]['data'][j][k].value);
			            }
			        }
			        datatable.setValue(row, col, value);
		        } else if(selectedAnalysisType[j] == "min") {
			        var value = "undefined";
			
			        for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
					  if( !isNaN(isenseData.sessions[i]['data'][j][k].value) )
			            if(value == "undefined" || parseFloat(isenseData.sessions[i]['data'][j][k].value) < value) {
				          value = parseFloat(isenseData.sessions[i]['data'][j][k].value);
			          	}
			        }
			        datatable.setValue(row, col, value);
		        } else if(selectedAnalysisType[j] == "total") {
			        var value = 0;
			      
			        for(var k = 0; k < isenseData.sessions[i]['data'][j].length; ++k) {
						if( !isNaN(isenseData.sessions[i]['data'][j][k].value) )
			          		value += parseFloat(isenseData.sessions[i]['data'][j][k].value);
			        }
			        datatable.setValue(row, col, value);
		        } else if(selectedAnalysisType[j] == "point") {
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
	    visObject = new google.visualization.ColumnChart(viewPane);

	    //
	    // Check Google options to ensure good defaults exist
	    //
	    options = { legend : "top",
		    titleY : "Value",
		    colors : colorsToUse,
		    titleX : "Fields"};

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
