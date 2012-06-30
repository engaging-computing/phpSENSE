$ = jQuery

update = (target, settings) ->
    $(target).children().each (index) ->
        $(this).show()
        if index < settings.start or index > ( settings.start + settings.limit )
            $(this).hide()
            
init = ( target, settings ) ->
    a = "<div id='pageControls'>
            <div id='page_back'>
            </div>
            <div id='page_info'>
            </div>
            <div id='page_forward'>
            </div>
        </div>"
    $(target).append(a)

$.fn.experimentPaginate = ( options = null ) ->
    settings =
        start : 0
        limit : 20
    
    settings = $.extend settings, options

    return @each ()->
        console.log "Preparing magic show..." 
        
        update(this, settings)
        init(this, settings)
        