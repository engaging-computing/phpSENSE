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

class window.Bar extends BaseVis
    constructor: (@canvas) ->
    
    analysisType: "Max"
    
    buildOptions: ->
        super()
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "column"
            title:
                text: "Bar"
            xAxis:
                categories:
                    for fieldIndex in data.normalFields
                        data.fields[fieldIndex].fieldName
        
        for groupIndex of data.groups
            options = 
                data: for fieldIndex in data.normalFields
                    data.getMax(fieldIndex, @groupFilter)
                showInLegend: false
                name: data.groups[groupIndex]
            @chartOptions.series.push options
    
    drawAnalysisTypeControls: ->

        controls =  '<div id="AnalysisTypeControl" class="vis_controls">'
            
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Analysis Type:</td></tr>'
        
        controls += '<tr><td><div class="vis_control_table_div">'
        
        controls += '<select><option>Max</option><option>Min</option><option>Mean</option></select>'
        
        controls += '</div></td></tr>'
        
        controls += '</table></div>'
        
        # Write HTML
        ($ '#controldiv').append controls
        
    drawControls: ->
        @drawGroupControls()
        @drawAnalysisTypeControls()

globals.bar = new Bar 'bar_canvas'