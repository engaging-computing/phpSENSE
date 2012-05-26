var images= new function Images(){

    this.inited = 0;
    this.data = Array();

    /* The function to draw the controls */    
    this.drawControls = function(){

    }

    /* The function to set all the listeners needed*/
    this.setListeners = function(){
    
    }
    
    /* Clears the canvas so other things can be drawn */
    this.clear = function(){

    }


    /* The function that draws everything on the screen */
    this.draw = function(){
        for(var ses in data.sessions) {
           for(var pics in data.sessions[ses].pictures){
               console.log(data.sessions[ses].pictures[pics]['provider_url']);
           }    
        }
    }

    /* Last step after initing. Clears what was there then draws */
    this.start = function(){		
		this.clear();
			
		this.draw();
		
		this.drawControls();
		
		this.setListeners();
	}

    /* Initializes everything needed for the visualizaiton */
    this.init = function(){
        this.inited = 1;
    
      
                
        this.start();
    }

    /* Kills all listeners when visualization is no longer needed */
    this.end = function(){
		$('canvas#viscanvas').unbind();
		
		$('div#controldiv').find().unbind();
		
		$('div#controldiv').empty();
		
		this.clear();
		
	}


}
