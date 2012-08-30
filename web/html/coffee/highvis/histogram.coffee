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

class window.Histogram extends BaseHighVis
    constructor: (@canvas) ->
    
    binSize: 10000
    displayField: data.normalFields[0]
    
    buildOptions: ->
        super()
        
        self = this
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "column"
            title:
                text: "Histogram"
            legend:
                symbolWidth: 0
            plotOptions:
                column:
                    stacking: 'normal'
                    groupPadding: 0
                    pointPadding: 0
                series:
                    events:
                        legendItemClick: do => (event) ->

                           self.displayField = this.options.legendIndex

                           self.delayedUpdate()
            
            
    update: ->
        super()
        
        while @chart.series.length > data.normalFields.length
            @chart.series[@chart.series.length-1].remove false
        
        ### --- ###
        
        globalmin = Number.MAX_VALUE
        globalmax = Number.MIN_VALUE
        
        for groupIndex in globals.groupSelection
        
            selecteddata = data.selector @displayField, groupIndex
        
            tempdata = for i in selecteddata
                Math.round(i/@binSize)*@binSize
            
            tempdict = {}
        
            roundedmin = Math.round((data.getMin @displayField, groupIndex)/@binSize)*@binSize
            roundedmax = Math.round((data.getMax @displayField, groupIndex)/@binSize)*@binSize
            
            globalmin = Math.min globalmin, roundedmin
            globalmax = Math.max globalmax, roundedmax
        
            for i in [roundedmin..roundedmax] by @binSize
                tempdict[i] = 0;
        
            for i in tempdata
                tempdict[i]++
            
            tempdata = histogramdata = for number, occurences of tempdict
                [(Number number), occurences]
        
            ### --- ###

            options =
                showInLegend: false
                color: globals.colors[groupIndex % globals.colors.length]
                name: data.groups[groupIndex]
                
            options.data = tempdata
                        
            @chart.addSeries options, false
            
        @chart.xAxis[0].setExtremes globalmin-(@binSize/2), globalmax+(@binSize/2), false
        
        @chart.redraw()
        
    buildLegendSeries: ->
        count = -1
        for field, fieldIndex in data.fields when fieldIndex in data.normalFields
            count += 1
            dummy =
                data: []
                color: '#000'
                visible: @displayField is fieldIndex
                name: field.fieldName
                type: 'area'
                xAxis: 1
                legendIndex: fieldIndex
    
    drawToolControls: ->
        
        controls = ""
        
        # --- #
         
        controls +=  '<div id="toolControl" class="vis_controls">'
         
        controls += "<h3 class='clean_shrink'><a href='#'>Tools:</a></h3>"

        controls += "<div class='inner_control_div'>"
            
        controls += "Bin Size: <input id='binSizeInput' type='text' value='#{@binSize}' size='#{4}'></input>"
        
        controls += '</div>'
        
        controls += '</div>'
        
        controls += '</div>'
        
        
        ###
        for typestring, type in @analysisTypeNames
        
            controls += '<div class="inner_control_div">'
        
            controls += "<input class='analysisType' type='radio' name='analysisTypeSelector' value='#{type}' #{if type is @analysisType then 'checked' else ''}> #{typestring}</input><br>"
        
            controls += '</div>'
        ###
        
        
        # Write HTML
        ($ '#controldiv').append controls
        
        
        ###
        ($ '.analysisType').change (e) =>
            @analysisType = Number e.target.value
            @delayedUpdate()
            
        ($ '.sortField').change (e) =>
            @sortField = Number e.target.value
            @delayedUpdate()
        ###

        #Set up accordion
        globals.toolsOpen ?= 0

        ($ '#toolControl').accordion
            collapsible:true
            active:globals.toolsOpen

        ($ '#toolControl > h3').click ->
            globals.toolsOpen = (globals.toolsOpen + 1) % 2
            
        ($ "#binSizeInput").keydown =>
            if event.keyCode == 13
                @binSize = Number ($ '#binSizeInput').val()
                @update()
        
    drawControls: ->
        super()
        @drawGroupControls()
        @drawToolControls()
    
if "Histogram" in data.relVis
    globals.histogram = new Histogram 'histogram_canvas'
else
    globals.histogram = new DisabledVis 'histogram_canvas'