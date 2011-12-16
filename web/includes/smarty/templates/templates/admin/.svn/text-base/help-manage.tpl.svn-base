<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="helpnew" value="New Article" onclick="window.location.href='admin.php?action=helpadd';" /> 
			<input type="submit" id="helpdelete" value="Delete Article" onclick="deleteHelp();" /> 
			<input type="submit" id="helppublish" value="Publish Article" onclick="publishHelp();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>
			<a href="javascript:void(0);" onclick="checkAllPublishedArticles();">Published</a>, 
			<a href="javascript:void(0);" onclick="checkAllUnpublishedArticles();">Unpublished</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Issue</td>
			<td>Creator</td>
			<td>Created On</td>
			<td>Published</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=help }
				<tr>
					<td align="center"><input type="checkbox" name="help_{ $help.article_id }" id="help_{ $help.article_id }" value="{ $help.article_id }" /></td>
					<td><a href="help.php?id={ $help.article_id }">{ $help.title }</td>
					<td><a href="profile.php?id={ $help.author_id }">{ $help.firstname } { $help.lastname }</a></td>
					<td>{ $help.pubDate }</td>
					<td class="published">{ if $help.published == 1 }Yes{ else }No{ /if }</td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any Help Articles.</td>
			</tr>
		{ /if }
	</table>
</div>
