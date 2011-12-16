<div id="main-full">
	{ if $single }
		<div id="details" style="min-height:60px; margin:0px 0px 0px 0px;">
			<div>
				<table width="100%" class="profile">
					<tr>
						<td class="heading" valign="top">Author:</td>
						<td><a href="profile.php?id={ $data.author_id }">{ $data.firstname } { $data.lastname }</a></td>
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
	{ else }
		<p>
			{ foreach from=$data item=headline }
				<a href="#{ $headline.article_id }">{ $headline.title }</a><br/>
			{ /foreach }
		</p>
		{ foreach from=$data item=article }
			<p id="{ $article.article_id }" style="padding:0px 0px 16px 0px;">
				<h3>{ $article.title }</h3>
				<div>
					{ $article.content }
				</div>
			</p>
		{ /foreach }
	{ /if }
</div>
