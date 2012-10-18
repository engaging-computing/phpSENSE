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

globals.logY ?= 0

#Only init selections if this is not a saved vis
if not data.savedGlobals?
    globals.groupSelection ?= for vals, keys in data.groups
        Number keys
    globals.fieldSelection ?= data.normalFields[0..0]

class window.BaseVis
    constructor: (@canvas) ->

    ###
    Start sequence used by runtime
    ###
    start: ->
        @drawControls()
        @update()
        
    ###
    Update minor state
        Redraws html controls

        Derrived classes should overload to reload content.
    ###
    update: ->

    ###
    Default delayed update simply updates
    ###
    delayedUpdate: ->
        @update()

    ###
    Method called when vis resize has begun
        Defaults to doing nothing.
    ###
    resize: (newWidth, newHeight) ->
    
    ###
    End sequence used by runtime
        This is called when the user switches away from this vis.
        Should destroy the chart, hide its canvas and remove controls.
    ###
    end: ->
        console.log console.trace()
        alert   """
                BAD IMPLEMENTATION ALERT!

                Called: 'BaseVis.end'

                See logged stack trace in console.
                """
        
    ###
    Draws controls
        Derived classes should write control HTML and bind handlers using the method such as drawGroupControls.
    ###
    drawControls: ->
        @clearControls()

    ###
    Clear the controls
        Unbinds control handlers and clears the HTML elements.
    ###
    clearControls: ->
        ($ '#controldiv').empty()

    ###
    Draws group selection controls
        This includes a series of checkboxes and a selector for the grouping field.
        The checkbox text color should correspond to the graph color.
    ###
    drawGroupControls: (startOnGroup = false) ->

        controls = '<div id="groupControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Groups:</a></h3>"

        controls += "<div class='outer_control_div'>"

        # Add grouping selector
        controls += '<div class="inner_control_div"> Group By: '
        controls += '<select id="groupSelector" class="control_select">'

        for fieldIndex in data.textFields
            sel = if fieldIndex is data.groupingFieldIndex then 'selected' else ''
            controls += "<option value='#{Number fieldIndex}' #{sel}>#{data.fields[fieldIndex].fieldName}</option>"

        controls += "</select></div>"

        # Populate choices
        counter = 0
        for group, gIndex in data.groups
            controls += "<div class='inner_control_div' style=\"color:#{globals.colors[counter % globals.colors.length]};\">"

            controls += "<input class='group_input' type='checkbox' value='#{gIndex}' #{if (Number gIndex) in globals.groupSelection then "checked" else ""}/>&nbsp"
            controls += "#{group}"
            controls += "</div>"
            counter += 1
        controls += '</div></div>'

        # Write HTML
        ($ '#controldiv').append controls

        # Make group select handler
        ($ '#groupSelector').change (e) =>
            element = e.target or e.srcElement
            data.setGroupIndex (Number element.value)
            globals.groupSelection = for vals, keys in data.groups
                Number keys
            @delayedUpdate()
            @drawControls()

        # Make group checkbox handler
        ($ '.group_input').click (e) =>
            selection = []
            ($ '.group_input').each ()->
                if @checked
                    selection.push Number @value
                else
            globals.groupSelection = selection
            
            if startOnGroup
                @start()
            else
                @delayedUpdate()

        #Set up accordion
        globals.groupOpen ?= 0
            
        ($ '#groupControl').accordion
            collapsible:true
            active:globals.groupOpen

        ($ '#groupControl > h3').click ->
            globals.groupOpen = (globals.groupOpen + 1) % 2

    ###
    Draws vis saving controls
    ###
    drawSaveControls: (e) ->

        controls = '<div id="saveControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Saving:</a></h3>"
        controls += "<div class='outer_control_div' style='text-align:center'>"

        controls += "<div class='inner_control_div'>"
        controls += "<button id='saveVisButton' class='save_button'>Save Visualization </button>"
        controls += "</div>"

        if @chart?
            controls += "<div class='inner_control_div'>"
            controls += "<button id='downloadVisButton' class='save_button'> Download Visualization </button>"
            controls += "</div>"

            controls += "<div class='inner_control_div'>"
            controls += "<button id='printVisButton' class='save_button'> Print Visualization </button>"
            controls += "</div>"

        controls += '</div></div>'

        # Write HTML
        ($ '#controldiv').append controls

        ($ "#saveControl button").button()
        
        ($ "#saveVisButton").click ->
            globals.verifyUser (-> globals.savedVisDialog()), (-> alert 'You must be logged in to save a visualization.')

        ($ '#downloadVisButton').click =>
            @chart.exportChart
                type: "image/svg+xml"

        ($ '#printVisButton').click =>
            @chart.print()
        
        #Set up accordion
        globals.saveOpen ?= 0

        ($ '#saveControl').accordion
            collapsible:true
            active:globals.saveOpen

        ($ '#saveControl > h3').click ->
            globals.saveOpen = (globals.saveOpen + 1) % 2
            
    ###
    Hides the control div and remembers its previous size.
    ###
    hideControls: ->
        @controlWidth = ($ '#controldiv').width()
        ($ '#controldiv').width 0
        ($ '#controlhider').hide()
        ($ '#' + @canvas).css
            width: ($ "#viscontainer").innerWidth() - (($ "#controlhider").outerWidth() + globals.VIS_MARGIN)

    ###
    Returns the control div with its previous size intact.
    ###
    unhideControls: ->
        ($ '#controldiv').width @controlWidth
        ($ '#controlhider').show()

    ###
    Do any nessisary cleanup work before serialization.
    ###
    serializationCleanup: ->

