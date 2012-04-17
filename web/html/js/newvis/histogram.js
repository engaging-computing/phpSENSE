
// Function descriptions are all above the function they describe

var histogram = new function Histogram(){
    
    this.makeBins = function() {
        
        var bounds = data.getFieldBounds([data.fields[this.field].name], false);
        var bins = new Object();
        var max = 0;
        
        for (var i = 0; i < this.numbins; i++) {
            bins[i] = new Object();
            bins[i].start = Math.max(bounds[0], (this.baseBin - this.binInc) + this.binInc * i);
            bins[i].end = Math.min(bounds[1], (this.baseBin - this.binInc) + this.binInc * i + this.binInc);
            bins[i].size = bins[i].end - bins[i].start;
        }
        
        for (var ses in data.sessions) {
            if (data.sessions[ses].visibility) {
                
                for (var i in bins) {
                    bins[i][ses] = 0;
                }
                
                for (var dp in data.sessions[ses].data) {
                    var bin = Math.floor((data.sessions[ses].data[dp][this.field] - (this.baseBin - this.binInc)) / this.binInc);
                    
                    //Catch edge cases
                    bin = Math.min(this.numbins - 1, Math.max(0, bin));
                    
                    bins[bin][ses]++;
                }
            }
        }
        
        for (bin in bins) {
            var sum = 0;
            
            for (ses in data.sessions) {
                if (data.sessions[ses].visibility) {
                    sum += bins[bin][ses];
                }
            }
            
            max = Math.max(max, sum);
        }
        
        return [bins, max];
    }
    
    /*
    // Use: myhistogram.drawControls();
    //
    // This will populate controls (it's broken right now)
    */
    
    this.drawControls = function(){
        
        var controls = '';
        
        controls += '<div style="float:left;margin:10px;border:1px solid grey;padding:5px;"><div style="text-align:center;text-decoration:underline;padding-bottom:5px;">Tools:</div>';

        controls += '<button id="set_bins" type="button">Set # of Bins:</button><input type="text" id="bin_num" value="10"></input>';
        controls += '<br>'
        controls += '<input class="binselect" id="sug" name="binselect" type="checkbox" value="suggest_bins" checked></input>&nbsp;Automatic';
        controls += '<div id="binSize"><br>Bin size: ' + this.binSize + '</div>';
        controls += '<div id="binNum"><br>Bin size: ' + this.numbins + '</div>';
        
        controls += '</div>';
        
        /* Add the session controls*/
        controls += buildSessionControls('histogram');
                
        controls += '<div id="fieldcontrols" style="float:left;margin:10px;">';
        
        controls += '<table style="border:1px solid grey;padding:5px;"><tr><td style="text-align:center;text-decoration:underline;padding-bottom:5px;">Fields:</tr></td>';
        
        var picked = true;
        
        for( var i in data.fields ){
            
            if (data.fields[i].type_id != 7 && data.fields[i].type_id != 19 && 
                data.fields[i].type_id != 37 ) {
                
                controls += '<tr><td>';
            
                controls += '<div style="font-size:14px;font-family:Arial;text-align:center;color:#000000;float:left;">';
            
                controls += '<input class="fieldselect" name="fieldselect" type="radio" value="' + i + '" ' + ( picked ? 'checked' : '' ) + '></input>&nbsp;';
                if (picked) {
                    picked = false;
                    this.field = i;
                }
                controls += data.fields[i].name + '&nbsp;';
                
                controls += '</div>';
                controls += '</td></tr>';
                
                }
                
        }
        
        controls += '</table>'
        
        controls += '</div>';
        
        controls += '<div style="clear:both;"></div>';
        
        // --- //
        
        controls += '';

        // --- //
        
        this.controls.innerHTML = controls;
        
    }
    
    this.setListeners = function(){
        
        $('input.sessionvisible').click(function(e){
            
            var visible = data.sessions[$(e.target).val()].visibility;
            
            visible = visible > 0 ? 0 : 1;
            
            data.sessions[$(e.target).val()].visibility = visible;
            
            histogram.calcBinSize();
            
            histogram.draw();
            
        });
        
        $('input.fieldselect').click(function(e){
            
            histogram.field = $(e.target).val();
            data.fields[$(e.target).val()].visibility = 1;
            
            histogram.calcBinSize();
            
            histogram.draw();
            
        });
        
        $('#viscanvas').mousemove(function(e){
            
            histogram.mouseX = e.pageX - $('canvas#viscanvas').offset().left;
            histogram.mouseY = e.pageY - $('canvas#viscanvas').offset().top;
            
        });
        
        $('button#set_bins').click(function(e){
            
            histogram.calcBinSize();
            
            histogram.draw();
            
        });
        
        $('input.binselect').click(function(e){
            
            histogram.calcBinSize();
            
            histogram.draw();
        });
        
    }
    
    this.calcBinSize = function(){
        
        var entered = Math.floor($('input#bin_num').val());
        
        if (isNaN(entered) || entered < 1){
            entered = 1;
            
            alert("Please only enter numbers greater than or equal to one.\r\n\r\nDefaulting to 1.");
        }
        
        if ($('input#sug').attr('checked')){
            //Suggest
            var bounds = data.getFieldBounds([data.fields[this.field].name], false);
            
            this.binInc = getDataIncrement(entered, entered, bounds[1] - bounds[0]);
            this.baseBin = getNextDataIncrement(bounds[0], this.binInc, null);
            this.numbins = 1 + Math.ceil((bounds[1] - bounds[0] - this.binInc) / this.binInc);
        }
        else {
            //Dictate
            this.numbins = entered;
            
            var bounds = data.getFieldBounds([data.fields[this.field].name], false);
            var binSize = (bounds[1] - bounds[0]) / this.numbins;
            
            this.baseBin = bounds[0] + binSize;
            this.binInc = binSize;
        }
        
        $('#binSize').html('Bin size: ' + this.binInc);
        $('#binNum').html('Bin num: ' + this.numbins);
    }
    
    /*
    // Use: myhistogram.clear();
    //
    // This clears the canvas and redraws the graph border.
    */
    
    this.clear = function(){
        
        this.context.fillStyle = this.bgcolor;
        this.context.fillRect (0, 0, this.canvaswidth, this.canvasheight);
        
        this.context.strokeStyle = this.gridcolor;
        this.context.lineWidth = 0.25;
        this.context.strokeRect(0, 0 + this.fontheight/2, this.drawwidth, this.drawheight);
        
    }
    

    /*
    // Use: myhistogram.draw();
    //
    // This draws the histogram to the canvas.
    */
    
    this.draw = function(){
        
        this.clear();
        
        var bins_Max = this.makeBins();
        var bins = bins_Max[0];
        var yMax = bins_Max[1];
        
        var xBounds = data.getFieldBounds([data.fields[this.field].name], false);
        
        drawYAxis(0, yMax, this);
        drawXAxis(xBounds[0], xBounds[1], this, data.fields[this.field].name);
        // --- //
        
        var barUnitHeight = this.drawheight / yMax;
        var xOff = 0;
        
        for (var bin in bins) {
            
            var yOff = 0;
            var barWidth;
            
            for (var ses in data.sessions) {
                
                if (data.sessions[ses].visibility) {
                    
                    barWidth = (bins[bin].size / (xBounds[1] - xBounds[0])) * this.drawwidth;
                    
                    yOff += bins[bin][ses] * barUnitHeight;
                    
                    var color = getSessionColor(ses);
                    this.context.fillStyle = 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',0.85)';
                    this.context.fillRect( this.xoff + xOff, this.drawheight + this.yoff - yOff, 
                                           barWidth, bins[bin][ses] * barUnitHeight);
                }
            }
            
            this.context.strokeStyle = this.gridcolor;
            this.context.lineWidth = 0.5;
            this.context.strokeRect( this.xoff + xOff, this.drawheight + this.yoff - yOff, 
                                       barWidth, yOff);
            
            xOff += barWidth;
        }
    }
    
    /*
    // Use: myhistogram.init();
    //
    // This draws the histogram to the canvas and populates
    // the div that holds the histogram controls.
    */
    
    this.start = function(){
        
        this.clear();
        
        this.drawControls();
        
        this.calcBinSize();
        
        this.draw();
        
        this.setListeners();
        
    }
    
    this.init = function(){

        this.canvas     = document.getElementById("viscanvas");
        this.controls   = document.getElementById("controldiv");
        this.context    = this.canvas.getContext('2d');

        this.canvaswidth    = this.canvas.width;
        this.canvasheight   = this.canvas.height;

        // RMS is a crappy way to calculate this. It should be done better.
        this.fontheight = Math.floor( Math.sqrt( this.canvasheight*this.canvasheight + this.canvaswidth*this.canvaswidth ) / 55 );

        this.context.font = this.fontheight + "px sans-serif";

        this.xlabelsize = Math.floor(this.fontheight*2);
        this.ylabelsize = this.context.measureText( data.getMax() + "" ).width + this.fontheight/2;

        this.drawwidth  = Math.floor(this.canvaswidth   - (this.ylabelsize*1.5));
        this.drawheight = Math.floor(this.canvasheight  - (this.xlabelsize*2.5));

        this.xoff = 0;
        this.yoff = this.fontheight/2;

        this.hRangeLower = 0.0;
        this.hRangeUpper = 1.0;
        this.vRangeLower = 0.0;
        this.vRangeUpper = 1.0;

        this.one2one = false;
        
        this.numbins = 10;

        this.animateTimeout = null;

        this.mouseX = 0;
        this.mouseY = 0;

        this.bgcolor = "rgb(255,255,255)";
        this.gridcolor = "rgb(0,0,0)";
        this.bordercolor = "rgb(0,0,0)";
        this.textcolor = "rgb(0,0,0)";
        
        this.field = 0;
        
        this.inited = true;

        this.start();

         $("a[rel^='prettyPhoto']").prettyPhoto();
    }
    
    this.end = function(){
        
        $('canvas#viscanvas').unbind();
        
        $('div#controldiv').find().unbind();
        
        $('div#controldiv').empty();
        
        this.clear();
        
    }
    
}
