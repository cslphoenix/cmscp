<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="de" xml:lang="de">
<head>
	{META}
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>{L_HEADER}</title>
	<link rel="icon" href="./../favicon.ico" type="image/x-icon">
	
	<link rel="stylesheet" type="text/css" href="style/style.css">
	
<<<<<<< .mine
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="./../includes/js/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery.ui.datepicker.min.js"></script>
	
	<script type="text/javascript" src="style/lightbox.js">
	/*
		http://www.lokeshdhakar.com/projects/lightbox/ <- aktiv
		http://www.lokeshdhakar.com/projects/lightbox2/
	*/	
	</script>
=======
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.custom.css">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.lightbox.css" media="screen" />
>>>>>>> .r85
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.min.js"></script>
	<!--
			Name:		JQuery
			Version:	1.6.1
			require:	acp_user.tpl
	-->
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.datepicker.min.js"></script>
	<!--
			Name:		JQuery.UI
			Version:	1.8.13
	-->
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.lightbox.min.js"></script>
	<!--
			Name:		JQuery Lightbox
			Version:	0.5
	-->
	
	<script type="text/javascript" src="./../includes/js/jscolor.js"></script>
	<!--
			Name:		JSColor
			Version:	1.3.3
			WebSite:	jscolor.com
	-->
	
	<script type="text/javascript">
	// <![CDATA[
	
	/*	
<<<<<<< .mine
		require:	acp_logs.tpl
					acp_match.tpl
	*/
	function checked(id)
	{
		if ( document.getElementById('check_'+id).checked == true )
		{
			document.getElementById('check_'+id).checked = false;
		}
		else
		{
			document.getElementById('check_'+id).checked = true;
		}
	}
	
	</script>
		
	<script type="text/javascript">
	/*	
=======
		require:	acp_logs.tpl
					acp_match.tpl
	*/
	function checked(id)
	{
		if ( document.getElementById('check_'+id).checked == true )
		{
			document.getElementById('check_'+id).checked = false;
		}
		else
		{
			document.getElementById('check_'+id).checked = true;
		}
	}
	
	/*	
>>>>>>> .r85
		require:	acp_training.tpl
					acp_news.tpl
					acp_gallery.tpl
	*/
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
				
				if ( arrInput[i].type=='file' )
				{
					arrInput[i].value='';
				}
			}
			
			target.appendChild(tmpNode);
			objButton.value="{L_REMOVE}";
			objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
		}
	}
	
	/*
		Einfacher Klapptext, wird mit jquery noch erweitert!
		
		require:	acp_database.tpl
					acp_forum.tpl
					acp_forum_auth.tpl
					acp_match.tpl
					acp_maps.tpl
	*/
	function clip(id)
	{
		if ( document.getElementById(id).style.display == 'none' )
		{
			document.getElementById("img_" + id).src = "../images/collapse.gif";
			document.getElementById(id).style.display = "";
		}
		else
		{
			document.getElementById("img_" + id).src = "../images/expand.gif";
			document.getElementById(id).style.display = "none";
		}
	}
	
	/*
		by phpBB3
	*/
	function toggle(name)
	{
		var e = document.getElementById(name);
		
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
	
	<script type="text/javascript">
	
	function UnCryptMailto( s )
	{
		var n = 0;
		var r = "";
	  
		for( var i = 0; i < s.length; i++)
		{
			n = s.charCodeAt( i );
			if( n >= 8364 )
			{
				n = 128;
			}
			r += String.fromCharCode( n - 1 );
		}
		return r;
	}
	
	function linkTo_UnCryptMailto( s )
	{
		location.href=UnCryptMailto( s );
	}
	
	</script>
	
	<script type="text/javascript">
	$(function() {
		$('a.lightbox').lightBox({
			overlayBgColor: '#FFF',
			overlayOpacity: 0.6,
			imageLoading: './../images/jquery/lightbox-ico-loading.gif',
			imageBtnClose: './../images/jquery/lightbox-btn-close.gif',
			imageBtnPrev: './../images/jquery/lightbox-btn-prev.gif',
			imageBtnNext: './../images/jquery/lightbox-btn-next.gif',
			imageBlank: './../images/jquery/lightbox-blank.gif',
			containerResizeSpeed: 350,
			txtImage: 'Imagem',
			txtOf: 'de'
		});
	});
	</script>
	
</head>
<body>
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