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
globals.groupIndex ?= 0
globals.groupSelection ?= data.getUnique(globals.groupIndex)
globals.fieldSelection ?= [] #This needs a sane init value
globals.xAxis ?= 1 #This needs a sane init value

class window.BaseVis
    constructor: (@canvas) ->

    buildOptions: ->
        @chartOptions = 
            chart:
                renderTo: @canvas
            colors: globals.getColors()
            credits:
                enabled: false
            #global: {}
            #labels: {}
            #legend: {}
            #loading: {}
            #plotOptions: {}
            #point: {}
            #series: [{}]
            #subtitle: {}
            symbols: globals.getSymbols()
            title: {}
            #tooltop: {}
            #xAxis: {}
            #yAxis: {}
            #exporting: {}
            #navigation: {}
    
    generateColors: ->
        groups = data.getUnique(globals.groupIndex)
    
    start: ->
        @buildOptions()
        
        if @chart?
            @chart.destroy()
        @chart = new Highcharts.Chart @chartOptions
    
        ($ '#' + @canvas).show()
        @update()
        
    end: ->
        @clearControls()
        ($ '#' + @canvas).hide()
        
    update: ->
        @clearControls()
        @drawControls()
        
    clearControls: ->
        ($ '#controldiv').find('*').unbind()
        ($ '#controldiv').innerHTML = ''
        
    drawControls: ->
        alert 'CALLED DRAW CONTROLS STUB IN BASEVIS'
        
    drawGroupControls: ->
        controls = '<div id="groupControl" class="vis_controls">'
        
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Groups:</tr></td>'
        
        # Add grouping selector
        controls += '<tr><td><div class="vis_control_table_div">'
        controls += '<select class="group_selector">'
        
        for fieldIndex in data.getTextFields()
            controls += "<option value=\"#{fieldIndex}\">#{data.fields[fieldIndex].fieldName}</option>"
        
        controls += "</select></div></td></tr>"
        
        # Populate choices
        counter = 0
        for group in data.getUnique(globals.groupIndex)
            controls += '<tr><td>'
            controls += "<div class=\"vis_control_table_div\" style=\"color:#{@chartOptions.colors[counter]};\">"
            
            controls += "<input class=\"group_input\" type=\"checkbox\" value=\"#{group}\" #{if group in globals.groupSelection then "checked" else ""}></input>&nbsp"
            controls += "#{group}&nbsp"
            controls += "</div></td></tr>"
            counter += 1
        controls += '</table></div>'
        
        # Write HTML
        (($ '#controldiv').html ($ '#controldiv').html() + controls)
        
        # Make group select handler
        ($ '.group_selector').change (e) =>
            element = e.target or e.srcElement
            globals.groupIndex = Number element.value
            
            # Set up new groups
            globals.groupSelection = data.getUnique(globals.groupIndex)
            @init()
            
        # Make group checkbox handler
        ($ '.group_input').click (e) =>
            selection = []
            ($ '.group_input').each ()->
                if @checked
                    selection.push @value
            globals.groupSelection = selection
            @update()
            
    drawFieldChkControls: ->
        controls = '<div id="fieldControl" class="vis_controls">'
        
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Fields:</tr></td>'
        
        # Populate choices (not time or text)
        # Maybe should allow time here?
        for field of data.fields
            if 7 != (Number data.fields[field].typeID) != 37
                controls += '<tr><td>'
                controls += '<div class="vis_control_table_div">'
                
                controls += "<input class=\"field_input\" type=\"checkbox\" value=\"#{field}\" #{if field in globals.fieldSelection then "checked" else ""}></input>&nbsp"
                controls += "#{data.fields[field].fieldName}&nbsp"
                controls += "</div></td></tr>"
        
        controls += '</table></div>'
        
        # Write HTML
        (($ '#controldiv').html ($ '#controldiv').html() + controls)
            
        # Make field checkbox handler
        ($ '.field_input').click (e) =>
            selection = []
            ($ '.field_input').each ()->
                if @checked
                    selection.push @value
            globals.fieldSelection = selection
            @update()
            
    drawXAxisControls: ->
        controls = '<div id="xAxisControl" class="vis_controls">'
        
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">X Axis:</tr></td>'
        
        # Populate choices (not text)
        for field of data.fields
            if (Number data.fields[field].typeID) != 37
                controls += '<tr><td>'
                controls += '<div class="vis_control_table_div">'
                
                controls += "<input class=\"xAxis_input\" type=\"radio\" name=\"xaxis\" value=\"#{field}\" #{if field in globals.fieldSelection then "checked" else ""}></input>&nbsp"
                controls += "#{data.fields[field].fieldName}&nbsp"
                controls += "</div></td></tr>"
        
        controls += '</table></div>'
        
        # Write HTML
        (($ '#controldiv').html ($ '#controldiv').html() + controls)
            
        # Make xAxis radio handler
        ($ '.xAxis_input').click (e) =>
            selection = null
            ($ '.xAxis_input').each ()->
                if @checked
                    selection = @value
            globals.xAxis = selection
            @update()
        