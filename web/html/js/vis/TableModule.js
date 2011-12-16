/*
 * Table
 */
function TableModule(a_parent, a_name, a_data, a_state) {
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
  var infoPane;
  var width_px;
  var height_px;
  var thisModule;
  var svbutn;
  
  //Meta data
  var fieldVisible      = new Array();
  var showField         = new Array();
  var selectedGenerator = 0;
    
  this.parseSavedState = function() {
    if (stateObject[0] != name) return;

    /* Okay, we have a saved state
     * First we need to set every field off and evevry sessions off.
     */
     for (var ses in sessions) {
       sessions[ses]['visible'] = 0;
     }

     for(var i = 0; i < isenseData.fields['count']; ++i) {		
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
	      }
	    }
  }
  
  this.createSessionInfoPane = function() { 
    var table;
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
 		      'td', { }, sessions[ses]['id']+' - '+sessions[ses]['title']+' : by '+sessions[ses]['creator']]);

      table.createAppend(
        'tr', {}, [
          'td', {colSpan : 3, style:'text-align:left;padding-bottom:10px'}, 'Description: '+sessions[ses]['description']]);
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

  this.eh_toggleCheckbox = function(place) {
    if( $( place+':checked').val() == on ){
      $(place).removeAttr('checked');
    } else {
      $(place).attr('checked', 'checked');
    }
  }
  
  /*
   * Generate state and meta data.
   * TODO: change for new fieldType values
   */
  this.generateMetaData = function() {
    for(var i = 0; i < isenseData.fields['count']; ++i) {
      fieldVisible[i] = true;
    }
  }
  
  
  this.registerSession = function(session_id) {

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
  
  /*
   * Create control pane generators.
   */
  this.createLegendGenerators = function() {
    legendGenerator.length = 1;

    legendGenerator[0] = function() {
      var table;

      controlPane.empty();

      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

      controlPane.createAppend(
        'form', { id : 'f_'+name+'_form' }, [
	      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
	        'tr', {}, [
	          'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Column Chart'],
	        'tr', {}, [
                  'td', {colSpan : 2, style : 'text-align:center'}, [
                    'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn}, []]],
	        'tr', { id:'save_vis_wrapper'}, [],
	        'tr', {}, [
	          'td', { colSpan : 3, style : 'text-decoration:underline;' }, ['Fields']],
		'tr', {}, [
		   'td', { colSpan : 3, style : 'text-align:center; width:100%' }, [
		     'input', { type : 'button', value : 'Reload', id: 'ReloadTable' }, []]]]] );

	      $('#ReloadTable').bind( 'click', function() { thisModule.redraw(); });

    if(!$('#profile_link').length > 0) {
	$('#i_vis_Table_save_state').hide();
    }

      table = $('#f_'+name+'_table');
    
      $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
		    function(evt, obj) {
		      evt.data.scope.eh_saveState();
		      return false;
	      });
    
      for(var i = 0; i < isenseData.fields['count']; ++i) {
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
			      return false;
		      });
      }
    
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
	          'td', { style : 'width:3px;' }, [
	            'input', { id : 'i_'+name+'_session_'+ses+'_select', name : 'i_'+name+'_session_'+ses+'_select', type : 'checkbox', checked : chk }, []],
	          'td', {  }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
	
	      $('#i_'+name+'_session_'+ses+'_select').bind('click', { scope : thisModule, session : ses },
		    function(evt, obj) {
		      evt.data.scope.eh_toggleSession(evt.data.session);
		      evt.data.scope.eh_toggleCheckbox(this);
		      return false;
		    });
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
    
      var row = 0;
      var col;
      datatable.addColumn('number', "Session Number"); 
      for(var j = 0; j < isenseData.fields['count']; ++j) {
	      if(fieldVisible[j]) {
	        var label = isenseData.fields['title'][j];
	        
	        if (isenseData.fields['sensor'][j] == FIELD_TYPE.TIME || isenseData.fields['sensor'][j] == 37 ) {
	          label += "";
	          datatable.addColumn('string', label);
	        } else {
	          label += " (" + isenseData.fields['units'][j] + ")";
	          datatable.addColumn('number', label);
	        }
	      }
	    }
	    
	    col = 1;
	    var offset = row;
	    for (var ses in sessions) {
	      if(!sessions[ses]['visible']) {
	        continue;
	      }
	      
	      for(var k = 0; k < isenseData.sessions[ses]['data'][0].length; ++k) { 
          datatable.addRow(); 
        }
	      
	      for(var j = 0; j < isenseData.fields['count']; ++j) {
	        if(!fieldVisible[j]) {
	          continue;
	        }
	        
	        for(var k = 0; k < isenseData.sessions[ses]['data'][j].length; ++k) {
	          datatable.setValue(k + offset, 0, parseInt(sessions[ses]['id']));
	          
	          if (isenseData.fields['sensor'][j] == FIELD_TYPE.TIME ) {
	          	var newDate = new Date();
              	newDate.setTime(getTimeData(ses,j,k));
              	var dateString = newDate.toUTCString();
	            datatable.setValue(k + offset, col, dateString);	            
	          } else if (isenseData.fields['sensor'][j] == 37) {
				datatable.setValue(k + offset, col, isenseData.sessions[ses]['data'][j][k].value)
			  } else {
	            var num = new Number(parseFloat(isenseData.sessions[ses]['data'][j][k].value));
	            datatable.setValue(k + offset, col, parseFloat(num.toFixed(4)));
	          }
	        }
	    
	        col++;
	      }
	      offset += k;
	      col = 1;
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
    visObject = new google.visualization.Table(viewPane);

    //
    // Check Google options to ensure good defaults exist
    //
    options = { showRowNumber : true,
                width : "650",
                height : "525",
                allowHtml : true};

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
