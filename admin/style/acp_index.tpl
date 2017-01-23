<li class="header">{L_WELCOME}</li>
<p>{L_EXPLAIN}</p>
{SESSION}
<table>
<tr>
	<td width="50%" class="top">
		<table class="info">
		<tr>
			<th>{L_INFO}</th>
			<th>{L_VALUE}</th>
		</tr>
		<tr>
			<td>{L_PAGE_STARTED}:</td>
			<td>{PAGE_STARTED}</td>
		</tr>
		<tr>
			<td>{L_PAGE_VERSION}:</td>
			<td>{PAGE_VERSION}</td>
		</tr>
		<tr>
			<td>{L_PAGE_BACKUP}:</td>
			<td><span title="{PAGE_BACKUP_INFO}">{PAGE_BACKUP}</span></td>
		</tr>
		<tr>
			<td>{L_PAGE_GZIP}:</td>
			<td>{PAGE_GZIP}</td>
		</tr>
		<tr>
			<td>{L_SERVER}:</td>
			<td>{SERVER}</td>
		</tr>
		<tr>
			<td>{L_DB_INFO}:</td>
			<td><span title="{DB_INFO_INFO}">{DB_INFO}</span></td>
		</tr>
		</table>
	</td>
	<td width="50%" class="top">
		<table class="info">
		<tr>
			<th>{L_DIR}</th>
			<th>{L_SIZE}</th>
		</tr>
		<!--
		<th><ul id="navlist"><li><a href="#" id="right" onclick="return false;">{L_DIR}</a></li></ul></th>
		<th><ul id="navlist"><li><a href="#" id="current" onclick="return false;">{L_SIZE}</a></li></ul></th>
		-->
		<tr>
			<td>{L_SIZE_CACHE}:</td>
			<td>{SIZE_CACHE}</td>
		</tr>
		<tr>
			<td>{L_SIZE_FILES}:</td>
			<td>{SIZE_FILES}</td>
		</tr>
		<tr>
			<td>{L_SIZE_DOWNLOADS}:</td>
			<td>{SIZE_DOWNLOADS}</td>
		</tr>
		<tr>
			<td>{L_SIZE_GALLERY}:</td>
			<td>{SIZE_GALLERY}</td>
		</tr>
		<tr>
			<td>{L_SIZE_MATCHS}:</td>
			<td>{SIZE_MATCHS}</td>
		</tr>
		<tr>
			<td>{L_SIZE_USERS}:</td>
			<td>{SIZE_USERS}</td>
		</tr>
		<tr>
			<td>{L_SIZE_GROUPS}:</td>
			<td>{SIZE_GROUPS}</td>
		</tr>
        <tr>
			<td>{L_SIZE_TEAMS}:</td>
			<td>{SIZE_TEAMS}</td>
		</tr>
		<tr>
			<td>{L_SIZE_NETWORK}:</td>
			<td>{SIZE_NETWORK}</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<br />
{ERROR_BOX}

<div class="index">
	<fieldset class="info">
		<legend><img src="{I_NEWS}" width="12" height="12" alt="" /> <a href="{U_NEWS}">{L_NEWS}</a></legend>
		<!-- BEGIN news_row -->
		<dl>
			<dt><span class="right">{news_row.DATE}</span>{news_row.GAME} {news_row.TITLE}</dt>
			<dd>{news_row.PUBLIC}{news_row.UPDATE}{news_row.DELETE}</dd>
		</dl>
		<!-- END news_row -->
		<!-- BEGIN news_empty -->
		<ul align="center">{L_EMPTY}</ul>
		<!-- END news_empty -->
	</fieldset>
</div>

<div class="index right">
	<fieldset class="info">
		<legend><img src="{I_EVENT}" width="12" height="12" alt="" /> <a href="{U_EVENT}">{L_EVENT}</a></legend>
		<!-- BEGIN event_row -->
		<dl>
			<dt><span class="right">{event_row.DATE}</span>{event_row.LEVEL} {event_row.TITLE}</dt>
			<dd>{event_row.UPDATE}{event_row.DELETE}</dd>
		</dl>
		<!-- END event_row -->
		<!-- BEGIN event_empty -->
		<ul align="center">{L_EMPTY}</ul>
		<!-- END event_empty -->
	</fieldset>
