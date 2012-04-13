var mouseClkX = 0;
var mouseClkY = 0;
var mouseRlsX = 0;
var mouseRlsY = 0;

var curVis = null;

$(document).ready(function(){
			
		var highlight;
	
		$('#map_canvas').hide();
	
        //Add the tabs and set the colors to default.
		for(var vis in data.relVis){

            $('#vis_select').append('<li class="vis_tab_'+vis+'"><a href="#">' + data.relVis[vis] + '</a></li>');

        }

		$('#vis_select  > li > a').css('background-color', '#ccc');
        $('#vis_select  > li > a').css('border-bottom','1px solid black');
        
        $('.vis_tab_0 > a').css('background-color', '#fff');
        $('.vis_tab_0 > a').css('border-bottom','1px solid white');
  
		curVis = map;

		$('#vis_select > li > a').unbind();
			

        //Mouse click function that gets added 
		$('#vis_select').children().children().click( function() {
			if( curVis != null )
				curVis.end();
				
            //Flip previously selection				
			$('#vis_select  > li > a').css('background-color', '#ccc');
		    $('#vis_select  > li > a').css('border-bottom','1px solid black');
            		
            //Set new selection			 
			curVis = eval(this.text.toLowerCase());
			$(this).css("background-color", "#ffffff");
			$(this).css('border-bottom','1px solid white');
			if( !curVis.inited )
				curVis.init(data);
			else
				curVis.start(data);
			
		});

		curVis.init(data);

        
		/*
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		var height;
		alert(isiPad);
		if( isiPad == 1 ){ height = 800; }
		else { height = 400; }
		
		$('#map_canvas').attr('height', height+'px');
		$('#viscanvas').attr('height', height+'px');
		*/
    });
