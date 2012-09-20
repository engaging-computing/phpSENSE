<head>
    <link rel="stylesheet" href="./html/css/mobile/jquery.mobile.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
    <script src="/html/js/isense.js"></script>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
</head>

<div data-theme="a" data-role="header"><h3>{$title}</h3></div>
    
<div data-role="content" style="padding: 15px">
{ if $user.guest }
    <div id="main-full">
        <div>Guests do not have access to contribute to experiments. If you already have an account, click <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.</div>
    </div>
{ elseif $closed == 1}
    <div id="main-full">
        <div>Sorry this experiment is currently closed.</div>
    </div>
{ else }

    <form method="POST" data-ajax="false" id="upload_form" name="upload_form" enctype="multipart/form-data">
        

        { if $hideName }
            <label for="session_name">* Name:</label>
            <input type="text" name="session_name" id="session_name" class="required urlSafe" value="{$session_name}" onKeyPress="return event.keyCode!=13"/>          
        {/if}
        { if $hideProcedure }
            <label for="session_description">* Procedure:</label>
            <textarea name="session_description" id="session_description" class="required">{$session_description}</textarea>                
        {/if}
        { if $hideLocation }
            <label for="session_citystate">* Location:</label>
            <input type="text" name="session_citystate" id="session_citystate" value="{$session_citystate}" class="required" onKeyPress="return event.keyCode!=13"/>         
        {/if}

        <table id="manual_table">
            { foreach from=$fields item=field }
                <tr>
                    <td>{ $field.field_name } ({$field.unit_abbreviation})</td>
                    <td><input type="text" onKeyPress="return event.keyCode!=13" id="{ $field.field_name|replace:' ':'_' }_1" name="{ $field.field_name|replace:' ':'_'  }_1"{ if $field.field_name == 'Time' }value="now"{/if}></td>
                </tr>
            { /foreach }
            <tr>
                <td colspan="2">
                    <button type="submit" name="session_create" >Create</button>
                </td>
            <tr>
        </table>
        <input type="hidden" name="id" value="{ $meta.experiment_id }" />
        <input type="hidden" name="session_type" value="manual" />
        <input type="hidden" name="row_count" value="1" />
    </form>

{/if}
</div>    