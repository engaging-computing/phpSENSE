<div class="module">
	{ if !$user.guest }<div style="float:right; font-size:80%;"><a href="upload-pictures.php?id={ $meta.experiment_id }">Add Pictures</a></div>{ /if }
	<h1>Pictures</h1>
	{ if $pictures|@count > 0 }
		<div id="pictures" style="overflow-x:scroll;"> 
			<table>
				<tr>
					{ foreach from=$pictures item=picture }
						<td>
							<a href="{$picture.set_url}"><img src="picture.php?url={$picture.source}&amp;h=160&w=230" /></a>
						</td>
					{ /foreach }
				</tr>
			</table>
		</div>
	{ else }
		<div id="pictures"> 
			<table width="100%">
				<tr>
					<td>No pictures found.</td>
				</tr>
			</table>
		</div>
	{ /if }
</div>