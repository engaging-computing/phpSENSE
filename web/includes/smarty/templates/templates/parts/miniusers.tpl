<div class="module">
    <h1>Related Users:</h1>
    <ul>
		{ foreach from=$collabs.experiments item=collab }
      		<li><a href="profile.php?id={ $collab.user_id }">{ $collab.owner_firstname } { $collab.owner_lastname }</a></li>
		{ /foreach }
    </ul>
</div>