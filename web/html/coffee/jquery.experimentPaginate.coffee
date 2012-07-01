$ = jQuery

$.fn.experimentPaginate = ( options = null ) ->
    settings =
        start : 0
        limit : 20
    
    settings = $.extend settings, options
    
    if (settings.start + settings.limit) > $('#session_list').children().length
        end = $('#session_list').children().length
    else
        end = settings.start + settings.limit
        
    page_controls = "<div id='page_controls'>
                        <div id='page_back'>
                            <a class='page_button'>Back!</a>
                        </div>
                        <div id='page_info'>
                            <p>Displaying Session ##{settings.start} to Session ##{end}</p>
                        </div>
                        <div id='page_forward'>
                            <a class='page_button'>Next!</a>
                        </div>
                    </div>"
                    
    update = (target, settings) ->
        if $('#page_controls')
            $('#page_controls').remove()
        $(target).parent().append(page_controls)
        
        if settings.start - settings.limit >= 0
            $('#page_back a').click () ->
                $('#session_list').experimentPaginate({ start : (settings.start - settings.limit), limit : settings.limit});
        else
            $('#page_back a').addClass 'page_disabled'
            
        if (settings.start + settings.limit) < $('#session_list').children().length
            $('#page_forward a').click () ->
                $('#session_list').experimentPaginate({ start : (settings.start + settings.limit), limit : settings.limit});    
        else
            $('#page_forward a').addClass 'page_disabled'
        
        $(target).children().each (index) ->
            $(this).hide()
            if index >= settings.start and index < ( settings.start + settings.limit )
                $(this).show()
            
    return @each ()->
        update(this, settings)