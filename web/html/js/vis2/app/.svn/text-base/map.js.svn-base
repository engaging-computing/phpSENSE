var vmap = function() {
    
    var enabled = true;
    var plot_session = false;
    var drawn = false;
    
    // Options for the flot drawing
    var options = {
        // Set of unified colors to use
        
    };
    
    var colors = [  "4684ee","dc3912","ff9900","008000","666666",
                    "4942cc","cb4ac5","d6ae00","336699","dd4477",
                    "aaaa11","66aa00","888888","994499","dd5511",
                    "22aa99","999999","705770","109618","a32929"];
    
    // Setup some vars in the name space for event handling
    var dataindex = null;
    var compute = null;

    // This is used to hold excluded session ids
    var excluded = [];
    
    // These objects are signifigant dom elements
    this.controls = null;
    this.chart = null;
    this.vis = null;
    var map = null;
    
    // This is used as an internal data store
    this.dindex = null;
    
    var lat = null;
    var lng = null;
    var current_field = null;
    
    this.init = function(container, x_dataindex) {
        this.dindex = dataindex = x_dataindex;
        
        var meets_reqs = [];
        
        // Loop though the session
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            
            var session_reqs = { has_geo:false };
            
            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                // Get fields from the session
                var fields = dataindex[dataindex.sessions[i]].fields;
                var sdata = dataindex[dataindex.sessions[i]].data;
                
                for(var j = 0; j < fields.length && session_reqs.has_time == false; j++) {
                    
                    // Get the field object we need
                    var field = fields[j];
                                        
                    if(field.type_name == "Geospacial") {
                        session_reqs.has_geo = true;
                        
                        if (Date.parse(sdata[0][field.field_name]) != Date.parse(sdata[1][field.field_name])) {
                            session_reqs.valid_time = true;
                        }
                    }
                }
            }
            
            meets_reqs.push(session_reqs);
        }
        
        for(var i = 0; i < meets_reqs.length && plot_session == false; i++) {
            if(meets_reqs[i].has_geo == false) {
                plot_session = true;
            }
        }
        
        if(typeof(google) == "undefined") {
            enabled = false;
        }
        
        if(enabled) {
            
            var map_control_wrapper_style = "";
            var map_control_style = "width:224px; min-height:120px; padding-right:12px;";
            var map_field_list_style = "padding-bottom:20px;";
            
            if(plot_session == true) {
                map_control_wrapper_style = "display:none;"
                map_field_list_style = "display:none;";
                map_control_style = "display:none;";
            }

            // Create the dom to hold our controls and chart
            container.createAppend(
                "div", { id:"map", style:"display:none;" }, [
                    "table", { id:"map_wrapper", width:"100%" }, [
                        "tr", { }, [
                            "td", { id:"map_vis_wrapper", colSpan:2 }, [
                                "div", { style:"border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:6px;" }, [
                                    "div", { id:"map_vis", style:"width:100%; height:600px; " }, []
                                ]
                            ]
                        ],
                        "tr", { }, [
                            // Create control holding cell, ha ha get it!?! its a cell, like in jail!
                            "td", { id:"map_controls_wrapper", width:"25%", style:map_control_wrapper_style }, [
                                // Create the controls div
                                "div", { id:"map_controls", style:map_control_style }, [
                                    // Create the field lists
                                    "div", { id:"map_field_list", style:map_field_list_style }, [
                                        "div", { }, "Currently Plotting: ",
                                        "div", { }, [
                                            "select", { id:"map_field_list_control", style:"width:100%" }, []
                                        ]
                                    ]
                                ]
                            ],
                            "td", { id:"map_session_list_wrapper", width:"75%" }, [
                                // Create the session list
                                "div", { id:"map_session_list", style:"padding-bottom:20px;" }, [
                                    "div", { }, "Showing Sessions"
                                ]
                            ]
                        ]
                    ]
                ]
            );

            // Do a little clean up of our controls
            $("#map_wrapper").attr("valign", "top");
            $("#map_controls_wrapper").attr("valign", "top");
            $("#map_vis_wrapper").attr("valign", "top");

            // Get the signifigant dom elements
            this.controls = $("#map_controls");
            this.vis = $("#map_vis");

            // Grab some insignifigant controls
            var session_list = $("#map_session_list");
            var field_list_control = $("#map_field_list_control");

            for(var i = 0; i < dataindex.sessions.length; i++) {

                // Add fields if we need to
                if($("option", field_list_control).length == 0) {

                    // Need to find lat and lng while we"re plotting
                    var fields = dataindex[dataindex.sessions[i]].fields;


                    for(var j = 0; j < fields.length; j++) {
                        var field = fields[j];

                        if(field.type_name == "Geospacial") {
                            if(field.unit_id == "57" && lat == null) lat = j;

                            if(field.unit_id == "57" && lng == null) lng = j;
                        }
                        else if(plot_session == false) {
                            // Create the label for use later
                            var label = field.field_name.capitalize() +  " ("  + field.unit_abbreviation + ")";

                            var opts = {
                                value:j+"",
                                selected:false
                            };

                            if(current_field == null) {
                                current_field = j;
                                opts.selected = true;
                            }

                            // Add field to control
                            field_list_control.createAppend("option", opts, label);
                        }

                    }
                }

                // Create some basic vars we"ll use later
                var label = "map_session_" + dataindex.sessions[i];
                var val = dataindex.sessions[i] + "";

                // Create color for session
                var c = "#" + colors[(i % colors.length)];

                var name = " Session " + dataindex.sessions[i];
                if(typeof(dataindex[dataindex.sessions[i]].meta.name) != "undefined") {
                    name = " " + dataindex[dataindex.sessions[i]].meta.name;
                }

                // Add session to list
                session_list.createAppend("div", { style:"margin-top:2px; float:left; width:28%; margin-right:2%;  overflow:hidden; height:19px;" }, [
                        "div", { style:"width:50px; float:left;" }, [
                            "span", { style:"padding-right:20px; margin-right:6px; background:"+c }, "&nbsp;",
                            "input", { id:label, value:val, type:"checkbox", checked:"checked" }, [],
                        ],
                        "div", { style:"padding-left:50px;" }, name
                    ]
                );

                // Add event listner
                $("#"+label).click(function(){
                    $(window).trigger("session_change");
                });
            }
            
            this.setup_events();
            
            compute = this.compute_data;
        }
        
        return enabled;
    };
    
    this.compute_data = function(x_dataindex) {
        var data = [];
                
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                var session = x_dataindex[x_dataindex.sessions[i]];
                
                var xlat = session.meta.latitude;
                var xlng = session.meta.longitude;
                var x = new google.maps.LatLng(xlat, xlng);
                
                var ximg_color = colors[(i % colors.length)];
                var ximg_url = "/html/img/vis/custommap/icon" + ximg_color + ".png";
                
                var ximg = new google.maps.MarkerImage(ximg_url);
                
                var xp = new google.maps.Marker({ 
                    animation: google.maps.Animation.DROP,
                    icon: ximg,
                    title: session.meta.name
                });
                
                data.push(xp);
                
                google.maps.event.addListener(xp, "click", function(e){
                    
                    var infoWindow = new google.maps.InfoWindow({
                        content:"Awesome Marker!"
                    });
                    
                    infoWindow.open(map, xp);
                    
                    if(xp.getAnimation() != null) {
                        xp.setAnimation(null);
                    } else {
                        xp.setAnimation(google.maps.Animation.BOUNCE);
                    }
                });
                
                xp.setPosition(x);
                xp.setMap(map);

                if(plot_session == false) {
                    var sessiondata = session.data;
                     var line = [];
                    
                    for(var j = 0; j < sessiondata.length; j++) {
                        var datum = sessiondata[j];
                        lat = datum.latitude;
                        lng = datum.longitude;

                        if(lat != 0.0 && lng != 0.0) {
                            line.push(new google.maps.LatLng(lat, lng));
                        }
                    }

                    var opts = {};
                    opts.strokeColor = colors[(i % colors.length)];

                    pLine = new google.maps.Polyline(opts);
                    pLine.setPath(line);
                    pLine.setMap(map);

                    data.push(pLine);
                }
            }
        }
        
        return data;
    };
    
    this.is_enabled = function() {
        return enabled;
    };
    
    this.open = function() {
        $("#map").css("display", "block");
        if(drawn == false) {
            this.draw();
        }
    };
    
    this.close = function() {
        $("#map").css("display", "none");
    };
    
    this.draw = function() {
        
        if(enabled == true && drawn == false) {
            
            $("#map").css("display", "block");
            
            var lat = null;
            var lng = null;
            
            options.zoom = 8;
            options.mapTypeId = google.maps.MapTypeId.HYBRID;

            if(plot_session == false) {
                lat = dataindex[dataindex.sessions[0]].data[0].latitude;
                lng = dataindex[dataindex.sessions[0]].data[0].longitude;
            }
            else {
                lat = dataindex[dataindex.sessions[0]].meta.latitude;
                lng = dataindex[dataindex.sessions[0]].meta.longitude;
            }
            
            var latlng = new google.maps.LatLng(lat, lng);
            options.center = latlng;
            
            map = new google.maps.Map(document.getElementById("map_vis"), options);

            compute(dataindex);
            drawn = true;
        }
        
        return true;
    };
    
    var redraw = this.draw;
    
    this.setup_events = function() {
        
        // Setup field change event
        $("select#map_field_list_control").change(function(){
            $(window).trigger("field_change");
        });
                        
        // Setup listeners for field, session and alignment changing
        $(window).bind("field_change", this.field_change);
        $(window).bind("session_change", this.session_change);
    };
    
    this.field_change = function(eventx, new_field) {
        redraw();
    };
    
    this.session_change = function(eventx, excluded) {
        redraw();
    };
};