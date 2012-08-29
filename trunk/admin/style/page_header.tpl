<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="{S_USER_LANG}" xml:lang="{S_USER_LANG}">
<head>
	{META}
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>{L_HEADER}</title>
	<link rel="icon" href="./../favicon.ico" type="image/x-icon">
	
	<link rel="stylesheet" type="text/css" href="style/style.css">
	
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.atooltip.css">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.minicolors.css">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.lightbox.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.maxlength.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.custom.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.datepicker.css" media="screen" />
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.js"></script>

	<script type="text/javascript" src="./../includes/js/jquery/jquery.minicolors.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.sparkline.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.atooltip.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.exptextarea.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.lightbox.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.maxlength.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.litetabs.js"></script>
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.timepicker-addon.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.sliderAccess.js"></script>
	
	<script type="text/javascript" src="style/vars.js"></script>
	<script type="text/javascript" src="style/jquery.vars.js"></script>

</head>
<body>
<a name="#top"></a>
<div id="head">
	<table style="padding:5px;" id="hover">
	<tr>
		<td width="35%" align="left"><span class="small">{L_TIME}</span></td>
		<td width="30%" align="center"><a href="{U_ACP}">{L_ACP}</a><span class="small">&nbsp;&bull;&nbsp;</span><a href="{U_SITE}">{L_SITE}</a></td>
		<td width="35%" align="right"><span class="small"><b>{L_USER}</b> [ <a href="{U_LOGOUT}" target="_parent">{L_LOGOUT}</a> ] [ <a href="{U_SESSION}" target="_parent">{L_SESSION}</a> ]</span></td>
	</tr>
	</table>
</div>
<div id="cont">
<div id="wrap">
	<div class="border-left">
	<div class="border-right">
	<div class="border-top">
	<div class="border-top-left">
	<div class="border-top-right">
	<div class="inside">
		<div id="page-header"></div>
		<div id="page-body">
			<table width="980" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="2">
					<ul id="navlist">
					<!-- BEGIN icat -->
					<li{icat.ACTIVE}><a href="{icat.URL}"{icat.CURRENT}>{icat.NAME}</a></li>
					<!-- END icat -->
					</ul>
				</td>
			</tr>
			<tr>
				<td width="170" valign="top">
					<!-- BEGIN ilab -->
					<br />
					<table class="navi">
					<tr>
						<td>{ilab.NAME}</td>
					</tr>
					<!-- BEGIN isub -->
					<tr>
						<td><a href="{ilab.isub.U_MODULE}"{ilab.isub.CLASS}>{ilab.isub.L_MODULE}</a></td>
					</tr>
					<!-- END isub -->
					</table>
					<!-- END ilab -->
					
					<!-- BEGIN isub -->
					<table class="navi">
					<tr>
						<td><a href="{isub.U_MODULE}"{isub.CLASS}>{isub.L_MODULE}</a></td>
					</tr>
					</table>
					<!-- END isub -->
				</td>
				<td width="810" valign="top">