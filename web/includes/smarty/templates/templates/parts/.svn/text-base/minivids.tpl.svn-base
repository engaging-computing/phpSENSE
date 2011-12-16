<div class="module">
	{ if !$user.guest }<div style="float:right; font-size:80%;"><a href="upload-videos.php?id={ $meta.experiment_id }">Add Videos</a></div>{ /if }
	<h1>Videos</h1>
	{ if $videos }
		<div id="videos" style="overflow-x:scroll;"> 
			<table>
				<tr>
					{ foreach from=$videos item=video }
						<td>
							<a href="http://www.youtube.com/watch?v={$video.provider_id}"><img src="http://i4.ytimg.com/vi/{$video.provider_id}/default.jpg" /></a>
						</td>
					{ /foreach }
				</tr>
			</table>
		</div>
	{ else }
		<div id="videos"> 
			<table width="100%">
				<tr>
					<td>No videos found.</td>
				</tr>
			</table>
		</div>
	{ /if }
</div>