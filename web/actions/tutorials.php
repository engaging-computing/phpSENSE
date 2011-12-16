<?php

$tutorial = "pinpoint";
if(isset($_GET['tutorial'])) {
	$tutorial = $_GET['tutorial'];
}

if($tutorial == "upload") { ?>

<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" WIDTH="888" HEIGHT="604" CODEBASE="http://active.macromedia.com/flash5/cabs/swflash.cab#version=7,0,0,0">
	<PARAM NAME="movie" VALUE="../html/swf/DataUploadDemo.swf">
	<PARAM NAME="play" VALUE="true">
	<PARAM NAME="loop" VALUE="false">
	<PARAM NAME="wmode" VALUE="transparent">
	<PARAM NAME="quality" VALUE="low">
	<EMBED SRC="../html/swf/DataUploadDemo.swf" WIDTH="888" HEIGHT="604" quality="low" loop="false" wmode="transparent" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></EMBED>
</OBJECT>

<?php } else { ?>

<OBJECT CLASSID="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" WIDTH="515" HEIGHT="558" CODEBASE="http://active.macromedia.com/flash5/cabs/swflash.cab#version=7,0,0,0">
	<PARAM NAME="movie" VALUE="../html/swf/PINCushionDemo.swf">
	<PARAM NAME="play" VALUE="true">
	<PARAM NAME="loop" VALUE="false">
	<PARAM NAME="wmode" VALUE="transparent">
	<PARAM NAME="quality" VALUE="low">
	<EMBED SRC="../html/swf/PINCushionDemo.swf" WIDTH="515" HEIGHT="558" quality="low" loop="false" wmode="transparent" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></EMBED>
</OBJECT>

<?php } ?>