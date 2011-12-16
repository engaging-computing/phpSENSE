/*
 * TAG HISTOGRAM
 */
function HistogramModule(a_parent, a_name, a_data, a_state) {
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
    var sortCnt		     = 0;
    var selectedGenerator    = 0; 
    var xAxisField           = 0; 
    var yAxisField	     = 0;
    var point;
    var analysisTypes;
    var selectedAnalysisType = new Array();
    var sortData	     = new Array();
    var startData	     = new Array();
    var showData	     = new Array();


    //
    var value 	 	     = new Array();
    var count 		     = new Array();
    var graphData	     = new Array();
    var sortdatalock	     = true	  ;
    var bins		     = 0	  ;
    var binned		     = true	  ;
    var xSel		     = false	  ;
    var drpdnbin	     = 0	  ;
    var defplc               = 0          ;
    var op		     = this	  ;
        
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

       if (stateObject.length <= 2) return;

       //Okay, now we actually want to start going through the state object
	for (var i = 1; i < stateObject.length; i++) {
	var field = stateObject[i].split(":")[0];
	var value = stateObject[i].split(":")[1];

          if (field.indexOf("x") > 0) {
 	    xAxisField = value;
 	  } else if (field.indexOf("_session") > 0) {
	    var se = field.substr(24, field.indexOf("_select") - 24 );
 	    sessions[se]['visible'] = 1;
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

    this.eh_toggleCheckbox = function(place) {
	if( $(place+':checked').val() == on ){
	    $(place).removeAttr('checked');
	} else {
	    $(place).attr('checked', 'checked');
	}
    }

    this.eh_xSelectAxis = function() {
	    var selectedId = parseInt(findInputElement("f_"+name+"_form",
			  "i_"+name+"_x_axis_select").value);
	    xAxisField = selectedId;
    	    sortdatalock = true ;
	    drpdnbin = 0 ;
	    //this.redraw();
    }

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
	    if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL || isenseData.fields['sensor'][i] == FIELD_TYPE.STRING || isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
		        continue;
	    }    
	  xAxisField = i;
 	  break;
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
               
	      if(!drpdnbin)
		drpdnbin = showData.length ;

	      ( IS_ACTIVITY == true ) ? svbutn = 'Save Work' : svbutn = 'Save Vis' ;

	      controlPane.createAppend(
	        'form', { id : 'f_'+name+'_form' }, [
		      'table', { id : 'f_'+name+'_table', style : 'width:100%;font-size:80%' }, [
		        'tr', {}, [
		          'td', { colSpan : 3, style : 'text-align:center;width:100%' }, 'Histogram Chart'],
		        'tr', {}, [
	                  'td', { colSpan : 2, style : 'text-align:center'}, [
	              'input', { type : 'button', id : 'i_'+name+'_save_state', value : svbutn }, []]],
			//'tr', {}, [
			//  'td', { colSpan : 2, style : 'text-align:center' }, [
		      //'input', { type : 'button', id : 'Print', value : 'Print' }, []]],
		        'tr', {}, [
		           'td', { colSpan : 3 }, 'X Axis:'],
		        'tr', {}, [
		          'td', {}, '',
		          'td', { colSpan : 2 }, [
		            'select', { id : 'i_'+name+'_x_axis_select', name : 'i_'+name+'_x_axis_select' }, []]],
			'tr', {}, [
			  'td', { colSpan : 3 }, 'Bins:'],
			'tr', {}, [
			  'td', { colSpan : 3, style : 'text-align:center;width:100%' }, [
			    'select', { id : 'Bins', name : 'Bins' }, [
			      'option', { value : 0 }, 'Default',
			      'option', { value : 3 }, '3 Bins',
			      'option', { value : 5 }, '5 Bins',
			      'option', { value : 10 }, '10 Bins',
			      'option', { value : 20 }, '20 Bins']]],
		    'tr', {}, [
		      'td', { colSpan : 3, style : 'text-align:center; width:100%' }, [
			 'input', { type : 'button', value : 'Reload', id: 'ReloadHist' }, []]]]] );

	      $('#ReloadHist').bind( 'click', function() { thisModule.redraw(); });

    if(!$('#profile_link').length > 0) {
	$('#i_vis_Histogram_save_state').hide();
    }


	      //Align current bins with dropdown menu

	      if( drpdnbin > 3 )
		  defplc = 0 ;
	      if( drpdnbin > 5 )
		  defplc = 1 ;
	      if( drpdnbin > 10 )
		  defplc = 2 ;
	      if( drpdnbin > 20 )
		  defplc = 3 ;


	      if( drpdnbin == 1 )
		$('#Bins option:contains("Default")').text("1 Bin");
	      else if ( drpdnbin < 3 && drpdnbin != 1 ) {
		$('#Bins option:contains("Default")').remove();
		$('#Bins option:eq('+defplc+')').before('<option value="0">'+drpdnbin+' Bins</options>');
	      } else {
		$('#Bins option:contains("Default")').remove();
		$('#Bins option:eq('+defplc+')').after('<option value="0">'+drpdnbin+' Bins</options>');
	      }

	      $('#Bins option').each( function() {
		      if( drpdnbin == $(this).val() )
			  $(this).hide();
		  });
	      

	      $('option[value='+bins+']').attr( 'selected', 'selected' );

	      //End dropdown code

	      table = $('#f_'+name+'_table');

	      $('#Bins option').bind( 'click', function() {
	
		bins = this.value;
		binned = true;
		//thisModule.redraw();

	      });



	
	    
	      $('#i_'+name+'_save_state').bind('click', {scope:thisModule},
  		    function(evt, obj) {
  		      evt.data.scope.eh_saveState();
  		      return false;
  	      });
	    
	      for(var i = 0; i < isenseData.fields['count']; ++i) {		
		      if(isenseData.fields['sensor'][i] == FIELD_TYPE.GEOSPACIAL || isenseData.fields['sensor'][i] == FIELD_TYPE.STRING || isenseData.fields['sensor'][i] == FIELD_TYPE.TIME) {
		        continue;
		      }

		      var sel = "";
		      if(i == xAxisField) {
		        sel = "selected";
		      } 
		      $('#i_'+name+'_x_axis_select').createAppend(
		        'option', { value : i, selected : sel }, isenseData.fields['title'][i]);}


            $('#i_'+name+'_x_axis_select').bind('change', { scope:thisModule },
		      function(evt, obj) {
			      xSel = true ;
			      evt.data.scope.eh_xSelectAxis();
			      return false;
		      });

	  // $('#Print').bind( 'click', function() {
	
	//	window.open('http://www.google.com', 'Print View', 'scrollbars=no', 'toolbar=no', 'location=no', 'dirrectories=no', 'status=no', 'menubar=no', 'copyhistory=no' );

	//	});

	    
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
	
   
           var foundTimestampField = -1;

  	   var xAxisFieldTitle = isenseData.fields['title'][xAxisField] + " ("; 
	    
  	   if(isenseData.fields['sensor'][xAxisField] == FIELD_TYPE.TIME) {
  	      xAxisFieldTitle += "s)";
  	    } else {
  	      xAxisFieldTitle += isenseData.fields['units'][xAxisField] + ")";
  	    }

	   options.titleX = xAxisFieldTitle;	   


	   //Set the Y axis title
	   options.titleY = "Number of Occurrences";

		while( sortData.length > 0 )
			sortData.pop();					
		while( graphData.length > 0 )
			graphData.pop();
		while( showData.length > 0 )
			showData.pop();
		while( count.length > 0 )
			count.pop();
		while( value.length > 0 )
			value.pop();
		

          for (var ses in sessions){
           if (!sessions[ses]['visible'])
           {
               continue;
           }
           
           for(var i = 0; i < isenseData.sessions[ses]['data'][xAxisField].length; i++)
           {
             if(isenseData.fields['sensor'][xAxisField] == FIELD_TYPE.TIME)
             {
                var xNum = getTimeData(ses, xAxisField, i)/1000;

             }
             else
             {
                var xNum = new Number(parseFloat(isenseData.sessions[ses]['data'][xAxisField][i].value));
             }
             if(isenseData.fields['sensor'][yAxisField] == FIELD_TYPE.TIME)
             {
                var yNum = getTimeData(ses, yAxisField, i)/1000;

             }
             else
             {
                var yNum = new Number(parseFloat(isenseData.sessions[ses]['data'][yAxisField][i].value));
             }
       		if( isNaN( xNum ) )
			sortData[sortData.length] = new Array(0, yNum);
		else if( isNaN( yNum ) )
			sortData[sortData.length] = new Array(xNum, 0);
		else
			sortData[sortData.length] = new Array(xNum, yNum);

            }

          }

	while( showData.length > 0 )
		showData.pop() ;




          if (sortData.length == 0)
          {
            datatable.addColumn('number', "");
            return;

          }
		
	//Sort the data
	for (var i = sortData.length - 1 ; i >= 0;  i-- ) {
	for (var j = 0; j < i; j++) {
	if (sortData[j+1][0] < sortData[j][0]) {
	var tempValue = sortData[j][0];
	sortData[j][0] = sortData[j+1][0];
	sortData[j+1][0] = tempValue;

 	     }
 	  }
	}

	for( var i = 0; i < sortData.length; i++ ) 
		startData[i] = new Array( sortData[i][0], sortData[i][1] );


	//Start manipulations

	for( var i = 0; i < sortData.length; i++ ) {		
		graphData[i] = sortData[i][0];
	}

	var index = 0;
	var temp = 1;
	var j = 1;
	var ind;
	

	datatable.addRow();

		
	for( var i = 0; i < graphData.length; i++ ) {
		value[i] = parseFloat(graphData[i])
	}

	for( i = 0; i < graphData.length; i++ ) {
		if( value[i] == value[i+1] ){
			temp += 1;
		}
		else {
		 	count[index] = new Array(temp, value[i]);
			index++;
			temp = 1;
		}
	}
	
		for( i = 0; i < count.length; i++ )
			showData[i] = new Array( "" + count[i][1] + "", count[i][0] );

	//BEGIN BINS
	if( bins != 0 ) {

		var gap;
		var bin = new Array(bins);

		while( showData.length > 0 )
			showData.pop();
		while( value.length > 0 )
			value.pop();
		while( count.length > 0 )
			count.pop();

		for( var i = 0; i < startData.length ; i++ ) {
			value[i] = ( startData[i][0] );
		}

		for( i = 0; i < value.length; i++ )
			if( value[i] == 'NaN' )
				value[i] = 0;

		gap = value[value.length -1] - value[0];
		gap = gap / bins;

		for( index = 0; index < bins; index++ ) {

			temp = 0;			

			for( i = 0; i < startData.length; i++ ) {
				if( value[i] >= ( value[0] + ( gap * index ) ) && value[i] <= (value[0] + (gap * ( index + 1 ))) )
					temp++;
			}
			

			showData[index] = new Array( "" + (Math.floor( (value[0] + ( gap * index ))* 100 ) / 100 ) + " - " + (Math.floor( (value[0] + ( gap * ( index + 1) ))* 100 ) / 100 ) + "", temp );
		
			}

		}
	//END BINS


	datatable.addColumn( 'string', 'Value' );
	datatable.addColumn( 'number', 'Number of Occurrences:' );

	datatable.addRows(showData.length - 1);

	for( j = 0; j < showData.length; j++ ) {
		datatable.setCell( j, 0, showData[j][0] );
		datatable.setCell( j, 1, showData[j][1] );
				
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
	    $('#'+name+'_cntlpane').css({'float':'right','width':'180px','height':'600px','overflow-y':'auto'});
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
