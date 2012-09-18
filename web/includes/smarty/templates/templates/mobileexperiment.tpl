<head>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.1.1/jquery.mobile-1.1.1.min.js"></script>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />
</head>

<div id="main">
    <div data-theme="a" data-role="header">
        <h3>
            {$exp}
        </h3>
    </div>

    <div data-role="content" style="padding: 15px">


        <div data-role="collapsible-set" data-theme="b" data-content-theme="">
            <div data-role="collapsible" data-collapsed="false">
                <h3>
                    Session Tools
                </h3>
                <a data-role="button" data-transition="fade" href="#page1">
                    Contribute
                </a>
                <a data-role="button" data-transition="fade" href="#page1">
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
                        { foreach from=$sessions item=session key=j }
                            <input name="" id="checkbox{$session.session_id}" type="checkbox">
                            <label for="checkbox{$session.session_id}">
                                {$session.name}
                            </label>
                        {/foreach}
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>