var scatter = function() {
  
    var enabled = true;
    var drawn = false;
  
    // Options for the flot drawing
    var options = {
        // Setup the series data for lines
        series: {
            points: { show: true }
        },
        // Remove the legend
        legend: { show: false },
        // Setup the x-axis for time
        xaxis: {
            min:null,
            max:null
        },
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
    var xaxis_field = null;
    var yaxis_field = null;
    
    // This is used to hold excluded session ids
    var excluded = [];
    
    // These objects are signifigant dom elements
    this.controls = null;
    this.chart = null;
    this.vis = null;
    
    // This is used as an internal data store
    this.dindex = null;
    
    this.init = function(container, x_dataindex) {
        this.dindex = dataindex = x_dataindex;
        
        container.createAppend(
            "div", { id:"scatter", style:"display:none;" }, [
                "table", { id:"scatter_wrapper", width:"100%" }, [
                    "tr", { }, [
                        // Create the Vis container
                        "td", { id:"scatter_vis_wrapper", colSpan:2 }, [
                            "div", { id:"scatter_vis", style:"width:100%; height:600px; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:6px;" }, []
                        ]
                    ],
                    "tr", { }, [
                        "td", { id:"scatter_controls_wrapper", width:"25%" }, [
                            // Create the Vis Controls
                            "div", { id:"scatter_controls", style:"width:224px; min-height:120px; padding-right:12px;" }, [
                                // Create the X-Axis Control
                                "div", { id:"scatter_x_axis_list", style:"padding-bottom:20px;" }, [ 
                                    "div", {}, "X Axis: ",
                                    "div", {}, [
                                        "select", { id:"scatter_x_axis_list_control", style:"width:100%" }, []
                                    ]
                                ],
                                // Create the Y-Axis Control
                                "div", { id:"scatter_y_axis_list", style:"padding-bottom:20px;" }, [
                                    "div", {}, "Y Axis: ",
                                    "div", {}, [
                                        "select", { id:"scatter_y_axis_list_control", style:"width:100%" }, []
                                    ]
                                ]
                             ]
                        ],
                        "td", { id:"scatter_session_list_wrapper", width:"75%" }, [
                            // Create the list of Session
                             "div", { id:"scatter_session_list" }, [
                                 "div", {}, "Showing Sessions: "
                            ]
                        ]
                    ]
                ]
            ]
        );
        
        // Do a little clean up of our controls
        $("#scatter_wrapper").attr("valign", "top");
        $("#scatter_controls_wrapper").attr("valign", "top");
        $("#scatter_session_list_wrapper").attr("valign", "top");
        $("#scatter_vis_wrapper").attr("valign", "top");
        
        // Get some signifigant dom elements
        this.controls = $("#scatter_controls");
        this.vis = $("#scatter_vis");
        
        // Grab some insignifigant dom elements
        var session_list = $("#scatter_session_list");
        var x_axis_list_control = $("#scatter_x_axis_list_control");
        var y_axis_list_control = $("#scatter_y_axis_list_control");
        
        for(var i = 0; i < dataindex.sessions.length; i++) {
            
            // Create some basic vars we"ll use later
            var label = "scatter_session_" + dataindex.sessions[i];
            var val = dataindex.sessions[i] + "";
            
            // Create color for session
            var c = options.colors[(i % options.colors.length)];
            
            var name = " Session " + dataindex.sessions[i];
            if(typeof(dataindex[dataindex.sessions[i]].meta.name) != "undefined") {
                name = " " + dataindex[dataindex.sessions[i]].meta.name;
            }

            // Add session to list
            session_list.createAppend("div", { style:"margin-top:2px; float:left; width:28%; margin-right:2%;  overflow:hidden; height:19px;" }, [
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
                $("#scatter_session_list > div > input:checkbox").each(function(i) {
                    if(!$(this).is(":checked")) {
                        excluded.push($(this).val());
                    }
                });
                
                $(window).trigger("session_change", [ excluded ]);
            });
            
            if($("option", x_axis_list_control).length == 0 && $("option", y_axis_list_control).length == 0) {
                var fields = dataindex[dataindex.sessions[i]].fields;
                
                for(var j = 0; j < fields.length; j++) {
                    var field = fields[j];
                    
                    // Create the label for use later
                    var label = field.field_name.capitalize() +  " ("  + field.unit_abbreviation + ")";
                    
                    if(field.type_name != "Geospacial") {   
                        
                        var x = x_axis_list_control.createAppend("option", { value:j+"" }, label);
                        var y = y_axis_list_control.createAppend("option", { value:j+"" }, label);
                        
                        if(xaxis_field == null) {
                            xaxis_field = j;
                            x.attr("selected", true);
                        }
                        else if(yaxis_field == null) {
                            yaxis_field = j;
                            y.attr("selected", true);
                        }
                    }
                }
                
                //console.log("x:" + xaxis_field);
                //console.log("y:" + yaxis_field);
            }       
        }
        
        this.setup_events();
        
        compute = this.compute_data;
        
        return true;
    };
    
    this.compute_data = function(x_dataindex) {
        var data = [];
        var max_val = null;
        var min_val = null;
        
        // Loop though the session
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            
            // Pull out the session index
            var session = x_dataindex[x_dataindex.sessions[i]];
            var fields  = session.fields;
            var sdata   = session.data;
            
            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                // Create data for session
                var plotdata = { label:null, data:[], color:options.colors[(i % options.colors.length)] };
                plotdata.label = "Session " + x_dataindex.sessions[i];
                
                // Get the field name
                var xaxis_field_name = fields[xaxis_field].field_name.toString();
                var yaxis_field_name = fields[yaxis_field].field_name.toString();
                
                for(var j = 0; j < sdata.length; j++) {
                    
                    var val = sdata[j][xaxis_field_name];
                    if(fields[xaxis_field].type_name == "Time") {
                        val = Date.parse(val);
                    }
                    
                    if(max_val == null || max_val < val) max_val = val;

                    if(min_val == null || min_val > val) min_val = val;
                    
                    plotdata.data.push([
                                        val,
                                        sdata[j][yaxis_field_name]
                                    ]);
                }
                
                data.push(plotdata);
            }
        }
                
        options.xaxis.min = min_val;
        options.xaxis.max = max_val;
        
        return data;
    };
    
    this.is_enabled = function() {
        return enabled;
    };
    
    this.open = function() {
        $("#scatter").css("display", "block");
        if(drawn == false) {
            this.draw();
        }
    };
    
    this.close = function() {
        $("#scatter").css("display", "none");
    };
    
    this.draw = function() {
        var data = compute(dataindex);
        //console.log(enabled);
        if(enabled == true && drawn == false && $('#scatter').is(':visible') == true) {
            drawn = true;
            
            $.plot($("#scatter_vis"), data, options);
        }
        else {
            drawn = false;
        }
        
        return true;
    };
    
    var redraw = this.draw;
    
    this.setup_events = function() {
        
        // Setup x-axis event
        $("select#scatter_x_axis_list_control").change(function(){
            // Update the current y-axis field
            xaxis_field = $("select#scatter_x_axis_list_control").val();
            
           $(window).trigger("xaxis_change", [ xaxis_field ]);
        });
        
        // Setup y-axis event
        $("select#scatter_y_axis_list_control").change(function(){
            // Update the current y-axis field
            yaxis_field = $("select#scatter_y_axis_list_control").val();
            
            $(window).trigger("yaxis_change", [ yaxis_field ]);
        });
                
        // Setup listeners for field, session and alignment changing
        $(window).bind("session_change", this.session_change);
        $(window).bind("xaxis_change", this.xaxis_change);
        $(window).bind("yaxis_change", this.yaxis_change);
    };
    
    this.session_change = function(eventx, new_excluded) {
        excluded = new_excluded;
        redraw();
    };
    
    this.xaxis_change = function(eventx, new_x_axis) {
        x_axis = new_x_axis;
        redraw();
    };
    
    this.yaxis_change = function(eventx, new_y_axis) {
        y_axis = new_y_axis;
        redraw();
    };
    
    /*    
    // Event handler for changing sessions
    this.session_change = function() {
        console.log("[Scatter]: Session Change");
        
        // Reset the excluded sessions
        excluded = [];
        
        // Get the list of sessions not currently displayed
        $("#scatter_session_list > div > input:checkbox").each(function(i) {
            if(!$(this).is(":checked")) {
                excluded.push($(this).val());
            }
        });
        
        // Recompute the data
        var data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#scatter_vis"), data, options);
    };
    
    // Event handler for changing x-axis
    this.xaxis_change = function() {
        console.log("[Scatter]: X Axis Change");
        
        // Update the current x-axis field
        xaxis_field = $("select#scatter_x_axis_list_control").val();
        
        // Recompute the data
        var data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#scatter_vis"), data, options);
    };
    
    // Event handler for changing y-axis
    this.yaxis_change = function() {
        console.log("[Scatter]: Y Axis Change");
        
        // Update the current y-axis field
        yaxis_field = $("select#scatter_y_axis_list_control").val();
        
        // Recompute the data
        var data = compute(dataindex);
        
        // Redraw the graph
        $.plot($("#scatter_vis"), data, options);
    };
    
    this.bindings = [
        { name:"scatter.session_change", func:this.session_change },
        { name:"scatter.xaxis_change", func:this.xaxis_change },
        { name:"scatter.yaxis_change", func:this.yaxis_change }
    ];
    */
    
    this.bindings = [ ];

};