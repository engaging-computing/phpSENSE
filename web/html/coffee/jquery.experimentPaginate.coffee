###   Copyright (c) 2011, iSENSE Project. All rights reserved.

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
###

#Define jQuery
$ = jQuery

#Extend jQuery
$.fn.experimentPaginate = ( options = null ) ->

    #Default settings
    settings =
        start : 0
        limit : 20
        
    #Overwrite if options aren't empty
    settings = $.extend settings, options
    
    #Calculate end number for page_info
    if (settings.start + settings.limit) > $('#session_list').children().length
        end = $('#session_list').children().length
    else
        end = settings.start + settings.limit
        
    #Define page_controls
    page_controls = """
                    <div id='page_controls'>
                        <div id='page_back'>
                            <input type="button" class='page_button' value="Back" />
                        </div>
                        <div id='page_info'>
                            <p>Displaying Session ##{settings.start} to Session ##{end} of #{$('#session_list').children().length}</p>
                        </div>
                        <div id='page_forward'>
                            <input type="button" class='page_button' value="Next" />
                        </div>
                    </div>
                    """
                    
    #My version of Main()
    update = (target, settings) ->
    
        #Strip page controls
        if $('#page_controls')
            $('#page_controls').remove()
        
        #Append page controls
        $(target).parent().append(page_controls)
        
        #En/Disable Back button
        if settings.start - settings.limit >= 0
            $('#page_back input').click () ->
                $('#session_list').experimentPaginate({ start : (settings.start - settings.limit), limit : settings.limit});
            $('#page_back input').mouseover () ->
                $('#page_back input').css { 'background-color' : '#DDD', 'border-color' : '#CCC' }
            $('#page_back input').mouseout () ->
                $('#page_back input').css { 'background-color' : '#EEE', 'border-color' : '#DDD' }
        else
            $('#page_back input').addClass 'page_disabled'
            
        #En/Disable Next button
        if (settings.start + settings.limit) < $('#session_list').children().length
            $('#page_forward input').click () ->
                $('#session_list').experimentPaginate({ start : (settings.start + settings.limit), limit : settings.limit});
            $('#page_forward input').mouseover () ->
                $('#page_forward input').css { 'background-color' : '#DDD', 'border-color' : '#CCC' }
            $('#page_forward input').mouseout () ->
                $('#page_forward input').css { 'background-color' : '#EEE', 'border-color' : '#DDD' }
        else
            $('#page_forward input').addClass 'page_disabled'
        
        #Hide divs
        $(target).children().each (index) ->
            $(this).hide()
            if index >= settings.start and index < ( settings.start + settings.limit )
                $(this).show()
            
        #Call "Main()" on each .exp..ate() target
    return @each ()->
        update(this, settings)