</div>

<div class="index">
	<fieldset class="info">
		<legend><img src="{I_MATCH}" width="12" height="12" alt="" /> <a href="{U_MATCH}">{L_MATCH}</a></legend>
		<!-- BEGIN match_row -->
		<dl>
			<dt><span class="right">{match_row.DATE}</span>{match_row.GAME} {match_row.RIVAL}</dt>
			<dd>{match_row.DETAIL}{match_row.UPDATE}{match_row.DELETE}</dd>
		</dl>
		<!-- END match_row -->
		<!-- BEGIN match_empty -->
		<ul align="center">{L_EMPTY}</ul>
		<!-- END match_empty -->
	</fieldset>
</div>

<div class="index right">
	<fieldset class="info">
		<legend><img src="{I_TRAIN}" width="12" height="12" alt="" /> <a href="{U_TRAIN}">{L_TRAIN}</a></legend>
		<!-- BEGIN training_row -->
		<dl>
			<dt><span class="right">{training_row.DATE}</span>{training_row.GAME} {training_row.VS}</dt>
			<dd>{training_row.UPDATE}{training_row.DELETE}</dd>
		</dl>
		<!-- END training_row -->
		<!-- BEGIN training_empty -->
		<ul align="center">{L_EMPTY}</ul>
		<!-- END training_empty -->
	</fieldset>
</div>

<div class="index large">
	<fieldset class="full">
		<legend><img src="{I_USERS}" width="12" height="12" alt="" /> <a href="{U_USERS}">{L_USERS}</a></legend>
		<!-- BEGIN user_row -->
		<dl>
			<dt><span class="right">{user_row.REGDATE}</span>{user_row.LEVEL} {user_row.NAME}</dt>
			<dd>{user_row.AUTH}{user_row.UPDATE}{user_row.DELETE}</dd>
		</dl>
		<!-- END user_row -->
	</fieldset>
</div>

<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
<tr>
	<th width="20%" class="thCornerL" height="25">&nbsp;{L_USERNAME}&nbsp;</th>
	<th width="20%" height="25" class="thTop">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="20%" class="thCornerR">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
	<th width="20%" height="25" class="thCornerR">&nbsp;{L_IP_ADDRESS}&nbsp;</th>
</tr>
<!-- BEGIN online_users -->
<tr> 
	<td width="20%" class="{online_users.ROW_CLASS}">&nbsp;<span class="gen"><a href="{online_users.U_USER_PROFILE}" class="gen">{online_users.USERNAME}</a></span>&nbsp;</td>
	<td width="20%" align="center" class="{online_users.ROW_CLASS}">&nbsp;<span class="gen">{online_users.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{online_users.ROW_CLASS}">&nbsp;<span class="gen">{online_users.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{online_users.ROW_CLASS}">&nbsp;<span class="gen"><a href="{online_users.U_FORUM_LOCATION}" class="gen">{online_users.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{online_users.ROW_CLASS}">&nbsp;<span class="gen"><a href="{online_users.U_WHOIS_IP}" class="gen" target="_phpbbwhois">{online_users.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END online_users -->
<tr> 
	<td colspan="5" height="1" class="row3"><img src="../templates/subSilver/images/spacer.gif" width="1" height="1" alt="."></td>
</tr>
<!-- BEGIN online_guests -->
<tr> 
	<td width="20%" class="{online_guests.ROW_CLASS}">&nbsp;<span class="gen">{online_guests.USERNAME}</span>&nbsp;</td>
	<td width="20%" align="center" class="{online_guests.ROW_CLASS}">&nbsp;<span class="gen">{online_guests.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{online_guests.ROW_CLASS}">&nbsp;<span class="gen">{online_guests.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{online_guests.ROW_CLASS}">&nbsp;<span class="gen"><a href="{online_guests.U_FORUM_LOCATION}" class="gen">{online_guests.FORUM_LOCATION}</a></span>&nbsp;</td>
	<td width="20%" class="{online_guests.ROW_CLASS}">&nbsp;<span class="gen"><a href="{online_guests.U_WHOIS_IP}" target="_phpbbwhois">{online_guests.IP_ADDRESS}</a></span>&nbsp;</td>
</tr>
<!-- END online_guests -->
</table>