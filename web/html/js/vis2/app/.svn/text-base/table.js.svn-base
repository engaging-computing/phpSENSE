var table = function() {
    
    var enabled = true;
    var drawn = false;
    
    var options = {
        // Set of unified colors to use
        colors: [   "#4684ee","#dc3912","#ff9900","#008000","#666666",
                    "#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477",
                    "#aaaa11","#66aa00","#888888","#994499","#dd5511",
                    "#22aa99","#999999","#705770","#109618","#a32929"]
    };
    
    // Setup some vars in the name space for event handling
    var dataindex = null;
    var compute = null;
    var chart = null;
    
    // This is used to hold excluded session ids
    var excluded = [];
    var excluded_cols = [];
    
    // These objects are signifigant dom elements
    this.controls = null;
    this.vis = null;
    
    // This is used as an internal data store
    this.dindex = null;
    
    this.init = function(container, x_dataindex) {
        this.dindex = dataindex = x_dataindex;
        
        container.createAppend(
            "div", { id:"datatable", style:"display:none;" }, [
                "table", { id:"datatable_wrapper", width:"100%" }, [
                    "tr", { }, [
                        "td", { id:"datatable_vis_wrapper", colSpan:2 }, [
                            "div", { id:"datatable_vis", style:"width:100%; max-height:600px; overflow-y:scroll; border-bottom:1px solid #000; padding-bottom:10px; margin-bottom:6px;" }, [
                                "table", { id:"datatable_vis_data", width:"100%", cellSpacing:0 }, []
                            ]
                        ]
                    ],
                    "tr", { }, [
                        // Create the cell to hold the controls
                        "td", { id:"datatable_controls_wrapper", width:"25%" }, [
                            // Create the controls div
                            "div", { id:"datatable_controls", style:"width:224px; min-height:120px; padding-right:12px;" }, [
                                // Create the field lists
                                "div", { id:"datatable_field_list" }, [
                                    "div", { }, "Currently Plotting: "
                                ]
                            ]
                        ],
                        "td", { id:"datatable_session_list_wrapper", width:"75%" }, [
                            // Create the session list
                            "div", { id:"datatable_session_list" }, [
                                "div", { }, "Showing data from: "
                            ]
                        ]
                    ]
                ]
            ]
        );
        
        $("#datatable_session_list_wrapper").attr("valign", "top");
        $("#datatable_controls_wrapper").attr("valign", "top");
        $("#datatable_vis_wrapper").attr("valign", "top");
                                
        // Get the signifigant dom elements
        this.controls = $("#datatable_controls");
        this.vis = $("#datatable_vis");
        
        var session_list = $("#datatable_session_list");
        var field_list = $("#datatable_field_list");
        
        chart = $("#datatable_vis_data");
        
        // Build the session and field lists
        for(var i = 0; i < dataindex.sessions.length; i++) {
            
            // Create some basic vars we"ll use later
            var label = "datatable_session_" + dataindex.sessions[i];
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
                $("#datatable_session_list > div > input:checkbox").each(function(i) {
                    if(!$(this).is(":checked")) {
                        excluded.push($(this).val());
                    }
                });
                
                $(window).trigger("session_change", [ excluded ]);
            });
            
            if($("th", chart).length == 0) {
                var fields = dataindex[dataindex.sessions[i]].fields;    

                chart.createAppend("th", { style:"background:#CCC; font-weight:bold; padding:6px;" }, "Session");

                for(var j = 0; j < fields.length; j++) {
                    var field = fields[j];
                    var label = field.field_name.capitalize() +  " ("  + field.unit_abbreviation + ")";
                    
                    if(excluded_cols.indexOf(j) == -1) {
                        chart.createAppend("th", { style:"background:#CCC; font-weight:bold; padding:6px;" }, label);
                    }
                    
                    var val = j;
                    var field_label = "datatable_field_" + j
                    
                    field_list.createAppend(
                        "div", {}, [
                            "input", { id:field_label, value:val, type:"checkbox", checked:"checked" }, [],
                            "span", { }, " "+label
                        ]
                    );
                    
                    $("#"+field_label).click(function(){
                        
                        excluded_cols = [];

                        // Get the list of sessions not currently displayed
                        $("#datatable_field_list > div > input:checkbox").each(function(i) {
                            if(!$(this).is(":checked")) {
                                excluded_cols.push(parseInt($(this).val()));
                            }
                        });
                        
                        $(window).trigger("table_field_change", [ excluded_cols ]);
                    });
                }
            }
        }
        
        compute = this.compute_data;
        
        return true;
    };
    
    this.compute_data = function(x_dataindex) {
        for(var i = 0; i < x_dataindex.sessions.length; i++) {
            
            var session   = x_dataindex[x_dataindex.sessions[i]];
            var fields    = session.fields;
            var sdata     = session.data;
            
            // Make sure the session isn"t excluded
            if(excluded.indexOf(x_dataindex.sessions[i]) == -1) {
                
                if($("th", chart).length == 0) {
                    var fields = dataindex[dataindex.sessions[i]].fields;    

                    chart.createAppend("th", { style:"background:#CCC; font-weight:bold; padding:6px;" }, "Session #");

                    for(var j = 0; j < fields.length; j++) {
                        
                        if(excluded_cols.indexOf(j) == -1) {
                        
                            var field = fields[j];
                            var label = field.field_name.capitalize() +  " ("  + field.unit_abbreviation + ")";

                            chart.createAppend("th", { style:"background:#CCC; font-weight:bold; padding:6px;" }, label);
                        }
                    }
                }
                
                for(var j = 0; j < sdata.length; j++) {

                    var l = "r_" + i + "_" + j;
                    chart.createAppend("tr", { id:l, className:"data_row" }, []);

                    $("#"+l).createAppend(
                        "td", 
                        { style:"text-align:center; border-bottom:1px solid #CCC; border-left:1px solid #CCC;" },
                        x_dataindex.sessions[i].toString()
                    );

                    for(var k = 0; k < fields.length; k++) {
                        if(excluded_cols.indexOf(k) == -1) {
                            var val = sdata[j][fields[k].field_name].toString();
                            
                            if(fields[k].type_name == "Time") {
                                val = Date.parse(val)+"";
                            }
                            
                            $("#"+l).createAppend(
                                "td", 
                                { style:"text-align:center; border-bottom:1px solid #CCC; border-left:1px solid #CCC;"}, 
                                val
                            );
                        }
                    }
                }
            }
        }
    };
    
    this.is_enabled = function() {
        return enabled;
    };
    
    this.open = function() {
        $("#datatable").css("display", "block");
        if(drawn == false) {
            this.draw();
            drawn = true;
        }
    };
    
    this.close = function() {
        $("#datatable").css("display", "none");
    };
    
    this.draw = function() {
        if(enabled && drawn == false) {
            if($("#datatable").is(":visable") == true) {
                $("#datatable").css("display", "block");
            }
            
            compute(dataindex);
        }
       
        return true;
    };
    
    var redraw = this.draw;
    
    this.setup_events = function() {
                
        // Setup listeners for field, session and alignment changing
        $(window).bind("table_field_change", this.field_change);
        $(window).bind("session_change", this.session_change);
    };
    
    this.table_field_change = function(eventx, new_excluded_cols) {
        excluded_cols = new_excluded_cols;
        rewdraw();
    };
        
    this.session_change = function(eventx, new_excluded) {
        excluded = new_excluded;
        redraw();
    };
    
    /*
    // Event handler for changing sessions
    this.field_change = function() {
        //console.log("[DataTable]: Field Change");
        
        // Reset the excluded sessions
        excluded_cols = [];
        
        // Get the list of sessions not currently displayed
        $("#datatable_field_list > div > input:checkbox").each(function(i) {
            if(!$(this).is(":checked")) {
                excluded_cols.push(parseInt($(this).val()));
            }
        });
        
        //console.log(excluded_cols);
        
        chart.children().remove();
        
        // Recompute the data
        compute(dataindex);
    };
    
    // Event handler for changing sessions
    this.session_change = function() {
        //console.log("[DataTable]: Session Change");
        
        // Reset the excluded sessions
        excluded = [];
        
        // Get the list of sessions not currently displayed
        $("#datatable_session_list > div > input:checkbox").each(function(i) {
            if(!$(this).is(":checked")) {
                excluded.push($(this).val());
            }
        });
        
        chart.children().remove();
        
        // Recompute the data
        compute(dataindex);
    };
    
    this.bindings = [
        { name:"datatable.session_change", func:this.session_change },
        { name:"datatable.field_change", func:this.field_change },
    ];
    */
    
    this.bindings = [ ];
}