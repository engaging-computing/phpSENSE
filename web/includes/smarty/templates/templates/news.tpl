<div id="main-full">
	{ if $error }
		<div>Sorry, we could not find the article you are looking for.</div>
	{ else }
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="/profile.php?id={ $data.author_id }">{ $data.firstname } { $data.lastname }</a></td>
					</tr>
					<tr>
						<td class="heading" valign="top">Published On:</td>
						<td>{ $data.pubDate }</td>
					</tr>
					<tr>
						<td colspan="2">{ $data.content }</td>
					</tr>
				</table>
			</div>
		</div>
	{ /if }
</div>