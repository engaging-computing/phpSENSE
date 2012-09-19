<head>
    <link rel="stylesheet" href="./html/css/mobile/jquery.mobile.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
    <script src="html/js/isense.js"></script>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
</head>

<div id="main">
    <div data-theme="a" data-role="header">
        <h3>
            {$title}
        </h3>
    </div>

    <div data-role="content" style="padding: 15px">


        <div data-role="collapsible-set" data-theme="b" data-content-theme="">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                    Session Info
                </h3>
                <div class="session_info">
                 <table>
                    <tr>
                        <td><h3 >Description:</h3></td>
                        <td><p>{$meta.description}</p></td>
                    </tr>
                    
                    <tr>
                        <td><h3 >Fields:</h3></td>
                        <td><p>
                            { section name=foo loop=$fields }                           
                                    { $fields[foo].field_name } ({ $fields[foo].unit_abbreviation }){ if $smarty.section.foo.total-1 != $smarty.section.foo.index }, { /if }         
                            { /section }
                        </p></td>
                    </tr>
                    <tr>    
                        <td><h3 >Creator:</h3></td>
                        <td><p>{$meta.firstname}</p></td>
                    </tr>
                    <tr>
                        <td><h3 >Created:</h3></td>
                        <td><p>{ $meta.timecreated }</p></td>
                    </tr>
                   </table>
                </div>
            </div>
        </div>

        <div data-role="collapsible-set" data-theme="b" data-content-theme="">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                    Session Tools
                </h3>
                <a data-role="button" data-transition="fade" href="#page1">
                    Contribute
                </a>
                <a href="#" onclick="loadVis2({$meta.experiment_id});" data-role="button" data-transition="fade" href="#page1">
                    Visualize
                </a>
            </div>
        </div>

        <div data-role="collapsible-set" data-theme="b" data-content-theme="">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                    Sessions
                </h3>

                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup" data-type="vertical">
                        {if $sessions != null}
                            { foreach from=$sessions item=session key=j }
                                <div class="session_select">
                                    <input name="sessions" id="{$session.session_id}" value="{$session.session_id}" type="checkbox" {if $j==0}checked="checked"{/if}>
                                    <label for="{$session.session_id}">
                                        {$session.name}
                                    </label>
                                </div>
                            {/foreach}
                        {else}
                        <b>No sessions were found.<b>
                        {/if}
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>