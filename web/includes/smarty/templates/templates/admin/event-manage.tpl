<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="eventnew" value="New Event" onclick="window.location.href='admin.php?action=eventadd';" /> 
			<input type="submit" id="eventdelete" value="Delete Event" onclick="deleteEvent()" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllUpcomingEvents();">Upcoming</a>, 
			<a href="javascript:void(0);" onclick="checkAllPastEvents();">Past</a>
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Title</td>
			<td>Creator</td>
			<td>Start Date</td>
			<td>End Date</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=event }
				<tr>
					<td align="center"><input type="checkbox" name="event_{ $event.event_id }" id="event_{ $event.event_id }" value="{ $event.event_id }" /></td>
					<td><a href="events.php?id={ $event.event_id }">{ $event.title|capitalize }</a></td>
					<td><a href="profile.php?id={ $event.author_id }">{ $event.firstname|capitalize } { $event.lastname|capitalize }</a></td>
					<td class="start">{ $event.start }</td>
					<td class="end">{ $event.end }</td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any events.</td>
			</tr>
		{ /if }
	</table>
</div>
