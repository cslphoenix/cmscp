<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><head>
	<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
	<meta http-equiv="Content-Style-Type" content="text/css">
	{META}
	<title>{SITENAME} :: {PAGE_TITLE}</title>
	<link rel="stylesheet" href="templates/subSilver/subSilver.css" type="text/css">
	<link rel="stylesheet" href="templates/subSilver/lightbox.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" media="all" type="text/css" href="templates/subSilver/dropdown.css" />
	
	<!--[if lte IE 6]>
	<link rel="stylesheet" media="all" type="text/css" href="templates/subSilver/dropdown_ie.css" />
	<![endif]-->
	
	<!--	
	/***
	*
	*	http://www.lokeshdhakar.com/projects/lightbox/
	*
	***/
	-->
	<script type="text/javascript" src="templates/subSilver/lightbox.js"></script>
</head>
<body bgcolor="#000000" text="#FFFFFF" leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">

<a name="top"></a>
<!--
<table width="100%" cellspacing="0" cellpadding="1" border="0" align="center"> 
<tr> 
	<td><table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr> 
			<td align="center" width="100%" valign="middle">
				{SITENAME}<br />{SITE_DESCRIPTION}<br />
				
			</td>
		</tr>
	</table>

	<br />
-->
<div align="center">
<table width="986" border="0" cellspacing="0" cellpadding="0" align="center" class="table">
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_01.png); height:35px; background-repeat:repeat-x;">
		<span class="small">
		<span style="float:right;" class="small">{TOTAL_USERS_ONLINE_HEAD}</span>
		<!-- BEGIN switch_user_logged_in -->
		{LAST_VISIT_DATE}<br />
		<!-- END switch_user_logged_in -->
		{CURRENT_TIME}
		</span>
	</td>
</tr>
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0" >
		<tr>
			<td style="background-image:url(templates/subSilver/images/page/phx_02.png);" height="54" width="471" align="right">{SITENAME}<br />{SITE_DESCRIPTION}</td>
			<td style="background-image:url(templates/subSilver/images/page/phx_03.png);" height="54" width="515" align="center"><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a><br /><a href="match.php">Begegnungen</a><br /><a href="index.php?mode=cache">Cache Leeren</a></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_03.png); height:11px;"></td>
</tr>

