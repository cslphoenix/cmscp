<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="de" xml:lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	{META}
	<title>{L_HEADER}</title>
	<link rel="stylesheet" href="style/style.css" type="text/css">
	<link rel="stylesheet" href="style/lightbox.css" type="text/css" media="screen" />
	
	<link rel="icon" href="./../favicon.ico" type="image/x-icon">

	<!--	http://www.lokeshdhakar.com/projects/lightbox/	-->
	<script type="text/javascript" src="style/lightbox.js"></script>
	
	<script type="text/javascript">
	// <![CDATA[
				
	/*
	 *	ToggleScript
	 *
	 *	name = name ;)
	 */			
	function toggle(name)
	{
		var el = document.getElementById(name);
		
		if ( el.style.display != 'none' )
		{
			el.style.display = 'none';
		}
		else
		{
			el.style.display = '';
		}
	}
				
	/*
	 *	Mark/unmark checkboxes
	 *	id = ID of parent container, name = name prefix, aktion = aktion [true/false]
	 */
	function marklist(id, name, aktion)
	{
		var parent = document.getElementById(id);
		if (!parent)
		{
			eval('parent = document.' + id);
		}
	
		if (!parent)
		{
			return;
		}
	
		var rb = parent.getElementsByTagName('input');
		
		for (var r = 0; r < rb.length; r++)
		{
			if (rb[r].name.substr(0, name.length) == name)
			{
				rb[r].checked = aktion;
			}
		}
	}
	// ]]>
	</script>

</head>
<body onload="initLightbox()">
<div id="wrap">
	<div class="border-left">
	<div class="border-right">
	<div class="border-top">
	<div class="border-top-left">
	<div class="border-top-right">
	<div class="inside">
		<div id="page-header">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="35%" align="left"><span class="small">{CURRENT_TIME}</span></td>
				<td width="30%" align="center"><a href="{U_INDEX_ADMIN}">{L_INDEX_ADMIN}</a><span class="small">&nbsp;&bull;&nbsp;</span><a href="{U_INDEX_PAGE}">{L_INDEX_PAGE}</a></td>
				<td width="35%" align="right"><span class="small"><b>{L_HEAD_USER}</b> [ <a href="{U_LOGOUT}" target="_parent">{L_LOGOUT}</a> ] [ <a href="{U_ADMIN_LOGOUT}" target="_parent">{L_SESSION}</a> ]</span></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			</table>
		</div>
		<div id="page-body">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="150" valign="top">			