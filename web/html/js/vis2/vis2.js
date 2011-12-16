// Define the console object if it not already defined by the JS VM
// console = (typeof(console) !== 'undefined' && console != null) ? console : (new logmanager());

// Add capitalize method to String class
String.prototype.capitalize = function(){
   return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

var colorsToUse  = ["#4684ee","#dc3912","#ff9900","#008000","#666666",
                    "#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477",
                    "#aaaa11","#66aa00","#888888","#994499","#dd5511",
                    "#22aa99","#999999","#705770","#109618","#a32929"];

function gup( name ) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

$(document).ready(function(){
    
    var session_list = gup('sessions');
    session_list = session_list.split('+');
    
    try {
        start_vis_manager({ sessions: session_list, target:$("#vis") });
    }
    catch(err) {
        console.log(err);
        var title = document.getElementsByTagName('title')[0].text; 
        var subject = title + ' error';
        var prompt = 'Please describe your problem with as much detail as possiable: ';
        var mailToLink = "mailto:ccorcora+isense@cs.uml.edu?subject=" + subject + '?body=' + prompt;
        
        $('#vis').children().remove();
        $('#vis').createAppend(
            "div", { }, [
                "div", { style:"padding:12px 0px 0px 0px;" }, "We are sorry, but an error has occured while building your visualization.",
                "div", { style:"padding:12px 0px 12px 0px;" }, [
                    "div", { style:"margin-bottom:6px;" }, "We are unable to continue at this time. Please select from one of these three options:",
                    "div", { style:"margin-left:12px; margin-bottom:6px;" }, [
                        "span", { }, "1) ",
                        "a", { href:"javascript:location.reload(true);" }, "Try reloading the page"
                    ],
                    "div", { style:"margin-left:12px; margin-bottom:6px;" }, [
                        "span", { }, "2) ",
                        "a", { href:mailToLink }, "Email an admin to get help with this problem"
                    ], 
                    "div", { style:"margin-left:12px; margin-bottom:6px;" }, [
                        "span", { }, "3) ",
                        "a", { href:"javascript:history.go(-1);" }, "Return to " + title
                    ]
                    
                ]
            ]
        );
    }
    
});