var bar = function() {
    
    var enabled = true;
    var drawn = false;
    
    // Options for the flot drawing
    var options = {
        // Setup the series data for lines
        series: {
            stack: true,
            lines: { show:false, steps:false },
            bars: { show:true }
        },
        // Remove the legend
        legend: { show: false },
        // Setup the x-axis for time
        xaxis: {},
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
    
    // This is used to hold excluded session ids
    var excluded = [];
    
    // This is used to hold weither or not to align data sets
    var metric = "max";
        
    // These objects are signifigant dom elements
    this.controls = null;
    this.chart = null;
    this.vis = null;
    
    // This is used as an internal data store
    this.dindex = null;
    
    this.init = function(container, x_dataindex) {
        this.dindex = dataindex = x_dataindex;
        
        // Create the dom to hold our controls and chart
        container.createAppend(
            "div", { id:"bar", style:"display:none;" }, [
                "table", { id:"bar_wrapper", width:"100%" }, [
                    "tr", { }, [
                        "td", { id:"bar_vis_wrapper", colSpan:2 }, [
                            "div", { id:"bar_vis", style:"width:100%; height:600px; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:6px;" }, []
                        ]
                    ],
                    "tr", { }, [
                        // Create the cell to hold the controls
                        "td", { id:"bar_controls_wrapper", width:"25%" }, [
                            // Create the controls div
                            "div", { id:"bar_controls", style:"width:224px; min-height:120px; padding-right:12px;" }, [
                                // Create the field lists
                                "div", { id:"bar_field_list", style:"padding-bottom:20px;" }, [
                                    "div", { }, "Currently Plotting: ",
                                    "div", { }, [
                                        "select", { id:"bar_field_list_control", style:"width:100%" }, []
                                    ]
                                ],
                                // Create the field lists
                                "div", { id:"bar_metric_list", style:"padding-bottom:20px;" }, [
                                    "div", { }, "Currently Displaying: ",
                                    "div", { }, [
                                        "select", { id:"bar_metric_list_control", style:"width:100%" }, [
                                            "option", { value:"max" }, "Max",
                                            "option", { value:"avg" }, "Average",
                                            "option", { value:"min" }, "Min"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "td", { id:"bar_session_list_wrapper", width:"75%" }, [
                            // Create the session list
                            "div", { id:"bar_session_list" }, [
                                "div", { }, "Showing Sessions"
                            ]
                        ]
                    ]
                ]
            ]
        );
        
        // Do a little clean up of our controls
        $("#bar_wrapper").attr("valign", "top");
        $("#bar_controls_wrapper").attr("valign", "top");
        $("#bar_session_list_wrapper").attr("valign", "top");
        $("#bar_vis_wrapper").attr("valign", "top");
        
        // Get the signifigant dom elements
        this.controls = $("#bar_controls");
        this.vis = $("#bar_vis");
        
        // Grab some insignifigant controls
        var session_list = $("#bar_session_list");
        var field_list_control = $("#bar_field_list_control");
        
        // Build the session and field lists
        for(var i = 0; i < dataindex.sessions.length; i++) {
            
            // Create some basic vars we"ll use later
            var label = "bar_session_" + dataindex.sessions[i];
            var val = dataindex.sessions[i] + "";
            
            // Create color for session
            var c = options.colors[(i % options.colors.length)];
            
            var name = " Session " + dataindex.sessions[i];
            if(typeof(dataindex[dataindex.sessions[i]].meta.name) != "undefined") {
                name = " " + dataindex[dataindex.sessions[i]].meta.name;
            }

            // Add session to list
            session_list.createAppend("div", { style:"margin-top:2px; float:left; width:28  %; margin-right:2%;  overflow:hidden; height:19px;" }, [
                    "div", { style:"width:50px; float:left;" }, [
                        "span", { style:"padding-right:20px; margin-right:6px; margin-left:3px; background:"+c }, "&nbsp;",
                        "input", { id:label, value:val, type:"checkbox", checked:"checked" }, [],
                    ],
                    "div", { style:"padding-left:50px;" }, name
                ]
            );
            
            
            // Add event listner
            $("#"+label).click(function(){
                // Reset the excluded sessions
                excluded = [];

                // Get the list of sessions not currently displayed
                $("#bar_session_list > div > input:checkbox").each(function(i) {
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
                        var opts = { value:j+"" };
                        
                        if(current_field == null) {
                            current_field = j;
                            opts.selected = true;
                        }
                        
                        // Add field to control
                        field_list_control.createAppend("option", opts, label);                        
                    }
                }
            }
        }
        
        this.setup_events();
        
        compute = this.compute_data;
        
        return true;
    };
    
    this.compute_data = function(x_dataindex) {
        var data = [];
        
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            
            // Pull out the session index
            var session = x_dataindex[x_dataindex.sessions[i]];
            var fields  = session.fields;
            var sdata   = session.data;
            
            var current_field_name  = fields[current_field].field_name.toString();
            var minmax = null;
            var avg = 0;
                        
            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                var plotdata = { label:null, data:[], stack:true, color:options.colors[(i % options.colors.length)] };
                plotdata.label = "Session " + x_dataindex.sessions[i];
                
                for(var j = 0; j < sdata.length; j++) {
                    
                    if(metric == "max") {
                        if(sdata[j][current_field_name] > minmax || minmax == null) {
                            minmax = sdata[j][current_field_name];
                        }
                    }
                    else if(metric == "avg") {
                        avg = avg + sdata[j][current_field_name];
                    }
                    else if(metric == "min") {
                        if(sdata[j][current_field_name] < minmax || minmax == null) {
                            minmax = sdata[j][current_field_name];
                        }
                    }
                }
                
                if(metric == "max" || metric == "min") {
                    plotdata.data.push([i, minmax]);
                }
                else if(metric == "avg") {
                    avg = (avg / sdata.length);
                    plotdata.data.push([i, avg]);
                }

                data.push(plotdata);
            }
        }
        
        options.xaxis = { ticks:[] };
        
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            options.xaxis.ticks.push([i+0.5, "Session " + x_dataindex.sessions[i]]);
        }
                
        return data;
    };
    
    this.is_enabled = function() {
        return enabled;
    };
    
    this.open = function() {
        $("#bar").css("display", "block");
        if(drawn == false) {
            this.draw();
        }
    };
    
    this.close = function() {
        $("#bar").css("display", "none");
    };
    
    this.draw = function() {
        var data = compute(dataindex);
        
        if(enabled == true && drawn == false && $('#bar').is(':visible') == true) {
            drawn = true;
            
            $.plot($("#bar_vis"), data, options);
        }
        else {
            drawn = false;
        }
        
        return true;
    };
    
    var redraw = this.draw;
    
    this.setup_events = function() {
        
        // Setup field change event
        $("select#bar_field_list_control").change(function(){
            current_field = $("#bar_field_list_control").val();
            $(window).trigger("field_change", [ current_field ]);
        });
        
        // Setup field change event
        $("select#bar_metric_list_control").change(function(){
            metric = $("#bar_metric_list_control").val();
           $(window).trigger("metric_change", [ metric ]);
        });
                
        // Setup listeners for field, session and alignment changing
        $(window).bind("field_change", this.field_change);
        $(window).bind("metric_change", this.metric_change);
        $(window).bind("session_change", this.session_change);
    };
    
    this.field_change = function(eventx, new_current_field) {
        current_field = new_current_field;
        redraw();
    };
    
    this.metric_change = function(eventx, new_metric) {
        metric = new_metric;
        redraw();
    };
    
    this.session_change = function(eventx, new_excluded) {
        excluded = new_excluded;
        redraw();
    };
    
    /*
    // Event handler for changing sessions
    this.session_change = function() {
        //console.log("[Bar]: Session Change");
        
        // Reset the excluded sessions
        excluded = [];
        
        // Get the list of sessions not currently displayed
        $("#bar_session_list > div > input:checkbox").each(function(i) {
            if(!$(this).is(":checked")) {
                excluded.push($(this).val());
            }
        });
                
        // Recompute the data
        var data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#bar_vis"), data, options);
    };
    
    // Event handler for changing metric
    this.metric_change = function() {
        //console.log("[Bar]: Metric Change");
        
        // Get the new metric
        metric = $("#bar_metric_list_control").val();
        
        // Recompute the data
        data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#bar_vis"), data, options);
    };
    
    // Event handler for changing the field
    this.field_change = function() {
        //console.log("[Bar]: Field Change");
        
        // Get the new field 
        current_field = $("#bar_field_list_control").val();
        
        // Recompute the data
        data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#bar_vis"), data, options);
    };
    
    this.bindings = [
        { name:"bar.session_change", func:this.session_change },
        { name:"bar.metric_change", func:this.metric_change },
        { name:"bar.field_change", func:this.field_change }
    ];
    */
}