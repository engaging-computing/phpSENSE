// Set some Defaults
var name = 'Flot';
var Modules = ['Map','Timeline','Scatter','Motion','Bar','Histogram','Data'];
var ModuleTitle = ['Map','Timeline','Scatter Chart','Motion Chart','Bar Chart','Histogram','Data Table']
var TabVisited = new Array();
var GraphGlobals = new Array();
var JSON;
var plot;


function Data() {
	this.values = new Array();
	this.sessions = new Array();
	this.fields = new Array();
	this.visibility = new Array();

	this.setValues = function() {
		for( var field in this.fields ) {
			this.values[this.fields[field]] = new Array();
			for( var ses in this.sessions ) {
				this.values[this.fields[field]][ses] = new Array();
				for( var data in this.sessions[ses].data ) {
					this.values[this.fields[field]][ses][this.values[this.fields[field]][ses].length] = this.sessions[ses].data[data][field];
				}
			}		
		}
	};

	this.getAxis = function( field ) {
		return this.values[field];
	};

	this.setVisibility = function( index, value ) {
		this.visibility[index] = value;
	};

}

      function Graph(type, plot) {
        this.x = new Array();
        this.y = new Array();
        this.xy = new Array();
        this.options = new Array();
      
        if (type == 'Timeline') {

	  this.options['lines'] = true;
	  this.options['points'] = true;
	  this.options['xaxis'] = 'time';

	  this.options.set = function(x,l,p) {
	    return { xaxis : { mode  : x,
			       ticks : 0 },
		     series: { lines : { show: l },
			       points: { show: p }},
		     grid  : { hoverable: true , clickable: true }};
	  };

        } else if (type == 'Scatter') {

	  this.options['lines'] = false;
	  this.options['points'] = true;

	  this.options.set = function(l,p) {
	    return { series: { lines : { show: l },
			       points: { show: p }},
		     grid  : { hoverable: true , clickable: true }};
	  };
        } else if (type == 'Bar') {
	  this.options.set = function() {
	    return { series: { bars: { show: true }},
		     grid  : { hoverable: true , clickable: true }};
	  };
        } else if (type == 'Histogram') {
	  this.options.set  = function() {
	    return { series: { bars: { show: true }},
		     grid  : { hoverable: true , clickable: true }};
	  };
        }

        this.set_xAxis = function (arr) {
          for( var i = 0; i < arr.length; i++ )
	    this.x[i] = arr[i];
	};

	this.parse_X = function() {
	for( var ses = 0; ses < this.x.length; ses++ ) {
	  for( var point = 0; point < this.x[ses].length; point++ ) {
	    this.x[ses][point] = Date.parse(this.x[ses][point]);
	    }
	  }
	};

        this.set_yAxis = function (arr) {
          for( var i = 0; i < arr.length; i++ ) 
	    this.y[i] = arr[i];
	};

	this.set_XY = function () {
	for( var ses = 0; ses < this.x.length; ses++ ) {
	  this.xy[ses] = new Array();
	  for( var point = 0; point < this.x[ses].length; point++ ) {
	    this.xy[ses][point] = new Array( this.x[ses][point], this.y[ses][point] );
	    }
	  }
	};

	this.get_data = function(visibility, ex, ov) {
	var visd = new Array();

	if(ov == 'overview') {
	  for( var ses = 0; ses < this.xy.length; ses++ ) {
	    if( visibility[ses] )
	      visd[ses] = { data : this.xy[ses] };
	    else
	      visd[ses] = { data : [0,0] };
	    }

	  return visd;
	}

	if( ex == 'relative' ){
	  var temp = new Array(this.xy.length);
	  for( var ses = 0; ses < this.xy.length; ses++ ){
	    temp[ses] = new Array(); 
	    for( var pt = 0; pt < this.xy[ses].length; pt++ ){
	      temp[ses][pt] = [pt, this.xy[ses][pt][1]];
	    } 
	    visd[ses] = { data : temp[ses], label : 'Session ' + ses };
	  }
	} else {
	  for( var ses = 0; ses < this.xy.length; ses++ ) {
	    if( visibility[ses] )
	      visd[ses] = { data : this.xy[ses], label : 'Session ' + ses };
	    else
	      visd[ses] = { data : [0,0], label : 'Session ' + ses };
	    }
	}
	return visd;	
	};

      }

//Load Data Object
var Data = new Data();


// Load data from DATA JSON
var EXPERIMENT_ID = DATA.id;

$.each( DATA.sessions, function( index, ses ) {
  Data.sessions[Data.sessions.length] = ses;
});

$.each( DATA.sessions[0].fields, function( index, field ) {
  Data.fields[Data.fields.length] = field['field_name'];
});

$.each( Data.sessions, function( index, ses ) {
	ses.data.shift();
});

$.each( Data.sessions, function( index, ses ) {
	Data.visibility[Data.visibility.length] = ses.visibility
});

