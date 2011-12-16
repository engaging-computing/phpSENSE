// A bunch of very very important things
var target = null;
var excluded = {};
var vis_list = null;
var sessions = null;
var label_hash = null;
var session_hash = null;
var datapoint_count = 0;

// Live Updating Control Variables
var lively = null;
var lively_updating_enabled = false;

// Control Variables
var finished = false;
var previousPoint = false;

// Timeline Window
var y_max = null;
var y_min = null;
var x_max = null;
var x_min = null;

// Vis Variables
var timeline = null;
var timeline_zoomed = false;
var timeline_overview = null;

// These are colors
var colors = [   
    "#4684ee","#dc3912","#ff9900","#008000","#666666",
    "#4942cc","#cb4ac5","#d6ae00","#336699","#dd4477",
    "#aaaa11","#66aa00","#888888","#994499","#dd5511",
    "#22aa99","#999999","#705770","#109618","#a32929"
];

// Field type constants
var TIME = 7;
var GEOSPACIAL = 19;

// Field Type Checkers
function is_geospacial(field) {
    return (field.type_id == GEOSPACIAL);
}

function is_time(field) {
    return (field.type_id == TIME);
}

function all_sessions_fetched() {
    for(var i = 0; i < sessions.length; i++) {
        if(sessions[i].fetched == false) {
            return false;
        }
    }
    
    return true;
}

// Array Helpers
function init_array(columns, rows) {
    var x = Array(rows);
    
    for(var i = 0; i < rows; i++) x[i] = Array(columns);
    
    return x;
}

var global_sort_time_index = -1;
function compare_on_time(a, b) {
    var a = a[global_sort_time_index];
    var b = b[global_sort_time_index];
    
    if(a > b) return 1;
    
    if(a < b) return -1;
    
    return 0;
}

// Live Updating Functions
function enable_lively_updating() {
    if(finished) {
        if(!lively_updating_enabled) {
            // console.log("Enabled Lively Updating");

            lively_updating_enabled = true;
            lively = setTimeout(update_lively, 1000);
        }
        else {
            // console.log("Disabled Lively Updating");

            lively_updating_enabled = false;
            clearTimeout(lively);
            lively = null;
        }
    }
    else {
        setTimeout(enable_lively_updating, 2000);
    }
}

function update_lively() {
    // Start session fetch
    if(sessions.length > 0) {
        for(var i = 0; i < sessions.length; i++) {
            sessions[i].fetched = false;
            fetch_session_data(sessions[i].id);
        }
        
        // Clear the timeout, because that seems like a good idea
        clearTimeout(lively);
        lively = null;
    }
}

// Timeline Vis
function is_session_visable(session, field) {
    return (excluded[session][field] == 0);
}

function add_session_visability_entry(session, field) {
    if(typeof(excluded[session]) == "undefined") {
        excluded[session] = {};
    }
    else if(typeof(excluded[session][field]) == "undefined") {
        excluded[session][field] = 0;
    }
}

function toggle_session_visability(session, field) {
    timeline_zoomed = true;
    excluded[session][field] = !excluded[session][field];
    draw_timeline();
}

function add_to_timeline_legend(session, field, session_name, field_name, color) {
    var l = session_name + ' ' + field_name;
    var i = session + '_' + field;
    
    $('#timeline_legend').createAppend(
        'div', { style:'margin:5px 0px 0px 0px;' }, [
            'input', { id:i, type:'checkbox', checked:'checked' }, [],
            'span', { style:'width:30px; background:'+color+'; margin-left:6px; margin-right:6px;' }, '&nbsp;&nbsp;&nbsp;',
            'span', { }, l
        ]
    );
    
    $('#'+i).bind('click', {session:session, field:field}, function(e) {
        toggle_session_visability(e.data.session, e.data.field);
    });
}

function build_timeline_dataset() {
    y_max = null;
    y_min = null;
    x_max = null;
    x_min = null;
    
    var dataset = [];
    var ccount = 0;
    
    for(var i = 0; i < sessions.length; i++) {
        
        var session     = sessions[i];
        var time_index  = session.time_index;

        for(var j = 0; j < session.fields.length; j++) {
            add_session_visability_entry(session.id, j);
            
            var field = session.fields[j];
            var color = colors[(ccount % colors.length)];
            ccount++;
            
            if(j != time_index && !is_geospacial(field) && is_session_visable(session.id, j)) {
                
                if(label_hash == null) label_hash = {};
                
                var data_name = session.id + ' ' + field.field_name;           
                label_hash[data_name] = field.unit_abbreviation;
                
                if(!timeline_zoomed) add_to_timeline_legend(session.id, j, session.name, field.field_name, color);
                
                var x       = {};
                x['data']   = [];
                x['color']  = color;
                x['label']  = data_name;
                x['lines']  = { show:true };
               
                for(var k = 0; k < session.data.length; k++) {                    
                    if(y_max == null || y_max < session.data[k][j]) y_max = session.data[k][j];
                    if(y_min == null || y_min > session.data[k][j]) y_min = session.data[k][j];
                    
                    x['data'].push(
                        [
                            session.data[k][time_index],
                            session.data[k][j]
                        ]
                    );
                    
                    if(x_max == null || x_max < session.data[k][time_index]) x_max = session.data[k][time_index];
                    if(x_min == null || x_min > session.data[k][time_index]) x_min = session.data[k][time_index];
                }
                
                x['data'].sort(compare_on_time);
                dataset.push(x);
            }
        }
    }
    
    return dataset;
}

