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

var vPanels;

var SAVE_SESSIONS = "";
var SAVE_SERIAL = "";
var SAVE_EID = "";

var FIELD_TYPE = {
  STRING : -1,
  TIME : 7,
  GEOSPACIAL : 19,
  GEO_LAT : 57,
  GEO_LON : 58
};

function liveUpdate() {
	vPanels.liveUpdate();
}

function startUp() {
    $("#vis").css({'min-height':'800px','clear':'both','position':'relative'});

    vPanels = new VisPanel('vis',DATA,STATE);

	//setTimeout('liveUpdate()', 5000);
}

google.load("visualization","1", {packages:['annotatedtimeline','table','scatterchart','motionchart','columnchart']});
google.setOnLoadCallback(function() { $(document).ready(startUp); });

/*
* Function passed to sort() array method to sort numbers.
*/
function SortNumbers(a,b) {
  return a - b;
}

function compare ( array, left, right ) {

    var depth = 0;


while ( depth < array[left].length && depth < array[right].length ) {


if ( array[left][depth] < array[right][depth] )
   return 1;
else if ( array[left][depth] > array[right][depth] )
   return -1;

depth++;    

    }

    return 0;

}

function qsort ( array, lo, hi ) {

  var low  = lo;
  var high = hi;
  mid = Math.floor( (low+high)/2 );

  do {
    while ( compare(array, low,  mid) > 0 )
      low++;

    while ( compare(array, high, mid) < 0 )
      high--;

    if ( low <= high ) {
      swap( array, low, high );
      low++;
      high--;
    }

  } while ( low <= high );

  if ( high > lo )
    qsort( array, lo, high );

  if ( low < hi )
    qsort( array, low, hi );

}

function swap ( a, i, j ) {

  var tmp = a[i]; 
  a[i] = a[j];
  a[j] = tmp;

}

