<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 -->
<div id="main">
    { include file="parts/errors.tpl" }
    <div id="searchboxwrapper">
        <div id="searchbox">
            <form id="browseform" method="GET" action="browse.php">
                <div style="padding-bottom:5px">Search: <input type="text" name="query" value="{ $query }" /> <input type="hidden" name="type" value="{ $type }" /> <input type="submit" name="action" value="Search" /></div>

                { if $type != "people" and $type != "visualizations" }
                    <div style="padding-bottom:5px">
                        <span>Sort:</span>

                        <select class="selectformsubmitter" name="sorttype">
                            <option value="recent"{if $sorttype == "recent"} selected{/if}>Recent</option>
                            <option value="popularity"{if $sorttype == "popularity"} selected{/if}>Popularity</option>
                            <option value="activity"{if $sorttype == "activity"} selected{/if}>Activity</option>
                            <option value="rating"{if $sorttype == "rating"} selected{/if}>Rating</option>
                        </select>
                    </div>
                        <tr>
                        <td><span>Filter:</span></td>
                        <td>Recommended: <input class="checkboxformsubmitter" type="checkbox" id="recommended" name="recommended" {if $recommended == 'on'}checked{/if} /></td>
                        <td> | </td>
                        <td>Featured: <input class="checkboxformsubmitter" type="checkbox" id="featured" name="featured" {if $featured == 'on'}checked{/if} /></td>
                        <tr>
                    </div>
                { /if }
            </form>
        </div>
        <div id="results">
            { if !empty($results) }

                <!-- If viewing only visualizations -->
                { if $type == "visualizations" }

                    { foreach from=$results item=result }
                        <div class="result{ if $result.is_activity == 1} activity { else } vis { /if }">
                            <table width="100%" cellpaddding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">
                                        <div class="name">
                                            <a href="visdir.php?id={ $result.meta.vis_id }">{ $result.meta.name }</a>
                                        </div>
                                        <div class="description" >{ $result.meta.description|truncate:180:"...":true}</div>
                                        <div class="sub">
                                            <span> Last Modified { $result.meta.timecreated|date_diff } </span>
                                        </div>
                                    </td>
                                    <!--
                                    <td width="48px">
                                        <img src="picture.php?type=experiment&amp;id={ $result.meta.experiment_id }&amp;w=75&amp;h=75" height="75px" width="75px" />
                                    </td>
                                    -->
                                </tr>
                            </table>
                        </div>
                    { /foreach }

                <!-- If viewing only People -->
                { elseif $type == "people"}

                    { foreach from=$results item=result }
                        <div class="result">
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
                                    <!--
                                    <td width="48px">
                                        <img src="picture.php?id={ $result.user_id }&amp;w=75&amp;h=75" height="75px" width="75px" />
                                    </td>
                                    -->
                                </tr>
                            </table>
                        </div>
                    { /foreach }


                <!-- If viewing Experiments -->
                { else }

                    { foreach from=$results item=result }
                        <div class="result">
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
                                    <!--
                                    <td width="48px">
                                        <img src="picture.php?type=experiment&amp;id={ $result.meta.experiment_id }&amp;w=75&amp;h=75" height="75px" width="75px" />
                                    </td>
                                    -->
                                </tr>
                            </table>
                        </div>
                    { /foreach }

                { /if }

            { else }
                <div class="result">Sorry, we could not find any { $marker } matching your search criteria.</div>
            { /if }
        </div>
        <div class="pagination" style="margin-bottom: 10px;">
            <table cellpadding="0" cellspacing="0">
                <tr>

                { if $page != 1 }

                    <td width="44">
                            <a href="browse.php?type={ $type }&amp;page={ math equation="y - x" x=1 y=$page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sorttype={ $sorttype }&amp;featured={ $featured }&amp;recommended={ $recommended }">Previous</a>
                    </td>

                { /if }

                    { if $page > 5 }

                        <td>
                            <a href="browse.php?type={ $type }&amp;page=1&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sorttype={ $sortype }&amp;featured={ $featured }&amp;recommended={ $recommended }">
                            1
                            </a>
                        </td>
                        <td>
                        ...
                        </td>

                    { /if }

                    { foreach item=navbar from=$navbarpages }

                        <td>

                            { if $page == $navbar } <u> { /if }

                                <a href="browse.php?type={ $type }&amp;page={ $navbar }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sorttype={ $sorttype }&amp;featured={ $featured }&amp;recommended={ $recommended }">
                                    { $navbar }
                                </a>

                            { if $page == $navbar } </u> { /if }

                        </td>

                    { /foreach }

                    { if $page < $numpages - 4 }

                        <td>
                        ...
                        </td>
                        <td>
                            <a href="browse.php?type={ $type }&amp;page={ $numpages }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sorttype={ $sorttype }&amp;featured={ $featured }&amp;recommended={ $recommended }">
                            { $numpages }
                            </a>
                        </td>

                    { /if }

                    <td width="44">
                        { if $next == true }
                                <a href="browse.php?type={ $type }&amp;page={ math equation="x + y" x=1 y=$page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sorttype={ $sorttype }&amp;featured={ $featured }&amp;recommended={ $recommended }">Next</a>
                        { else }
                            &nbsp;
                        { /if }
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div id="sidebar">
    <div class="module">
        { if $action == "browse" }
            You are currently viewing all <span style="font-weight:bold;">{ $type }</span> sorted <span style="font-weight:bold;">{ $sorttext }</span>.
        { else }
            You are currently viewing all <span style="font-weight:bold;">{ $type }</span> with the term <span style="font-weight:bold;">{ $query }</span> sorted <span style="font-weight:bold;">{ $sorttext }</span>.
        { /if }
    </div>
</div>
