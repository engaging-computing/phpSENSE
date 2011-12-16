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
    var infoPane;
    var width_px;
    var height_px;
    var custom          = true;
    var thisModule;
    var svbutn;

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
    var pointSkip            = 10;
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
            
       if(measuredField == -1) {
         	$('#f_'+name+'_sessionInfo_'+ses+'_color_bar')
         		.css({'background-color' : colorsToUse[sessionColor++],
         			      'border-width' : 'thin',
         			      'border-color' : 'black'});
         		}
       else {
         	$('#f_'+name+'_sessionInfo_'+ses+'_color_bar')
         			.css({'border-width' : 'thin',
         			      'border-color' : 'black'});
         		    $('#f_'+name+'_sessionInfo_'+ses+'_color_bar')
         			.html(String.fromCharCode(65 + sessionColor));
         		    ++sessionColor;
      }
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
		      
		      if (field[0] == "recipe") {
		        selectedGenerator = value;
		      } else if (field[0] == "skip") {
		        pointSkip = parseInt(value);
		      } else if (field[0] == "field") {
		        measuredField = value;
		        if (measuredField == -1) {
      	      options.useFilledMarkers = false;
      	    }
      	    else {
      	      options.useFilledMarkers = true;
      	    }
		      } else if (field[0] == "session" && field[1] == "select") {
		        sessions[field[2]]['visible'] = true;
		      } else if (field[0] == "session" && field[2] == "point" && field[3] == "count") {
		        if (selectedGenerator == 0) {
		          pointCount[field[1]] = parseInt(value);
		        } else {
		          shownPointsCollapsed[field[1]] = parseInt(value);
		        }
		      } else if (field[0] == "pointstart") {
		        if (selectedGenerator == 0) {
		          pointStart[field[1]] = parseInt(value);
		        } else {
		          cm_pointStart[field[1]] = parseInt(value);
		        }
		      } else if (field[0] == "sort" && field[1] == "collapsed") {
		        sortByHighestDensity = true;
		      }
		    }
    }

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

     this.eh_saveState = function() {
       var serial = name+',' + $('#f_'+name+'_form').serialize().replace(/&/g, ",").replace(/=/g, ":");
       for (var ses in sessions) {
         serial = serial + ',i_' + name + '_pointstart_' + ses + ':'
         if (selectedGenerator == 0) {
           serial = serial + pointStart[ses];
         } else {
           serial = serial + cm_pointStart[ses];
           
         }
       }
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
    this.eh_selectMeasuredField = function() {
      measuredField = parseInt(findInputElement("f_" + name + "_form", "i_"+name+"_field_select").value);
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
	    pointSkip = sp;
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
	    var endPoint = pointStart[ses] + pointCount[ses];
	    var oflow = endPoint - isenseData.sessions[ses]['data'][0].length;
	    if(oflow > 0) {
	      pointStart[ses] -= (oflow);
	    }
	    this.redraw();
    }

    this.eh_modifySlide = function(ses, val) {
      if( pointStart[ses] + val < 0 || pointStart[ses] + val > isenseData.sessions[ses]['data'][0].length  )
	return;
      else {
        pointStart[ses] += val;
        var endPoint = pointStart[ses] + pointCount[ses];
        var oflow = endPoint - isenseData.sessions[ses]['data'][0].length;
        if(oflow > 0) {
          pointStart[ses] -= (oflow);
        }
      }

      this.redraw();
    }

    this.eh_modifySlideCm = function(ses, val) {
      if( cm_pointStart[ses] + val < 0 || cm_pointStart[ses] + val > maxPointsCollapsed[ses] - 1 )
	return;
      else {
        cm_pointStart[ses] += val;
        var endPoint = cm_pointStart[ses] + pointCount[ses];
        var oflow = endPoint - isenseData.sessions[ses]['data'][0].length;
        if(oflow > 0) {
          pointStart[ses] -= (oflow);
        }
      }

      this.redraw();
    }
    
    //
    //
    //
    this.eh_cm_movePointEnd = function(ses, val) {
	    var endPoint = cm_pointStart[ses] + shownPointsCollapsed[ses];
	    var oflow = endPoint - maxPointsCollapsed[ses];
	    if(oflow > 0) {
	      cm_pointStart[ses] -= (oflow);
	    }
    }
    
    //
    //
    //
    this.eh_movePointTick = function(ses, val) {
	    pointStart[ses] = val;
	
	    $('#f_'+name+'_session_'+ses+'_interval_info').empty().append('Will display points '+(pointStart[ses]+1)+' through '+(pointStart[ses] + pointCount[ses]));
    }

    //
    //
    //
    this.eh_cm_movePointTick = function(ses, val) {
	    cm_pointStart[ses] = val;
	
	    $('#f_'+name+'_session_'+ses+'_interval_info').empty().append('Will display points '+(cm_pointStart[ses]+1)+' through '+(cm_pointStart[ses] + shownPointsCollapsed[ses]+1));
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
	    
	    var f_lat = parseFloat(isenseData.sessions[session_id]['data'][F_LAT][dp].value);
	    var f_lon = parseFloat(isenseData.sessions[session_id]['data'][F_LON][dp].value);
	
	    while((isNaN(f_lat) || isNaN(f_lon)) || f_lat == 200 || f_lon == 200) {
	      if(dp < isenseData.sessions[session_id]['data'][F_LAT].length - 1) {
		      ++dp;
		      f_lat = parseFloat(isenseData.sessions[session_id]['data'][F_LAT][dp].value);
    	    f_lon = parseFloat(isenseData.sessions[session_id]['data'][F_LON][dp].value);
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
	      this.createSessionInfoPane();
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

	      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form', name : 'f_'+name+'_form', action : "javascript:function() {return false;}"  }, [
		      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
		        'tr', {}, [
		          'td', { colSpan : 2, style : 'width:100%;text-align:center;' }, 'Map'],
		        'tr', {}, [
		          'td', {colSpan : 2, style : 'text-align:center'}, [
	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn }, []]],
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
		          'td', { colSpan :3 }, [
		            'select', { id : 'i_'+name+'_field_select', name : 'i_'+name+'_field_select', style : 'width:100%'}, [
		              'option', { id : 'i_'+name+'_field_select_-1', value : -1, select:'selected'}, 'No Measurement']]]]]);

    if(!$('#profile_link').length > 0) {
	$('#i_vis_Map_save_state').hide();
    }


	      table = $('#f_'+name+'_table');
	    
	      $('#i_'+name+'_recipe_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_selectRecipe();
			      return false;
		      });
		   
		    $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
		      function(evt, obj) {
		        evt.data.scope.eh_saveState();
		        return false;
	        });
		    
	      //
	      // Input to scale number of points displayed (skip points)
	      //
	      $('#f_'+name+'_skip_point_row').append('Use ');
	      
	      $('#f_'+name+'_skip_point_row').createAppend(
	        'select', { id : 'i_'+name+'_skip_point_select',
			      name : 'i_'+name+'_skip_point_select'}, [
		        'option', { id : 'i_'+name+'_skip_opt10', value : 10 }, '10%',
		        'option', { id : 'i_'+name+'_skip_opt4', value : 4 }, '25%',
		        'option', { id : 'i_'+name+'_skip_opt2', value : 2 }, '50%',
		        'option', { id : 'i_'+name+'_skip_opt1', value : 1 }, '100%' ]);
		        
	      $('#f_'+name+'_skip_point_row').append('of data points');
	    
	      $('#i_'+name+'_skip_opt'+pointSkip).attr('selected','selected');
	    
	      $('#i_'+name+'_skip_point_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_skipPointSelect();
			      return false;
		      });

	      //
	      // Add fields to select which will be measured (including no measurement)
	      //
	      //
	      for(var fld = 0; fld < isenseData.fields['count']; ++fld) {
	        $('#i_'+name+'_field_select').createAppend('option', {id : 'i_'+name+'_field_select_'+fld, value : fld}, isenseData.fields['title'][fld]+' ('+isenseData.fields['units'][fld]+')');
	      }
	    
	      $('#i_'+name+'_field_select_'+measuredField).attr('selected','selected');
	    
	      $('#i_'+name+'_field_select').bind('change', { scope : thisModule },
  			  function(evt, obj) {
  			    evt.data.scope.eh_selectMeasuredField();
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
		          'td', {}, [
		            'input', { type : 'checkbox',
			            id : 'i_'+name+'_session_select_'+ses,
			            name : 'i_'+name+'_session_select_'+ses }, []],
		          'td', { }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
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

		      table.createAppend(
		        'tr', {}, [
		          'td', { id : 'f_'+name+'_session_'+ses+'_color_bar' }, [],
		          'td', { id : 'f_'+name+'_session_'+ses+'_info', colSpan : 2}, []]);
		
		      table.createAppend(
		        'tr', {}, [
		          'td', {}, [],
		            'td', { id : 'f_'+name+'_session_'+ses+'_point_choice', colSpan : 2}, []]);
		
		      table.createAppend(
		        'tr', {}, [
		          'td', {}, [],
		          'td', { id : 'f_'+name+'_session_'+ses+'_interval_info', name : 'f_'+name+'_session_'+ses+'_interval_info',
			          colSpan : 2 }, 'Displaying from point '+(pointStart[ses]+1)+' to '+(pointStart[ses] + (pointCount[ses]))]);
		
		    table.createAppend(
		      'tr', {}, [
		        'td', {}, [
			  'input', { type : 'button', id : 'i_'+name+'_'+ses+'_slide_left', value : '<' }, []],
		        'td', {  }, [
		          'div', { id : 'i_'+name+'_session_'+ses+'_move_slider', style : "margin-left:.6em; margin-right:.6em;" }, []],
		        'td', {}, [
			  'input', { type : 'button', id : 'i_'+name+'_'+ses+'_slide_right', value : '>' }, []]]);

		    $('#i_'+name+'_'+ses+'_slide_right').bind( 'click', { scope : thisModule, session : ses }, 
		      function(evt, obj) {
		        evt.data.scope.eh_modifySlide(evt.data.session, 1);
			});

		    $('#i_'+name+'_'+ses+'_slide_left').bind( 'click', { scope : thisModule, session : ses }, 
		      function(evt, obj) {
		        evt.data.scope.eh_modifySlide(evt.data.session, -1);
			});

		      //
		      // Vertical bar filled with same color as associated map markers,
		      // or the correct 'tag' number
		      //
		      if(measuredField == -1) {
		        $('#f_'+name+'_session_'+ses+'_color_bar').css({'background-color' : colorsToUse[sessionColor++],
			        'border-width' : 'thin',
			        'border-color' : 'black'});
		      } else {
		        $('#f_'+name+'_session_'+ses+'_color_bar').css({'border-width' : 'thin', 'border-color' : 'black'});
		        $('#f_'+name+'_session_'+ses+'_color_bar').html(String.fromCharCode(65 + sessionColor));
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
		
		      $('#i_'+name+'_session_'+ses+'_point_count').bind('change',{ scope:thisModule, session:ses },
			      function(evt, obj) {
			        evt.data.scope.eh_enterPointCount(evt.data.session);
			        return false;
			      });

		      //
		      // Add total point information
		      //
		      $('#f_'+name+'_session_'+ses+'_info').append('Data points: '+sessions[ses]['data'][0].length);

		      //
		      // Create a slider to control the interval of points displayed
		      //
		      var peeklen = sessions[ses]['data'][0].length;
		      var max_clicks = Math.max(peeklen - pointCount[ses] + 1, 0);
		
		      $('#i_'+name+'_session_'+ses+'_move_slider').slider({ min:0, 
			      max:(max_clicks-1),
			      value:pointStart[ses], 
			      steps:max_clicks});
		
		      $('#i_'+name+'_session_'+ses+'_move_slider').bind('slidestop', { scope : thisModule, session : ses },
			      function(evt, obj) {
			        evt.data.scope.eh_movePointEnd(evt.data.session, obj.value);
			      });
		
		      $('#i_'+name+'_session_'+ses+'_move_slider').bind('slide', { scope : thisModule, session : ses },
			      function(evt, obj) {
			        evt.data.scope.eh_movePointTick(evt.data.session, obj.value);
			      });
	      }
	    };
	
	    legendGenerator[1] = function() {
	      var table;
	      var sessionColor = 0;
	    
	      controlPane.empty();
	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form', name : 'f_'+name+'_form',action : "javascript:function() {return false;}" }, [
		      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%;' }, [
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-align:center;' }, 'Map'],
		        'tr', {}, [
	            'td', {colSpan : 2, style : 'text-align:center'}, [
	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : 'Save Vis'}, []]],
		        'tr', {}, [
		          'td', { colSpan : 3 }, [
		            'select', { id : 'i_'+name+'_recipe_select', name : 'i_'+name+'_recipe_select', style : 'width:100%' }, [
			          'option', { value : 0 }, 'Individual Points',
			          'option', { value : 1, selected:'selected' }, 'Average Point']]],
		        'tr', {}, [
		          'td', { id : 'f_'+name+'_skip_point_row', colSpan : 3 }, ''],
		        'tr', {}, [
		          'td', { style : "width:16px" }, [
		            'input', { type : 'checkbox', id : 'i_'+name+'_sort_collapsed', name : 'i_'+name+'_sort_collapsed' }, []],
		          'td', { }, 'Show most collapsed points first'],
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-decoration:underline;' }, 'Measured Field'],
		        'tr', {}, [
		          'td', { colSpan :3 }, [
		            'select', { id : 'i_'+name+'_field_select', name : 'i_'+name+'_field_select', style : 'width:100%'}, [
		            'option', { id : 'i_'+name+'_field_select_-1', value : -1, select:'selected'}, 'No Measurement']]]]]);

	      table = $('#f_'+name+'_table');

	      $('#i_'+name+'_recipe_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_selectRecipe();
			      return false;
		      });

        $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
  		    function(evt, obj) {
  		      evt.data.scope.eh_saveState();
  		      return false;
  	      });

	      //
	      // Control for sorting by density of collapse points
	      //
	      $('#i_'+name+'_sort_collapsed').bind('click', { scope:thisModule },
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
	      $('#f_'+name+'_skip_point_row').createAppend(
	        'select', { id : 'i_'+name+'_skip_point_select', name : 'i_'+name+'_skip_point_select'}, [
				    'option', { id : 'i_'+name+'_skip_opt1', value : 1 }, '100%', 
				    'option', { id : 'i_'+name+'_skip_opt2', value : 2 }, '50%',
				    'option', { id : 'i_'+name+'_skip_opt4', value : 4 }, '25%',
				    'option', { id : 'i_'+name+'_skip_opt10', value : 10 }, '10%']);
	    
	      $('#f_'+name+'_skip_point_row').append('of data points');
	    
	      $('#i_'+name+'_skip_opt'+pointSkip).attr('selected','selected');
	      $('#i_'+name+'_skip_point_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      evt.data.scope.eh_skipPointSelect();
			      return false;
		      });
	    
	      //
 	      // Add fields to select which will be measured (including no measurement)
 	      //
 	      for(var fld = 0; fld < isenseData.fields['count']; ++fld) {
 	        $('#i_'+name+'_field_select').createAppend('option', {id : 'i_'+name+'_field_select_'+fld, value : fld}, isenseData.fields['title'][fld]+' ('+isenseData.fields['units'][fld]+')');
 	      }

 	      $('#i_'+name+'_field_select_'+measuredField).attr('selected','selected');

 	      $('#i_'+name+'_field_select').bind('change', { scope : thisModule },
   			  function(evt, obj) {
   			    evt.data.scope.eh_selectMeasuredField();
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
		          'td', {}, [
		            'input', { type : 'checkbox', id : 'i_'+name+'_session_select_'+ses, name : 'i_'+name+'_session_select_'+ses }, []],
		          'td', { }, sessions[ses]['id']+' - '+sessions[ses]['title']]);
		
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

		    table.createAppend(
		      'tr', {}, [
		        'td', { id : 'f_'+name+'_session_'+ses+'_color_bar'}, [],
		        'td', { id : 'f_'+name+'_session_'+ses+'_info', colSpan : 2}, []]);
		
		    table.createAppend(
		      'tr', {}, [
		        'td', {}, [],
		        'td', { id : 'f_'+name+'_session_'+ses+'_point_choice', colSpan : 2 }, []]);
		
		    table.createAppend(
		      'tr', {}, [
		        'td', {}, [],
		        'td', { id : 'f_'+name+'_session_'+ses+'_interval_info',
			        colSpan : 2 }, 'Displaying from point '+(cm_pointStart[ses]+1)+' to '+(cm_pointStart[ses] + shownPointsCollapsed[ses] + 1)]);
		
		    table.createAppend(
		      'tr', {}, [
		        'td', {}, [
			  'input', { type : 'button', id : 'i_'+name+'_'+ses+'_slide_left', value : '<' }, []],
		        'td', { colspan : 2 }, [
		          'div', { id : 'i_'+name+'_session_'+ses+'_move_slider', style : "margin-left:.6em; margin-right:.6em;" }, []],
		        'td', {}, [
			  'input', { type : 'button', id : 'i_'+name+'_'+ses+'_slide_right', value : '>' }, []]]);

		    $('#i_'+name+'_'+ses+'_slide_right').bind( 'click', { scope : thisModule, session : ses }, 
		      function(evt, obj) {
		        evt.data.scope.eh_modifySlideCm(evt.data.session, 1);
			});

		    $('#i_'+name+'_'+ses+'_slide_left').bind( 'click', { scope : thisModule, session : ses }, 
		      function(evt, obj) {
		        evt.data.scope.eh_modifySlideCm(evt.data.session, -1);
			});
		
		    //
		    // Vertical bar filled with same color as associated map markers,
		    // or the correct 'tag' number
		    //
		    if(measuredField == -1) {
		      $('#f_'+name+'_session_'+ses+'_color_bar').css({'background-color' : colorsToUse[sessionColor++],
			      'border-width' : 'thin',
			      'border-color' : 'black'});
		    } else {
		      $('#f_'+name+'_session_'+ses+'_color_bar').css({'border-width' : 'thin', 'border-color' : 'black'});
		      $('#f_'+name+'_session_'+ses+'_color_bar').html(String.fromCharCode(65 + sessionColor));
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
				    size : 4, maxlength : 4, value : shownPointsCollapsed[ses]}, []);
		
		    $('#f_'+name+'_session_'+ses+'_point_choice').append(' points');
		
		    $('#i_'+name+'_session_'+ses+'_point_count').bind('change',{ scope:thisModule, session:ses },
			    function(evt, obj) {
			      evt.data.scope.eh_cm_enterPointCount(evt.data.session);
			      return false;
			    });

		    //
		    // Add total point information
		    //
		    $('#f_'+name+'_session_'+ses+'_info').append('Total point count: '+maxPointsCollapsed[ses]);
		
		    //
		    // Create a slider to control the interval of points displayed
		    //
		    $('#i_'+name+'_session_'+ses+'_move_slider').slider({ min:0, 
			      max:(maxPointsCollapsed[ses] - shownPointsCollapsed[ses]), 
			      value:cm_pointStart[ses], 
			      steps:(maxPointsCollapsed[ses] - shownPointsCollapsed[ses])});
		
		    $('#i_'+name+'_session_'+ses+'_move_slider').bind('slidestop', { scope : thisModule, session : ses },
			    function(evt, obj) {
			      evt.data.scope.eh_cm_movePointEnd(evt.data.session, obj.value);
			    });
		
		    $('#i_'+name+'_session_'+ses+'_move_slider').bind('slide', { scope : thisModule, session : ses },
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
		      var limit = pointCount[ses];
          
		      datatable.addRows(Math.ceil(limit/pointSkip));
		      		      
		      for(var i = 0; i < limit; i += pointSkip) {
		        if(custom) {
		          datatable.setValue(addednow+totadded,0,ses);
	          }

	          var dataStr = "Session #" + sessions[ses]['id'] + ", Datapoint #" + (i+pos+1) + "<br/>";
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

			          datatable.setValue(addednow+totadded,colsel, parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
			        } else if(isenseData.fields['unitType'][j] == FIELD_TYPE.GEO_LON) {
			          
			          // Longitude
			          if(custom) {
				          colsel = 2;
			          } else { 
				          colsel = 1;
			          }
			          datatable.setValue(addednow+totadded,colsel, parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
			        } else if(isenseData.fields['sensor'][j] == FIELD_TYPE.TIME) {
			          // Time
			          var curTime = Date.parse(isenseData.sessions[ses]['data'][j][pos+i].value)
			    
			          if (isNaN(curTime)) {
			            curTime = parseInt(isenseData.sessions[ses]['data'][j][pos+i].value);
			            curTime = curTime * 1000;
			          }
			    
			          var tzOffset = (new Date().getTimezoneOffset()) - (new Date(curTime).getTimezoneOffset());
			          tzOffset *= 60000;
			          var convertedTime = curTime + tzOffset;
			          dataStr += "Time: " + new Date(convertedTime) + "<br/>";
			        } else {
			          // Other Data
			          if(custom) {
				          if(j == measuredField/*[ses]*/) {
				            measureStr = "Measured Field:<br/>" + isenseData.fields["title"][j] + ": "
					            + isenseData.sessions[ses]['data'][j][pos+i].value + " " 
					            + isenseData.fields['units'][j] + "<br/>";
				    
				              datatable.setValue(addednow+totadded,4, parseFloat(isenseData.sessions[ses]['data'][j][pos+i].value));
				          } else {
				            dataStr += isenseData.fields['title'][j] + ": " 
					            + isenseData.sessions[ses]['data'][j][pos+i].value + " "
					            + isenseData.fields['units'][j] + "<br/>";
				          }
			          } else {
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
  		    } else if(isenseData.fields['unitType'][fld] == FIELD_TYPE.GEO_LON && !lonField) {
  		      lonField = fld;
  		    } else if(isenseData.fields['sensor'][fld] == FIELD_TYPE.TIME && !timeField) {
  		      timeField = fld;
  		    } else {
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
  		      if(n_lat <= cur_lat + 0.00005 && n_lat >= cur_lat - 0.00005 && n_lon <= cur_lon + 0.00005 && n_lon >= cur_lon - 0.00005) {
  			      ++cur_count;
			
  			      for(var c = 0; c < numberFields.length; ++c) {
  			        var cfld = numberFields[c];
  			        cur_datAccum[cfld].push(parseFloat(isenseData.sessions[ses]['data'][cfld][dp].value));
  			      }
  		      } else {
  		        //
    		      // Otherwise, add the currently accumulated point into the array of collapsed points
    		      // and start on the next accumulated point.
    		      //
    		      
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
  			        } else {
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
  			        if(n_lat <= com_array[ac].lat + 0.00005 && n_lat >= com_array[ac].lon - 0.00005 && n_lon <= com_array[ac].lon + 0.00005 && n_lon >= com_array[ac].lon - 0.00005) {
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
  			      } else { 
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
  			        } else if(!isFinite(cacc + elem.data[cfld][0])) {
  				        if(ccnt < 1) {
  				          //
  				          // A single value is astronomically high, ignore it.
  				          //
  				          elem.data[cfld].shift();
  				        } else {
  				          //
  				          // Since adding one more would overflow, take the current average
  				          // and push it back into the array of data.
  				          //
  				          cacc = cacc / ccnt;
  				          elem.data[cfld].push(cacc.toFixed(2));
  				          cacc = 0;
  				          ccnt = 0;
  				        }
  			        } else {
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
  				        + isenseData.fields["title"][cfld] + ": " + cacc.toFixed(2) + " " 
  				        + isenseData.fields['units'][cfld] + "<br/>";
  			      } else {
  			        com_text += isenseData.fields['title'][cfld] + ": " + cacc.toFixed(2)
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
	    visObject = new isenseMap(viewPane);
	
	    //
	    // Check Google options to ensure good defaults exist
	    //
	    options = { enableScrollWheel : false,
		    showTip : true,
		    showLines : false };

      for(var ses in sessions) {
  	    this.registerSession(ses);
  	  }
	    this.generateMetaData();
	    this.parseSavedState();
	    this.createTableGenerators();
	    this.createLegendGenerators();
	    this.createSessionInfoPane();
    }
    
    thisModule = this;
}
