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

window.globals ?= {}

###
Removes 'item' from the array 'arr'
Returns the modified (or unmodified) arr.
###
window.arrayRemove = (arr, item) ->
    index = arr.indexOf item
    if index isnt -1
        arr.splice index, 1
    arr

###
Tests to see if a and b are within thresh%
of the smaller value.
###
window.fpEq = (a, b, thresh = 0.0001) ->
    diff = Math.abs (a - b)
    e = (Math.abs (Math.min a, b)) * thresh

    return diff < e

###
Date formatter
###
globals.dateFormatter = (dat) ->

    if isNaN dat
        return "Invalid Date"

    dat = new Date(Number dat)
    
    monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                  "Jul","Aug", "Sep", "Oct", "Nov", "Dec"]

    minDigits = (num, str) ->
        str = String str
        while str.length < num
            str = '0' + str
        str

    str = ""
    str += dat.getUTCDate()              + " "
    str += monthNames[dat.getUTCMonth()] + " "
    str += dat.getUTCFullYear()          + " "
    
    

    str += (minDigits 2, dat.getUTCHours())   + ":"
    str += (minDigits 2, dat.getUTCMinutes()) + ":"
    str += (minDigits 2, dat.getUTCSeconds()) + "."
    str += (minDigits 3, dat.getUTCMilliseconds()) + " GMT"
    
###
Cross platform accessor/mutator for element inner text
###
window.innerTextCompat = (self, value = null) ->
    if document.getElementsByTagName("body")[0].innerText?
        if value is null
            return self.innerText
        else
            self.innerText = value
    else
        if value is null
            return self.textContent
        else
            self.textContent = value
    
###
This function adds a parameterizable radial marker to Highchart's list of
marker styles.
###
addRadialMarkerStyle = (name, points, phase, magnitudes=[1]) ->

    extension = {}

    extension[name] = (x, y, w, h) ->

        svg = Array()

        verticies = Array()

        offset = phase*2*Math.PI

        modpoints = points * magnitudes.length

        for i in [0..modpoints]

            tx = (Math.sin 2*Math.PI*i/modpoints+offset) * magnitudes[i % magnitudes.length]
            ty = (Math.cos 2*Math.PI*i/modpoints+offset) * magnitudes[i % magnitudes.length]

            #console.log [tx, ty, magnitudes[i % magnitudes.length]]

            tx = tx/2+0.5
            ty = ty/2+0.5

            verticies.push [tx*w+x, ty*h+y]

        svg.push "M"
        svg.push verticies[0][0]
        svg.push verticies[0][1]
        svg.push "L"

        for [vx, vy] in verticies

            svg.push vx
            svg.push vy

        svg.push "Z"

        svg

    Highcharts.extend Highcharts.Renderer.prototype.symbols, extension
    
###
Generated using http://jiminy.medialab.sciences-po.fr/tools/palettes/palettes.php
Colors: 30
Hue:       0.0 - 360.00
Chroma:    0.0 -   1.70
Lightness: 0.3 -   0.95
K-means
###
globals.colors = ['#5E5A83', '#609B36', '#DC644F', '#9A8867', '#DA6694', '#40938C', '#A78E20', '#884646', '#546222', '#688CCF', '#529F69', '#415B62', '#AE8188', '#D1762F', '#408FB2', '#B18347', '#944B70', '#9F7FBC', '#C77967', '#914C2A', '#396B43', '#625744', '#C25562', '#735521', '#7D9080', '#715365', '#8A9044', '#C573B2', '#788AA2', '#EC5D7A']

###
Generate a list of dashes
###
globals.dashes = []

globals.dashes.push 'Solid'
globals.dashes.push 'ShortDot'
globals.dashes.push 'ShortDash'
globals.dashes.push 'Dot'

globals.dashes.push 'ShortDashShortDot'
globals.dashes.push 'DashDotDot'
globals.dashes.push 'LongDashDotDotDot'

globals.dashes.push 'LongDashDash'
 
###
Generate a list of symbols and symbol rendering routines and then add them
in an order that is clear and easy to read. 
###

fanMagList           = [1, 1, 15/16, 7/8, 3/4, 1/4, 1/4, 3/4, 7/8, 15/16, 1]
pieMagList           = [1,1,1,1,1,1,1,1,1,1,1,1,1,0]
halfmoonMagList      = [1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0]
starMagList          = [Math.sqrt(2), 2/3]

tempSymbolsMatrix = []
symbolList        = ['circle', 'square', 'diamond', '5-star', 'up-tri', 'down-tri', 'right-tri', 'left-tri', '6-star']

tempSymbolsMatrix[tempSymbolsMatrix.length] = []

addRadialMarkerStyle "blank", 1, 0, [0]

tempSymbolsMatrix[tempSymbolsMatrix.length] = []
for i in [5,6]
        addRadialMarkerStyle "#{i}-star", i, 0.5, starMagList
        #tempSymbolsMatrix[tempSymbolsMatrix.length-1].push "#{i}-star"

tempSymbolsMatrix[tempSymbolsMatrix.length] = []
for i in [2,3,4,6]
	addRadialMarkerStyle "#{i}-fan", i, 0, fanMagList
	tempSymbolsMatrix[tempSymbolsMatrix.length-1].push "#{i}-fan"

