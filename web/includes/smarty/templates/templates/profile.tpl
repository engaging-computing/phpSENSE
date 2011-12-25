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
{ if $user.guest }
	<div id="main-full">
		<div>Guests do not have access to profiles. If you already have an account, click 
                     <a href="login.php">here</a> to login. If not, click <a href="register.php">here</a> to register for an account.
                </div>
	</div>
{ else }
	<div id="main">
		{ include file="parts/errors.tpl" }
		
		<div id="searchbox" style="margin:0px 0px 10px 0px; padding:0px 0px 10px 0px; border-bottom:1px solid #CCC;">
			You are viewing: 
			<select id="filter" name="filter" onchange="filterProfile();">
				<option value="all">All Activity</option>
				<option value="experiment">Only Experiments</option>
				<option value="session">Only Sessions</option>
				<option value="vis">Only Visualizations</option>
				<option value="video">Only Videos</option>
				<option value="image">Only Images</option>
				<option value="activity">Only Activities</option>
			</select>
		</div>
				
		<div id="results">
			{ if !empty($results) }
				{ foreach from=$results item=result }

					{ if $result.type == 'experiment' }
					
						<div class="result experiment" style="margin:0px 0px 10px 0px;">
							<div class="icon_column" style="float:left; width:40px; height:55px;"> 
							 <img src="../../../html/img/icons/archive.png"/>						
							</div>
							<div class="info_column" style="float;right; "> 
								<div class="name" style="font-size:18px;"> <a href="experiment.php?id={ $result.experiment_id }">{ $result.name }</a></div>
        							<div class="description" style="font-size:13px;">{ $result.description }</div>
								<div class="sub" style="font-size:10px;">
									<span>Last Modified { $result.timeobj|date_diff }</span>
									{ if $result.owner_id == $user.user_id or $user.administrator == 1 }
										<span><a href="javascript:void(0);" onclick="window.location.href='experiment-edit.php?id={ $result.experiment_id }';">
										Edit</a>
									</span>
									{ /if }
								</div>
							</div>
						</div>
						
					{ elseif $result.type == 'session' }
					
						<div class="result session" style="margin:0px 0px 10px 0px;">
                                                	<div class="icon_column" style="float:left; width:40px;"> 
								<img src="../../../html/img/icons/folder.png"/>						
							</div>
							<div class="info_column" style="float;right;"> 
								<div class="name" style="font-size:16px;"><a href="vis.php?sessions={ $result.session_id }">{ $result.name }</a></div>
								<div class="description" style="font-size:13px;">{ $result.description }</div>
								<div class="sub" style="font-size:10px;">
									<span>Last Modified { $result.timeobj|date_diff }</span>
									{ if $result.owner_id == $user.user_id or $user.administrator == 1 }
								    		<span><a href="javascript:void(0);" onclick="window.location.href='session-edit.php?id={ $result.session_id }';">Edit</a></span>
									{ /if }
								</div>
							</div>
						</div>
						 
					{ elseif $result.type == 'vis' }

						<div class="result vis" style="margin:0px 0px 10px 0px;">
							<div class="icon_column" style="float:left; width:40px; height:55px;"> 
								<img src="../../../html/img/icons/chart_bar.png"/>					
							</div>
							<div class="info_column" style="float;right;"> 							
								<div class="name" style="font-size:16px;"><a href="vis.php?sessions={ $result.sessions }&state={ $result.url_params }">{ $result.name }</a></div>
								<div class="description" style="font-size:13px;">{ $result.description }blah</div>
								<div class="sub" style="font-size:13px;"><span>Last Modified { $result.timeobj|date_diff }</span></div>
							</div> 
						</div>
					
					{ elseif $result.type == 'image' }
					
						<div class="result image" style="margin:0px 0px 10px 0px;">
							<div class="name" style="font-size:16px;">{ $result.name }</div>
							<div class="description" style="font-size:13px;"><img src="picture.php?url={ $result.provider_url }" /></div>
							<div class="sub" style="font-size:13px;">
								<span>Last Modified { $result.timeobj|date_diff }</span>
							</div>
						</div>
					
					{ elseif $result.type == 'video' }
					
						<div class="result video" style="margin:0px 0px 10px 0px;">
							<div class="name" style="font-size:16px;">{ $result.name }</div>
							<div class="description" style="font-size:13px;">{ $result.description }</div>
							<div class="sub" style="font-size:13px;">
								<span>Last Modified { $result.timeobj|date_diff }</span>
							</div>
						</div>
						
					{ else if $result.type == 'activity_responses' }
					    
					    <div class="result activity" style="margin:0px 0px 10px 0px;">
					        <div class="name">
					           <a href="visdir.php?id={ $result.vis_id }">
					               { $result.owner_firstname } { $result.owner_lastname } completed { $result.exp_name }
					            </a>
					        </div>
					        <div class="description" style="font-size:13px;">{ $result.description }</div>
					        <div class="sub" style="font-size:13px;">
								<span>Last Modified { $result.timeobj|date_diff }</span>
							</div>
					    </div>
					    
					{ /if }

				{ /foreach }
			{ else }
			    <div class="result activity" style="margin:0px 0px 10px 0px;">
			        <div class="name">
			            { if $is_owner }
			                No one has completed your activities.
			            { else }
			                { $userdata.firstname } has not completed any activities.
			            { /if }
			        </div>
			    </div>
			{ /if }
		</div>
	</div>
	<div id="sidebar">
		<div class="module">
			<div style="text-align:center;">
				<img src="picture.php?id={$id}" alt="No picture uploaded." />
			</div>
			<div>
				<div>
					{ $userdata.firstname } is from { $userdata.city } and joined iSENSE on { $userdata.firstaccess }.
				</div>
			</div>
		</div>
		{ include file="parts/graph.tpl" }
		<!--
		<div class="module">
			<h1>{ $userdata.firstname }'s Stats</h1>
			<div>
				{ $counts.experiments } Experiments<br/>
				{ $counts.sessions } Sessions<br/>
				{ $counts.vises } Visualizations<br/>
				{ $counts.videos } Videos<br/>
				{ $counts.images } Images<br/>
			</div>
		</div>
		-->
	</div>
{ /if }
