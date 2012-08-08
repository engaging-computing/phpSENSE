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

class window.Bar extends BaseHighVis
    constructor: (@canvas) ->
    
    ANALYSISTYPE_MAX:       0
    ANALYSISTYPE_MIN:       1
    ANALYSISTYPE_MEAN:      2
    ANALYSISTYPE_MEDIAN:    3
    
    analysisTypeNames: ["Max","Min","Mean","Median"];
    
    analysisType:   0
    sortField:      data.normalFields[0]
    
    buildOptions: ->
        super()
        
        self = this
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "column"
            title:
                text: "Bar"
            legend:
                symbolWidth: 0
            tooltip:
                formatter: ->
                    console.log this
                    str  = "<div style='width:100%;text-align:center;color:#{@series.color};margin-bottom:5px'> #{@point.name}</div>"
                    str += "<table>"
                    str += "<tr><td>#{@x} (#{self.analysisTypeNames[self.analysisType]}):</td><td><strong>#{@y}</strong></td></tr>"
                    str += "</table>"
                useHTML: true
                
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
        
        visibleCategories = for selection in data.normalFields when selection in globals.fieldSelection
            data.fields[selection].fieldName
        
        @chart.xAxis[0].setCategories visibleCategories, false
        
        while @chart.series.length > data.normalFields.length
            @chart.series[@chart.series.length-1].remove false
        
        ### --- ###
        
        tempGroupIDValuePairs = for groupName, groupIndex in data.groups when groupIndex in globals.groupSelection
            switch @analysisType
                when @ANALYSISTYPE_MAX      then [groupIndex, data.getMax    @sortField, groupIndex]
                when @ANALYSISTYPE_MIN      then [groupIndex, data.getMin    @sortField, groupIndex]
                when @ANALYSISTYPE_MEAN     then [groupIndex, data.getMean   @sortField, groupIndex]
                when @ANALYSISTYPE_MEDIAN   then [groupIndex, data.getMedian @sortField, groupIndex]
                
        fieldSortedGroupIDValuePairs = tempGroupIDValuePairs.sort (a,b) ->
            return if a[1] > b[1] then 1 else -1
        
        fieldSortedGroupIDs = for [groupID, groupValue] in fieldSortedGroupIDValuePairs
            groupID
        
        ### --- ###
        
        for groupIndex in fieldSortedGroupIDs when groupIndex in globals.groupSelection
            options =
                showInLegend: false
                color: globals.colors[groupIndex % globals.colors.length]
                name: data.groups[groupIndex]
                
            options.data = for fieldIndex in data.normalFields when fieldIndex in globals.fieldSelection
                switch @analysisType
                    when @ANALYSISTYPE_MAX
                        ret =
                            y:      data.getMax fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                    when @ANALYSISTYPE_MIN
                        ret =
                            y:      data.getMin fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                    when @ANALYSISTYPE_MEAN
                        ret =
                            y:      data.getMean fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                    when @ANALYSISTYPE_MEDIAN
                        ret =
                            y:      data.getMedian fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                
            @chart.addSeries options, false
        
        @chart.redraw()
        
    buildLegendSeries: ->
        count = -1
        for field, fieldIndex in data.fields when fieldIndex in data.normalFields
            count += 1
            dummy =
                data: []
                color: '#000'
                visible: if fieldIndex in globals.fieldSelection then true else false
                name: field.fieldName
                type: 'area'
                xAxis: 1
    
    drawAnalysisTypeControls: ->

        controls =  '<div id="AnalysisTypeControl" class="vis_controls">'
            
        controls += '<table class="vis_control_table"><tr><td class="vis_control_table_title">Analysis Type:</td></tr>'
        
        for [type, typestring] in [[@ANALYSISTYPE_MAX, 'Max'],[@ANALYSISTYPE_MIN, 'Min'],[@ANALYSISTYPE_MEAN, 'Mean'],[@ANALYSISTYPE_MEDIAN, 'Median']]
        
            controls += '<tr><td><div class="vis_control_table_div">'
        
            controls += "<input class='analysisType' type='radio' name='analysisTypeSelector' value='#{type}' #{if type is @analysisType then 'checked' else ''}> #{typestring}</input><br>"
        
            controls += '</div></td></tr>'
            
        ### --- ###
        
        controls += '<tr><td><div class="vis_control_table_div"><br>'
    
        controls += 'Sort by: <select class="sortField">'
        
        for fieldID in data.normalFields
        
            controls += "<option value='#{fieldID}'#{if @sortField is fieldID then ' selected' else ''}>#{data.fields[fieldID].fieldName}</option>"
        
        controls += '</select>'
    
        controls += '</div></td></tr>'
        
        controls += '</table></div>'
        
        ### --- ###
        
        # Write HTML
        ($ '#controldiv').append controls
        
        ($ '.analysisType').change (e) =>
            @analysisType = Number e.target.value
            @delayedUpdate()
            
        ($ '.sortField').change (e) =>
            @sortField = Number e.target.value
            console.log @sortField
            @delayedUpdate()
        
    drawControls: ->
        @drawGroupControls()
        @drawAnalysisTypeControls()
    

globals.bar = new Bar 'bar_canvas'