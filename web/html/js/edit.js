$(document).ready(function () {
	
	var tab_tables = new Array();
	var tab_index = 0;
	
	$('table').each(function() {
		if( $(this).attr('id') != undefined )
			tab_tables.push($(this).attr('id'));
	});
			
	$('td[name="Time"]').children().datetimepicker({
		changeMonth: true,
		changeYear: true,
		showSecond: true,
		showMillisec: true,
		timeFormat: 'hh:mm:ss:l',
		create: function (evt, ui) {
			
			var thing = this._getDate();
			console.log(thing); 
		}

	});
		

    $("input:submit").click( function ( event ) {

       	event.preventDefault();
       	var rows = $(this).parent().find('table').find('tr');
       	var data = new Array();
        
       	rows.each( function ( index ) { 

           	var row = new Array();
                        
           	$(this).children().each( function ( index ) {
                               
				if( index != 0 )
                   	row[row.length] = $(this).find('input[name!=add↑][name!=add↓][name!=sub]').val();
					
           	});

           	data[data.length] = row;
                        
       	});
        
       	console.log(data);
        
       	var send = [ data, keys ];
        
       	$.ajax({
           	url: 'http://127.0.0.1/raac/update.php',
           	data: { data : send },
           	jsonp: true,
           	type: 'POST',
           	}).done( function( msg ) {
               	alert( "Data Saved: " + msg );
		});

   	});

	function DoThings() {
			
		var x;
			
		x = $('<tr></tr>');

		for( i = 0; i < keys.length; i++ )
			if(keys[i] == 'Time'||keys[i] == 'time')
				var tkey = i;
			
		console.log(keys.length);
							
		for( i = 0; i < keys.length; i++ ) {
				
			if(i == 0) {
				x.append('<td><input type="button" name="add↑" value="+"/><input type="button" name="sub" value="-"/><input type="button" name="add↓" value="+"/></td><td name="_id"><input type="hidden" /> New ID</td>');
			} else if( i != keys.length-1 && i != keys.length-2 ) {
				if( i == tkey )
					x.append('<td name=' + keys[i] + ' ><input type="text"/></td>');
				else
					x.append('<td><input type="text"/></td>');
			} else{
				x.append('<td><input type="hidden" value="' + $(this).parent().parent().children().eq(i+1).text() + '" />' + $(this).parent().parent().children().eq(i+1).text() + '</td>');
			}
		}
		
		x.find('input[name=add↓]').click(DoThings);
		x.find('input[name=add↑]').click(DoThings);
		x.find('input[name=sub]').click( function() {
			$(this).parent().parent().remove();
		})
		
		if($(this).attr('name') == 'add↓')
			x.insertBefore($(this).parent().parent());
		else
			x.insertAfter($(this).parent().parent());
			
		$('td[name="Time"]').children().datetimepicker({
			changeMonth: true,
			changeYear: true,
			showSecond: true,
			showMillisec: true,
			timeFormat: 'hh:mm:ss:l',
			create: function (evt, ui) {
					var thing = this._getDate();
				console.log(thing); 
			}
			});
		}
		$('input[name="add↑"]').each( function () {
		$(this).click(DoThings);
	});
	
	$('input[name="add↓"]').each( function () {
		$(this).click(DoThings);
	});
	
		
	$('input[name="sub"]').each( function () {
		$(this).click( function() {
			$(this).parent().parent().remove();
		});
	});
	
	 $('table').fixedHeaderTable({
		height: '600',
		altClass: 'odd',
		themeClass: 'fancyTable'
	});
	
	function hideTabs() {
		
		$('#last').show();
		$('#next').show();
		
		if( tab_index == 0 )
			$('#last').hide();
		else if( tab_index == tab_tables.length - 1 )
			$('#next').hide();
		
		for( var i = 0; i < tab_tables.length; i++ ) {
				$('#' + tab_tables[i]).fixedHeaderTable('show');
		}
		
		for( var i = 0; i < tab_tables.length; i++ ) {
			if( tab_index != i )
				$('#' + tab_tables[i]).fixedHeaderTable('hide');
		}
		
	}
	
	hideTabs();
	
	$('#last').click(function(){ 
		tab_index--;
		hideTabs();
		});
	
	$('#next').click(function() {
		tab_index++;
		hideTabs();
	})
	
});