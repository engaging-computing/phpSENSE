<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>
		    <?php isense_page_title(); ?>
        </title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
		<script type="text/javascript" src="/html/js/lib/jquery.js"></script>
		<script type="text/javascript" src="/html/js/lib/jquery-ui.js"></script>
        <script type="text/javascript" src="/html/js/lib/thickbox.js"></script>
        <script type="text/javascript" src="/html/js/lib/flydom.js"></script>
        <script type="text/javascript" src="/html/js/lib/autocomplete.js"></script>
        <script type="text/javascript" src="/html/js/lib/rating.js"></script>
        <script type="text/javascript" src="/html/js/isense.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<link rel="shortcut icon" href="img/favicon.png" />
	</head>
	<body>
		<div id="container">
  			<div id="header">
    			<div id="logo"><a href="/" title="iSENSE"><img src="/html/img/logo.png" alt="iSENSE"/></a></div>
    			<div id="navigation">
      				<div id="quickbar">
        				<div id="user">Not logged in. Please <a href="/login.php?ref=/">login</a> or <a href="/register.php">register</a>.</div>
      				</div>
      				<div id="links">
      				    <ul>
      				        <li><a href="/browse.php?type=experiments" ><img src="/html/img/drawer.png" alt="Browse experiments and their sessions"/>Experiments</a></li>
                            <li><a href="/browse.php?type=people"><img src="/html/img/group.png" alt="Browse experiments and their sessions"/>People</a></li>
                            <li><a href="/browse.php?type=visualizations"><img src="/html/img/chart_bar.png" alt="Browse experiments and their sessions"/>Visualizations</a></li>
                            <li><a href="/browse.php?type=activities"><img src="/html/img/chart_line.png" alt="Browse activities"/>Activities</a></li>
      				    </ul>
      				</div>
    			</div>
  			</div>