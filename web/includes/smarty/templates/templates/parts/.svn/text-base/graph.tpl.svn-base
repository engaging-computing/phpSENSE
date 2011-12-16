{ if $is_following == "Yes" or $is_following == "No" }
<div class="module">
    {literal}<script>var follower = {/literal}{$user.user_id}{literal};</script>{/literal}
    {literal}<script>var followee = {/literal}{$userdata.user_id}{literal};</script>{/literal}
	<div id="graph_status_wrapper">
	    { if $is_following == "Yes"}
    	    <button id="graph_status" class="stop_following" onclick="stop_following({$user.user_id}, {$userdata.user_id})">Unfollow</button>
    	{ else }
    	    <button id="graph_status" class="start_following" onclick="start_following({$user.user_id}, {$userdata.user_id})">Follow</button>
        { /if }
    </div>
</div>
{ /if }

<div class="module">
    <h1>Followers</h1>
    <div>
    { if !empty($followers) }
		{ foreach from=$followers item=follower }
		    <a href="profile.php?id={$follower.user_id}">{ $follower.firstname } { $follower.lastname }</a><br/>
		{ /foreach }
	{ else }
	    { $userdata.firstname } doesn't have any followers.
	{ /if }
    </div>
</div>

<div class="module">
    <h1>Following</h1>
    { if !empty($following) }
		{ foreach from=$following item=followee }
		    <a href="profile.php?id={$followee.user_id}">{$followee.firstname} { $followee.lastname}</a><br/>
		{ /foreach }
	{ else }
    	{ $userdata.firstname } isn't following anyone.
	{ /if }
</div>