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
    
    ANALYSISTYPE_MAX:       0
    ANALYSISTYPE_MIN:       1
    ANALYSISTYPE_MEAN:      2
    ANALYSISTYPE_MEDIAN:    3
    
    analysisType: 0
    
    buildOptions: ->
        super()
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "column"
            title:
                text: "Bar"
            legend:
                symbolWidth: 0
                
            ###
            xAxis:
                categories:
                    for fieldIndex in data.normalFields when (fieldIndex in globals.fieldSelection)
                        data.fields[fieldIndex].fieldName
        
            #if (groupIndex in globals.groupSelection) and (fieldIndex in globals.fieldSelection)
        
            for fieldIndex, categoryIndex in data.normalFields
                for groupName, groupIndex in data.groups when ((groupIndex in globals.groupSelection) and (fieldIndex in globals.fieldSelection))
                    options =
                        data: [
                            x: categoryIndex
                            y: data.getMax fieldIndex, groupIndex
                            ]
                        showInLegend: false
                        color: globals.colors[groupIndex % globals.colors.length]
                        name: data.groups[groupIndex] + data.fields[fieldIndex].fieldName
                    @chartOptions.series.push options
            ###
            
    update: ->
        super()
        
        visibleCategories = for selection in globals.fieldSelection
            data.fields[selection].fieldName
        
        @chart.xAxis[0].setCategories visibleCategories, false
        
        while @chart.series.length > data.normalFields.length
            @chart.series[@chart.series.length-1].remove false
        
        
        ###
        categoryIndex = -1
        for fieldIndex in data.normalFields when fieldIndex in globals.fieldSelection
            categoryIndex += 1
            
            for groupName, groupIndex in data.groups when groupIndex in globals.groupSelection
                options =
                    data: [
                        x: categoryIndex
                        y: data.getMax fieldIndex, groupIndex
                        ]
                    showInLegend: false
                    color: globals.colors[groupIndex % globals.colors.length]
                    name: data.groups[groupIndex] + data.fields[fieldIndex].fieldName
                    
                @chart.addSeries options, false
        ###
        
        for groupName, groupIndex in data.groups when groupIndex in globals.groupSelection
            options =
                showInLegend: false
                color: globals.colors[groupIndex % globals.colors.length]
                name: data.groups[groupIndex]
                
            options.data = for fieldIndex in data.normalFields when fieldIndex in globals.fieldSelection
                switch @analysisType
                    when @ANALYSISTYPE_MAX      then data.getMax    fieldIndex, groupIndex
                    when @ANALYSISTYPE_MIN      then data.getMin    fieldIndex, groupIndex
                    when @ANALYSISTYPE_MEAN     then data.getMean   fieldIndex, groupIndex
                    when @ANALYSISTYPE_MEDIAN   then data.getMedian fieldIndex, groupIndex
                
            @chart.addSeries options, false
        
        @chart.redraw()
        
    buildLegendSeries: ->
        count = -1
        for field in data.fields when (Number field.typeID) not in [37, 7]
            count += 1
            dummy =
                data: []
                color: '#000'
                name: field.fieldName
                type: 'area'
                xAxis: 1
    
    drawAnalysisTypeControls: ->

        controls =  '<div id="AnalysisTypeControl" class="vis_controls">'
            
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Analysis Type:</td></tr>'
        
        for [type, typestring] in [[@ANALYSISTYPE_MAX, 'Max'],[@ANALYSISTYPE_MIN, 'Min'],[@ANALYSISTYPE_MEAN, 'Mean'],[@ANALYSISTYPE_MEDIAN, 'Median']]
        
            controls += '<tr><td><div class="vis_control_table_div">'
        
            controls += "<input class='analysisType' type='radio' name='analysisTypeSelector' value='#{type}' #{if type is @analysisType then 'checked' else ''}>#{typestring}</input><br>"
        
            controls += '</div></td></tr>'
        
        controls += '</table></div>'
        
        # Write HTML
        ($ '#controldiv').append controls
        
        ($ '.analysisType').change (e) =>
            @analysisType = Number e.target.value
            @delayedUpdate()
        
    drawControls: ->
        @drawGroupControls()
        @drawAnalysisTypeControls()
    

globals.bar = new Bar 'bar_canvas'