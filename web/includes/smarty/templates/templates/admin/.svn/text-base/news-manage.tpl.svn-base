<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="newsnew" value="New Article" onclick="window.location.href='admin.php?action=newsadd';" /> 
			<input type="submit" id="newsdelete" value="Delete Article" onclick="deleteNews();" /> 
			<input type="submit" id="newspublish" value="Publish Article" onclick="publishNews();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllPublishedArticles();">Published</a>, 
			<a href="javascript:void(0);" onclick="checkAllUnpublishedArticles();">Unpublished</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Title</td>
			<td>Creator</td>
			<td>Published Date</td>
			<td>Published?</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=news }
				<tr>
					<td align="center"><input type="checkbox" name="news_{ $news.article_id }" id="news_{ $news.article_id }" value="{ $news.article_id }" /></td>
					<td><a href="news.php?id={ $news.article_id }">{ $news.title|capitalize }</a></td>
					<td><a href="profile.php?id={ $news.author_id }">{ $news.firstname|capitalize } { $news.lastname|capitalize }</a></td>
					<td>{ $news.pubDate }</td>
					<td class="published">{ if $news.published == 1 }Yes{ else }No{ /if }</td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any articles.</td>
			</tr>
		{ /if }
	</table>
</div>
