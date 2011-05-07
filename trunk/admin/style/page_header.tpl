<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="de" xml:lang="de">
<head>
	{META}
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>{L_HEADER}</title>
	<link rel="stylesheet" href="style/style.css" type="text/css">
	<link rel="stylesheet" href="style/lightbox.css" type="text/css" media="screen" />
	
	<link rel="icon" href="./../favicon.ico" type="image/x-icon">
	
	<script type="text/javascript" src="./../includes/js/jquery-1.5.2.min.js">
	/*
		require:	acp_user.tpl
		or:	http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js <- old ... better local
	*/
	</script>
	
	<script type="text/javascript" src="style/lightbox.js">
	/*
		http://www.lokeshdhakar.com/projects/lightbox/ <- aktiv
		http://www.lokeshdhakar.com/projects/lightbox2/
	*/	
	</script>
	
	<script type="text/javascript" src="./../admin/style/jscolor.js">
	/*	http://jscolor.com/
		
		require:	acp_groups.tpl
	*/
	</script>
	
	<script type="text/javascript">
	/*	
		require:	acp_training.tpl
					acp_news.tpl
	*/
	// <![CDATA[
	function clone(objButton)
	{
		if ( objButton.parentNode )
		{
			tmpNode		= objButton.parentNode.cloneNode(true);
			target		= objButton.parentNode.parentNode;
			arrInput	= tmpNode.getElementsByTagName("input");
			
			for ( var i = 0; i < arrInput.length; i++ )
			{
				if ( arrInput[i].type == 'text' )
				{
					arrInput[i].value = '';
				}
			}
			
			target.appendChild(tmpNode);
			objButton.value="{L_REMOVE}";
			objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
		}
	}
	
	// ]]>
	</script>
	
	<script type="text/javascript">  
	/*
		Einfacher Klapptext, wird mit jquery noch erweitert!
		
		require:	acp_database.tpl
					acp_forum.tpl
					acp_forum_auth.tpl
					acp_match.tpl
	*/
	
	function clip(id)
	{
		if ( document.getElementById(id).style.display == 'none' )
		{
			document.getElementById("img_" + id).src = "style/images/collapse.gif";
			document.getElementById(id).style.display = "";
		}
		else
		{
			document.getElementById("img_" + id).src = "style/images/expand.gif";
			document.getElementById(id).style.display = "none";
		}
	}
	
	/*
	function clip(id)
	{
		if ( document.getElementById("tbody_" + id).style.display == 'none' )
		{
			document.getElementById("img_" + id).src = "style/images/collapse.gif";
			document.getElementById("tbody_" + id).style.display = "";
		}
		else
		{
			document.getElementById("img_" + id).src = "style/images/expand.gif";
			document.getElementById("tbody_" + id).style.display = "none";
		}
	}
	*/
	
	</script>
	
	<script type="text/javascript">
	/*
		by phpBB3
	*/
	// <![CDATA[
				
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
	
	function toggleMe(a)
	{
		var e = document.getElementById(a);
		if (!e) return true;
		
		if ( e.style.display == "none" )
		{
			e.style.display = "block"
		}
		else
		{
			e.style.display = "none"
		}
		return true;
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
<div id="acp_head">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:5px;" id="hover">
	<tr>
		<td width="35%" align="left"><span class="small">{L_TIME}</span></td>
		<td width="30%" align="center"><a href="{U_ACP}">{L_ACP}</a><span class="small">&nbsp;&bull;&nbsp;</span><a href="{U_SITE}">{L_SITE}</a></td>
		<td width="35%" align="right"><span class="small"><b>{L_USER}</b> [ <a href="{U_LOGOUT}" target="_parent">{L_LOGOUT}</a> ] [ <a href="{U_SESSION}" target="_parent">{L_SESSION}</a> ]</span></td>
	</tr>
	</table>
</div>
<div id="acp_cont">
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
				<td><img src="style/images/logo.png" width="189" height="32" alt="" /></td>
			</tr>
			</table>
		</div>
		<div id="page-body">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="150" valign="top">			