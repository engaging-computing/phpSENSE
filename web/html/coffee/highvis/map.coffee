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

class window.Map extends BaseVis
    constructor: (@canvas) ->
        @HEATMAP_NONE = -2
        @HEATMAP_MARKERS = -1

        @visibleMarkers = 1
        @heatmapSelection = @HEATMAP_NONE

        @heatmapRadius = 30

    serializationCleanup: ->
        delete @gmap
        delete @heatPoints
        delete @markers
        delete @heatmap
    
    start: ->
        ($ '#' + @canvas).show()

        # Remove old handlers if they exist
        if @markers?
            for group in @markers
                for marker in group
                    google.maps.event.clearInstanceListeners marker
        
        @markers = []
        for group in data.groups
            @markers.push []

        @heatmaps = {}
        @heatPoints = {}
        @heatPoints[@HEATMAP_NONE] = []
        @heatPoints[@HEATMAP_MARKERS] = []
        for index in data.normalFields
            @heatPoints[index] = []

        for index of @heatPoints
            for group in data.groups
                @heatPoints[index].push []
        ###############################
        latlngbounds = new google.maps.LatLngBounds()

        mapOptions =
            center: new google.maps.LatLng(0,0)
            zoom: 8
            mapTypeId: google.maps.MapTypeId.SATELLITE

        @gmap = new google.maps.Map(document.getElementById(@canvas), mapOptions)
        
        for dataPoint in data.dataPoints
            lat = lon = null
            do =>
                # Grab geospatial
                for field, fieldIndex in data.fields
                    if (Number field.typeID) is data.types.GEOSPATIAL
                        if (Number field.unitID) is data.units.GEOSPATIAL.LATITUDE
                            lat = dataPoint[fieldIndex]
                        else if (Number field.unitID) is data.units.GEOSPATIAL.LONGITUDE
                            lon = dataPoint[fieldIndex]

                if (lat is null) or (lon is null)
                    return

                groupIndex = data.groups.indexOf dataPoint[data.groupingFieldIndex].toLowerCase()
                color = globals.colors[groupIndex % globals.colors.length]

                latlng = new google.maps.LatLng(lat, lon)

                # Build info window content
                label  = "<div style='font-size:9pt;overflow-x:none;'>"
                label += "<div style='width:100%;text-align:center;color:#{color};'> #{dataPoint[data.groupingFieldIndex]}</div>"#<br>"
                label += "<table>"

                for field, fieldIndex in data.fields when dataPoint[fieldIndex] isnt null
                    dat = if (Number field.typeID) is data.types.TIME
                        (globals.dateFormatter dataPoint[fieldIndex])
                    else
                        dataPoint[fieldIndex]

                    label += "<tr><td>#{field.fieldName}</td>"
                    label += "<td><strong>#{dat}</strong></td></tr>"

                label += "</table></div>"

                # make infowindow
                info = new google.maps.InfoWindow
                    content: label
                    
                if groupIndex in globals.groupSelection
                    latlngbounds.extend latlng

                newMarker = new StyledMarker
                  styleIcon: (new StyledIcon StyledIconTypes.MARKER, {color: color})
                  position: latlng
                  map: @gmap

                google.maps.event.addListener newMarker, 'click', =>
                    info.open @gmap, newMarker
                
                @markers[groupIndex].push newMarker

                for index in data.normalFields when dataPoint[index] isnt null
                    @heatPoints[index][groupIndex].push
                        weight: dataPoint[index]
                        location: latlng

                @heatPoints[@HEATMAP_MARKERS][groupIndex].push latlng

        @gmap.fitBounds(latlngbounds)

        
        super()
       
    update: ->

        # Build heatmap points from pre-computed data
        heats = []
        for index, heatArray of @heatPoints when (Number index) is @heatmapSelection
            for groupArray, groupIndex in heatArray when groupIndex in globals.groupSelection
                heats = heats.concat groupArray
        
        # Disable old heatmap (if there)
        if @heatmap?
            @heatmap.setMap null

        # Draw heatmap
        @heatmap = new google.maps.visualization.HeatmapLayer
            data: heats
            radius: @heatmapRadius
            dissipating:true
        @heatmap.setMap @gmap

        # Set marker visibility
        for markGroup, index in @markers
            for mark in markGroup
                mark.setVisible ((index in globals.groupSelection) and @visibleMarkers is 1)
        
        
        super()
        
    end: ->
        ($ '#' + @canvas).hide()
        @heatmap = undefined
        
    drawControls: ->
        super()
        @drawGroupControls(true)
        @drawToolControls()
        @drawSaveControls()

    drawToolControls: ->
        controls =  '<div id="toolControl" class="vis_controls">'

        controls += "<h3 class='clean_shrink'><a href='#'>Tools:</a></h3>"
        controls += "<div class='outer_control_div'>"
        
        controls += "<h4 class='clean_shrink'>Heat Maps</h4>"

        # Add heatmap selector
        controls += '<div class="inner_control_div"> Map By: '
        controls += '<select id="heatmapSelector" class="control_select">'

        sel = if @heatmapSelection is @HEATMAP_NONE then 'selected' else ''
        controls += "<option value=\"#{@HEATMAP_NONE}\" #{sel}>None</option>"
        sel = if @heatmapSelection is @HEATMAP_MARKERS then 'selected' else ''
        controls += "<option value=\"#{@HEATMAP_MARKERS}\" #{sel}>Location</option>"
        
        for fieldIndex in data.normalFields
            sel = if @heatmapSelection is fieldIndex then 'selected' else ''
            controls += "<option value=\"#{Number fieldIndex}\" #{sel}>#{data.fields[fieldIndex].fieldName}</option>"

        controls += "</select></div>"

        #Heatmap slider
        controls += "<br>"
        controls += "<div class='inner_control_div'> Heatmap Radius: "
        controls += "<b id='radiusText'>#{@heatmapRadius}</b></div>"
        controls += "<div id='heatmapSlider' style='width:95%'></div>"

        # Other
        controls += "<br>"
        controls += "<h4 class='clean_shrink'>Other</h4>"

        #marker checkbox
        controls += '<div class="inner_control_div">'
        controls += "<input id='markerBox' type='checkbox' name='marker_selector' #{if @visibleMarkers is 1 then 'checked' else ''}/> Markers "
        controls += "</div></div></div>"

        # Write HTML
        ($ '#controldiv').append controls

        ($ '#markerBox').click (e) =>
            @visibleMarkers = (@visibleMarkers + 1) % 2
            @delayedUpdate()

        # Make heatmap select handler
        ($ '#heatmapSelector').change (e) =>
            element = e.target or e.srcElement
            @heatmapSelection = (Number element.value)
            
            @delayedUpdate()

        #Set up slider
        ($ '#heatmapSlider').slider
            range: 'min'
            value: @heatmapRadius
            min: 5
            max: 150
            step: 5
            slide: (event, ui) =>
                @heatmapRadius = Number ui.value
                ($ '#radiusText').html("#{@heatmapRadius}")
                @delayedUpdate()
        
        #Set up accordion
        globals.toolsOpen ?= 0

        ($ '#toolControl').accordion
            collapsible:true
            active:globals.toolsOpen

        ($ '#toolControl > h3').click ->
            globals.toolsOpen = (globals.toolsOpen + 1) % 2

    resize: (newWidth, newHeight, duration) ->
        func = =>
            google.maps.event.trigger @gmap, 'resize'
        setTimeout func, duration
        
if "Map" in data.relVis
    globals.map = new Map "map_canvas"
else
    globals.map = new DisabledVis "map_canvas"
        