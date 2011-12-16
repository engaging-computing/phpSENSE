<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>{ if $title == "Featured Experiment" }Home{ else }{ $title|capitalize }{ /if } - iSENSE</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		
		<!--<script type="text/javascript" src="loader.php/js"></script>-->
		<script type="text/javascript" src="/html/js/lib/jquery.js"></script>
		<script type="text/javascript" src="/html/js/lib/jquery-ui.js"></script>
        <script type="text/javascript" src="/html/js/lib/thickbox.js"></script>
        <script type="text/javascript" src="/html/js/lib/flydom.js"></script>
        <script type="text/javascript" src="/html/js/lib/autocomplete.js"></script>
        <script type="text/javascript" src="/html/js/lib/rating.js"></script>
        <script type="text/javascript" src="/html/js/isense.js"></script>
		
		<link rel="stylesheet" type="text/css" href="loader.php/css" />
		<link rel="shortcut icon" href="img/favicon.png" />
		
		{ $head }
	</head>
	<body>
		<div id="container">
  			<div id="header">
    			<div id="logo"><a href="." title="iSENSE"><img src="html/img/logo.png" alt="iSENSE"/></a></div>
    			<div id="navigation">
      				<div id="quickbar">
        				<div id="user">{include file="parts/user.tpl"}</div>
      				</div>
      				<div id="links">{include file="parts/links-new.tpl"}</div>
    			</div>
  			</div>
            
			{ if $title != "" }
				<div id="pagetitle">{ if $link != "" }{$link}{else}{$title|capitalize}{/if}</div>
			{ /if }
  			<div id="content">{$content}</div>
  			<div class="button"></div>
		</div>
		<div id="footer">{include file="parts/footer.tpl"}</div>
		{ include file="parts/google.tpl" }
	</body>
</html>