<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<div class="menu">
				<ul>
					<li style="background-image:url(templates/subSilver/images/page/phx_08.png); height:50px; width:5px;"></li>
					<!-- BEGIN navi_main -->
					<li><a class="hide" href="#"><br>Main</a>
						
					<!--[if lte IE 6]>
						<a href="index.html">MENUS
						<table>
						<tr>
							<td>
					<![endif]-->			
						<ul>
							<!-- BEGIN navi_main_row -->
							<li><a href="{navi_main.navi_main_row.NAVI_URL}" target="{navi_main.navi_main_row.NAVI_TARGET}" title="{navi_main.navi_main_row.NAVI_NAME}">{navi_main.navi_main_row.NAVI_NAME}</a></li>
							<!-- END navi_main_row -->
						</ul>	
					<!--[if lte IE 6]>
					</td></tr></table>
					</a>
					<![endif]-->
					</li>
					<li style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;"></li>
					<!-- END navi_main -->
					
					<!-- BEGIN navi_clan -->
					<li><a class="hide" href="#"><br>Clan</a>
					<!--[if lte IE 6]>
						<a href="index.html">MENUS
						<table>
						<tr>
							<td>
					<![endif]-->			
						<ul>
							<!-- BEGIN navi_clan_row -->
							<li><a href="{navi_clan.navi_clan_row.NAVI_URL}" target="{navi_clan.navi_clan_row.NAVI_TARGET}" title="{navi_clan.navi_clan_row.NAVI_NAME}">{navi_clan.navi_clan_row.NAVI_NAME}</a></li>
							<!-- END navi_clan_row -->
						</ul>	
					<!--[if lte IE 6]>
					</td></tr></table>
					</a>
					<![endif]-->
					
					</li>
					<li style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;"></li>
					<!-- END navi_clan -->
					
					<!-- BEGIN navi_community -->
					<li>
						<a class="hide" href="#"><br>Community</a>
					<!--[if lte IE 6]>
						<a href="index.html">MENUS
						<table>
						<tr>
							<td>
					<![endif]-->			
						<ul>
							<!-- BEGIN navi_community_row -->
							<li><a href="{navi_community.navi_community_row.NAVI_URL}" target="{navi_community.navi_community_row.NAVI_TARGET}" title="{navi_community.navi_community_row.NAVI_NAME}">{navi_community.navi_community_row.NAVI_NAME}</a></li>
							<!-- END navi_community_row -->
						</ul>	
					<!--[if lte IE 6]>
					</td></tr></table>
					</a>
					<![endif]-->
					
					</li>
					<li style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;"></li>
					<!-- END navi_community -->
					
					<!-- BEGIN navi_misc -->
					<li>
						<a class="hide" href="#"><br>Misc</a>
					<!--[if lte IE 6]>
						<a href="index.html">MENUS
						<table>
						<tr>
							<td>
					<![endif]-->			
						<ul>
							<!-- BEGIN navi_misc_row -->
							<li><a href="{navi_misc.navi_misc_row.NAVI_URL}" target="{navi_misc.navi_misc_row.NAVI_TARGET}" title="{navi_misc.navi_misc_row.NAVI_NAME}">{navi_misc.navi_misc_row.NAVI_NAME}</a></li>
							<!-- END navi_misc_row -->
						</ul>	
					<!--[if lte IE 6]>
					</td></tr></table>
					</a>
					<![endif]-->
					
					</li>
					
					<li style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;"></li>
					<!-- END navi_misc -->
					<li style="background-image:url(templates/subSilver/images/page/phx_12.png); height:50px; width:{WIDTH}px;"></li>
					<!-- BEGIN navi_user -->
					<li style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;"></li>
					<li>
						<a class="hide" href="#"><br>User</a>
					<!--[if lte IE 6]>
						<a href="index.html">MENUS
						<table>
						<tr>
							<td>
					<![endif]-->			
						<ul>
							<!-- BEGIN navi_user_row -->
							<li><a href="{navi_user.navi_user_row.NAVI_URL}" target="{navi_user.navi_user_row.NAVI_TARGET}" title="{navi_user.navi_user_row.NAVI_NAME}">{navi_user.navi_user_row.NAVI_NAME}</a></li>
							<!-- END navi_user_row -->
							<li><a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a></li>
						</ul>	
					<!--[if lte IE 6]>
					</td></tr></table>
					</a>
					<![endif]-->
					</li>
					<!-- END navi_misc -->
					
					<li style="background-image:url(templates/subSilver/images/page/phx_15.png); height:50px; width:5px;"></li>
				</ul>
			</div>
			<!--
			<td style="background-image:url(templates/subSilver/images/page/phx_08.png); height:50px; width:5px;" height="50" width="5"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_09.png); height:50px; width:120px;" height="50" width="120" align="center">main</td>
			<td style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;" height="50" width="2"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_09.png); height:50px; width:120px;" height="50" width="120" align="center">clan</td>
			<td style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;" height="50" width="2"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_09.png); height:50px; width:120px;" height="50" width="120" align="center">server</td>
			<td style="background-image:url(templates/subSilver/images/page/phx_10.png); height:50px; width:1px;" height="50" width="2"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_12.png); height:50px;" height="50" width="611"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_15.png); height:50px; width:5px;" height="50" width="4"></td>
			-->
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_09-11.png); height:9px;"></td>
</tr>
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page/phx_18.png); height:146px; width:244px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_20.png); height:146px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_18.png); height:146px; width:243px; padding:5px 3px;" valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="info_head" colspan="4">{L_LAST_MATCH}</td>
				</tr>
				<!-- BEGIN match_row -->
				<tr>
					<td class="{match_row.CLASS}" align="center" width="1%">{match_row.MATCH_GAME}</td>
					<td class="{match_row.CLASS}" align="left" width="100%">{match_row.MATCH_NAME}</td>
					<td class="{match_row.CLASS}" align="center" nowrap><span class="{match_row.CLASS_RESULT}">{match_row.MATCH_RESULT}</span></td>
					<td class="{match_row.CLASS}" align="center" width="1%"><a href="{match_row.U_DETAILS}">{L_DETAILS}</a></td>
				</tr>
				<!-- END match_row -->
				<!-- BEGIN no_entry_last -->
				<tr>
					<td class="row1" align="center" colspan="4">{NO_ENTRY}</td>
				</tr>
				<!-- END no_entry_last -->
				</table>
			</td>
			<td style="background-image:url(templates/subSilver/images/page/phx_20.png); height:146px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_18.png); height:146px; width:243px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_20.png); height:146px; width:4px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_18.png); height:146px; width:244px;"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_22.png); height:12px;"></td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_19.png); width:986px;">
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
		<tr>
			<td width="15%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN new_users -->
				<tr>
					<td class="info_head">{NEW_USERS}</td>
				</tr>
				<!-- BEGIN user_row -->
				<tr>
					<td>{new_users.user_row.USERNAME}</td>
				</tr>
				<!-- END user_row -->
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END new_users -->
				<tr>
					<td class="info_head"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="info_head"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="70%" valign="top">