function shortenURL(url) {
   // set up default options
     var defaults = {
       version:    '2.0.1',
       login:      'isenseproject',
       apiKey:     'R_ddd0ffb6866e9740b75561986014cae6',
       history:    '0',
       longUrl:    escape(url)
     };

     // Build the URL to query
     var daurl = "http://api.bit.ly/shorten?"
       +"version="+defaults.version
       +"&longUrl="+defaults.longUrl
       +"&login="+defaults.login
       +"&apiKey="+defaults.apiKey
       +"&history="+defaults.history
       +"&format=json&callback=?";
  /* Bitly only supports url's < 200 chars... we do not meet that most cases... :-(
     $.getJSON(daurl, function(data){
        // Make a good use of short URL
        if (data.results[url].shortUrl) {
          alert("To view this visualization again go to : \n\n" + data.results[url].shortUrl);
        } else {
          alert("To view this visualization again go to : \n\n" + url);
        }
      });*/
       
      alert("To view this visualization again go to : \n\n" + url);
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

function openSaveVisDialog(sessions, serial, eid) {
    $('#savestateopen').trigger('click');
    SAVE_SESSIONS = sessions.join(",");
    SAVE_SERIAL = serial;
    SAVE_EID = eid;
    
    $('#savebutton').click(function(){
        $('#savename').attr('disabled', true);
        $('#savedesc').attr('disabled', true);
        $('#savebutton').attr('disabled', true);
        
        var name = $('#savename').val();
        var desc = $('#savedesc').val();
        
        var datastring = 'action=save&eid=' + SAVE_EID + '&sessions=' + SAVE_SESSIONS + '&name=' + name + '&desc=' + desc + '&url_params=' + SAVE_SERIAL; 
        
        $.ajax({
            type:'GET',
            url:'actions/vis.php',
            data:datastring,
            success:function(xml) {
                if(xml == 'You are not logged in!') {
                    $('#urlholder').html('You can not save this visualization because you are not logged in. Please <a href="login.php">login</a> or <a href="register.php">join iSENSE</a> then try again.');
                }
                else {
                    $('#urlholder').createAppend('a', { href:'http://isense.cs.uml.edu/visdir.php?id=' + xml }, 'http://isense.cs.uml.edu/visdir.php?id=' + xml);
                }
                $('#savetable').hide();
                $('#savedtable').show();
            }
        });
    });
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

function VisPanel(a_name,a_data,a_savestate) {

  var name          = a_name;        // name = div id for this panel
  var baseData      = a_data;
  var saveState     = a_savestate;
  var stateObject   = "";
  var isenseData;
  var visModules    = new Array(7);
  var sessionMap    = null;
  var currentModule = 0;

  var enableMap         = false;
  var enableTimeline    = false;
  var enableMotionChart = false;
  var enableBarChart    = true;
  var enableSessionMap  = false;
  var sesMapOnly        = false;

  /* ---------------- *
  * Public Functions *
  * ---------------- */

	this.liveUpdate = function() {
		visModules[1].liveUpdate();
	}

  /* parseBaseData
  * Convert the data from the DATA page object format to the old
  * isenseData format.
  */
  this.makeSesMap = function(bd) {
    if (bd[0].experimentId) return false;
    isenseData = new VisData();
    
    for (var s in bd) {
      var ses = bd[s].session_id + 'S';
      
      isenseData.sessions[ses] = new Array();
      isenseData.sessions[ses]['id'] = bd[s].session_id;
      isenseData.sessions[ses]['date'] = bd[s].timecreated;
      isenseData.sessions[ses]['modified'] = bd[s].timemodified;
      isenseData.sessions[ses]['address'] = bd[s].street+", "+bd[s].city;
      isenseData.sessions[ses]['latitude'] = bd[s].latitude;
      isenseData.sessions[ses]['longitude'] = bd[s].longitude;
      isenseData.sessions[ses]['title'] = bd[s].name;
      isenseData.sessions[ses]['visible'] = true;
      isenseData.sessions[ses]['description'] = bd[s].description;
      isenseData.sessions[ses]['creator'] = bd[s].firstname+" "+bd[s].lastname
      isenseData.sessions[ses]['data'] = new Array(0);
      isenseData.sessions[ses]['data'][0] = new Array(0);
    }
    
    return true;
  }
  
  this.parseBaseData = function(bd) {
    isenseData = new VisData();
    var hasLat = false;
    var hasLon = false;
    var latFld;
    var lonFld;
    var otherCount = 0;
    var timeFld;

    isenseData.experiment_id = bd[0]['experimentId'];

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
        timeFld = fld;
      }
      else if(bdfld.unit_id == FIELD_TYPE.GEO_LAT) {
        hasLat = true;
        latFld = fld
      }
      else if(bdfld.unit_id == FIELD_TYPE.GEO_LON) {
        hasLon = true;
        lonFld = fld;
      }
      else {
        ++otherCount;
      }
    }
    var numflds = isenseData.fields['count'];
    /*
    if(hasLat && hasLon) {
    enableMap = true;
    }*/

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
      isenseData.sessions[ses]['address'] = meta.street+", "+meta.city;
      isenseData.sessions[ses]['latitude'] = meta.latitude;
      isenseData.sessions[ses]['longitude'] = meta.longitude;
      isenseData.sessions[ses]['title'] = meta.name;
      isenseData.sessions[ses]['visible'] = true;
      isenseData.sessions[ses]['description'] = meta.description;
      isenseData.sessions[ses]['creator'] = meta.firstname+" "+meta.lastname;

      //
      // Data
      //
      //first see if any data is good gps
      if (hasLat && hasLon) {
        for (j = 0; j < data.length; ++j) {
          if (data[j][latFld] != "" && data[j][lonFld] != "") {
            enableMap = true;
            break;
          }
        }
      }
      
      if (enableTimeline) {
        enableTimeline = false;
        for (j = 0; j < data.length - 1; ++j) {
          if (data[j][timeFld] != data[j+1][timeFld]) {
            enableTimeline = true;
            break;
          }
        }
      }
      
      isenseData.sessions[ses]['data'] = new Array(numflds);
      for(var i = 0; i < numflds; ++i) {
        isenseData.sessions[ses]['data'][i] = new Array(data.length-1);
        var peeklen = isenseData.sessions[ses]['data'][i].length;

        for(j = 0; j < data.length; ++j) {
          var peekval = data[j][i];
          isenseData.sessions[ses]['data'][i][j] = { value : data[j][i], row : j };
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
    if (ss == "") return;
    stateObject = ss.split(",");
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
  var sesMap;
  sesMap = this.makeSesMap(baseData);
  
  if (!sesMap) {
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
            'span', { id : name+'_title_Bar' }, 'Bar Chart']],
        'li', { id : name+'_tab_Histogram', display : 'none' }, [
          'a', { href : '#'+name+'_Histogram' }, [
            'span', { id : name+'_title_Histogram' }, 'Histogram Chart']],
        'li', { id : name+'_tab_Table', display : 'none' }, [
          'a', { href : '#'+name+'_Table' }, [
            'span', { id : name+'_title_Table' }, 'Data Table']]]);

    //
    // Add the appropriate modules based on clues found when 
    // parsing the data object.
    //
    
    if(enableMap) {
      $("#"+name).createAppend('div', { id : name+'_Map' }, []);
	    
      visModules[0] = new MapModule(this, name+'_Map', isenseData, stateObject);
    } else {
      $("#"+name+"_title_Map").prepend('Session ');
      $("#"+name).createAppend('div', { id : name+'_Map' }, []);
      visModules[0] = new SessionMapModule(this, name+'_Map', isenseData, stateObject, true);
    }

    if(enableTimeline) {
      $("#"+name).createAppend('div', { id : name+'_Timeline' }, []);
      visModules[1] = new AnnotatedTimeLineModule(this, name+'_Timeline', isenseData, stateObject);
    } else {
      $("#"+name+"_tab_Timeline").hide();
      visModules[1] = null;
    }

    $("#"+name).createAppend('div', { id : name+'_Scatter' }, []);
    visModules[2] = new ScatterChartModule(this, name+'_Scatter', isenseData, stateObject);

    if(enableMotionChart) {
      $("#"+name).createAppend('div', { id : name+'_Motion' }, []);
      visModules[3] = new MotionChartModule(this, name+'_Motion', isenseData, stateObject);
    } else {
      $("#"+name+"_tab_Motion").hide();
      visModules[3] = null;
    }

    if(enableBarChart) {
      $("#"+name).createAppend('div', { id: name+'_Bar'}, []);
      visModules[4] = new ColumnChartModule(this, name+'_Bar', isenseData, stateObject);
    } else {
      $("#"+name+"_tab_Bar").hide();
      visModules[4] = null;
    }
    
    var enableHistogram = 1;
    
    if(enableHistogram) {
      $("#"+name).createAppend('div', { id : name+'_Histogram' }, []);
      visModules[5] = new HistogramModule(this, name+'_Histogram', isenseData, stateObject);
    } else {
      $("#"+name+"_tab_Histogram").hide();
      visModules[5] = null;
    }

    $("#"+name).createAppend('div', { id : name+'_Table' }, []);
    visModules[6] = new TableModule(this, name+'_Table', isenseData, stateObject);

    
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
    if (stateObject != null) { 
      $('#'+name).tabs('select', stateObject[0]);
    }
  } else {
    sessionMap = new SessionMapModule(this, name+'_Map', isenseData, stateObject, false);
    sessionMap.init();
  }
}

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

  mainDiv.append('<div id="adiv">AAA</div><div id="bdiv">BBB</div><div id="vis-left"></div><div id="vis-right"></div><div id="vis-bottom"></div>');
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
  $("#vis-bottom").css({'background-color':'green','width':'900px','height':'100px','clear':'both','margin-top':'4px'});
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
  
  $('#slidertest').slider({ max:60, min:-60, value:0, steps:120 }).bind('slidestop', {}, 
    function(evt, obj) { 
      $('#slideres').append(obj.value + '!<br/>');
    }).bind('slide', {}, function(evt, obj) { $('#slideres').append('.'); });

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
