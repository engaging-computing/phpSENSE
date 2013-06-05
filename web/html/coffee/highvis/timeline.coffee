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

class window.Timeline extends Scatter
    ###
    Constructor
        Change default mode to lines only
    ###
    constructor: (@canvas) ->
        super @canvas
        @mode = @LINES_MODE
        @xAxis = data.timeFields[0]

    ###
    Build options relevent to timeline
    ###
    buildOptions: ->
        super()

        self = this

        $.extend true, @chartOptions,
            title:
                text: ''
            tooltip:
                formatter: ->
                    if self.advancedTooltips
                        str  = "<div style='width:100%;text-align:center;color:#{@series.color};'> #{@series.name.group}</div><br>"
                        str += "<table>"

                        for field, fieldIndex in data.fields when @point.datapoint[fieldIndex] isnt null
                            dat = if (Number field.typeID) is data.types.TIME
                                (globals.dateFormatter @point.datapoint[fieldIndex])
                            else
                                @point.datapoint[fieldIndex]

                            str += "<tr><td>#{field.fieldName}</td>"
                            str += "<td><strong>#{dat}</strong></td></tr>"

                        str += "</table>"
                    else
                        str  = "<div style='width:100%;text-align:center;color:#{@series.color};'> #{@series.name.group}</div><br>"
                        str += "<table>"
                        str += "<tr><td>#{@series.xAxis.options.title.text}:</td><td><strong>#{globals.dateFormatter @x}</strong></td></tr>"
                        str += "<tr><td>#{@series.name.field}:</td><td><strong>#{@y}</strong></td></tr>"
                        str += "</table>"
                useHTML: true

        @chartOptions.xAxis =
            type: 'datetime'
        
    ###
    Overwrite xAxis controls to only allow time fields
    ###
    drawXAxisControls: ->
        super (fieldIndex) -> fieldIndex in data.timeFields

if "Timeline" in data.relVis
    globals.timeline = new Timeline 'timeline_canvas'
else
    globals.timeline = new DisabledVis "timeline_canvas"