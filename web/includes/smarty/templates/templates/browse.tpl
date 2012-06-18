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
			<form method="GET" action="browse.php">
				<div>Search: <input type="text" name="query" value="{ $query }" /> <input type="hidden" name="type" value="{ $type }" /> <input type="submit" name="action" value="Search" /></div>
			</form>
			{ if $type != "people" and $type != "visualizations" } 
				<div>
					<span>Sort:</span>
					<a href="browse.php?type={ $type }&amp;page={ $page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort=recent">Recent</a> | 
					<a href="browse.php?type={ $type }&amp;page={ $page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort=popularity">Popularity</a> | 
					<a href="browse.php?type={ $type }&amp;page={ $page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort=activity">Activity</a> | 
					<a href="browse.php?type={ $type }&amp;page={ $page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort=rating">Rating</a>
				</div>
			{ /if }
		</div>
		<div id="results">
			{ if !empty($results) }
				
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
                                            {if $user.administrator == 1}
                                                <span class="loading_msg" style="display:none;">Loading...</span>
                                                <span style="color:#444">Feature:</span> <input type="checkbox" class="feature_vis" value="{$result.meta.vis_id}" {if $result.meta.featured == 1}checked{/if} />
                                                {if $result.meta.featured == 1}
                                                    <a style="display:inline" id="pickimage_{ $result.meta.vis_id }" href="upload-pictures.php?id={ $result.meta.vis_id }">Pick Image</a>
                                                {else}
                                                    <a style="display:none;" id="pickimage_{ $result.meta.vis_id }" href="upload-pictures.php?id={ $result.meta.vis_id }">Pick Image</a>
                                                {/if}
                                                <span style="color:#444">Hidden:</span> <input type="checkbox" class="hide_vis" value="{$result.meta.vis_id}" {if $result.meta.hidden == 1} checked {/if} />
                                                
                                                
                                            {/if}
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
										    <a class="rating_browse">{ $result.meta.rating_comp|substr:0:3 }</a>
										    {if $user.administrator == 1}
                                                <span class="loading_msg" style="display:none;">Loading...</span>
                                                <span style="color:#444">Feature:</span> <input type="checkbox" class="feature_experiment" value="{$result.meta.experiment_id}" {if $result.meta.featured == 1}checked{/if} />
                                                {if $result.meta.featured == 1}
                                                    <a style="display:inline" id="pickimage_{ $result.meta.experiment_id }" href="upload-pictures.php?id={ $result.meta.experiment_id }">Pick Image</a>
                                                {else}
                                                    <a style="display:none;" id="pickimage_{ $result.meta.experiment_id }" href="upload-pictures.php?id={ $result.meta.experiment_id }">Pick Image</a>
                                                {/if}
                                                <span style="color:#444">Hidden:</span> <input type="checkbox" class="hide_experiment" id="{$result.meta.experiment_id}" {if $result.meta.hidden == 1} checked {/if} />
                                                
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
				<div class="result">Sorry, it we could not find any { $marker } matching your criteria.</div>
			{ /if }
		</div>
		<div class="pagination" style="margin-bottom: 10px;">
			<table cellpadding="0" cellspacing="0">
				<tr>

				{ if $page != 1 } 

					<td width="44">
							<a href="browse.php?type={ $type }&amp;page={ math equation="y - x" x=1 y=$page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort={ $sort }">Previous</a>
					</td>	

				{ /if }

					{ if $page > 5 }

						<td>
							<a href="browse.php?type={ $type }&amp;page=1&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort={ $sort }">
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
							
								<a href="browse.php?type={ $type }&amp;page={ $navbar }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort={ $sort }">
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
							<a href="browse.php?type={ $type }&amp;page={ $numpages }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort={ $sort }">
							{ $numpages }
							</a>
						</td>

					{ /if }

					<td width="44">
						{ if $next == true }
								<a href="browse.php?type={ $type }&amp;page={ math equation="x + y" x=1 y=$page }&amp;limit={ $limit }&amp;action={ $action }&amp;query={ $query }&amp;sort={ $sort }">Next</a>
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
