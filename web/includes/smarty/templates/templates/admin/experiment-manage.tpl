<div id="main-full">
	<div id="management_toolbar">
		<div>
			<input type="submit" id="experimentnew" value="New Experiment" onclick="window.location.href='create.php';" /> 
			<input type="submit" id="experimentdelete" value="Delete Experiment" onclick="deleteExperiment();" /> 
			<input type="submit" id="experimentfeature" value="Feature Experiment" onclick="featureExperiment();" />
		</div>
		<div>
			Select: 
			<a href="javascript:void(0);" onclick="checkAll();">All</a>, 
			<a href="javascript:void(0);" onclick="uncheckAll();">None</a>, 
			<a href="javascript:void(0);" onclick="checkAllFeaturedExperiments();">Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllNonFeaturedExperiments();">Non-Featured</a>, 
			<a href="javascript:void(0);" onclick="checkAllVisibleExperiments();">Visible</a>,
			<a href="javascript:void(0);" onclick="checkAllHiddenExperiments();">Hidden</a> 
		</div>
	</div>
	<table width="100%" id="management_table" class="mangement_table" cellspacing="0" cellpadding="6">
		<tr class="header" style="background:#EAEAEA; font-weight:bold;">
			<td>&nbsp;</td>
			<td>Experiment Title</td>
			<td>Creator</td>
			<td>Created On</td>
			<td>Last Modified</td>
			<td>Hidden?</td>
			<td>Featured?</td>
		</tr>
		{ if $data|@count > 0 }
			{ foreach from=$data item=experiment }
				<tr>
					<td align="center"><input type="checkbox" name="news_{ $experiment.experiment_id }" id="news_{ $experiment.experiment_id }" value="{ $experiment.experiment_id }" /></td>
					<td><a href="experiment.php?id={ $experiment.experiment_id }">{ $experiment.name|capitalize }</a></td>
					<td><a href="profile.php?id={ $experiment.owner_id }">{ $experiment.firstname|capitalize } { $experiment.lastname|capitalize }</a></td>
					<td>{ $experiment.timecreated }</td>
					<td>{ $experiment.timemodified }</td>
					<td class="hidden">{ if $experiment.hidden == 1 }Yes{ else }No{ /if }</td>
					<td class="featured">{ if $experiment.featured == 1 }Yes{ else }No{ /if }</td>
				</tr>
			{ /foreach }
		{ else }
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">Sorry, we could not find any experiments.</td>
			</tr>
		{ /if }
	</table>
</div>
