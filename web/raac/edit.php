<?php

require_once '../includes/config.php';

if(!isset($_GET['exp']))
	$presort = $mdb->find('e350');
else
	$presort = $mdb->find('e' . $_GET['exp']);

$keys = array_keys($presort[0]);

echo '<html><head><script src="/html/js/lib/jquery.js" type="text/javascript"></script>';
echo '<script src="/html/js/lib/jquery-ui.js" type="text/javascript"></script>';
echo '<script src="/html/js/lib/MillisecondClock.js" type="text/javascript"></script>';
echo '<title>iSENSE : River As A Classroom - Edit Page</title>';
echo '<link rel="stylesheet" type="text/css" href="/html/css/table.css" />';
echo '<link rel="stylesheet" type="text/css" href="/html/css/jss.css" />';
echo '<link rel="stylesheet" type="text/css" href="/html/css/jclock.css" />';

echo '</head><body>';


$javascript = '<script> var keys = [';
foreach( $keys as $i => $key ) {
    if( $i < (count($keys)-1) )
        $javascript .= '"' . $key . '", ';
    else
        $javascript .= '"' . $key . '"';

}

$javascript .= '];';


$javascript .= <<<EOT

	$(document).ready(function () {
		
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
		
	});
	
</script>

EOT;

//Loads data from mongo into a sortable array

foreach( $presort as $index => $row ) {
    
    for( $i = 0; $i < count($keys); $i++ ) {

			$tmp[$index][$i] = $row[$keys[$i]];
        
    }
        
}

//Find session_id field and decrement it by 1

$dc = count($tmp[0]);
$dc--;

//Create sortArray[Ses] for each session_id
//Load each data point connected to a session into the appropriate session

for( $t = 0; $t < count($tmp); $t++) {
	if(!isset($sortArray[$tmp[$t][$dc]]))
		$sortArray[$tmp[$t][$dc]] = array();

		array_push( $sortArray[$tmp[$t][$dc]], $tmp[$t] );
}

/*foreach( $tmp as $dp ) {
    if(!isset($sortArray[$dp[$dc]]))
        $sortArray[$dp[$dc]] = array();
    else
        array_push( $sortArray[$dp[$dc]], $dp );
}*/

//Dump unsorted data

unset($tmp);

//Tabularize data

$empty = 0;

for( $in = 0; $in < count($keys); $in++ ) {
    if( $keys[$in] == 'Session' || $keys[$in] == 'session' )
        $i_ses = $in;
    else if( $keys[$in] == 'Experiment' || $keys[$in] == 'experiment' )
        $i_exp = $in;
    else if( $keys[$in] == '_id' )
        $i_id = $in;
    else
        $empty++;
}

if( $empty == count($keys) ) {
    $i_ses = $dc;
    $i_exp = $dc-1;
}

unset($empty);

foreach($sortArray as $ses) {
    if( isset($ses[0][$i_ses]) ){         //&& $ses[0][$i_ses] > 2830 ) {
    echo '<form name="' . $ses[0][$i_ses] . '" ><table>';
    foreach($ses as $dp) {
        echo '<tr>';
        foreach($dp as $i => $d) {
            if( $i == $i_id )
                echo '<td><input type="button" name="add↓" value="+"/><input type="button" name="sub" value="-"/><input type="button" name="add↑" value="+"/></td><td name="' . $keys[$i] . '"><input type="hidden" value="' . $d . '" />Mongo_ID</td>';
            else if($i == $i_ses || $i == $i_exp)
                echo '<td name="' . $keys[$i] . '"><input type="hidden" value="' . $d . '" />' . $d . '</td>';
			else
                echo '<td name="' . $keys[$i] . '"><input type="text" value="' . $d . '" /></td>';
                
        }
        echo '</tr>';    
    }
    echo '</table><input type="submit" value="Save!" class="submit" /></form>';

    }
}

echo $javascript;
echo '</body></html>';
