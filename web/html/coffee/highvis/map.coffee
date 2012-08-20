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
        
    start: ->
        ($ '#' + @canvas).show()

        super()
       
    update: ->
    
        latlngbounds = new google.maps.LatLngBounds()
        
        markers = Array()
        
        mapOptions =
            center: new google.maps.LatLng(0,0)
            zoom: 8
            mapTypeId: google.maps.MapTypeId.SATELLITE
        
        gmap = new google.maps.Map(document.getElementById("map_canvas"), mapOptions)
                                
        geo = for fields,fieldIndex in data.fields when (Number fields.typeID) is (Number data.types.GEOSPATIAL)
                (Number fieldIndex)
        
        #Get all visible data points.
        visibleGroups = for group, groupIndex in data.groups when groupIndex in globals.groupSelection
            group
        
        rows = for dataPoint in data.dataPoints when (String dataPoint[data.groupingFieldIndex]).toLowerCase() in visibleGroups
            line = for dat, fieldIndex in dataPoint when fieldIndex in geo
                dat

        for row in rows
            tmp = new google.maps.LatLng(row[0],row[1])
            m =
                position: tmp
                map: gmap   
            markers[markers.length]= new google.maps.Marker(m)
            latlngbounds.extend tmp
        
        gmap.fitBounds(latlngbounds)
        
        super()
        
    end: ->
        ($ '#' + @canvas).hide()
        
    drawControls: ->
        super()
        @drawGroupControls()
        
globals.map = new Map "map_canvas"
        