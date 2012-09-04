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
    ANALYSISTYPE_COUNT:     4
    ANALYSISTYPE_TOTAL:     5
    
    analysisTypeNames: ["Max","Min","Mean","Median","Count","Total"]
    
    analysisType:   0
    sortField:      null
    
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
                    str  = "<div style='width:100%;text-align:center;color:#{@series.color};margin-bottom:5px'> #{@point.name}</div>"
                    str += "<table>"
                    str += "<tr><td>#{@x} (#{self.analysisTypeNames[self.analysisType]}):</td><td><strong>#{@y}</strong></td></tr>"
                    str += "</table>"
                useHTML: true
            
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
                when @ANALYSISTYPE_MAX      then [groupIndex, data.getMax       @sortField, groupIndex]
                when @ANALYSISTYPE_MIN      then [groupIndex, data.getMin       @sortField, groupIndex]
                when @ANALYSISTYPE_MEAN     then [groupIndex, data.getMean      @sortField, groupIndex]
                when @ANALYSISTYPE_MEDIAN   then [groupIndex, data.getMedian    @sortField, groupIndex]
                when @ANALYSISTYPE_COUNT    then [groupIndex, data.getCount     @sortField, groupIndex]
                when @ANALYSISTYPE_TOTAL    then [groupIndex, data.getTotal     @sortField, groupIndex]
        
        if @sortField != null
            fieldSortedGroupIDValuePairs = tempGroupIDValuePairs.sort (a,b) ->
                if a[1] > b[1] then 1 else -1
        
            fieldSortedGroupIDs = for [groupID, groupValue] in fieldSortedGroupIDValuePairs
                groupID
        else
            fieldSortedGroupIDs = for groupName, groupID in data.groups
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
                    when @ANALYSISTYPE_COUNT
                        ret =
                            y:      data.getCount fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                    when @ANALYSISTYPE_TOTAL
                        ret =
                            y:      data.getTotal fieldIndex, groupIndex
                            name:   data.groups[groupIndex]
                
            @chart.addSeries options, false
        
        @chart.redraw()
        
    buildLegendSeries: ->
        count = -1
        for field, fieldIndex in data.fields when fieldIndex in data.normalFields
            count += 1
            dummy =
                legendIndex: fieldIndex
                data: []
                color: '#000'
                visible: if fieldIndex in globals.fieldSelection then true else false
                name: field.fieldName
                type: 'area'
                xAxis: 1
    
    drawToolControls: ->

        controls =  '<div id="toolControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Tools:</a></h3>"
        controls += "<div class='outer_control_div'>"

        controls += "<div class='inner_control_div'>"
        controls += 'Sort by: <select class="sortField control_select">'

        tempFields = for fieldID in data.normalFields
            [fieldID, data.fields[fieldID].fieldName]

        tempFields = [].concat [[null, 'Group Name']], tempFields

        for [fieldID, fieldName] in tempFields

            controls += "<option value='#{fieldID}'#{if @sortField is fieldID then ' selected' else ''}>#{fieldName}</option>"

        controls += '</select></div><br>'
        
        controls += "<h4 class='clean_shrink'>Analysis Type</h4>"
        
        for typestring, type in @analysisTypeNames
        
            controls += '<div class="inner_control_div">'
        
            controls += "<input class='analysisType' type='radio' name='analysisTypeSelector' value='#{type}' #{if type is @analysisType then 'checked' else ''}> #{typestring}</input><br>"
        
            controls += '</div>'
            
        controls += '</div></div>'
        
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

        #Set up accordion
        globals.toolsOpen ?= 0

        ($ '#toolControl').accordion
            collapsible:true
            active:globals.toolsOpen

        ($ '#toolControl > h3').click ->
            globals.toolsOpen = (globals.toolsOpen + 1) % 2
        
    drawControls: ->
        super()
        @drawGroupControls()
        @drawToolControls()
    

if "Bar" in data.relVis
    globals.bar = new Bar 'bar_canvas'
else
    globals.bar = new DisabledVis "bar_canvas"
