###
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 *
###

class window.DisabledVis extends BaseVis
    constructor: (@canvas) -> 

    motion_err = "Motion Chart could not be displayed<br>A time field was not found in this experiment"
    time_err = "Timeline could not be displayed<br>Either a time field was not found or there were not enough data"
    scatter_err = "Scatter Chart could not be displayed<br>Either two numeric fields were not found or there were not enough data"
    histogram_err = "Histogram could not be displayed<br>Either no numeric fields were found, or there were not enough data"
    bar_err = "Bar Chart could not be displayed<br>Either no numeric fields were found, or there were not enough data"
    map_err = "Map could not be displayed<br>No geographic data were found"
    photos_err = "There are no photos to display"
    
    start: ->  
        ($ '#' + @canvas).show()
        
        switch @canvas
            when "map_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{map_err}</div>")
            when "motion_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{motion_err}</div>")
            when "bar_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{bar_err}</div>")
            when "histogram_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{histogram_err}</div>")
            when "timeline_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{time_err}</div>")
            when "scatter_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{scatter_err}</div>")
            when "photos_canvas" then ($ '#' + @canvas).html("<div id='vis_disabled'>#{photos_err}</div>")
        
        @hideControls()
        
    end: ->
        ($ '#' + @canvas).hide()
        @unhideControls(@controlWidth)