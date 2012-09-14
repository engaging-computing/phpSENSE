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

window.globals ?= {}
globals.curVis = null

globals.CONTROL_SIZE = 210
globals.VIS_MARGIN = 20

###
CoffeeScript version of runtime.
###
($ document).ready ->
    ($ can).hide() for can in ['#map_canvas', '#timeline_canvas', '#scatter_canvas', '#bar_canvas', '#histogram_canvas', '#table_canvas', '#viscanvas','#motion_canvas','#photos_canvas']
    
    ### Generate tabs ###
    for vis of data.allVis
        if data.allVis[vis] in data.relVis
            ($ '#visTabList').append "<li class='vis_tab'><a href='##{data.allVis[vis].toLowerCase()}_canvas'>#{data.allVis[vis]}</a></li>"
        else
            ($ '#visTabList').append "<li class='vis_tab' ><a href='##{data.allVis[vis].toLowerCase()}_canvas' style='text-decoration:line-through'>#{data.allVis[vis]}</a></li>"
            
    ### Jquery up the tabs ###
    ($ '#viscontainer').tabs()
    

    ($ '#viscontainer').width ($ '#viscontainer').width() - (($ '#viscontainer').outerWidth() - ($ '#viscontainer').width())
    
    if(data.default_vis == null)
        globals.curVis = (eval 'globals.' + data.relVis[0].toLowerCase())
        ($ '#viscontainer').tabs('select', "##{data.relVis[0].toLowerCase()}_canvas")
    else
        globals.curVis = (eval 'globals.' + data.default_vis.toLowerCase())
        ($ '#viscontainer').tabs('select', "##{data.default_vis.toLowerCase()}_canvas")
    ### Change vis click handler ###
    ($ '#visTabList a').click ->
        oldVis = globals.curVis

        globals.curVis = (eval 'globals.' + innerTextCompat(this).toLowerCase())
        
        if oldVis is globals.curVis
            return

        oldVis.end() if oldVis?
        globals.curVis.start()
        
    #Set initial div sizes
    containerSize = ($ '#viscontainer').width()
    hiderSize     = ($ '#controlhider').outerWidth()
    controlSize = globals.CONTROL_SIZE

    visWidth = containerSize - (hiderSize + controlSize + globals.VIS_MARGIN)
    visHeight = ($ '#viscontainer').height() - ($ '#visTabList').outerHeight()

    ($ '.vis_canvas').width  visWidth
    ($ '.vis_canvas').height visHeight
    ($ '#controlhider').height visHeight
    ($ '#controldiv').height visHeight

    ($ '.vis_canvas').css('padding', 0)
    ($ '.vis_canvas').css('margin', 0)

    
    #Start up vis
    globals.curVis.start()

    #Toggle control panel
    resizeVis = ->
    
        containerSize = ($ '#viscontainer').width()
        hiderSize     = ($ '#controlhider').outerWidth()
        controlSize = if ($ '#controldiv').width() <= 0
            globals.CONTROL_SIZE
        else
            0

        newWidth = containerSize - (hiderSize + controlSize + globals.VIS_MARGIN)
        
        ($ '#controldiv').animate {width: controlSize}, 600, 'linear'
        ($ '.vis_canvas').animate {width: newWidth}, 600, 'linear'
        globals.curVis.resize newWidth, $('.vis_canvas').height(), 600

    ($ '#control_hide_button').click ->
        console.log 'CLICKED'
        if ($ '#controldiv').width() is 0
            $("##{@id}").html('>');
        else
            $("##{@id}").html('<');
        resizeVis()
        
        
        
