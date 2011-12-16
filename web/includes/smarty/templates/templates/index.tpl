<div id="featuredsix">
	{ if $six }
		{ foreach from=$six item=exp }
			<div class="featureditemwrapper">
				<div class="featureditem">
					<div class="featuredimage">
						<a href="experiment.php?id={$exp.experiment_id}"><img height="200" width="287" src="picture.php?url={$exp.provider_url}&h=200&w=287"></a>
					</div>
					<div class="featuredtext">
						<a href="experiment.php?id={$exp.experiment_id}">{ $exp.name|capitalize|truncate:32:"":true }</a>
					</div>
					<div class="featuredsubtext">
						Created by <a href="profile.php?id={$exp.owner_id}">{ $exp.firstname } { $exp.lastname }</a>
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
					<img src="http://isense.cs.uml.edu/html/img/PINPointStack.jpg" width="203px" />
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
					<a href="/blog/downloads">Duck!</a><br/>
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
						<img src="http://isense.cs.uml.edu/html/img/VernierLogo.gif" />
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
