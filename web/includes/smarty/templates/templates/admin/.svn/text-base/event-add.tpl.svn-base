<div id="main-full">
	<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
		<div>
			{ if $done }
				<p>You have successfully created a new event. <a href="admin.php?action=eventadd">Click here</a> to create another.</p>
			{ else }
				{include file="parts/errors.tpl"}
				<form method="post">
					<table width="100%" id="management_table" class="profile">
						<tr>
							<td class="heading" valign="top">Title:</td>
							<td><input type="text" name="title" id="title" /</td>
						</tr>
						<tr>
							<td class="heading" valign="top">Location:</td>
							<td><input type="text" name="location" id="location" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Starts:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="start" id="start" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Ends:</td>
							<td><input type="text" style="margin:0px 5px 0px 0px;" name="end" id="end" /></td>
						</tr>
						<tr>
							<td class="heading" valign="top">Description:</td>
							<td><textarea cols="60" name="description" id="description" rows="10"></textarea></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" id="create" name="create" value="Create"></td>
						</tr>
					</table>
				</form>
			{ /if}
		</div>
	</div>
</div>