<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	{META}
<title>{SITENAME} :: {PAGE_TITLE}</title>
	<link rel="stylesheet" href="templates/subSilver/theme/stylesheet.css" type="text/css">
	
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php">


	
	<script type="text/javascript" src="includes/teamspeak/overlib.js"></script>
	<!--<link rel="stylesheet" type="text/css" href="includes/teamspeak/stylesheet.css">-->

	<!--[if lte IE 6]>
	<link rel="stylesheet" media="all" type="text/css" href="templates/subSilver/theme/dropdown_ie.css" />
	<![endif]-->

	
	<script type="text/javascript">
	// <![CDATA[
				
	/***
	*	
	*	....
	*	
	***/
	
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
	
	// ]]>
	</script>

	
	<!--	
	/***
	*
	*	http://www.lokeshdhakar.com/projects/lightbox/
	*
	***/ 
	-->
	<script type="text/javascript" src="templates/subSilver/lightbox.js"></script>

	
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<a name="top"></a>

<div align="center">
<table width="986" border="0" cellspacing="0" cellpadding="0" align="center" class="table">
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0" >
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_03.png); height:33px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_04.png); height:33px; background-repeat:repeat-x;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="small">{CURRENT_TIME}</td>
					<td class="small" align="right">{TOTAL_USERS_ONLINE_HEAD}</td>
				</tr>
				<tr>
					<td class="small">
						<!-- BEGIN switch_user_logged_in -->
						{LAST_VISIT_DATE}
						<!-- END switch_user_logged_in -->
					</td>
					<td class="small" align="right">{TOTAL_COUNTER_HEAD}</td>
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
		<table width="986" border="0" cellspacing="0" cellpadding="0" >
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_08.png); height:54px; width:300px; background-repeat:repeat-x;" align="right">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
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
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
				<form action="{S_LOGIN_ACTION}" method="post">
				<tr>
					<td width="100%">{SITENAME}</td>
					<td align="left"><input type="text" class="post" name="user_name" size="10"></td>
					<td align="left"><input class="post" type="password" name="password" size="10" maxlength="32"> <!--  autocomplete="off" --></td>
					<td align="left"><input type="submit" class="button3" name="login" value=""></td>
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
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
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
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<div class="menu">
				<ul>
					<!-- BEGIN _navi_main -->
					<li><a class="hide" href="#"><br>Main</a>
						<ul>
							<!-- BEGIN _navi_main_row -->
							<li><a href="{_navi_main._navi_main_row.NAVI_URL}" target="{_navi_main._navi_main_row.NAVI_TARGET}" title="{_navi_main._navi_main_row.NAVI_NAME}">{_navi_main._navi_main_row.NAVI_NAME}</a></li>
							<!-- END _navi_main_row -->
						</ul>	
					</li>
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_18.png); height:32px; width:2px;"></li>
					<!-- END _navi_main -->
					
					<!-- BEGIN _navi_clan -->
					<li><a class="hide" href="#"><br>Clan</a>
						<ul>
							<!-- BEGIN _navi_clan_row -->
							<li><a href="{_navi_clan._navi_clan_row.NAVI_URL}" target="{_navi_clan._navi_clan_row.NAVI_TARGET}" title="{_navi_clan._navi_clan_row.NAVI_NAME}">{_navi_clan._navi_clan_row.NAVI_NAME}</a></li>
							<!-- END _navi_clan_row -->
						</ul>	
					</li>
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_18.png); height:32px; width:2px;"></li>
					<!-- END _navi_clan -->
					
					<!-- BEGIN _navi_comm -->
					<li>
						<a class="hide" href="#"><br>Community</a>
						<ul>
							<!-- BEGIN _navi_comm_row -->
							<li><a href="{_navi_comm._navi_comm_row.NAVI_URL}" target="{_navi_comm._navi_comm_row.NAVI_TARGET}" title="{_navi_comm._navi_comm_row.NAVI_NAME}">{_navi_comm._navi_comm_row.NAVI_NAME}</a></li>
							<!-- END _navi_comm_row -->
						</ul>	
					</li>
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_18.png); height:32px; width:2px;"></li>
					<!-- END _navi_comm -->
					
					<!-- BEGIN _navi_misc -->
					<li>
						<a class="hide" href="#"><br>Misc</a>
						<ul>
							<!-- BEGIN _navi_misc_row -->
							<li><a href="{_navi_misc._navi_misc_row.NAVI_URL}" target="{_navi_misc._navi_misc_row.NAVI_TARGET}" title="{_navi_misc._navi_misc_row.NAVI_NAME}">{_navi_misc._navi_misc_row.NAVI_NAME}</a></li>
							<!-- END _navi_misc_row -->
						</ul>	
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_18.png); height:32px; width:2px;"></li>
					<!-- END _navi_misc -->
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_22.png); height:32px; width:476px;"></li>
					<!-- BEGIN _navi_user -->
					<li style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_18.png); height:32px; width:2px;"></li>
					<li>
						<a class="hide" href="#"><br>User</a>
						<ul>
							<!-- BEGIN _navi_user_row -->
							<li><a href="{_navi_user._navi_user_row.NAVI_URL}" target="{_navi_user._navi_user_row.NAVI_TARGET}" title="{_navi_user._navi_user_row.NAVI_NAME}">{_navi_user._navi_user_row.NAVI_NAME}</a></li>
							<!-- END _navi_user_row -->
							<li><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a></li>
							<li>{ADMIN_LINK}</li>
						</ul>	
					</li>
					<!-- END _navi_user -->
				</ul>
			</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:244px;" valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="info_head" colspan="4">{L_SUBNAVI_NEWS}</td>
				</tr>
				<!-- BEGIN _news_subnavi_row -->
				<tr>
					<td class="{_news_subnavi_row.CLASS}" align="left" width="1%">{_news_subnavi_row.NEWS_GAME}</td>
					<td class="{_news_subnavi_row.CLASS}" align="left" width="99%"><a href="{_news_subnavi_row.U_DETAILS}">{_news_subnavi_row.NEWS_TITLE}</a></td>
				</tr>
				<!-- END _news_subnavi_row -->
				<!-- BEGIN _news_subnavi_empty -->
				<tr>
					<td colspan="3" align="center">{L_ENTRY_NO}</td>
				</tr>
				<!-- END _news_subnavi_empty -->
				</table>
			</td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:243px;" valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="info_head" colspan="4" style="text-align:center;">{L_SUBNAVI_MATCH}</td>
				</tr>
				<!-- BEGIN _match_subnavi_row -->
				<tr>
					<td class="{_match_subnavi_row.CLASS}" align="center" width="1%">{_match_subnavi_row.MATCH_GAME}</td>
					<td class="{_match_subnavi_row.CLASS}" align="left" width="100%"><a href="{_match_subnavi_row.U_DETAILS}">{_match_subnavi_row.MATCH_NAME}</a></td>
					<td class="{_match_subnavi_row.CLASS}" align="center" nowrap="nowrap"><span class="{_match_subnavi_row.CLASS_RESULT}">{_match_subnavi_row.MATCH_RESULT}</span></td>
				</tr>
				<!-- END _match_subnavi_row -->
				<!-- BEGIN _match_subnavi_empty -->
				<tr>
					<td colspan="3" align="center">{L_ENTRY_NO}</td>
				</tr>
				<!-- END _match_subnavi_empty -->
				</table>
			</td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:243px;" valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="info_head" colspan="4" style="text-align:center;">Last Topics</td>
				</tr>
				</table>
			</td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); height:189px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.3-schnitt_23.png); padding:15px 3px 3px; width:244px;" valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="info_head" colspan="4" style="text-align:right;">Top Downloads</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page_/democms1.2_26.png); width:986px;">
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
		<!-- BEGIN page_disable -->
		<tr>
			<td colspan="3" align="center"><div align="center">{page_disable.MSG}</div></td>
		</tr>
		<!-- END page_disable -->
		<tr>
			<td width="15%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN _subnavi_newusers -->
				<tr>
					<td class="info_head"><span style="float: right;">{NEW_USERS_CACHE}</span>{L_NEW_USERS}</td>
				</tr>
				<!-- BEGIN _user_row -->
				<tr>
					<td><a class="small" href="{_subnavi_newusers._user_row.U_USERNAME}" {_subnavi_newusers._user_row.C_USERNAME}>{_subnavi_newusers._user_row.L_USERNAME}</a></td>
				</tr>
				<!-- END _user_row -->
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _subnavi_newusers -->
				
				<!-- BEGIN _subnavi_teams -->
				<tr>
					<td class="info_head">{L_SUBNAVI_TEAMS}</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _teams_row -->
						<tr>
							<td width="90%" align="left" style="vertical-align:top;"><a href="{_subnavi_teams._teams_row.U_TEAM}">&raquo;&nbsp;{_subnavi_teams._teams_row.L_TEAM}</a></td>
							<td width="10%">{_subnavi_teams._teams_row.I_TEAM}</td>
						</tr>
						<!-- END _teams_row -->
						<!-- BEGIN _teams_subnavi_empty -->
						<tr>
							<td>{L_ENTRY_NO}</td>
						</tr>
						<!-- END _teams_subnavi_empty -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _subnavi_teams -->
				
				<!-- BEGIN _subnavi_links -->
				<tr>
					<td class="info_head">{L_NETWORK_LINKS}</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _links_row -->
						<tr>
							<td width="100%" align="left"><a href="{_subnavi_links._links_row.U_URL}">&raquo;&nbsp;{_subnavi_links._links_row.L_URL}</a></td>
						</tr>
						<!-- END _links_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _subnavi_links -->
				
				<!-- BEGIN _subnavi_partner -->
				<tr>
					<td class="info_head">{L_NETWORK_PARTNER}</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _partner_row -->
						<tr>
							<td width="100%" align="left"><a href="{_subnavi_partner._partner_row.U_URL}">&raquo;&nbsp;{_subnavi_partner._partner_row.L_URL}</a></td>
						</tr>
						<!-- END _partner_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _subnavi_partner -->
				<!-- BEGIN _subnavi_sponsor -->
				<tr>
					<td class="info_head">{L_NETWORK_SPONSOR}</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _sponsor_row -->
						<tr>
							<td width="100%" align="left"><a href="{_subnavi_sponsor._sponsor_row.U_URL}">&raquo;&nbsp;{_subnavi_sponsor._sponsor_row.L_URL}</a></td>
						</tr>
						<!-- END _sponsor_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _subnavi_sponsor -->
				
				<!-- BEGIN statsonline -->
				<tr>
					<td class="info_head">Stats Online</td>
				</tr>
				<tr>
					<td>{STATS_ONLINE_TOTAL}</td>
				</tr>
				<tr>
					<td>{STATS_ONLINE_VISIBLE}</td>
				</tr>
				<tr>
					<td>{STATS_ONLINE_HIDDEN}</td>
				</tr>
				<tr>
					<td>{STATS_ONLINE_GUESTS}</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END statsonline -->
				
				<!-- BEGIN statscounter -->
				<tr>
					<td class="info_head"><span style="float: right;">{STATS_COUNTER_CACHE}</span>Stats Counter</td>
				</tr>
				<tr>
					<td>{STATS_COUNTER_TODAY}</td>
				</tr>
				<tr>
					<td>{STATS_COUNTER_YESTERDAY}</td>
				</tr>
				<tr>
					<td>{STATS_COUNTER_MONTH}</td>
				</tr>
				<tr>
					<td>{STATS_COUNTER_YEAR}</td>
				</tr>
				<tr>
					<td>{STATS_COUNTER_TOTAL}</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END statscounter -->
				
				</table>
			</td>
			<td width="70%" valign="top" align="center">