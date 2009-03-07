<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="{S_USER_LANG}" xml:lang="{S_USER_LANG}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	{META}
	<title>CMS CP :: {PAGE_TITLE}</title>
	<link rel="stylesheet" href="style/style.css" type="text/css">
	<link rel="stylesheet" href="style/lightbox.css" type="text/css" media="screen" />

	<!--	
	/***
	*
	*	http://www.lokeshdhakar.com/projects/lightbox/
	*
	***/
	-->
	<script type="text/javascript" src="style/lightbox.js"></script>
	
	<script type="text/javascript">
	// <![CDATA[
				
	/***
	*	Check Entry/Forum
	*
	*	name = name ;)
	***/
	
	function checkEntry(element)
	{
		id = element.id;
		
		if ( document.getElementById(id).value.length == 0 )
		{
			document.getElementById(id).style.border="solid #FF0000 2px";
			document.getElementById('msg').innerHTML = 'Fehler: wichtige Eingaben fehlen!';
			return false;
		}
		else
		{
			document.getElementById(id).style.border="solid #000 1px";
			document.getElementById('msg').innerHTML = '';
			return true;
		}
	}
	
	function checkForm()
	{
		retValue = true;
		
		for (var i=0; i < document.form.elements.length; i++)
		{
			element = document.form.elements[i];
			
			if (element.type == 'text' && element.id != '')
			{
				if (checkEntry(element) == false)
				{
					retValue=false;
				}
			}
		}
		return retValue;
	}
	
	/***
	*	ToggleScript
	*
	*	name = name ;)
	***/			
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
				
	/**
	* Mark/unmark checkboxes
	* id = ID of parent container, name = name prefix, aktion = aktion [true/false]
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
				<td width="35%" align="left" style="font-size:medium;">{L_WELCOME}</td>
				<td width="30%" align="center"><a href="{U_ADMIN_INDEX}">&raquo; {L_ADMIN_INDEX}</a><span class="small"> :: </span><a href="{U_PAGE_INDEX}">&raquo; {L_PAGE_INDEX}</a></td>
				<td width="35%" align="right"><a href="{U_ADMIN_LOGOUT}" target="_parent">&raquo; {L_SESSION}</a><span class="small"> :: </span><a href="{U_LOGOUT}" target="_parent">&raquo; {L_LOGOUT}</a>{L_USER}</td>
			</tr>
			</table>

		</div>
		
		<div id="page-body">
		
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="150" valign="top">
				