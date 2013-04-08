(function() {
  var $;

  $ = jQuery;

  $.fn.experimentPaginate = function(options) {
    var end, page_controls, settings, update;
    if (options == null) options = null;
    settings = {
      start: 0,
      limit: 20
    };
    settings = $.extend(settings, options);
    if ((settings.start + settings.limit) > $('#session_list').children().length) {
      end = $('#session_list').children().length;
    } else {
      end = settings.start + settings.limit;
    }
    page_controls = "<div id='page_controls'>\n    <div id='page_back'>\n        <input type=\"button\" class='page_button' value=\"Back\" />\n    </div>\n    <div id='page_info'>\n        <p>Displaying Session #" + settings.start + " to Session #" + end + " of <b>" + $('#session_list').children().length +"</b></p>\n    </div>\n    <div id='page_forward'>\n        <input type=\"button\" class='page_button' value=\"Next\" />\n    </div>\n</div>";
    update = function(target, settings) {
      if ($('#page_controls')) $('#page_controls').remove();
      $(target).parent().append(page_controls);
      if (settings.start - settings.limit >= 0) {
        $('#page_back input').click(function() {
          return $('#session_list').experimentPaginate({
            start: settings.start - settings.limit,
            limit: settings.limit
          });
        });
        $('#page_back input').mouseover(function() {
          return $('#page_back input').css({
            'background-color': '#DDD',
            'border-color': '#CCC'
          });
        });
        $('#page_back input').mouseout(function() {
          return $('#page_back input').css({
            'background-color': '#EEE',
            'border-color': '#DDD'
          });
        });
      } else {
        $('#page_back input').addClass('page_disabled');
      }
      if ((settings.start + settings.limit) < $('#session_list').children().length) {
        $('#page_forward input').click(function() {
          return $('#session_list').experimentPaginate({
            start: settings.start + settings.limit,
            limit: settings.limit
          });
        });
        $('#page_forward input').mouseover(function() {
          return $('#page_forward input').css({
            'background-color': '#DDD',
            'border-color': '#CCC'
          });
        });
        $('#page_forward input').mouseout(function() {
          return $('#page_forward input').css({
            'background-color': '#EEE',
            'border-color': '#DDD'
          });
        });
      } else {
        $('#page_forward input').addClass('page_disabled');
      }
      return $(target).children().each(function(index) {
        $(this).hide();
        if (index >= settings.start && index < (settings.start + settings.limit)) {
          return $(this).show();
        }
      });
    };
    return this.each(function() {
      return update(this, settings);
    });
  };

}).call(this);
