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

class window.Scatter extends BaseVis
    ###
    TODO: Comment This
    ###
    constructor: (@canvas) ->
        @SYMBOLS_LINES_MODE = 3
        @LINES_MODE = 2
        @SYMBOLS_MODE = 1

        @mode = @SYMBOLS_LINES_MODE

    ###
    TODO: Comment This
    ###
    buildOptions: ->
        super()
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "line"
                zoomType: "xy"
            title:
                text: "Scatter"
            xAxis:
                type: if (Number data.fields[globals.xAxis].typeID) == 7 then 'datetime' else 'linear'

    ###
    TODO: Comment This
    ###
    buildLegendSeries: ->
        count = -1
        for field in data.fields when (Number field.typeID) not in [37, 7]
            count += 1
            options =
                data: []
                color: '#000'
                visible: if field in globals.fieldSelection then true else false
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
    TODO: Comment This
    ###
    drawControls: ->
        @drawGroupControls()
        @drawXAxisControls()
        @drawModeControls()

    ###
    TODO: Comment This
    ###
    update: ->
        super()

        for fieldIndex, symbolIndex in data.normalFields when fieldIndex in globals.fieldSelection
            for group, groupIndex in data.groups when groupIndex in globals.groupSelection
                options =
                    data: data.xySelector(globals.xAxis, fieldIndex, groupIndex)
                    showInLegend: false
                    color: globals.colors[groupIndex % globals.colors.length]
                    name: data.groups[groupIndex] + data.fields[fieldIndex].fieldName

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
    TODO: Comment This
    ###
    drawModeControls: ->
        #console.log @mode
        controls =  '<div id="AnalysisTypeControl" class="vis_controls">'

        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Tools:</td></tr>'

        controls += '<tr><td><div class="vis_control_table_div">'
        controls += "<input class='mode_radio' type='radio' name='mode_selector' value='#{@SYMBOLS_LINES_MODE}' #{if @mode is @SYMBOLS_LINES_MODE then 'checked' else ''}/>"
        controls += "Symbols and Lines  </div></td></tr>"

        controls += '<tr><td><div class="vis_control_table_div">'
        controls += "<input class='mode_radio' type='radio' name='mode_selector' value='#{@LINES_MODE}' #{if @mode is @LINES_MODE then 'checked' else ''}/>"
        controls += "Lines Only </div></td></tr>"

        controls += '<tr><td><div class="vis_control_table_div">'
        controls += "<input class='mode_radio' type='radio' name='mode_selector' value='#{@SYMBOLS_MODE}' #{if @mode is @SYMBOLS_MODE then 'checked' else ''}/>"
        controls += "Symbols Only </div></td></tr>"

        controls += '</table></div>'
        #console.log @mode
        # Write HTML
        ($ '#controldiv').append controls

        ($ '.mode_radio').click (e) =>
            @mode = Number e.target.value
            @delayedUpdate()

        

globals.scatter = new Scatter 'scatter_canvas'