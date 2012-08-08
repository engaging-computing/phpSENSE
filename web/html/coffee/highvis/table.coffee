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

class window.Table extends BaseVis
    constructor: (@canvas) -> 

    start: ->
        #Make table visible? (or somthing)
        ($ '#' + @canvas).show()
        #Calls update
        super()

    #Gets called when the controls are clicked and at start
    update: ->
        ($ '#' + @canvas).html('')
    
        #updates controls by default       
        ($ '#' + @canvas).append '<table id="data_table"></table>'  
        
        ($ '#data_table').append '<thead><tr id="table_headers"></tr></thead>'
        
        #Build the headers for the table
        headers = for field in data.fields
            "<td>#{field.fieldName}</td>"
            
        ($ '#table_headers').append header for header in headers
        
        #Build the data for the table
        visibleGroups = for group, groupIndex in data.groups when groupIndex in globals.groupSelection
            group
        
        rows = for dataPoint in data.dataPoints when (String dataPoint[data.groupingFieldIndex]).toLowerCase() in visibleGroups
            line = for dat, fieldIndex in dataPoint 
                if((Number data.fields[fieldIndex].typeID) is data.types.TIME)
                    "<td>#{new Date(dat)}</td>"
                else 
                    "<td>#{dat}</td>"
            "<tr>#{line.reduce (a,b)-> a+b}</tr>"
        
        ($ '#data_table').append '<tbody id="table_body"></tbody>'
        
        ($ '#table_body').append row for row in rows 
        
        dt = 
            sScrollY: 400
            sScrollX: "100%"
            iDisplayLength: -1
            bDeferRender: true
            
        atable = ($ '#data_table').dataTable(dt)

        super()

    end: ->
        ($ '#' + @canvas).hide()
        #Hide table here? (or somthing)

    drawControls: ->
        @drawGroupControls()

globals.table = new Table "table_canvas"