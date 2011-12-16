/*
var timeline = function() {
    
    var enabled = true;
    var drawn = false;
    
    // Options for the flot drawing
    var options = {
        // Setup the series data for lines
        series: {
            lines: { show: true }
        },
        // Setup the x-axis for time
        xaxis: {
            mode:"time",
            min:null,
            max:null
        },
        // Remove the legend
        legend: { show: false },
        selection: { mode: "xy" },
        // Set of unified colors to use
        colors: [   "#4684ee","#dc3912","#ff9900","#008000","#666666",
                    "#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477",
                    "#aaaa11","#66aa00","#888888","#994499","#dd5511",
                    "#22aa99","#999999","#705770","#109618","#a32929"]
    };
    
    // Setup some vars in the name space for event handling
    var dataindex = null;
    var compute = null;
    
    // Following are used to make indexes onto the data
    var current_field = null;
    var time_field = null;
    
    // This is used to hold excluded session ids
    var excluded = [];
    
    // This is used to hold weither or not to align data sets
    var sidebyside = false;
        
    // These objects are signifigant dom elements
    this.controls = null;
    this.chart = null;
    this.vis = null;
    
    // This is used as an internal data store
    this.dindex = null;
    
    this.init = function(container, x_dataindex) {
        this.dindex = dataindex = x_dataindex;
        
        // Need to determine if we can draw this graph, before adding it
        // Check to see if we can display this data
        /*
        enabled = false;
        for (var j = 0; j < sdata.length; j++) {
            if (Date.parse(sdata[j][time_field_name]) != Date.parse(sdata[j][time_field_name])) {
                enabled = true;
                break;
            }
        }
        
        var meets_reqs = [];
        
        // Loop though the session
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            
            var session_reqs = { has_time:false, valid_time:false };
            
            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                // Get fields from the session
                var fields = dataindex[dataindex.sessions[i]].fields;
                var sdata = dataindex[dataindex.sessions[i]].data;
                
                for(var j = 0; j < fields.length && session_reqs.has_time == false; j++) {
                    
                    // Get the field object we need
                    var field = fields[j];
                                        
                    if(field.type_name == "Time") {
                        session_reqs.has_time = true;
                        
                        if (Date.parse(sdata[0][field.field_name]) != Date.parse(sdata[1][field.field_name])) {
                            session_reqs.valid_time = true;
                        }
                    }
                }
            }
            
            meets_reqs.push(session_reqs);
        }
        
        for(var i = 0; i < meets_reqs.length && enabled == true; i++) {
            if(meets_reqs[i].has_time == false || meets_reqs[i].valid_time == false) {
                enabled = false;
            }
        }
        
        if(enabled == true) {
            // Create the dom to hold our controls and chart
            container.createAppend(
                "div", { id:"timeline", style:"display:none;" }, [
                    "table", { id:"timeline_wrapper", width:"100%" }, [
                        "tr", { }, [
                            "td", { id:"timeline_vis_wrapper", colSpan:2 }, [
                                "div", { id:"timeline_vis", style:"width:100%; height:600px; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:6px;" }, []
                            ]
                        ],
                        "tr", { }, [
                            // Create the cell to hold the controls
                            "td", { id:"timeline_controls_wrapper", width:"25%" }, [
                                // Create the controls div
                                "div", { id:"timeline_controls", style:"width:224px; min-height:120px;padding-right:12px;" }, [
                                    // Create the field lists
                                    "div", { id:"timeline_field_list", style:"padding-bottom:20px;" }, [
                                        "div", { }, "Currently Plotting: ",
                                        "div", { }, [
                                            "select", { id:"timeline_field_list_control", style:"width:100%" }, []
                                        ]
                                    ],
                                    // Create alignment controls
                                    "div", { id:"timeline_alignment", style:"padding-bottom:20px;" }, [
                                        "input", { id:"timeline_alignment_control", type:"checkbox" }, [],
                                        "span", { }, " Side-by-Side Comparison"
                                    ]
                                ]
                            ],
                            "td", { id:"timeline_session_list_wrapper", width:"75%" }, [
                                // Create the session list
                                "div", { id:"timeline_session_list" }, [
                                    "div", { }, "Showing Sessions"
                                ]
                            ]
                        ]
                    ]
                ]
            );

            // Do a little clean up of our controls
            $("#timeline_wrapper").attr("valign", "top");
            $("#timeline_controls_wrapper").attr("valign", "top");
            $("#timeline_session_list_wrapper").attr("valign", "top");
            $("#timeline_vis_wrapper").attr("valign", "top");

            // Get the signifigant dom elements
            this.controls = $("#timeline_controls");
            this.vis = $("#timeline_vis");

            // Grab some insignifigant controls
            var session_list = $("#timeline_session_list");
            var field_list_control = $("#timeline_field_list_control");

            for(var i = 0; i < dataindex.sessions.length; i++) {

                // Create some basic vars we"ll use later
                var label = "timeline_session_" + dataindex.sessions[i];
                var val = dataindex.sessions[i] + "";

                // Create color for session
                var c = options.colors[(i % options.colors.length)];

                var name = " Session " + dataindex.sessions[i];
                if(typeof(dataindex[dataindex.sessions[i]].meta.name) != "undefined") {
                    name = " " + dataindex[dataindex.sessions[i]].meta.name;
                }

                // Add session to list
                session_list.createAppend("div", { id:"timeline_session_list", style:"margin-top:2px; float:left; width:28%; margin-right:2%;  overflow:hidden; height:19px;" }, [
                        "div", { style:"width:50px; float:left;" }, [
                            "span", { style:"padding-right:20px; margin-right:6px; margin-left:3px; background:"+c }, "&nbsp;",
                            "input", { id:label, className:"timeline_session_select", value:val, type:"checkbox", checked:"checked" }, [],
                        ],
                        "div", { style:"padding-left:50px;" }, name
                    ]
                );
                
                $("#"+label).click(function() {
                    
                    // Reset the excluded sessions
                    excluded = [];

                    // Get the list of sessions not currently displayed
                    $("#timeline_session_list > div > input:checkbox").each(function(i) {
                        if(!$(this).is(":checked")) {
                            excluded.push($(this).val());
                        }
                    });

                    $(window).trigger("session_change", [ excluded ]);
                });

                // Add fields if we need to
                if($("option", field_list_control).length == 0) {

                    // Get fields from the session
                    var fields = dataindex[dataindex.sessions[i]].fields;

                    for(var j = 0; j < fields.length; j++) {

                        // Get the field object we need
                        var field = fields[j];

                        // Create the label for use later
                        var label = field.field_name.capitalize() +  " ("  + field.unit_abbreviation + ")";

                        if(field.type_name != "Time" && field.type_name != "Geospacial") {
                            var opts = {
                                value:j+"",
                                selected:""
                            };

                            if(current_field == null) {
                                current_field = j;
                                opts.selected = true;
                            }

                            // Add field to control
                            field_list_control.createAppend("option", opts, label);
                        }
                        else if(field.type_name == "Time") {
                            if(time_field == null) {
                                time_field = j;
                            }
                        }
                    }
                }
            }
            
            // Call to setup events
            this.setup_events();
            
            compute = this.compute_data;
        }
        
        return enabled;
    };
    
    this.compute_data = function(x_dataindex) {
        var data = [];
        var max = null;
        var min = null;
        
        // Loop though the session
        for(var i = 0; i < x_dataindex.sessions.length; i++) {

            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                // Pull out the session index
                var session = x_dataindex[x_dataindex.sessions[i]];
                var fields  = session.fields;
                var sdata   = session.data;
                
                // Create data for session
                var c = options.colors[(i % options.colors.length)];
                
                var plotdata = { label:null, data:[], color:c };
                plotdata.label = "Session " + x_dataindex.sessions[i];
                
                // Get the field name
                //console.log(fields);
                var time_field_name     = fields[time_field].field_name.toString();
                var current_field_name  = fields[current_field].field_name.toString();
                
                sdata = sdata.sort(function(a, b){
                    return Date.parse(a[time_field_name]) - Date.parse(b[time_field_name]);
                });
                
                enabled = true;
                
                for(var j = 0; j < sdata.length; j++) {
                    
                    var time = j;
                    if(sidebyside == false) {
                        time = Date.parse(sdata[j][time_field_name]);
                    }
                                
                    if(min == null || time < min) {
                        min = time;
                    }
                    
                    if(max == null || time > max) {
                        max = time;
                    } 
                                       
                    plotdata.data.push([
                                    time,
                                    sdata[j][current_field_name]
                                ]);
                }
                
                data.push(plotdata);
            }
            
            options.xaxis.min = min;
            options.xaxis.max = max;
        }
                
        return data;
    };
    
    this.is_enabled = function() {
        return enabled;
    };
    
    this.open = function() {
        $("#timeline").css("display", "block");
        if(drawn == false) {
            this.draw();
        }
    };
    
    this.close = function() {
        $("#timeline").css("display", "none");
    };
    
    this.draw = function() {
        var data = compute(dataindex);
        
        if(enabled == true && drawn == false && $('#timeline').is(':visible') == true)  {
            drawn = true;

            $.plot($("#timeline_vis"), data, options);
        }
        else {
            drawn = false;
        }
        
        return true;
    };
    
    var redraw = this.draw;
    
    this.setup_events = function() {
        // Setup field change event
        $("select#timeline_field_list_control").change(function() {
            current_field = $("#timeline_field_list_control").val();
           $(window).trigger("field_change", [ current_field ]);
        });

        // Setup alignment change event
        $("input#timeline_alignment_control").click(function() {
            new_sidebyside = $("input#timeline_alignment_control").is(":checked");
            $(window).trigger("alignment_change", [ new_sidebyside ]);
        });
        
        // Setup listeners for field, session and alignment changing
        $(window).bind("field_change", this.field_change);
        $(window).bind("session_change", this.session_change);
        $(window).bind("alignment_change", this.alignment_change);
    };
    
    this.session_change = function(eventx, new_excluded) {
        excluded = new_excluded;
        console.log(new_excluded);
        redraw();
    };
    
    this.alignment_change = function(eventx, new_alignment) {
        this.sidebyside = new_alignment;
        redraw();
    };
    
    this.field_change = function(eventx, new_current_field) {
        current_field = new_current_field;
        redraw();
    };
}
*/