Data.setValues();

	var Timeline = new Graph('Timeline');
	var Scatter  = new Graph('Scatter');

// Create tab interface for Vizes
$(document).ready(function(){
	
  $('#vis').css({'min-height':'800px','clear':'both','position':'relative'});
  $('#vis').createAppend( 'div', { id : 'tabs' }, [
    'ul', { id : name+'_tabs' }, []] );


  $.each(Modules, function(index, Module) { 
 
    //Build tabs
    $('div#tabs > ul').createAppend( 
      'li', { id : name+'_tab_'+Module }, [
	'a', { href : '#'+name+'_'+Module },  [
	  'span', { id : name+'_title_'+Module }, ModuleTitle[index] ]]);

    //Build viz panel, cntrl panel and session list
    $('#tabs').createAppend(
	'div', { id : name+'_'+Module }, [
	  'div', { id : Module+'_viz' }, [],
	  'div', { id : Module+'_cntrl' }, [],
	  'table', { id : Module+'_session_list', style : 'font-size: 80%' }, []]);

    $('#'+Module+'_viz').css('width','650px').css('height','600px').css('margin-right','4px').css('float','left');
    $('#'+Module+'_cntrl').css('width','180px').css('height','600px').css('float','right').css('font-size','80%').css('overflow-y','auto');

    //Build session description 
    $.each( Data.sessions, function( index, ses ) {

      $('#'+Module+'_session_list').createAppend(
        'tr', {}, [
          'td', {}, ses.id+' - '+ses.meta[0].name+' : by '+ses.meta[0].firstname+' '+ses.meta[0].lastname ]);

      $('#'+Module+'_session_list').createAppend(
        'tr', {}, [
          'td', {}, 'Description: '+ses.meta[0].description.slice(0, 90)+'...' ]);

    });

  });


  $('#tabs').tabs();
$('#tabs').bind( 'tabsshow', function( event, ui ) {
  if( TabVisited[ui.index] != true ) {
    if(ui.index == 1) {
      TabVisited[ui.index] = true;
      $('#Timeline_Overview').css('width','80%').css('height','100px');

	for( var field = 0; field < DATA.sessions[0].fields.length; field++ ) {
	  if( DATA.sessions[0].fields[field].field_name == 'time' || DATA.sessions[0].fields[field].field_name == 'Time' )
	    var timeexist = true;
	  if( DATA.sessions[0].fields[field].type_name == 'time' || DATA.sessions[0].fields[field].type_name == 'Time' )
	    var timeis = field;
	}

	Data.setValues();

	if( timeexist == true )
	  Timeline.set_xAxis(Data.values['time']);
	else
	  Timeline.set_xAxis(Data.values[Data.fields[timeis]]);
	Timeline.set_yAxis(Data.values[Data.fields[3]]);
	Timeline.parse_X();
	Timeline.set_XY();

	$.plot($('#Timeline_viz'), Timeline.get_data(Data.visibility), Timeline.options.set(Timeline.options['xaxis'], Timeline.options['lines'], Timeline.options['points']));

    plot = $.plot($("#Timeline_Overview"), Timeline.get_data(Data.visibility, '', 'overview'),{
          series: {
            lines: { show: true, lineWidth: 1 },
            shadowSize: 0
          },
          xaxis: { ticks: 4, mode : 'time' },
          yaxis: { ticks: 3 },
          grid: { color: "#999" },
          selection: { mode: "xy" }
        });

    } else if (ui.index == 2) {
      TabVisited[ui.index] = true;
      $('#Scatter_Overview').css('width','80%').css('height','100px');

      for( var field = 0; field < DATA.sessions[0].fields.length; field++ )
	if( DATA.sessions[0].fields[field].type_name == 'time' || DATA.sessions[0].fields[field].type_name == 'Time' )
	  var timeis = field;

      Data.setValues();

      Scatter.set_xAxis(Data.values[Data.fields[timeis+1]]);
      Scatter.set_yAxis(Data.values[Data.fields[timeis+2]]);
      Scatter.set_XY();

	console.log(Scatter.xy);

      $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']));

      $.plot($("#Scatter_Overview"), Scatter.get_data(Data.visibility, '', 'overview'),{
          series: {
            lines: { show: true, lineWidth: 1 },
            shadowSize: 0
          },
          xaxis: { ticks: 4, mode : 'time' },
          yaxis: { ticks: 3 },
          grid: { color: "#999" },
          selection: { mode: "xy" }
        });
    }   
  }
});

   AnnotatedTimeLineModule( Data, Timeline, plot );
   ScatterModule( Data, Scatter, plot );

});

function showTooltip(x, y, contents) {
  $('<div id="tooltip">' + contents + '</div>').css( {
    position: 'absolute',
    display: 'none',
    top: y + 5,
    left: x + 5,
    border: '1px solid #fdd',
    padding: '2px',
    'background-color': '#fee',
    opacity: 0.80
  }).appendTo("body").fadeIn(200);
}
