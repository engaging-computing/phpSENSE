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
        @MAX_NUM_BINS = 1000
    
        @displayField = data.normalFields[0]
        @binNumSug = 1
        @binSize = @defaultBinSize()
        
    
    buildOptions: ->
        super()
        
        self = this
        
        @chartOptions
        $.extend true, @chartOptions,
            chart:
                type: "column"
            legend:
                symbolWidth: 0
            title:
                text: ""
            tooltip:
                formatter: ->
                    str  = "<table>"
                    str += "<tr><td>Bin Location:</td><td>#{@x}<td></tr>"
                    str += "<tr><td>Bin Total:</td><td>#{@total}<td></tr>"
                    if @y isnt 0
                        str += "<tr><td><div style='color:#{@series.color};'> #{@series.name}:</div></td>"
                        str += "<td>#{@y}</td></tr>"
                    str += "</table>"
                useHTML: true
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

        bestSize = curSize
        bestNum  = range / curSize

        binNumTarget = Math.pow 10, @binNumSug

        tryNewSize = (size) =>
            if (Math.abs (binNumTarget - (range / size))) < (Math.abs (binNumTarget - bestNum))
                bestSize = size
                bestNum  = range / size

                return true
            false
        
        loop 
            if (range / curSize) < binNumTarget
                curSize /= 10
            else if (range / curSize) > binNumTarget
                curSize *= 10

            break if not tryNewSize curSize

        tryNewSize (curSize / 2)
        tryNewSize (curSize * 2)
        tryNewSize (curSize / 5)
        tryNewSize (curSize * 5)

        bestSize

            
            
    
    update: ->
        super()
        
        while @chart.series.length > data.normalFields.length
            @chart.series[@chart.series.length-1].remove false
        
        ### --- ###
        
        @globalmin = Number.MAX_VALUE
        @globalmax = Number.MIN_VALUE
        
        for groupIndex in globals.groupSelection
            
            min = data.getMin @displayField, groupIndex
            min = Math.round(min/@binSize)*@binSize
            @globalmin = Math.min @globalmin, min

            max = data.getMax @displayField, groupIndex
            max = Math.round(max/@binSize)*@binSize
            @globalmax = Math.max @globalmax, max

        #### Make 'fake' data to ensure proper bar spacing ###
        fakeDat = for i in [@globalmin...@globalmax] by @binSize
            [i, 0]

        options =
            showInLegend: false
            data: fakeDat
        @chart.addSeries options, false
        ### ###

        # Generate all bin data
        binObjs = {}
        
        for groupIndex in globals.groupSelection
        
            selecteddata = data.selector @displayField, groupIndex
        
            binArr = for i in selecteddata
                Math.round(i/@binSize)*@binSize

            binObjs[groupIndex] = {}
        
            for bin in binArr
                binObjs[groupIndex][bin] ?= 0
                binObjs[groupIndex][bin]++

        # Convert bin data into series data
        for groupIndex in globals.groupSelection
        
            finalData = for number, occurences of binObjs[groupIndex]

                sum = 0

                # Get total for this bin
                for dc, groupData of binObjs
                    if groupData[number]
                        sum += groupData[number]

                ret =
                    x: (Number number)
                    y: occurences
                    total: sum
        
            ### --- ###

            options =
                showInLegend: false
                color: globals.colors[groupIndex % globals.colors.length]
                name: data.groups[groupIndex]
                data: finalData
                        
            @chart.addSeries options, false
            
        @chart.xAxis[0].setExtremes @globalmin-(@binSize/2), @globalmax+(@binSize/2), false
        
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
        
        controls += "<div class='outer_control_div'>"

        controls += "<h4 class='clean_shrink'>Bin Size</h4>"

        controls += "Automatic : <br>"
        controls += "<div id='binSizeSlider' style='width:90%;margin-left:5%'></div><br>"

        
        controls += "Manual: <input id='binSizeInput' class='control_select' value='#{@binSize}'></input>"
        
        controls += '</div></div></div>'
        
        
        # Write HTML
        ($ '#controldiv').append controls

        #Set up slider
        ($ '#binSizeSlider').slider
            range: 'min'
            value: @binNumSug
            min: .5
            max: 2.2
            step: .1
            slide: (event, ui) =>
                @binNumSug = Number ui.value

                newBinSize = @defaultBinSize()
                
                if not fpEq newBinSize, @binSize
                    @binSize = newBinSize
                    ($ '#binSizeInput').attr("value", "#{@binSize}")
                    @delayedUpdate()
        
        #Set up accordion
        globals.toolsOpen ?= 0

        ($ '#toolControl').accordion
            collapsible:true
            active:globals.toolsOpen

        ($ '#toolControl > h3').click ->
            globals.toolsOpen = (globals.toolsOpen + 1) % 2
            
        ($ "#binSizeInput").keydown =>
            if event.keyCode == 13
                newBinSize = Number ($ '#binSizeInput').val()

                if isNaN newBinSize
                    alert "Please enter a valid number."
                    return
            
                if newBinSize <= 0
                    alert "Please enter a positive bin size."
                    return

                if ((@globalmax - @globalmin) / newBinSize) < @MAX_NUM_BINS
                    @binSize = newBinSize
                    @update()
                else
                    alert "Entered bin size would result in too many bins."
                    
        
    drawControls: ->
        super()
        @drawGroupControls()
        @drawToolControls()
    
if "Histogram" in data.relVis
    globals.histogram = new Histogram 'histogram_canvas'
else
    globals.histogram = new DisabledVis 'histogram_canvas'