function draw_timeline() {
    var dataset = build_timeline_dataset();
    
    // Update and draw a new timeline
    timeline.setData(dataset);
    
    //console.log();
    var opts = timeline.getOptions();
    opts.yaxis.min = y_min;
    opts.yaxis.max = (y_max * 1.05);
    
    opts.xaxis.min = x_min;
    opts.xaxis.max = x_max;
        
    timeline = $.plot(
        $("#timeline"), 
        dataset,
        opts
    );
    
    timeline.draw();
    
    // Update and draw a new overview
    timeline_overview.setData(dataset);
    timeline_overview.draw();
}

function build_timeline() {

    // Check to see if we need to create the datatable
    if($('#timeline').length == false) {
        $(target).append("<div id=\"vis_timeline\"><div id=\"timeline\" style=\"height:600px; width:900px;\"></div><div id=\"timeline_overview\" style=\"height:50px; width:900px;\"></div><div id=\"timeline_legend\" style=\"width:900px;\"></div></div>");
        $('#vis_select').append("<option>Timeline</option>");
    }
    
    var dataset = build_timeline_dataset();
    
    if(y_min > 0) y_min = (y_min - (y_min * 0.09));
    y_max = (y_max * 1.05);
    
    // Setup the timeline options    
    var opts = { 
        // Remove shadows for SPEEEEED!
        series: { shadowSize: 0 },
        // Remove the legend
        legend: { show: false },
        xaxis: {
            mode: "time",
            timeformat: "%y/%m/%d",
            min:x_min,
            max:x_max
        },
        yaxis: {
            min:y_min,
            max:y_max
        },
        grid: {
            mouseActiveRadius:20,
            autoHighlight:true,
            hoverable:true,
            clickable:true
        }
    };
    
    // Setup the timeline overview options
    var overview_opts =  {
        // Remove the legend
        legend: { show: false },
        series: {
            lines: { show: true, lineWidth: 1 },
            shadowSize: 0
        },
        xaxis: { ticks: [], mode: "time" },
        yaxis: { ticks: [], min: 0, autoscaleMargin: 0.1 },
        selection: { mode: "x" }
    };
    
    // Plot the timeline and the overview
    timeline = $.plot($('#timeline'), dataset, opts);
    timeline_overview = $.plot($("#timeline_overview"), dataset, overview_opts);
    
    $("#timeline").bind("plotselected", function (event, ranges) {
        timeline_zoomed = true;
        
        var dataset = build_timeline_dataset();
        var options = timeline.getOptions();
        
        var ymax = null;
        var ymin = null;
        
        for(var i = 0; i < dataset.length; i++) {
            for(var j = 0; j < dataset[i]['data'].length; j++) {
                var y = dataset[i]['data'][j][1];
                var x = dataset[i]['data'][j][0];
                
                if(x >= ranges.xaxis.from && x <= ranges.xaxis.to) {
                    if(ymax == null || ymax < y) ymax = y;
                    if(ymin == null || ymin > y) ymin = y;
                }
            }
        }
        
        timeline = $.plot(
            $("#timeline"), 
            dataset,
            $.extend(true, {}, options, {
                yaxis: { min: ymin, max: ymax },
                xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
            })
        );

        // don't fire event on the overview to prevent eternal loop
        timeline_overview.setSelection(ranges, true);
    });

    $("#timeline_overview").bind("plotselected", function (event, ranges) {
        if($('#tooltip').length > 0) $("#tooltip").remove();
        timeline.setSelection(ranges);
    });

    // Add tooltip
    $("#timeline").bind("plotclick", function(event, pos, item) {
        if(item) {
            if(previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                $("#tooltip").remove();

                var label = item.series.label;
                var parts = label.split(" ");
            
                var stuff = "<div>Session: " + parts[0] + "</div>";
                stuff += "<div>Feild: " + parts[1] + "</div>";
                stuff += "<div>Value: " + item.datapoint[1] + " " + label_hash[label] + "</div>";
                
                showTooltip(item.pageX, item.pageY, stuff);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;            
        }
    });
}

// Datatable Vis
function add_header_to_datatable() {
    var d = sessions[0].fields;
    
    var x = "<tr>";
    for(var i = 0; i < d.length; i++) x += "<td>" + d[i].field_name + "</td>";
    x += "</tr>";
    
    $('#datatable').append(x);
}

function build_data_table() {
    
    // Check to see if we need to create the datatable
    if($('#datatable').length == false) {
        $(target).append("<div id=\"vis_datatable\" style=\"display:none;\"><table id=\"datatable\"></table></div>");
    }
    
    // Cycle through sessions adding them to the data table
    for(var i = 0; i < sessions.length; i++) {
        
        if(i == 0) add_header_to_datatable();
        
        var d = sessions[i].data;
        
        // Cycle through the data adding rows to the table
        for(var j = 0; j < d.length; j++) {
            
            // Cycle through each column adding them to the table
            var x = "<tr>";
            for(var k = 0; k < d[j].length; k++) x += "<td>" + d[j][k] + "</td>";
            x += "</tr>";
            
            // Append row to table
            $('#datatable').append(x);
        }
    }
}

// Vis Management Functions, for creating and doing stuff
function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 70,
        left: x + 5,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}

