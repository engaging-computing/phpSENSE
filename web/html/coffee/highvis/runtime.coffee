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

###
CoffeeScript version of runtime.
###
($ document).ready ->
    ($ can).hide() for can in ['#map_canvas', '#timeline_canvas', '#scatter_canvas', '#bar_canvas', '#histogram_canvas', '#table_canvas', '#viscanvas']
    
    ### Generate tabs ###
    for vis of data.relVis
        ($ '#vis_select').append '<li class="vis_tab_' + vis + '"><a href="#">' + data.relVis[vis] + '</a></li>'
        
    ($ '#vis_select > li > a').css 'background-color', '#ccc'
    ($ '#vis_select > li > a').css 'border-bottom', '1px solid black'
        
    ($ '.vis_tab_0 > a').css 'background-color', '#fff'
    ($ '.vis_tab_0 > a').css 'border-bottom','1px solid white'
        
    globals.curVis = (eval 'globals.' + data.relVis[0].toLowerCase())
        
    ($ '#vis_select > li > a').unbind()
    
    ### Change vis click handler ###
    ($ '#vis_select').children().children().click ->
        oldVis = globals.curVis
        globals.curVis = (eval 'globals.' + @text.toLowerCase())
        
        if oldVis is globals.curVis
            return
        
        ### Remove old selection ###
        ($ '#vis_select  > li > a').css 'background-color', '#ccc'
        ($ '#vis_select  > li > a').css 'border-bottom','1px solid black'
        
        ### Set new selection ###
        ($ @).css "background-color", "#ffffff"
        ($ @).css 'border-bottom','1px solid white'

        (oldVis.chart.showLoading 'Loading...') if oldVis.chart?

        ### Give the renderer a cycle to update the loading state before switching ###
        switchVis = ->
            globals.curVis.start()
            oldVis.end() if oldVis?

        setTimeout switchVis, 1

    #Set initial div sizes
    containerSize = ($ '#viscontainer').width()
    hiderSize     = ($ '#controlhider').outerWidth()
    controlSize = 200

    visWidth = containerSize - (hiderSize + controlSize)
    visHeight = ($ '#viscontainer').height()

    ($ '.vis_canvas').width  visWidth
    ($ '.vis_canvas').height visHeight

    
    #Start up vis
    globals.curVis.start()

    #Toggle control panel
    resizeVis = ->
    
        containerSize = ($ '#viscontainer').width()
        hiderSize     = ($ '#controlhider').outerWidth()
        controlSize = if ($ '#controldiv').width() is 0
            200
        else
            0

        newWidth = containerSize - (hiderSize + controlSize)
        newHeight = ($ '#viscontainer').height()

        ($ '#controldiv').animate {width: controlSize}, 600, 'linear'
        ($ '.vis_canvas').animate {width: newWidth, height: newHeight}, 600, 'linear'
        globals.curVis.resize newWidth, newHeight

    ($ '#control_hide_button').click ->
        resizeVis()
        
        
