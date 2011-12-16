function ScatterModule( Data, Scatter, plot ){

  $('#Scatter_cntrl').createAppend(
    'table', {}, [
      'tr', {}, [
        'td', {}, [
          'span', {}, 'Scatter:' ]],
      'tr', {}, [
        'td', {}, [
          'select', { style : 'width:100%;', id : 'Scatter_Lineup' }, [
            'option', { value : 0 }, 'Show Exact Time',
            'option', { value : 1 }, 'Side-By-Side Comparison']]],
      'tr', {}, [
        'td', {}, [
          'span', {}, 'Select Y Axis:']],
      'tr', {}, [
        'td', {}, [
          'select', { id : 'Scatter_Select_Axis' }, []]],
      'tr', {}, [
	'td', {}, [
	  'span', {}, 'Enable tooltip: ',
	  'input', { id : 'enableTooltip', type : 'checkbox' }, []]],
      'tr', {}, [
	'td', {}, [
	  'span', {}, 'Enable Points: ',
	  'input', { id : 'Scatter_Points', type : 'checkbox' }, []]],
      'tr', {}, [
	'td', {}, [
	  'span', {}, 'Enable Lines: ',
	  'input', { id : 'Scatter_Lines', type : 'checkbox' }, []]],
      'tr', {}, [
	'td', {}, [
	  'div', { id : 'Scatter_Overview' }, []]],
      'tr', {}, [
	'td', {}, [
	  'div', { id : 'Scatter_Ses_List' }, [
	    'table', {}, []]]]]);

  $('#Scatter_Points').attr('checked', 'true');
  $('#Scatter_Lines').attr('checked', 'true');

  $('#Scatter_Ses_List').css('width','100%').css('height','100%');


  $.each( Data.sessions, function( index, ses ) {
    $('#Scatter_Ses_List>table').createAppend(
      'tr', { id : 'Ses_'+index }, [
	'td', {}, [
	  'input', { id : ses.id+'_vis', type : 'checkbox' }, [],
	  'span', {  }, ' '+ses.id+' - '+ses.meta[0].name ]]);
    $('#Ses_'+index).css('background-color', Viz_colors[index]);

    $('#'+ses.id+'_vis').attr('checked', 'true');
    
    $('#'+ses.id+'_vis').bind('change', function() {
      if( $(this).is(':checked') )
	var state = true;
      else
	var state = false;
      var num = $(this).parent().parent().attr('id');
      num = parseInt(num.split('_')[1]);
      Data.visibility[num] = state;

      $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );


    });
  });

  $.each( Data.fields, function( index, Field ) {
    if( Data.fields[index] != 'time' )
      $('#Scatter_Select_Axis').createAppend( 
        'option', { value : index }, ''+Data.fields[index]+'' );
  });

  //Lines up datapoints if set to 'Side-By-Side'
  $('#Scatter_Lineup').bind( 'change', function() {

    if( this.value == 0 ) 
    $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );
    else 
    $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility, 'relative'), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );
    


  });

  //Turn on and off points based on checkbox
  $('#Scatter_Points').bind( 'change', function() {
    if( $(this).is(':checked') )
      Scatter.options['points'] = true;
    else if( !$(this).is(':checked') )
      Scatter.options['points'] = false;
    $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );
  });

  //Turn on and off lines based on checkbox
  $('#Scatter_Lines').bind( 'change', function() {
    if( $(this).is(':checked') )
      Scatter.options['lines'] = true;
    if( !$(this).is(':checked') )
      Scatter.options['lines'] = false;
    $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']));
  });

  //Change Y-axis. X-axis is locked for Scatter
  $('#Scatter_Select_Axis').bind( 'change', function() {

    Scatter.set_yAxis(Data.values[Data.fields[this.value]]);
    Scatter.set_XY();

    if( $('#Scatter_Lineup').val() == 0 )
      $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );
    else 
      $.plot($('#Scatter_viz'), Scatter.get_data(Data.visibility, 'relative'), Scatter.options.set(Scatter.options['lines'], Scatter.options['points']) );

      $.plot($('#Scatter_Overview'), Scatter.get_data(Data.visibility, '', 'overview'), {
          series: {
            lines: { show: true, lineWidth: 1 },
            shadowSize: 0
          },
          xaxis: { ticks: 4, mode : 'time' },
          yaxis: { ticks: 3 },
          grid: { color: "#999" },
          selection: { mode: "xy" }
        });

  });



  var previousPoint = null;
  $("#Scatter_viz").bind("plothover", function (event, pos, item) {
    $("#x").text(pos.x.toFixed(2));
    $("#y").text(pos.y.toFixed(2));

    if($("#enableTooltip:checked").length > 0) {
      if(item) {
        if(previousPoint != item.datapoint) {
          previousPoint = item.datapoint;
                    
          $("#tooltip").remove();4
          var x = item.datapoint[0].toFixed(2);
          var y = item.datapoint[1].toFixed(2);
                    
	  if( item.series.label == undefined ) {
	    if( $('#Scatter_Lineup').val() == 1 ) 
              showTooltip(ifilestem.pageX, item.pageY,
	        '( Point #' + parseInt(x) + ', ' + y + ' )');
	    else {
	      time = new Date(Math.floor(x));
	      showTooltip(item.pageX, item.pageY,
	        '( ' + time + ', ' + y + ' )');
	    }
	  } else {
	    showTooltip(item.pageX, item.pageY,
	      '( ' + parseInt(x) + ', ' + y + ' ' + item.series.label + ' )');
	  }


        }
      } else {
        $("#tooltip").remove();
        previousPoint = null;            
      }
    }
  });
/*
  $("#Scatter_viz").bind("plotselected", function (event, ranges) {
    // clamp the zooming to prevent eternal zoom
    if (ranges.xaxis.to - ranges.xaxis.from < 0.00001)
      ranges.xaxis.to = ranges.xaxis.from + 0.00001;
    if (ranges.yaxis.to - ranges.yaxis.from < 0.00001)
      ranges.yaxis.to = ranges.yaxis.from + 0.00001;
        
    // do the zooming
    plot = $.plot($("#Scatter_viz"), Scatter.get_data(Data.visibility),
      $.extend(true, {}, Scatter.options, {
        xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
        yaxis: { min: ranges.yaxis.from, max: ranges.yaxis.to }
      })
    );

    // don't fire event on the overview to prevent eternal loop
    overview.setSelection(ranges, true);
  });


    $("#Scatter_Overview").bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
});
*/
}