tempSymbolsMatrix[tempSymbolsMatrix.length] = []
for [phase, direction] in [[0, "down"],[1/4, "right"],[2/4, "up"],[3/4, "left"]]
	addRadialMarkerStyle "#{direction}-tri", 3, phase, [Math.sqrt(2)]
	#tempSymbolsMatrix[tempSymbolsMatrix.length-1].push "#{direction}-tri"

tempSymbolsMatrix[tempSymbolsMatrix.length] = []
for i in [2,3,4,5]
	addRadialMarkerStyle "#{i}-pie", i, 0, pieMagList
	tempSymbolsMatrix[tempSymbolsMatrix.length-1].push "#{i}-pie"
	
tempSymbolsMatrix[tempSymbolsMatrix.length] = []
for [phase, direction] in[[0, "right"],[1/4, "up"],[2/4, "left"],[3/4, "down"]]
	addRadialMarkerStyle "#{direction}-halfmoon", 1, phase, halfmoonMagList
	tempSymbolsMatrix[tempSymbolsMatrix.length-1].push "#{direction}-halfmoon"

while tempSymbolsMatrixCount != 0
    tempSymbolsMatrixCount = tempSymbolsMatrix.length
    for index in [0...tempSymbolsMatrix.length]
	    if tempSymbolsMatrix[index].length == 0
            tempSymbolsMatrixCount -= 1
        else
            symbolList.push tempSymbolsMatrix[index][0]
            tempSymbolsMatrix[index].splice 0, 1


###
Store the list
###
globals.symbols = symbolList

###
Generates an elapsed time field with given name from given
time field.
###
data.generateElapsedTime = (name, sourceField) ->
    timeMins = []

    for group in data.groups
        timeMins.push Number.MAX_VALUE

    for datapoint in data.dataPoints
        group = data.groups.indexOf (String datapoint[@groupingFieldIndex]).toLowerCase()
        time = datapoint[sourceField].valueOf()
        timeMins[group] = Math.min timeMins[group], datapoint[sourceField]

    for datapoint in data.dataPoints
        group = data.groups.indexOf (String datapoint[@groupingFieldIndex]).toLowerCase()
        curTime = datapoint[sourceField].valueOf()
        datapoint.push (curTime - timeMins[group]) / 1000.0

    data.fields.push
            fieldID: -1
            fieldName: name
            typeID: 21
            typeName: 'Numeric'
            unitAbbreviation: 'ms'
            unitID: 66
            unitName: "Number"

    data.numericFields.push (data.fields.length - 1)
    data.normalFields.push (data.fields.length - 1)
    
    if globals.scatter instanceof DisabledVis
      delete globals.scatter
      globals.scatter = new Scatter "scatter_canvas"
      ($ "#visTabList li[aria-controls='scatter_canvas'] a").css "text-decoration", ""
      
    globals.scatter.xAxis = data.normalFields[data.normalFields.length - 1]
    ($ "#visTabList li[aria-controls='scatter_canvas'] a").click()

###
If there is only one time field, generates an appropriate
elapsed time field. Otherwise it prompts using a dialog for
which time field to use.
###
globals.generateElapsedTimeDialog = ->

    if data.timeFields.length is 1
        name  = 'Elapsed Time ('
        name += data.fields[data.timeFields[0]].fieldName + ')'
        data.generateElapsedTime name, data.timeFields[0]
        globals.curVis.end()
        globals.curVis.start()
        return

    formText = """
    <div id="dialog-form" title="Generate Elapsed Time">

        <form>
        <fieldset>
    """

    formText += '<select id="timeSelector" class="control_select">'

    for fieldIndex, index in data.timeFields
            sel = if index is 0 then 'selected' else ''
            formText += "<option value='#{Number fieldIndex}' #{sel}>#{data.fields[fieldIndex].fieldName}</option>"
    
    formText += """
        </fieldset>
        </form>
    </div>
    """

    selectedTime = data.timeFields[0]
    
    ($ '#groupSelector').change (e) =>
        element = e.target or e.srcElement
        selectedTime = (Number element.value)

    ($ "#container").append(formText)
    
    ($ "#dialog-form" ).dialog
        resizable: false
        draggable: false
        autoOpen: true
        height: 'auto'
        width: 'auto'
        modal: true
        buttons:
            Generate: =>
                name  = 'Elapsed Time ('
                name += data.fields[selectedTime].fieldName + ')'
                data.generateElapsedTime name, selectedTime
                globals.curVis.end()
                globals.curVis.start()
                ($ "#dialog-form").dialog 'close'
        close: ->
            ($ "#dialog-form").remove()
        
###
Generate an axis label
###
globals.getAxisLabel = (fieldIndex) ->
  if fieldIndex in data.timeFields
    data.fields[fieldIndex].fieldName
  else
    "#{data.fields[fieldIndex].fieldName} (#{data.fields[fieldIndex].unitAbbreviation})"
        
###
Override default highcarts zoom behavior (because it sucks when allowing zoom out)
###
Highcharts.Axis.prototype.zoom = (newMin, newMax) ->

  this.displayBtn = newMin != undefined || newMax != undefined
  
  this.setExtremes newMin, newMax, true, undefined, {trigger: 'zoom'}
  
  true
