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
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.lightbox.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.maxlength.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.custom.css" media="screen" />
	
	<!--	
		Name:		JSColor
		Version:	1.3.3
		WebSite:	http://jscolor.com/
	-->
	<script type="text/javascript" src="./../includes/js/jscolor.js"></script>
	
	<!--	
		JQuery // 1.7.1
		JQuery Lightbox // 0.5
		JQuery UI // 1.8.18 (datepicker acp_user.tpl)
	-->
	<script type="text/javascript" src="./../includes/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.atooltip.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.exptextarea.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.lightbox.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.maxlength.js"></script>
	
	<script language="javascript" type="text/javascript">
	// <![CDATA[
	
	/*	
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
		require:	acp_training.tpl
					acp_news.tpl
					acp_gallery.tpl
					acp_match.tpl
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
	
	function checkbox(checkboxname, checkboxvalue)
	{
		var count = document.getElementsByName(checkboxname).length;
		
		for (var i = 0; i < count; i++)
		{
			document.getElementsByName(checkboxname)[i].checked = checkboxvalue;
		}
	}
	
	/* acp_database, acp_match, acp_settings */
	function selector(bool)
	{
		var table = document.getElementById('table');
	
		for (var i = 0; i < table.options.length; i++)
		{
			table.options[i].selected = bool;
		}
	}

	$(function()
	{
		$('a').aToolTip();
		$('img').aToolTip();
		$('span').aToolTip();
		$('label').aToolTip();
		$('textarea').aToolTip();
		
		$("#datepicker").datepicker(
		{
			changeMonth: true,
			changeYear: true,
		//	minDate: 0,
		//	numberOfMonths: 2,
			dateFormat: 'dd.mm.yy'
		});
		
		
		$("#date_event").datepicker(
		{
			minDate: 0,
			numberOfMonths: 3,
			dateFormat: 'dd.mm.yy'
		});
		
		$('a[rel*=lightbox]').lightBox();	/* match, gallery */
		
		$('textarea').expandingTextArea();
		
		$('#config_page_desc').maxlength({max: 255});
		$('#config_page_disable_msg').maxlength({max: 255});
		
	});
	
	// ]]>
	</script>
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
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" valign="top">