function draw_vises() {
    for(var i = 0; i < vis_list.length; i++) {
        if(vis_list[i] == "timeline") {
            if(timeline == null) {
                build_timeline();
            }
            else {
                draw_timeline();
            }
        }
    }
}

function build_vis_list() {
   // console.log(sessions);
    
    if(vis_list == null) {
        vis_list = [];
    }
 
    // Push the universal items
    vis_list.push("scatter");
    vis_list.push("bar");
    
    // Check to see if timeline is available
    var timeline = true;
    
    for(var i = 0; i < sessions.length; i++) {
        var s = sessions[i];
        var local = false;
        
        for(var j = 0; j < s.fields.length; j++) {
            if(is_time(s.fields[j])) {
                sessions[i]['time_index'] = j;
                local = true;
            }
        }
        
        if(local == false) timeline = false;
    }
    
    if(timeline != false) vis_list.push('timeline');
}

function update_session_data(data) {
    // console.log(data);
    
    // Get the basic data we'll need to store later
    var id = data.meta.session_id;
    var index = session_hash[id];
    
    // Create array to store data
    var array = init_array(data.fields.length, data.data.length);
    
    // Increment the number of data points
    datapoint_count += data.data.length;
    
    // Loop through feilds adding them to array
    for(var i = 0; i < data.fields.length; i++) {
        
        // Loop through rows adding the corresponding data
        for(var j = 0; j < data.data.length; j++) {
            
            // Grab the value
            var value = data.data[j][data.fields[i].field_name];
            
            // See if it is time
            if(is_time(data.fields[i]))  {
                /*
                if(typeof(value) == "string") {
                    value = Date.parse(value);
                }
                else {
                    value = (new Date(value).getTime());
                }
                */
                
                if(typeof(value) == "string") value = parseInt(value);
                
                global_sort_time_index = sessions[index]['time_index'] = i;
            }
            
            // Add to array
            array[j][i] = value;
        }
    }
    
    array = array.sort(compare_on_time);
    
    // Update the meta object
    for(var x in data.meta) {
        sessions[index][x] = data.meta[x];
    }
    
    // Try and sort here to see if this fixes anything
    
    // Add data to session
    sessions[index].data = array;
    sessions[index].fields = data.fields;
    
    // Mark session as fetched
    sessions[index].fetched = true;

    // Check if all are fetched
    if(all_sessions_fetched()) {
        build_data_table();
        build_vis_list();
        draw_vises();
        finished = true;
        
        if(lively_updating_enabled) {
            lively = setTimeout(update_lively, 1000);
        }
    }
}

function fetch_session_data(session_id) {
    // Build URL and make JSON request
    var url = '/actions/get_data.php?session=' + session_id;
    $.getJSON(url, update_session_data);    
}

function start_vis_manager(params) {
    
    // Set target if its not set already
    if(target == null && typeof(params.target) != "undefined") {
        target = params.target;
    }
    
    // Set session if not set already, otherwise append
    if(sessions == null && typeof(params.sessions) != "undefined") {
        // console.log(params.sessions);
        sessions = Array();
        session_hash = {};
        
        for(var i = 0; i < params.sessions.length; i++) {
            var session = params.sessions[i];
            
            sessions.push({
                id:session,
                fetched:false
            });
            
            session_hash[session] = (sessions.length-1);
        }
    }
        
    // Start session fetch
    if(sessions.length > 0) {
        for(var i = 0; i < sessions.length; i++) {
            fetch_session_data(sessions[i].id);
        }
    }
}

