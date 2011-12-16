/*
 * TAG SESSIONMAP
 */
function SessionMapModule(a_parent, a_name, a_data, a_state, flag) {
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
    var infoPane;
    var width_px;
    var height_px;
    var thisModule;
    var full = flag

    //
    // Meta Data
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
    		    'td', { id : 'f_'+name+'_sessionInfo_'+ses+'_color_bar', style:'width:3px;', colSpan : 1}, [],
    		    'td', { colSpan : 2}, sessions[ses]['id']+' - '+sessions[ses]['title']+' : by '+sessions[ses]['creator']]);

        table.createAppend(
          'tr', {}, [
             'td', {}, [],
             'td', {style:'text-align:left;padding-bottom:10px'}, 'Description: '+sessions[ses]['description']]);

        	$('#f_'+name+'_sessionInfo_'+ses+'_color_bar')
        		.css({'background-color' : colorsToUse[sessionColor++],
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
		    
		    if (stateObject.length <= 2) return;
		    
		    //Okay, now we actually want to start going through the state object
		    for (var i = 1; i < stateObject.length; i++) {
		      var field = stateObject[i].split(":")[0].split("i_"+name+"_")[1].split("_");
		      var value = stateObject[i].split(":")[1];
		      
		      if (field[0] == "session" && field[1] == "select") {
		        sessions[field[2]]['visible'] = true;
		      } 
		    }
    }

    /*
     * Sorting function for collapsing datatable generator
     */
    var markerSort = function(a,b) {
	    return b.count - a.count;
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
    }

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
	      this.createSessionInfoPane();
	      return true;
	    } catch(e) {
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
	    
	      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

	      controlPane.empty();
	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form' }, [
		        'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
		          'tr', {}, [
		            'td', { colSpan : 3, style : 'text-align:center;' }, 'Session Map'],
  		        'tr', {}, [
		            'td', {colSpan : 3, style : 'text-align:center'}, [
  	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn }, []]]]]);

   	      if(!$('#profile_link').length > 0) {
		$('#i_vis_Map_save_state').hide();
	      }


	      table = $('#f_'+name+'_table');
	      
	      $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
  		    function(evt, obj) {
  		      evt.data.scope.eh_saveState();
  		      return false;
  	      });
	    
	      //
	      // Add session controls
	      //
	      table.createAppend(
	        'tr', {}, [
	          'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Sessions']);
	    
		    for(var ses in sessions) {
		      table.createAppend(
		        'tr', {}, [
		          'td', {colSpan : 2, style : 'background-color:'+colorsToUse[sessionColor++] }, [
		            'input', { type : 'checkbox', 
			            id : 'i_'+name+'_session_select_'+ses, 
			            name : 'i_'+name+'_session_select_'+ses }, []],
		          'td', { colSpan : 1}, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
		      $('#i_'+name+'_session_select_'+ses).bind('click',{ scope:thisModule, session:ses },
			      function(evt, obj) {
			        evt.data.scope.eh_toggleSession(evt.data.session);
			        return false;
			      });
		
		      if(!sessions[ses]['visible']) {
		        continue;
		      } else {
		        $('#i_'+name+'_session_select_'+ses).attr('checked','checked');
		      }
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
	    datatable.addColumn('number','Lat');
	    datatable.addColumn('number','Lon');
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
		    datatable.setValue(totadded,1,parseFloat(sessions[ses]['latitude']));
		    datatable.setValue(totadded,2,parseFloat(sessions[ses]['longitude']));

		    var infostr = "Session #" + sessions[ses]['id'] + "<br/>"
		      + sessions[ses]['title'] + "<br/>"
		      + sessions[ses]['address'] + "<br/>"
		      + sessions[ses]['date'] + "<br/>"
		      + sessions[ses]['data'][0].length + " rows of data.";

		    datatable.setValue(totadded,3,infostr);
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
      if (full) {
	      //
	      // Create control and vis DIVs
	      //
	      $('#'+name).createAppend('div', { id : name+'_viewpane' }, []);
	      $('#'+name).createAppend('div', { id : name+'_cntlpane' }, []);
	      $('#'+name).createAppend('div', { id : name+'_infopane' }, []);
	
	      $('#'+name+'_viewpane').css({'float':'left','width':'650px','height':'600px','margin-right':'4px'});
	      $('#'+name+'_cntlpane').css({'float':'right','width':'180px','height':'600px'});
	      $('#'+name+'_infopane').css({'width':'830px','clear':'both'});

	      controlPane = $('#'+name+'_cntlpane');
	      viewPane = document.getElementById(name+'_viewpane');
        infoPane = $('#'+name+'_infopane');

	      //
	      // Create Google visualization object
	      //
	      visObject = new isenseMap(viewPane);
	
	      //
	      // Check Google options to ensure good defaults exist
	      //
	      options = { enableScrollWheel : false,
		      showTip : true,
		      showLines : false};

	      this.generateMetaData();
	      this.parseSavedState();
	      this.createTableGenerators();
	      this.createLegendGenerators();
	      this.createSessionInfoPane();
	      for(var ses in sessions) {
	        this.registerSession(ses);
	      }
      } else {
        options = { enableScrollWheel : false,
		      showTip : true,
		      showLines : false, 
		      smallMap : true };
        
        visObject = new isenseMap(document.getElementById('map_canvas'));
        this.createTableGenerators();
        tableGenerator();
	      visObject.draw(datatable,options);
      }
    }

    thisModule = this;
}