class window.BaseHighVis extends BaseVis
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

        self = this
    
        @chartOptions = 
            chart:
                renderTo: @canvas
                reflow: false
            #colors:
            credits:
                enabled: false
            exporting:
                buttons:
                    exportButton:
                        enabled:false
                    printButton:
                        enabled:false
            legend:
                itemHoverStyle: ''
                backgroundColor: 'white'
                symbolWidth:60
                itemWidth: 200
            #loading: {}
            plotOptions:
                series:
                    turboThreshold: Number.MAX_VALUE
                    marker:
                        lineWidth:1
                        radius:5
                    events:
                        legendItemClick: (event) ->
                            false
            #point: {}
            series: []
            #subtitle: {}
            #symbols:
            title: {}
            #tooltop: {}
            #xAxis: {}
            yAxis:
                minorTickInterval: 'auto'
                title:
                    text: if globals.fieldSelection.length isnt 1
                        'Y-Values'
                    else
                        data.fields[globals.fieldSelection[0]].fieldName
                    

        @chartOptions.xAxis = []
        @chartOptions.xAxis.push {}
        @chartOptions.xAxis.push
            lineWidth: 0
            categories: ['']

    ###
    Builds the 'fake series' for legend controls.
        Derrived objects should implement this.
    ###
    buildLegendSeries: ->
        console.log console.trace()
        alert   """
                BAD IMPLEMENTATION ALERT!

                Called: 'BaseVis.buildLegendSeries'

                See logged stack trace in console.
                """
    ###
    Start sequence used by runtime
        This is called when the user switched to this vis.
        Should re-build options and the chart itself to ensure sync with global settings.
        This method should also be usable as a 'full update' in that it should destroy the current chart if it exists before generating a fresh one.

        TODO: Update comment
    ###
    start: ->
        @buildOptions()
        
        @chart = new Highcharts.Chart @chartOptions
    
        ($ '#' + @canvas).show()
        
        super()

    ###
    Update minor state
        Clears current series and re-loads the legend.

        Derrived classes should overload to add data drawing.
    ###
    update: ->
        #Name Y Axis
        title = if globals.fieldSelection.length isnt 1
            temp =
                text: 'Y-Values'
        else
            temp =
                text: data.fields[globals.fieldSelection[0]].fieldName
        @chart.yAxis[0].setTitle title, false
    
        #Remove curent data
        while @chart.series.length isnt 0
            @chart.series[0].remove(false)

        #Draw legend
        for options in @buildLegendSeries()
           @chart.addSeries options, false
        
    ###
    Performs an update while displaying the loading text
    ###
    delayedUpdate: ->
        @chart.showLoading 'Loading...'

        #Save context
        mySelf = this
        update = -> mySelf.update()
        setTimeout update, 1

        @chart.hideLoading()

    ###
    Draws y axis controls
        This includes a series of checkboxes or radio buttons for selecting
        the active y axis field(s).
    ###
    drawYAxisControls: (radio = false) ->

        controls = '<div id="yAxisControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Y Axis:</a></h3>"

        controls += "<div class='outer_control_div'>"

        # Populate choices
        for fIndex in data.normalFields
            controls += "<div class='inner_control_div' >"

            if radio
                controls += "<input class='y_axis_input' name='y_axis_group' type='radio' value='#{fIndex}' #{if (Number fIndex) is @displayField then "checked" else ""}/>&nbsp"
            else
                controls += "<input class='y_axis_input' type='checkbox' value='#{fIndex}' #{if (Number fIndex) in globals.fieldSelection then "checked" else ""}/>&nbsp"
                
            controls += "#{data.fields[fIndex].fieldName}"
            controls += "</div>"
            
        controls += '</div></div>'

        # Write HTML
        ($ '#controldiv').append controls

        # Make y axis checkbox/radio handler
        if radio
            # Currently specific to histogram - TODO: decouple
            ($ '.y_axis_input').click (e) =>
                @displayField = Number e.target.value
                @binSize = @defaultBinSize()
                ($ "#binSizeInput").attr('value', @binSize)
                @delayedUpdate()
        else
            ($ '.y_axis_input').click (e) =>
                index = Number e.target.value
                
                if index in globals.fieldSelection
                    arrayRemove(globals.fieldSelection, index)
                else
                    globals.fieldSelection.push(index)
                @delayedUpdate()

        #Set up accordion
        globals.yAxisOpen ?= 0

        ($ '#yAxisControl').accordion
            collapsible:true
            active:globals.yAxisOpen

        ($ '#yAxisControl > h3').click ->
            globals.yAxisOpen = (globals.yAxisOpen + 1) % 2
        
    ###
    Method called when vis resize has begun
        Resize highcharts to match
    ###
    resize: (newWidth, newHeight, duration) ->
        @chart.setSize(newWidth, newHeight, {duration: duration, easing:'linear'})
        
    ###
    End sequence used by runtime
        This is called when the user switches away from this vis.
        Should destroy the chart, hide its canvas and remove controls.
    ###
    end: ->
        if @chart?
            @chart.destroy()
            @chart = undefined;
            
        ($ '#' + @canvas).hide()

    ###
    Remove the chart and chart options object
    ###
    serializationCleanup: ->
        delete @chart
        delete @chartOptions


            
