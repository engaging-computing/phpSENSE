<head>
    <link rel="stylesheet" href="./html/css/mobile/jquery.mobile.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="/html/js/jquery.mobile-1.1.1.js"></script>
   <!-- <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.1.1/jquery.mobile-1.1.1.min.css" />-->
</head>

<div data-theme="a" data-role="header">
        <h3>iSENSE Project</h3>
</div>

<div data-role="content" style="padding:15px;">

    <div class="ui-grid-a">
        <div class="ui-block-a">
            <a href="login.php" id="mLogin" data-ajax="false" data-role="button">Login</a>
        </div>
        <div class="ui-block-b">
            <a id="mExperiments" data-ajax="false"data-role="button">Experiments</a>
        </div>
    </div>

    <ul data-role="listview" data-divider-theme="b" data-inset="true">
        <li data-role="list-divider" role="heading">Featured Experiments</li>
        {if $six}
            { foreach from=$six item=exp }
                <li class = "featuredimage" data-theme="c" style="text-align:center">
                    <a href="./experiment.php?id={$exp.experiment_id}" data-transition="slide" data-ajax="false" >
                        <img src="picture.php?url={$exp.exp_image}&h=200&w=287">
                        <table class="session_info">
                            <tr><td colspan="2"><b>{ $exp.name|capitalize|truncate:32:"":true }</b></td></tr>
                            <tr><td>{ $exp.description}</td></tr>
                        </table>
                    </a>
                </li>
            { /foreach }
        {/if}
    </ul>

</div>