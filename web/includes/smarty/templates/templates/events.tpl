<div id="main-full">
	{ if $error }
		<div>Sorry, we could not find the event you are looking for.</div>
	{ else }
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile" cellpadding="0" cellspacing="0">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="/profile.php?id={ $data.author_id }">{ $data.firstname } { $data.lastname }</a></td>
					</tr>
					<tr>
						<td class="heading" valign="top">Location:</td>
						<td>{ $data.location }</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Starts:</td>
						<td>{ $data.start }</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Ends:</td>
						<td>{ $data.end }</td>
					</tr>
					<tr>
						<td class="heading" valign="top">Description:</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">{ $data.description }</td>
					</tr>
				</table>
			</div>
		</div>
	{ /if }
</div>