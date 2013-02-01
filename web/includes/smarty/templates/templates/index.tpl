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
<div id="featuredsix">
	{ if $six }
		{ foreach from=$six item=exp }
			<div class="featureditemwrapper">
				<div class="featureditem">
					<div class="featuredimage">
						<a href="/experiment.php?id={$exp.experiment_id}"><img height="200" width="287" src="picture.php?url={$exp.exp_image}&h=200&w=287"></a>
					</div>
					<div class="fetauredtext">
						<a href="/experiment.php?id={$exp.experiment_id}">{ $exp.name|capitalize|truncate:64:"":true }</a>
					</div>
					<div class="featuredsubtext">
						Created by <a href="/profile.php?id={$exp.user_id}">{ $exp.firstname } { $exp.lastname }</a>
					</div>
				</div>
			</div>
		{ /foreach }
	{ /if }
</div>
<div id="subsix">
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>News and Events</h1>
			<div>
				{ if $events } 
					{ foreach from=$events item=event }
						<p><a href="{ $event.link }">{ $event.title }</a></p><br/>
					{ /foreach }
				{ else }
					<p>No upcoming events, check back later.</p>
				{ /if }
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>PINPoint Data Logger</h1>
			<div>
				<p>
					<img src="/html/img/PINPointv4.png" width="203px" />
					The portable iSENSE Network Point (PINPoint) is a GPS-enabled data logger designed specifically for use with the iSENSE web site.<br/>
					<a href="/blog/doc/pinpoint-data-logger">Learn More...</a>
				</p>
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:307px;">
			<h1>Resources</h1>
			<div>
				<p>
					<a href="/blog/downloads">Downloads</a><br/>
					Get the software needed to use the PINPoint data logger.
				</p>
				<br/>				
				<p>
					<a href="/blog/doc/data-upload">Data Upload Tutorial</a><br/>
					Learn how to upload and share data on iSENSE.
				</p>
				<br/>				
				<p>
					<a href="/blog/doc/lesson-plans">Lesson Plans</a><br/>
					View ideas for using iSENSE in your classroom.
				</p>
				<br/>
				<p>
					<a href="/blog/help">iSENSE Help Guide</a><br/>
					Learn more about using the iSENSE web system.
				</p>
			</div>
		</div>
	</div>
	
	<div class="subsixitem">
		<div class="module_subsix module_subsix_right" style="height:142px;">
			<div>
				<p>
					<div style="text-align:center">
						<img src="/html/img/VernierLogo.gif" />
					</div>
				</p>
				<p>
					Data collected with Vernier probes can be imported into iSENSE. <br/>
					<a href="/blog/doc/vernier-logger-lite-instructions">Learn More...</a>
				</p>
			</div>
		</div>
		
		<div class="module_subsix module_subsix_right" style="height:142px;">
			<h1>Site Statistics</h1>
			<div>
				<p>
					<ul style="list-style-type:disc; padding-left:10px; margin-left:15px;">
						<li>{ $count_users } teachers and students, who created</li>
						<li>{ $count_exps } experiments, containing</li>
						<li>{ $count_sessions } sessions.</li>
					</ul>
				</p>
			</div>
		</div>
		
	</div>
</div>
