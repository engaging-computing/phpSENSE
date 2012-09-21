<head>
    <link rel="stylesheet" href="./html/css/mobile/jquery.mobile.css" />
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="/html/js/jquery.mobile-1.1.1.js"></script>
    
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
	<div data-role="fieldcontain">
	    <fieldset >
		<label for="searchinput1">
		</label>
		<input name="" id="searchinput1" placeholder="" value="" type="search">
	    </fieldset>
	</div>
    </div>
</div>

    <ul data-role="listview" data-divider-theme="b" data-inset="true">
        <li data-role="list-divider" role="heading">Experiments</li>
        

            { if !empty($results) }

                <!-- If viewing only visualizations -->
                { if $type == "visualizations" }

                    { foreach from=$results item=result }
                        <li>
                            <table width="100%" cellpaddding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">
                                        <div class="name">
                                            <a href="highvis.php?vid={ $result.meta.vid }">{ $result.meta.title }</a>
                                        </div>
                                        <div class="description" >{ $result.meta.description|truncate:180:"...":true}</div>
                                        <div class="sub">
                                            <span> Last Modified { $result.meta.timeobj|date_diff } </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </li>
                    { /foreach }

                <!-- If viewing only People -->
                { elseif $type == "people"}

                    { foreach from=$results item=result }
                        <li>
                            <table width="100%" cellpaddding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">
                                        <div class="name">
                                            <a href="profile.php?id={ $result.user_id }">{ $result.firstname|capitalize } { $result.lastname|capitalize }</a>
                                        </div>
                                        <div class="description" >
                                            Created {$result.experiment_count} { if $result.experiment_count == 1}experiment{else}experiments{/if} and contributed { $result.session_count } { if $result.session_count == 1}session{else}sessions{/if}.
                                        </div>
                                        <div class="sub">
                                            <span>Joined { $result.firstaccess|date_diff }</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </li>
                    { /foreach }


                <!-- If viewing Experiments -->
                { else }

                    { foreach from=$results item=result }
                        <li>
                            <table width="100%" cellpaddding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">
                                        <div class="name">
                                            { if $type != "activities" }
                                                <a href="experiment.php?id={ $result.meta.experiment_id }">{ $result.meta.name|capitalize }</a>
                                            { else }
                                                <a href="activity.php?id={ $result.meta.experiment_id }">{ $result.meta.name|capitalize }</a>
                                            { /if }
                                        </div>
                                        <div class="description" >{ $result.meta.description|truncate:180:"...":true}</div>
                                        <div class="sub">
                                            <a class="session_count">{ $result.session_count }</a>
                                            <a class="contrib_count">{ $result.contrib_count }</a>
                                            {if $result.meta.rating_comp > 0}
                                            <a class="rating_browse">{ $result.meta.rating_comp|substr:0:3 }</a>
                                            {/if}
                                                <span>Last Modified { $result.meta.timemodified|date_diff }</span>
                                            { if $result.meta.hidden == 1 }
                                                <br><span>This experiment is hidden</span>
                                            { /if }
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </li>
                    { /foreach }

                { /if }

            { else }
                <div class="result">Sorry, we could not find any { $marker } matching your search criteria.</div>
            { /if }


    </ul>
    
    
    
    
    <div data-theme="a" data-role="footer" data-position="fixed">
        <div data-role="navbar" data-iconpos="top">
            <ul>
                <li>
                    <a href="./browse.php" data-theme="" data-icon="" data-ajax="false">
                        Experiments
                    </a>
                </li>
                <li>
                    <a href="./browse.php?type=visualizations" data-theme="" data-icon="" data-ajax="false">
                        Visualizations
                    </a>
                </li>
                <!--<li>
                    <a href="./browse.php?type=people" data-theme="" data-icon="" data-ajax="false">
                        People
                    </a>
                </li>-->
            </ul>
        </div>
    
</div>