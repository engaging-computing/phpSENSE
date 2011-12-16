<!-- Start vis javascript includes -->
<link type="text/css" href="loader.php/viscss" rel="stylesheet" />
{ if not $activity }
<script type="text/javascript" src="ws/json.php?sessions={ $sessions }&amp;state={ $state }"></script>
{ else }
<script type="text/javascript" src="ws/json.php?sessions={ $sessions }&amp;state={ $state }&amp;aid={ $aid }"></script>
{ /if }
{literal}<script type="text/javascript">var IS_ACTIVITY = {/literal}{ if $activity }true{else}false{/if}{literal};</script>{/literal}
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={ $GMAP_KEY }&amp;sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="loader.php/vis"></script>
<!-- End vis javascript includes -->
