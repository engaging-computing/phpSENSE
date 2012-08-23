{if $flag eq 1}
    
    <script>

    {literal}
    $(document).ready(function(){
    {/literal}
    var eid = '{$eid}';
    var filename = '{$filename}';
    var ufields = {$unmatched_fields|@json_encode};
    var uheaders = {$unmatched_headers|@json_encode};
    var sname = '{$session_name}';
    var sdesc = '{$session_description}';
    var sloc = '{$session_citystate}';
    {literal}

    $('#col_match').append("<table id='mismatch_table' width='100%''><thead><tr><th>Field</th><th>Headers</th></tr></thead><tbody id='mrows'></tbody></table>" );
    
    $('#match_submit').button();

    for( var field in ufields ){
        var header_drop_down = "<select id='header_"+ufields[field][0]+"'>";
        
        for( var header in uheaders ){
            header_drop_down += "<option value="+uheaders[header][0]+">"+uheaders[header][1]+"</option>"
        }

        header_drop_down += "</select>";

        $('#mrows').append('<tr><td id="field_'+ufields[field][0]+'">'+ufields[field][1]+'</td><td style=text-align:center;>'+header_drop_down+'</td></tr>');
    }

    $('#col_match').dialog({ buttons: { "Submit": function(){
        var matched = [];
        for( var field in ufields ){
            matched[matched.length] = {field: ufields[field][0], header:$('#header_'+ufields[field][0]).val()}
        }
        
        $.ajax({
            url: '../../actions/upload.php',
            type: "POST",
            data: {matched_columns: matched, file: filename, eid: eid, sname: sname, sdesc: sdesc, sloc: sloc}
        }).done(function(msg){
            $('#col_match').dialog('close');
            
            window.location = "newvis.php?sessions="+msg;
            
        }).fail(function(){
            alert('Something broke');
        });
        
    }},minWidth:"400",modal:true,draggable:false,closeOnEscape:false,resizable:false});  
    
    $('.ui-dialog-titlebar-close').hide();

    });
    {/literal}
    </script>

{/if}

{ if $user.guest }
    <div id="main-full">
        <div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
    </div>
{ elseif $closed == 1}
    <div id="main-full">
        <div>Sorry this experiment is currently closed.</div>
    </div>
{ else }

    <div id="main">
                { include file="parts/errors.tpl" }
                <form method="POST" id="upload_form" name="upload_form" enctype="multipart/form-data">
                        <fieldset id="basic-info">
            
                            { if $hideName || $hideProcedure || $hideLocation}

                                <legend>Step 1: Session Information</legend>
                                <p>Your session will be created with the following information.</p>
                                
                                { if $hideName }
                                <label for="session_name">* Name:</label>
                                <input type="text" name="session_name" id="session_name" class="required urlSafe" value="{$session_name}" onKeyPress="return event.keyCode!=13"/>
                                <br/>
                                <span id="session_name_hint" class="hint">Example: "My Super Awesome Test"</span><br/>
                                
                                {/if}{ if $hideProcedure }
                                <label for="session_description">* Procedure:</label>
                                <textarea name="session_description" id="session_description" class="required">{$session_description}</textarea>
                                <br/>
                                <span id="session_description_hint" class="hint">Describe the session procedure and other details.</span><br/>
                                
                                {/if}{ if $hideLocation }
                                <label for="session_citystate">* Location:</label>
                                <input type="text" name="session_citystate" id="session_citystate" value="{$session_citystate}" class="required" onKeyPress="return event.keyCode!=13"/>
                                <br/>
                                <span id="session_citystate_hint" class="hint">Example: "4 Yawkey Way, Boston, MA" or "Boston, Ma" </span><br/>
                                
                        {/if}
                        <legend>Step 2: Add Session Data</legend></br>
                        {else} 
                            <legend>Step 1: Add Session Data</legend></br>                 
                    {/if}
                                       
                                        <label for="session_type">Session Type:</label>
                                        
    
                    <div style="width:480px;">
                                            <input type="radio" id="manual_upload" name="session_type" group="session_type" value="manual" style="width:20px;" CHECKED /><span>Manual Entry</span>
                                                <input type="radio" id="file_upload" name="session_type" group="session_type" value="file" style="width:20px;"/><span>Data File</span>
                                        </div>
                                        <div id="error_rows" style="display:none;text-align:center"></div><br/>
                                        
                                        <div id="type_file" style="display:none;">
                                                <input type="file" id="session_file" name="session_file"/><br/>
                                                <span class="hint">Click browse and select your CSV data file.</span><br/>
                                        </div>
                                        <div id="type_manual" style="width:480px">
                                            
                                                <table width="480px" cellpadding="3" id="manual_table">
                                                        <tr>
                                                                { foreach from=$fields item=field }
                                                                        <td>{ $field.field_name } ({$field.unit_abbreviation})</td>
                                                                { /foreach }
                                                        </tr>
                                                        <tr id="template" style="display:none;">
                                                                { foreach from=$fields item=field }
                                                                        <td><input type="text" onKeyPress="return event.keyCode!=13" id="{ $field.field_name|replace:' ':'_' }_xxx" name="{ $field.field_name|replace:' ':'_'  }_xxx"  style="width:90%;"></td>
                                                                { /foreach }
                                                        </tr>
                                                        <tr>
                                                                { foreach from=$fields item=field }
                                                                    { if $field.field_name == 'Time' }
                                                                         <td><input type="text" onKeyPress="return event.keyCode!=13" id="{ $field.field_name|replace:' ':'_' }_1" name="{ $field.field_name|replace:' ':'_' }_1" style="width:90%;" class="time"></td>
                                                                    { else }
                                                                        <td><input type="text" onKeyPress="return event.keyCode!=13" id="{ $field.field_name|replace:' ':'_' }_1" name="{ $field.field_name|replace:' ':'_' }_1" style="width:90%;" ></td>
                                                                    { /if }
                                                                { /foreach }
                                                        </tr>
                                                </table>
                                                
                                                <input type="hidden" id="row_count" name="row_count" value="1" />
                                                
                                                <span>
                                                    <button type="button" id="addManualRowButton">Add Row</button>
                                                    <button type="button" id="removeManualRowButton" disabled="disabled">Remove Row</button>
                                                </span>
                                               
                                        </div>


                                <div>
                                    <input type="hidden" name="id" value="{ $meta.experiment_id }" />
                                    <button type="submit" name="session_create">Create Session</button> 
                                </div>

                                <div id="col_match" title="Please help us match your headers"></div>


{/if}
