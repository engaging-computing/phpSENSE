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
    var infoPane;
    var width_px;
    var height_px;
    var thisModule;
    var firstchecked = true ;
    var op = this ;
    var svbutn;

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
    
	this.liveUpdateCallback = function(data) {
		if (data == null) return;
		var gotData = false;
		var temp = eval(data);
		console.log(temp);
		//temp = temp['data'];
		var s = temp.data.sid;
		var mydata = temp.data.update;
		console.log(mydata);
		if(mydata != null) {
		    console.log('Got here?');
			if (mydata.length != 0) {
			    console.log('Got here???');
				for (var i = 0; i < isenseData.fields['count']; i++) {
				    console.log('Got here????');
					//isenseData.sessions[s]['data'][i] = new Array(mydata.length-1);
					//for(j = 0; j < mydata.length; ++j) {
					    console.log(s);
					    console.log(isenseData.sessions[s]);
						for (var j = isenseData.sessions[s].data[i].length; j < mydata.length; j++) {
						    console.log('Got here??????');
							isenseData.sessions[s]['data'][i][j] = { value : mydata[j][i], row : j };
							gotData = true;
						}
						
			          //isenseData.sessions[s]['data'][i][j] = { value : mydata[j][i], row : j };
			        //}
				}
				console.log(gotData);
				if (gotData) {
					gotData = false;
					tableGenerator[selectedGenerator]();
					visObject.draw(datatable, options);
				}
			}
		}
	};
	
	this.liveUpdate = function() {
		var gotData
		var timeFld;

		for (var i = 0; i < isenseData.fields['count']; i++) {
			if (isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
				timeFld = i;
				break;
			}
		}
		
		for (var s in sessions) {
			if (sessions[s]['visible'] == 0 ) continue; 
		//	console.log(isenseData.sessions[s]['data'][timeFld]);
			var url = 'http://isense.cs.uml.edu/ws/api.php?method=getDataSince&sid=' + s + '&eid=' + isenseData.experiment_id + '&since=' + isenseData.sessions[s]['data'][timeFld][isenseData.sessions[s]['data'][timeFld].length - 1].value;
			console.log(isenseData.sessions[s]['data'][timeFld][isenseData.sessions[s]['data'][timeFld].length - 1].value);
			console.log(url);
			$.ajax({
				url:url,
				dataType:'json', 
				success: this.liveUpdateCallback
			});
		}
	}
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
		      
		      if (field[0] == "recipe") {
		        selectedGenerator = value;
		      } else if (field[0] == "field") {
		        fieldVisible[field[1]] = true;
		      } else if (field[0] == "session") {
		        sessions[field[1]]['visible'] = 1;
		      } else if (field[0] == "ts" && field[1] == "unit") {
		        if (isNaN(value)) {
		          offsetSliderScale[field[3]] = 0;
		        } else {
		          offsetSliderScale[field[3]] = parseInt(value);
		        }
		        
		        offsetSliderLocation[field[3]] = 0;
		      } else if (field[0] == "startTimeOffset") {
		        startTimeOffset[field[1]] = parseInt(value);
		      } else if (field[0] == "offsetSliderLocation") {
		        offsetSliderLocation[field[1]] = parseInt(value);
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
      
      for (var ses in sessions) {
        serial = serial + "," + 'i_' + name + '_startTimeOffset_' + ses + ":" + startTimeOffset[ses];
        serial = serial + "," + 'i_' + name + '_offsetSliderLocation_' + ses + ":" + offsetSliderLocation[ses];
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

    this.eh_toggleField = function(fld) {
	    fieldVisible[fld] = !fieldVisible[fld];
	    //this.redraw();
    }
  
	this.eh_toggleCheckbox = function(place) {
    	if( $(place+':checked').val() == on ){
      		$(place).removeAttr('checked');
    	} else {
      		$(place).attr('checked', 'checked');
    	}
  	}

    this.eh_toggleSession = function(ses) {
	    sessions[ses]['visible'] = !sessions[ses]['visible'];
	    //this.redraw();
    }

    this.eh_selectRecipe = function() {
	    var selectedOp = parseInt(findInputElement("f_"+name+"_form",
						   "i_"+name+"_recipe_select").value);
	    switch(selectedOp) {
	      case 0:
	      case 1:
	        selectedGenerator = selectedOp;
	        //this.redraw();
	        break;
	      default:
	        break;
	    }
    }

    this.eh_tsReset = function(ses) {
	    offsetSliderLocation[ses] = 0;
	    startTimeOffset[ses] = 0;
	    //this.redraw();
    }
    
    this.eh_tsUnitSelect = function(ses) {
	    var selectedVal = parseInt(findInputElement("f_"+name+"_form",
						    "i_"+name+"_ts_unit_select_"+ses).value);
	    
	    if(isNaN(selectedVal)) {
	      selectedVal = 0;
	    }
	
	    offsetSliderScale[ses] = selectedVal;
	    offsetSliderLocation[ses] = 0;
	    //this.redraw();
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
	    //this.redraw();
    }
    /* --- */
    
    /*
     * Setup meta data for session.
     */
    this.registerSession = function(session_id) {
      if (stateObject[0] != name) {
	      startTimeOffset[session_id]      = 0;
	      offsetSliderLocation[session_id] = 0;
	      offsetSliderScale[session_id]    = 1;
	    }
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
	      	this.createSessionInfoPane();
	      	//visObject = new google.visualization.AnnotatedTimeLine(viewPane);
	      	visObject.draw(datatable, options);
		  	setInterval('this.liveUpdate()', 1000);
	      	return true;
	    } catch(e) {
	      	alert(e);
	      	return false;
	    }
    }

    this.clean = function() { }

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

	      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form' }, [
		      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
		        'tr', {}, [
		          'td', { style : 'text-align:center;width:100%' , colSpan : 3 }, 'Timeline'],
		        'tr', {}, [
              'td', {colSpan : 2, style : 'text-align:center'}, [
                'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn}, []]],		        
            'tr', {}, [
		          'td', { colSpan : 3 }, [
		          'select', { id : 'i_'+name+'_recipe_select', name : 'i_'+name+'_recipe_select', style : 'width:100%' }, [
		            'option', { id : 'i_'+name+'_recipe_opt_1', value : 1 }, 'Show Exact Time',
		            'option', { id : 'i_'+name+'_recipe_opt_0', value : 0 }, 'Side-by-Side Comparison']]],
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-decoration:underline;' }, ['Fields']],
		    'tr', {}, [
		      'td', { colSpan : 3, style : 'text-align:center; width:100%' }, [
			 'input', { type : 'button', value : 'Reload', id: 'ReloadTimeLine' }, []]]]] );

	      $('#ReloadTimeLine').bind( 'click', function() { thisModule.redraw(); });

    	if(!$('#profile_link').length > 0) {
			$('#i_vis_Timeline_save_state').hide();
    	}

	  	table = $('#f_'+name+'_table');

	      //
	      // Setup repice selection, which determines which datatable generator to use.
	      //
	      $('#i_'+name+'_recipe_opt_'+selectedGenerator).attr('selected','selected');	    
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
	      // Field Selection
	      //
	      for(var i = 0; i < isenseData.fields['count']; ++i) {		
		      if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME || isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
		        continue;
		      }

		      table.createAppend(
		        'tr', {}, [
		          'td', { style : 'width:16px' }, [
		            'input', { type : 'checkbox',
			            id : 'i_'+name+'_field_'+i+'_select',
			            name : 'i_'+name+'_field_'+i+'_select'}],
		          'td', { columnspan : 2 }, isenseData.fields['title'][i]+' ('+isenseData.fields['units'][i]+')']);

		      if(fieldVisible[i]) {
		        $('#i_'+name+'_field_'+i+'_select').attr('checked','checked');
		      }
		
		      $('#i_'+name+'_field_'+i+'_select').bind('click', { scope : thisModule, field : i },
			      function(evt, obj) {
			        evt.data.scope.eh_toggleField(evt.data.field);
			      	evt.data.scope.eh_toggleCheckbox(this);
			        return false;
			      });
	        }



	        table.createAppend('tr', {}, ['td', { colSpan : "3", style : 'text-decoration:underline;' }, 'Sessions']);

	        //
	        // Session Selection
	        //
	        for(var ses in sessions) {
		        table.createAppend(
		          'tr', {}, [
		            'td', {}, [
		              'input', { type : 'checkbox',
			              id : 'i_'+name+'_session_'+ses+'_select',
			              name : 'i_'+name+'_session_'+ses+'_select'}, []],
		            'td', { colSpan : "2" }, isenseData.sessions[ses]['id']+' - '+isenseData.sessions[ses]['title']]);
		
		        if(isenseData.sessions[ses]['visible']) {
		          $('#i_'+name+'_session_'+ses+'_select').attr('checked','checked');
		
		          //
		          // Field color distinction
		          //
		          var j = 1;
		          for(i = 0; i < isenseData.fields['count'];  ++i) {
			          if(isenseData.fields['sensor'][i] == FIELD_TYPE.TIME || isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL) {
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

		          //
		          // Session start time offset slider - only works in sync'd start mode
		          // (only if there are more than 1 session)
		          if(sessions.length > 1 && selectedGenerator == 0) {
			          table.createAppend(
			            'tr', {}, [
			              'td', {}, [],
			              'td', {}, "Time shift:",
			              'td', {}, [
				            'span', { id : 'i_'+name+'_ts_offset_disp'+ses}, millisToClockString(startTimeOffset[ses])]]);
			          table.createAppend(
			            'tr', {}, [
			              'td', {}, [],
			              'td', { colSpan : 2 }, [
			              'div', { id : 'i_'+name+'_ts_slider_'+ses, name : 'i_'+name+'_ts_slider_'+ses}, []]]);
			          table.createAppend(
			            'tr', {}, [
			              'td', {}, [],
			              'td', { colSpan : 2 }, [
			                'input', { type : 'button', id : 'i_'+name+'_ts_reset_'+ses, value : 'Reset' }, [],
				              'select', { id : 'i_'+name+'_ts_unit_select_'+ses, name : 'i_'+name+'_ts_unit_select_'+ses }, [
				                'option', { id : 'i_'+name+'_ts_unit_opt_sec_'+ses, value : 1 }, 'Seconds',
				                'option', { id : 'i_'+name+'_ts_unit_opt_min_'+ses, value : 60 }, 'Minutes',
				                'option', { id : 'i_'+name+'_ts_unit_opt_hour_'+ses, value : 3600 }, 'Hours']]]);
		    
			          if(offsetSliderScale[ses] == 1) {
			            $('#i_'+name+'_ts_unit_opt_sec_'+ses).attr('selected','selected');
			          } else if(offsetSliderScale[ses] == 60) {
			            $('#i_'+name+'_ts_unit_opt_min_'+ses).attr('selected','selected');
			          } else if(offsetSliderScale[ses] == 3600) {
			            $('#i_'+name+'_ts_unit_opt_hour_'+ses).attr('selected','selected');
			          }

			          $('#i_'+name+'_ts_reset_'+ses).bind('click', { scope : thisModule, session:ses },
				          function(evt, obj) {
				            evt.data.scope.eh_tsReset(evt.data.session);
				            return false;
				          });
			
			          $('#i_'+name+'_ts_unit_select_'+ses).bind('select', { scope : thisModule, session:ses },
				          function(evt, obj) {
				            evt.data.scope.eh_tsUnitSelect(evt.data.session);
				            return false;
				          });
			      
			          $('#i_'+name+'_ts_slider_'+ses).slider({ max:60, min:-60, value:0, steps:120 });
			
			          $('#i_'+name+'_ts_slider_'+ses).bind('slidestop', { scope : thisModule, session : ses },
				          function(evt, obj) {
				            evt.data.scope.eh_tsSlideEnd(evt.data.session, obj.value);
				          });
			      
			          $('#i_'+name+'_ts_slider_'+ses).bind('slide', { scope : thisModule, session : ses },
				          function(evt, obj) {
				            evt.data.scope.eh_tsSlideTick(evt.data.session, obj.value);
				          });
		          }
		        }
		
		        $('#i_'+name+'_session_'+ses+'_select').bind('click', { scope:thisModule, session:ses },
			        function(evt, obj) {
			          evt.data.scope.eh_toggleSession(evt.data.session);
			      	  evt.data.scope.eh_toggleCheckbox(this);
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
			        
			        if(earliestTimestamp == -1 || earliestTimestamp > nextdata) {
			          earliestTimestamp = nextdata;
			        }
		        } else if(isenseData.fields['sensor'][j] != FIELD_TYPE.GEOSPACIAL && fieldVisible[j]) {
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
		        if(!sessions[i].visible || sessionDataPoint[i] >= isenseData.sessions[i]['data'][timeField].length) {
			        continue;
		        }
		        keepAddingPoints = true;
		        var sesTimestamp = earliestTimestamp + startTimeOffset[i] + getTimeData(i,timeField,sessionDataPoint[i]) - getTimeData(i,timeField,0);
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
		        if(nextTimestamp == -1 || nextTimestamp > sesTimestamp) {
			        delete sessionsToAdd;
			        sessionsToAdd = [];
			        sessionsToAdd.push(i);
			        nextTimestamp = sesTimestamp;
		        } else if(nextTimestamp == sesTimestamp) {
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
			          datatable.setValue(row, sessionColumnStart[ses] + fldadd, parseFloat(isenseData.sessions[ses]['data'][j][sessionDataPoint[ses]]));
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
			        datatable.addColumn('number','No Data - Session ' + ses + ' only added ' + fldadd + ' fields out of ' + sessionFieldCount[ses]);
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
			        } else if(isenseData.sessions[i]['data'][j].length == 1) {
			          sessionIntervalLength[i] = 1000;
			    
			          if((first_t + startTimeOffset[i]) < startTime || startTime == -1) {
				          startTime = (first_t + startTimeOffset[i]);
			          }
			    
			          if((first_t + startTimeOffset[i]) > latestStartTime) {
				          latestStartTime = first_t + startTimeOffset[i];
			          }
			        }
		        } else if(isenseData.fields['sensor'][j] != FIELD_TYPE.GEOSPACIAL) {
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
	      } else if(startTime == -1) {
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
		        if(fieldVisible[j] && (isenseData.sessions[i]['data'][j].length * sessionIntervalLength[i]) + largestIntervalOffset > maxLength) {
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
		        } else {  
			        for(var k = 0; k < isenseData.fields['count']; k++) {
			          var sensorType = isenseData.fields['sensor'][k];
			        
			          if(fieldVisible[k] && sensorType != FIELD_TYPE.TIME && sensorType != FIELD_TYPE.GEOSPACIAL) {
				          if(isenseData.sessions[j]['data'][k].length > (i - startIntervalOffset[j]) && currentInterval[j] <= sessionIntervalLength[j]) {
				            datatable.setValue(sessionRowPosition[j], cpos, parseFloat(isenseData.sessions[j]['data'][k][(i-startIntervalOffset[j])].value));
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
		        } else if(fieldVisible[j] && sensorType != FIELD_TYPE.GEOSPACIAL) {
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
				            } else if(new_ts == early_ts){
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
	    $('#'+name).createAppend('div', { id : name+'_infopane' }, []);
	
	    $('#'+name+'_viewpane').css({'float':'left','width':'650px','height':'600px','margin-right':'4px'});
	    $('#'+name+'_cntlpane').css({'float':'right','width':'180px','height':'600px','overflow-y':'auto','position':'relative'});
	    $('#'+name+'_infopane').css({'width':'830px','clear':'both'});
	
	    controlPane = $('#'+name+'_cntlpane');
	    viewPane = document.getElementById(name+'_viewpane');
	    infoPane = $('#'+name+'_infopane');

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
