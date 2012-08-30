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

class window.Photos extends BaseVis
    constructor: (@canvas) -> 

    start: ->
        ($ '#' + @canvas).show()
        
        #Hide the controls
        @controlWidth = ($ '#controldiv').width()
        @hideControls()
        
        super()
 
    #Gets called when the controls are clicked and at start
    update: ->
        #clear the old canvas
        ($ '#' + @canvas).html('')
            
        #Append the photoTable to the canvas    
        ($ '#' + @canvas).append '<div id="photoTable"></div>'
        
        #Build the table of pictures with their onlick handlers
        i=0
        for ses of data.metaData
            if data.metaData[ses].pictures.length > 0
                for pic of data.metaData[ses].pictures
                    tmp = data.metaData[ses].pictures[pic]
                    session = data.metaData[ses]
                    do (tmp,session) =>
                        thumb = "<img id='pic_#{i}' class='photoTable_photo' src='#{tmp.provider_url}'/>"
                        full = "<img id='fullpic_#{i}' class='photoTable_openPhoto' src='#{tmp.provider_url}'/>"
                        ($ '#photoTable').append thumb
                        ($ '#pic_'+i).click ->
                            description = if(tmp.description != null)
                                tmp.description
                            else
                                "Description not provided."
                            ($ '#photoTable').append("<div id='dialog' style='max-width:800px;overflow-x:hidden'><table><tr><td style='text-align:center'>#{full}</td></tr><tr><td style='max-width:100%;word-wrap:break-word;'><b>Description: </b>#{description}</td></tr></table></div>")
                            console.log ($ '#fullpic_'+i).width()
                            ($ '#dialog').dialog
                                modal: true
                                draggable:false
                                width: 'auto'
                                height: 'auto'
                                resizable:false
                                title: "Session: #{session.name} (#{session.session_id})"
                        i++
                      
    end: ->    
        ($ '#' + @canvas).hide()
        @unhideControls(@controlWidth)
        
    drawControls: ->
        super()
        
if "Photos" in data.relVis
    globals.photos = new Photos "photos_canvas"
else
    globals.photos = new DisabledVis "photos_canvas"