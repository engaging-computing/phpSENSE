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
globals.groupSelection ?= for keys of data.groups
    Number keys
globals.fieldSelection ?= data.normalFields[0..0]
globals.xAxis ?= data.numericFields[0]

class window.BaseVis
    ###
    Constructor
        Assigns target canvas name
    ###
    constructor: (@canvas) ->

    ###
    Builds Highcharts options object
        Builds up the options common to all vis types.
        Subsequent derrived classes should use $.extend to expand upon these agter calling super()
    ###
    buildOptions: ->
        @chartOptions = 
            chart:
                renderTo: @canvas
                animation: false
            #colors:
            credits:
                enabled: false
            #global: {}
            #labels: {}
            #legend: {}
            #loading: {}
            plotOptions:
                series:
                    marker:
                        lineWidth:1
                        radius:5
                    events:
                        legendItemClick: (event) =>
                            index = data.normalFields[event.target.index]

                            if event.target.visible
                                arrayRemove(globals.fieldSelection, index)
                            else
                                globals.fieldSelection.push(index)
                            @update()
            #point: {}
            series: []
            #subtitle: {}
            #symbols:
            title: {}
            #tooltop: {}
            #xAxis: {}
            #yAxis: {}
            #exporting: {}
            #navigation: {}

        count = -1
        @chartOptions.series = for field in data.fields when (Number field.typeID) not in [37, 7]
            count += 1
            dummy =
                data: []
                color: '#000'
                marker:
                    symbol: globals.symbols[count % globals.symbols.length]
                name: field.fieldName

    ###
    Start sequence used by runtime
        This is called when the user switched to this vis.
        Should re-build options and the chart itself to ensure sync with global settings.
        This method should also be usable as a 'full update' in that it should destroy the current chart if it exists before generating a fresh one.
    ###
    start: ->
        @buildOptions()
        
        if @chart?
            @chart.destroy()
        @chart = new Highcharts.Chart @chartOptions

        #Sync hidden state from globals
        for ser in @chart.series[0...data.normalFields.length]
            index = data.normalFields[ser.index]
            if index in globals.fieldSelection
                ser.show()
            else
                ser.hide()
    
        ($ '#' + @canvas).show()
        @update()
        
    ###
    End sequence used by runtime
        This is called when the user switches away from this vis.
        Should destroy the chart, hide its canvas and remove controls.
    ###
    end: ->
        @chart.destroy()
        @clearControls()
        ($ '#' + @canvas).hide()

    ###
    Update minor state
        Should update the hidden status based on both high-charts legend action and control checkboxes.
    ###
    update: ->
        @clearControls()
        @drawControls()

        #Update hidden state
        for index in [0...@chart.series.length - data.normalFields.length]
            fieldIndex = data.normalFields[index % data.normalFields.length]
            groupIndex = Math.floor (index / data.normalFields.length)
            
            if (groupIndex in globals.groupSelection) and (fieldIndex in globals.fieldSelection)
                @chart.series[index + data.normalFields.length].setVisible(true, false)
            else
                @chart.series[index + data.normalFields.length].setVisible(false, false)
            @chart.redraw()
                

    ###
    Clear the controls
        Unbinds control handlers and clears the HTML elements.
    ###
    clearControls: ->
        #($ '#controldiv').find('*').unbind()
        ($ '#controldiv').html('')

    ###
    Draws controls
        Derived classes should write control HTML and bind handlers using the methods defined below.
    ###
    drawControls: ->
        console.log console.trace()
        alert   """
                BAD IMPLEMENTATION ALERT!

                Called: 'BaseVis.drawControls'

                See logged stack trace in console.
                """

                
    ###
    Draws group selection controls
        This includes a series of checkboxes and a selector for the grouping field.
        The checkbox text color should correspond to the graph color.
    ###
    drawGroupControls: ->
        controls = '<div id="groupControl" class="vis_controls">'
        
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Groups:</tr></td>'
        
        # Add grouping selector
        controls += '<tr><td><div class="vis_control_table_div">'
        controls += '<select class="group_selector">'
        
        for fieldIndex in data.textFields
            controls += "<option value=\"#{Number fieldIndex}\">#{data.fields[fieldIndex].fieldName}</option>"
        
        controls += "</select></div></td></tr>"
        
        # Populate choices
        counter = 0
        for gIndex, group of data.groups
            controls += '<tr><td>'
            controls += "<div class=\"vis_control_table_div\" style=\"color:#{globals.colors[counter]};\">"
            
            controls += "<input class='group_input' type='checkbox' value='#{gIndex}' #{if (Number gIndex) in globals.groupSelection then "checked" else ""}/>&nbsp"
            controls += "#{group}&nbsp"
            controls += "</div></td></tr>"
            counter += 1
        controls += '</table></div>'
        
        # Write HTML
        ($ '#controldiv').append controls
        
        # Make group select handler
        ($ '.group_selector').change (e) =>
            element = e.target or e.srcElement
            data.setGroupIndex (Number element.value)
            globals.groupSelection ?= for keys of data.groups
                Number keys
            @start()
        
        # Make group checkbox handler
        ($ '.group_input').click (e) =>
            selection = []
            ($ '.group_input').each ()->
                if @checked
                    console.log 'checked'
                    selection.push Number @value
                else
                    console.log 'unchecked'
            globals.groupSelection = selection
            @update()
            

    ###
    Draws x axis selection controls
        This includes a series of radio buttons.
    ###
    drawXAxisControls: ->
        controls = '<div id="xAxisControl" class="vis_controls">'
        
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">X Axis:</tr></td>'
        
        # Populate choices (not text)
        for field of data.fields
            if (Number data.fields[field].typeID) != 37
                controls += '<tr><td>'
                controls += '<div class="vis_control_table_div">'
                
                controls += "<input class=\"xAxis_input\" type=\"radio\" name=\"xaxis\" value=\"#{field}\" #{if (Number field) == globals.xAxis then "checked" else ""}></input>&nbsp"
                controls += "#{data.fields[field].fieldName}&nbsp"
                controls += "</div></td></tr>"
        
        controls += '</table></div>'
        
        # Write HTML
        ($ '#controldiv').append controls
            
        # Make xAxis radio handler
        ($ '.xAxis_input').click (e) =>
            selection = null
            ($ '.xAxis_input').each ()->
                if @checked
                    selection = @value
            globals.xAxis = Number selection
            @start()

    groupFilter: (dp) ->
        groups = globals.groupSelection.map (index) -> data.groups[index]
        (String dp[data.groupIndex]).toLowerCase() in groups
        