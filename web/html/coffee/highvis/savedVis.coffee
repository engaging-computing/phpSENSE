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
Ajax call to save the vis with the given title and description.
Calls the appropriate callback upon completetion. A failed attempt
will pass the callback an error string, a success will pass the callback
a string with the new VID.
###
globals.saveVis = (title, desc, succCallback, failCallback) ->
    savedData = globals.serializeVis()

    req = $.ajax
        type: 'POST'
        url: "/actions/highvis.php"
        data:
            action: "save"
            experiment_id: Number data.experimentID
            title: title
            description: desc
            data: savedData.data
            globals: savedData.globals
        success: (msg) ->
            if not isNaN Number msg
                succCallback(msg)
            else
                failCallback(msg)

###
Ajax call to check if the user is logged in. Calls the appropriate
given callback when completed.
###
globals.verifyUser = (succCallback, failCallback)->

    req = $.ajax
        type: 'GET'
        url: "/actions/users.php"
        data:
            action: "verify"
        success: (data) ->
            if (Number data) is 1
                succCallback(data)
            else
                failCallback(data)

###
Creates a saved vis dialog form. If the user finishes creating the new saved vis, the dialog will close
and the page will be re-directed to the new saved vis.
###
globals.savedVisDialog = ->

    formText = """
    <div id="dialog-form" title="Save Visualization">

        <form>
        <fieldset>
            <label for="title">Title:</label>
            <input type="text" size="45 maxlength="64" name="title" id="savedVisTitle" class="text ui-widget-content ui-corner-all" /> <br> <span id="titleHint" class="hint"> </span> <br>
            
            <label for="desc">Description:</label> <br>
            <textarea type="text" name="desc" maxlength="512" rows="10" cols="50" id="savedVisDesc" class="text ui-widget-content ui-corner-all" />
            <br>
        </fieldset>
        </form>
    </div>
    """
    
    ($ "#container").append(formText)

    
    
    ($ "#dialog-form" ).dialog
        resizable: false
        draggable: false
        autoOpen: true
        height: 'auto'
        width: 'auto'
        modal: true
        open: ->
            ($ "#dialog-form form").submit (evt) ->
                ($ "#dialog-form").parent().find('button').trigger "click"
                false
        buttons :
            Save: ->
                valid = true
                ($ '#dialog-form input').removeClass 'ui-state-error'
                ($ '#dialog-form .hint').text('')
            
                if not (0 < ($ '#savedVisTitle').val().length <= 64)
                    ($ '#savedVisTitle').addClass 'ui-state-error'
                    ($ '#titleHint').text 'Title cannot be ommitted.'
                    ($ '#titleHint').addClass 'ui-state-highlight'
                    setTimeout (-> ($ '#titleHint').removeClass 'ui-state-highlight', 1500), 500
                    valid = false

                if valid
                    (globals.saveVis (
                        ($ '#savedVisTitle').val()  ),(
                        ($ '#savedVisDesc').val()   ),(
                        (v) ->
                            window.location = "../highvis.php?vid=#{v}"
                            ($ "#dialog-form").dialog 'close' ),(
                        (v) ->
                            alert 'Error:' + v  ))
        close: ->
            ($ '#dialog-form').remove()

###
Serializes all vis data. Strips functions from the objects bfire serializing
since they cannot be serialized.

NOTE: Booleans cannot be serialized properly (Hydrate.js issue)
###
globals.serializeVis = ->

    # set current vis to default
    current = (globals.curVis.canvas.match /([A-z]*)_canvas/)[1]
    current = current[0].toUpperCase() + current.slice 1
    data.defaultVis = current

    hydrate = new Hydrate()

    stripFunctions = (obj) ->

        switch typeof obj
            when 'number'
                obj
            when 'string'
                obj
            when 'function'
                undefined
            when 'object'

                if obj is null
                    null
                else
                    cpy = if $.isArray obj then [] else {}
                    for key, val of obj
                        stripped = stripFunctions val
                        if stripped isnt undefined
                            cpy[key] = stripped

                    cpy

    for visName in data.allVis
        vis  = eval "globals.#{visName.toLowerCase()}"
        vis.serializationCleanup()

    globalsCpy = stripFunctions globals
    dataCpy = stripFunctions data

    globals.curVis.end()
    globals.curVis.start()

    delete globalsCpy.curVis

    ret =
        globals: (hydrate.stringify globalsCpy)
        data: (hydrate.stringify dataCpy)

###
Does a deep copy extend operation similar to $.extend
###
globals.extendObject = (obj1, obj2) ->
    switch typeof obj2
        when 'boolean'
            obj2
        when 'number'
            obj2
        when 'string'
            obj2
        when 'function'
            obj2
        when 'object'

            if obj2 is null
                obj2
            else
                if $.isArray obj2
                    obj1 ?= []
                else
                    obj1 ?= {}

                for key, val of obj2 when key isnt '__hydrate_id'
                    obj1[key] = globals.extendObject obj1[key], obj2[key]
                obj1
