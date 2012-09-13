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

class window.Scatter extends BaseHighVis
    ###
    Initialize constants for scatter display mode.
    ###
    constructor: (@canvas) ->
        @SYMBOLS_LINES_MODE = 3
        @LINES_MODE = 2
        @SYMBOLS_MODE = 1

        @mode = @SYMBOLS_MODE

        @xAxis = data.normalFields[0]

        @advancedTooltips = false

    ###
    Build up the chart options specific to scatter chart
        The only complex thing here is the html-formatted tooltip.
    ###
    buildOptions: ->
        super()

        self = this
        
        $.extend true, @chartOptions,
            chart:
                type: "line"
                zoomType: "xy"
            title:
                text: ""
            tooltip:
                formatter: ->
                    if self.advancedTooltips
                        str  = "<div style='width:100%;text-align:center;color:#{@series.color};'> #{@series.name.group}</div><br>"
                        str += "<table>"

                        for field, fieldIndex in data.fields when @point.datapoint[fieldIndex] isnt null
                            dat = if (Number field.typeID) is data.types.TIME
                                (globals.dateFormatter Date(@point.datapoint[fieldIndex]))
                            else
                                @point.datapoint[fieldIndex]
                                
                            str += "<tr><td>#{field.fieldName}</td>"
                            str += "<td><strong>#{dat}</strong></td></tr>"
                            
                        str += "</table>"
                    else
                        str  = "<div style='width:100%;text-align:center;color:#{@series.color};'> #{@series.name.group}</div><br>"
                        str += "<table>"
                        str += "<tr><td>#{@series.xAxis.options.title.text}:</td><td><strong>#{@x}</strong></td></tr>"
                        str += "<tr><td>#{@series.name.field}:</td><td><strong>#{@y}</strong></td></tr>"
                        str += "</table>"
                useHTML: true
                
        @chartOptions.xAxis =
            type: 'linear'

    ###
    Build the dummy series for the legend.
    ###
    buildLegendSeries: ->
        count = -1
        for field, fieldIndex in data.fields when fieldIndex in data.normalFields
            count += 1
            options =
                legendIndex: fieldIndex
                data: []
                color: '#000'
                visible: if fieldIndex in globals.fieldSelection then true else false
                name: field.fieldName

            switch
                when @mode is @SYMBOLS_LINES_MODE
                    options.marker =
                        symbol: globals.symbols[count % globals.symbols.length]
            
                when @mode is @SYMBOLS_MODE
                    options.marker =
                        symbol: globals.symbols[count % globals.symbols.length]
                    options.lineWidth = 0

                when @mode is @LINES_MODE
                    options.marker =
                        symbol: 'blank'
                    options.dashStyle = globals.dashes[count % globals.dashes.length]

            options

    ###
    Call control drawing methods in order of apperance
    ###
    drawControls: ->
        super()
        @drawGroupControls()
        @drawXAxisControls()
        @drawToolControls()

    ###
    Update the chart by removing all current series and recreating them
    ###
    update: ->
        #Remove all series and draw legend
        super()

        #Set axis title
        title =
           text: data.fields[@xAxis].fieldName
        @chart.xAxis[0].setTitle title, false

        #Draw series
        for fieldIndex, symbolIndex in data.normalFields when fieldIndex in globals.fieldSelection
            for group, groupIndex in data.groups when groupIndex in globals.groupSelection
                options =
                    data: data.xySelector(@xAxis, fieldIndex, groupIndex)
                    showInLegend: false
                    color: globals.colors[groupIndex % globals.colors.length]
                    name:
                        group: data.groups[groupIndex]
                        field: data.fields[fieldIndex].fieldName

                switch
                    when @mode is @SYMBOLS_LINES_MODE
                        options.marker =
                            symbol: globals.symbols[symbolIndex % globals.symbols.length]

                    when @mode is @SYMBOLS_MODE
                        options.marker =
                            symbol: globals.symbols[symbolIndex % globals.symbols.length]
                        options.lineWidth = 0

                    when @mode is @LINES_MODE
                        options.marker =
                            symbol: 'blank'
                        options.dashStyle = globals.dashes[symbolIndex % globals.dashes.length]

                @chart.addSeries options, false

        @chart.redraw()

    ###
    Draws radio buttons for changing symbol/line mode.
    ###
    drawToolControls: ->
        controls =  '<div id="toolControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Tools:</a></h3>"
        controls += "<div class='outer_control_div'>"

        controls += "<h4 class='clean_shrink'>Display Mode</h4>"

        for [mode, modeText] in [[@SYMBOLS_LINES_MODE, "Symbols and Lines"],
                                 [@LINES_MODE,         "Lines Only"],
                                 [@SYMBOLS_MODE,       "Symbols Only"]]
            controls += '<div class="inner_control_div">'
            controls += "<input class='mode_radio' type='radio' name='mode_selector' value='#{mode}' #{if @mode is mode then 'checked' else ''}/>"
            controls += modeText + "</div>"

        controls += "<br>"
        controls += "<h4 class='clean_shrink'>Other</h4>"
            
        controls += '<div class="inner_control_div">'
        controls += "<input class='tooltip_box' type='checkbox' name='tooltip_selector' #{if @advancedTooltips then 'checked' else ''}/> Advanced Tooltips "
        controls += "</div></div></div>"
        
        # Write HTML
        ($ '#controldiv').append controls

        ($ '.mode_radio').click (e) =>
            @mode = Number e.target.value
            @delayedUpdate()

        ($ '.tooltip_box').click (e) =>
            @advancedTooltips = not @advancedTooltips

        #Set up accordion
        globals.toolsOpen ?= 0

        ($ '#toolControl').accordion
            collapsible:true
            active:globals.toolsOpen

        ($ '#toolControl > h3').click ->
            globals.toolsOpen = (globals.toolsOpen + 1) % 2

    ###
    Draws x axis selection controls
        This includes a series of radio buttons.
    ###
    drawXAxisControls: (filter = (fieldIndex) -> (fieldIndex in data.normalFields)) ->
        #Don't draw if there's only one possible selection
        possible = for field, fieldIndex in data.fields when filter fieldIndex
            true
        if possible.length <= 1
            return
    
        controls =  '<div id="xAxisControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>X Axis:</a></h3>"
        controls += "<div class='outer_control_div'>"

        # Populate choices (not text)
        for field, fieldIndex in data.fields when filter fieldIndex
            controls += '<div class="inner_control_div">'

            controls += "<input class=\"xAxis_input\" type=\"radio\" name=\"xaxis\" value=\"#{fieldIndex}\" #{if (Number fieldIndex) == @xAxis then "checked" else ""}></input>&nbsp"
            controls += "#{data.fields[fieldIndex].fieldName}&nbsp"
            controls += "</div>"

        controls += '</div></div>'

        # Write HTML
        ($ '#controldiv').append controls

        # Make xAxis radio handler
        ($ '.xAxis_input').click (e) =>
            selection = null
            ($ '.xAxis_input').each ()->
                if @checked
                    selection = @value
            @xAxis = Number selection

            @delayedUpdate()

        #Set up accordion
        globals.xAxisOpen ?= 0

        ($ '#xAxisControl').accordion
            collapsible:true
            active:globals.xAxisOpen

        ($ '#xAxisControl > h3').click ->
            globals.xAxisOpen = (globals.xAxisOpen + 1) % 2

if "Scatter" in data.relVis
    globals.scatter = new Scatter "scatter_canvas"
else
    globals.scatter = new DisabledVis "scatter_canvas"
