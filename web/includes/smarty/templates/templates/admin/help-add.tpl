<div id="main-full">
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			{ if $done }
				<p>You have successfully created a new help article. <a href="admin.php?action=helpadd">Click here</a> to create another.</p>
			{ else }
				{include file="parts/errors.tpl"}
				<form method="post">
					<table width="100%" id="management_table" class="profile">
						<tr>
							<td class="heading" valign="top">Help Topic:</td>
							<td><input type="text" name="title" id="title" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Help Response:</td>
							<td><textarea cols="50" name="content" id="content" rows="10"></textarea></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Publish?:</td>
							<td><input type="checkbox" name="publish" id="publish" value="Yes" /></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="create" id="create" value="Create"/></td>
						</tr>
					</table>
				</form>
			{ /if }
		</div>
	</div>
</div>