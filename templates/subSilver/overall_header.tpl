<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" lang="{S_USER_LANG}" xml:lang="{S_USER_LANG}">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
    
    {META}
	
	<title>{SITENAME} :: {PAGE_TITLE}</title>

	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php">
	
	<link rel="stylesheet" type="text/css" href="templates/subSilver/theme/stylesheet.css">
	<link rel="stylesheet" type="text/css" href="includes/css/jquery.atooltip.css">
	<link rel="stylesheet" type="text/css" href="includes/css/jquery.lightbox.css" media="screen" />
	
	<link rel="stylesheet" type="text/css" href="includes/css/jRating.jquery.css" />
	
	<script type="text/javascript">
	// <![CDATA[
	
	function checkEntry(element)
	{
		id = element.id;
		
		if ( document.getElementById(id).value.length == 0 )
		{
			document.getElementById(id).style.border='solid #FF0000 2px';
			document.getElementById('msg').innerHTML = '<br>Fehler: wichtige Eingaben fehlen!<br><br>';
			return false;
		}
		else
		{
			document.getElementById(id).style.border='';
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
			
			if (element.type == 'textarea' && element.id != '')
			{
				if (checkEntry(element) == false)
				{
					retValue=false;
				}
			}
			
			if (element.type == 'select' && element.id != '')
			{
				if (checkEntry(element) == false)
				{
					retValue=false;
				}
			}
		}
		return retValue;
	}
	
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
	
	<script type="text/javascript" src="./includes/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="./includes/js/jquery/jquery.lightbox.js"></script>
	<script type="text/javascript" src="./includes/js/jquery/jquery.atooltip.js"></script>
	<script type="text/javascript" src="./includes/js/jquery/jquery.sparkline.js"></script>
	
	<script type="text/javascript" language="javascript">
	
	$(document).ready(function()
	{
		$('span.today').hover(function()
		{
			$(this).find('span.todaytext').show("slow");
		},
		
		function()
		{
			$(this).find('span.todaytext').hide("slow");
		});
	});
	
	$(function()
	{
		$('a').aToolTip();
		$('img').aToolTip();
		$('span').aToolTip();
		
	//	$('a[rel*=lightbox]').lightBox();	/* match, gallery */
		$('a[rel*=lightbox]').lightBox({
			overlayBgColor: '#000',
			overlayOpacity: 0.8,
			imageLoading: 'images/jquery/loader.gif',
			imageBtnClose: 'images/jquery/lightbox-btn-close.gif',
			imageBtnPrev: 'images/jquery/lightbox-btn-prev.gif',
			imageBtnNext: 'images/jquery/lightbox-btn-next.gif',
			imageBlank: 'images/jquery/lightbox-blank.gif',
			containerBorderSize:	10,
			containerResizeSpeed:	400,
			txtImage: 'Bild',
			txtOf: 'von',
			keyToClose:	'c',
			keyToPrev:	'p',
			keyToNext:	'n',
		   });
	});
	
	</script>
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<a name="top"></a>
<div align="center">
	<table width="986" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_03.png); height:33px; width:4px;"></td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_04.png); height:33px; background-repeat:repeat-x;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td align="left">{CURRENT_TIME}</td>
						<td align="right">{SO_HEADER}</td>
					</tr>
					<tr>
						<td class="small" align="left">
							<!-- BEGIN switch_user_logged_in -->
							{LAST_VISIT_DATE}
							<!-- END switch_user_logged_in -->
						</td>
						<td class="small" align="right">{SC_HEADER}</td>
					</tr>
					</table>
				</td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.2_07.png); height:33px; width:4px;"></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.2_08.png); height:54px; width:300px; background-repeat:repeat-x;" align="right">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right">CMS Version: {VERSION}</td>
					</tr>
					</table>
				</td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.2_09.png); height:54px; width:686px; background-repeat:repeat-x;" align="center">
					<!-- BEGIN switch_user_logged_out -->
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<form action="{S_LOGIN_ACTION}" method="post">
					<tr>
						<td width="100%">{SITENAME}</td>
						<td align="left"><input class="post" type="text" name="user_name" size="10"></td>
						<td align="left"><input class="post" type="password" name="password" size="10" maxlength="32"> <!--  autocomplete="off" --></td>
						<td align="left"><input class="button3" type="submit" name="login" value=""></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>{SITE_DESCRIPTION}</td>
						<td align="left">&nbsp;<img src="templates/subSilver/images/design/arrow_right.png">&nbsp;<a href="#">register</a></td>
						<td align="left">&nbsp;<img src="templates/subSilver/images/design/arrow_right.png">&nbsp;<a href="#">password</a></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					</form>
					</table>
					<!-- END switch_user_logged_out -->
					<!-- BEGIN switch_user_logged_in -->
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="100%">{SITENAME}</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>{SITE_DESCRIPTION}</td>
						<td>&nbsp;</td>
						<td nowrap><a href="index.php?mode=cache">Cache Leeren</a></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					</table>
					<!-- END switch_user_logged_in -->
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_15.png); height:15px; background-repeat:repeat-x;"></td>
	</tr>
	
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>{NAVIGATION}</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:244px;" valign="top">{NEWS}</td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"><!-- Leerraum --></td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:243px;" valign="top">{MATCH}</td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"><!-- Leerraum --></td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:243px;" valign="top">{TOPICS}</td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"><!-- Leerraum --></td>
				<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:244px;" valign="top">{DOWNLOADS}</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="background-image:url(templates/subSilver/images/page_/democms1.2_26.png); width:986px;">
			<table width="100%" cellpadding="0" cellspacing="5" border="0">
			<!-- BEGIN page_disable -->
			<tr>
				<td colspan="3" align="center"><div align="center">{page_disable.MSG}</div></td>
			</tr>
			<!-- END page_disable -->
			<tr>
				<td valign="top">
					<table class="type5" cellpadding="0" cellspacing="0" border="0">
					{NEWUSERS}
					{TEAMS}
					{NETWORK}
					{STATSONLINE}
					</table>
				</td>
				<td width="100%" valign="top" align="left">