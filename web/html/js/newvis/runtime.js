var mouseClkX = 0;
var mouseClkY = 0;
var mouseRlsX = 0;
var mouseRlsY = 0;

var curVis = null;

$(document).ready(function(){
			
		var highlight;
	
		$('#map_canvas').hide();
	
		for(var vis in data.relVis)
			$('#vis_select').append('<li class="vis_tab_'+vis+'"><a href="#">' + data.relVis[vis] + '</a></li>');
			
		curVis = map;
		
		$('#vis_select > li > a').unbind();
			
		$('#vis_select').children().children().click( function() {
			if( curVis != null )
				curVis.end();
								
			$('#vis_select  > li > a').css('background-color', '#eee');
											 
			curVis = eval(this.text.toLowerCase());
			$(this).css("background-color", "#ccc");
			
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