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
    
        @displayField = data.normalFields[0]
        @binSize = @defaultBinSize()
        
    
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
                           self.binSize = self.defaultBinSize()
                           ($ "#binSizeInput").attr('value', self.binSize)

                           self.delayedUpdate()
            
    ###
    Returns a rough default 'human-like' bin size selection
    ###
    defaultBinSize: ->
        lowBound = 10
        highBound = 35

        min = Number.MAX_VALUE
        max = Number.MIN_VALUE

        for groupIndex in globals.groupSelection

            localMin = data.getMin @displayField, groupIndex
            if localMin isnt null
                min = Math.min min, localMin

            localMax = data.getMax @displayField, groupIndex
            if localMax isnt null
                max = Math.max max, localMax

        range = max - min

        # No data
        if max < min
            return 1
        
        curSize = 1

        done = (s) -> (highBound > (range / s) > lowBound)
        
        while not (done curSize)
        
            # Bins too big
            if (range / curSize) < lowBound

                if (done (curSize / 2))
                    return curSize / 2

                if (done (curSize / 5))
                    return curSize / 5

                curSize /= 10

            # Bins too small
            else if (range / curSize) > highBound

                if (done (curSize * 2))
                    return curSize * 2

                if (done (curSize * 5))
                    return curSize * 5

                curSize *= 10

        curSize
                
    
    update: ->
        super()
        
        while @chart.series.length > data.normalFields.length
            @chart.series[@chart.series.length-1].remove false
        
        ### --- ###
        
        globalmin = Number.MAX_VALUE
        globalmax = Number.MIN_VALUE
        
        for groupIndex in globals.groupSelection
            
            min = data.getMin @displayField, groupIndex
            min = Math.round(min/@binSize)*@binSize
            globalmin = Math.min globalmin, min

            max = data.getMax @displayField, groupIndex
            max = Math.round(max/@binSize)*@binSize
            globalmax = Math.max globalmax, max

        #### Make 'fake' data to ensure proper bar spacing ###
        fakeDat = for i in [globalmin...globalmax] by @binSize
            [i, 0]

        options =
            showInLegend: false
            data: fakeDat
        @chart.addSeries options, false
        ### ###
        
        for groupIndex in globals.groupSelection
        
            selecteddata = data.selector @displayField, groupIndex
        
            binArr = for i in selecteddata
                Math.round(i/@binSize)*@binSize

            tempData = {}
        
            for bin in binArr
                tempData[bin] ?= 0
                tempData[bin]++
            
            finalData = for number, occurences of tempData
                [(Number number), occurences]
        
            ### --- ###

            options =
                showInLegend: false
                color: globals.colors[groupIndex % globals.colors.length]
                name: data.groups[groupIndex]
                data: finalData
                        
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
        
        controls += '</div></div></div>'
        
        
        # Write HTML
        ($ '#controldiv').append controls

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