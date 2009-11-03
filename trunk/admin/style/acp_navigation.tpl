<!-- BEGIN display -->
<form method="post" action="{S_NAVIGATION_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_NAVI_TITLE}</a></li>
			<li><a href="{S_NAVI_SET_ACTION}">{L_NAVI_SET}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_NAVI_EXPLAIN}</td>
</tr>
</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_NAVI_MAIN}</td>
			<td class="rowHead">{L_LANGUAGE}</td>
			<td class="rowHead">{L_SHOW}</td>
			<td class="rowHead" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN main_row -->
		<tr>
			<td class="{display.main_row.CLASS}" align="left" width="100%">{display.main_row.NAVI_TITLE}</td>
			<td class="{display.main_row.CLASS}" align="center">{display.main_row.NAVI_LANG}</td>
			<td class="{display.main_row.CLASS}" align="center">{display.main_row.NAVI_SHOW}</td>
			<td class="{display.main_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.main_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.main_row.CLASS}" align="center" nowrap="nowrap">{display.main_row.MOVE_UP} {display.main_row.MOVE_DOWN}</td>
			<td class="{display.main_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.main_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END main_row -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_NAVI_CLAN}</td>
			<td class="rowHead">{L_LANGUAGE}</td>
			<td class="rowHead">{L_SHOW}</td>
			<td class="rowHead" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN clan_row -->
		<tr>
			<td class="{display.clan_row.CLASS}" align="left" width="100%">{display.clan_row.NAVI_TITLE}</td>
			<td class="{display.clan_row.CLASS}" align="center">{display.clan_row.NAVI_LANG}</td>
			<td class="{display.clan_row.CLASS}" align="center">{display.clan_row.NAVI_SHOW}</td>
			<td class="{display.clan_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.clan_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.clan_row.CLASS}" align="center" nowrap="nowrap">{display.clan_row.MOVE_UP} {display.clan_row.MOVE_DOWN}</td>
			<td class="{display.clan_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.clan_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END clan_row -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_NAVI_COM}</td>
			<td class="rowHead">{L_LANGUAGE}</td>
			<td class="rowHead">{L_SHOW}</td>
			<td class="rowHead" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN com_row -->
		<tr>
			<td class="{display.com_row.CLASS}" align="left" width="100%">{display.com_row.NAVI_TITLE}</td>
			<td class="{display.com_row.CLASS}" align="center">{display.com_row.NAVI_LANG}</td>
			<td class="{display.com_row.CLASS}" align="center">{display.com_row.NAVI_SHOW}</td>
			<td class="{display.com_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.com_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.com_row.CLASS}" align="center" nowrap="nowrap">{display.com_row.MOVE_UP} {display.com_row.MOVE_DOWN}</td>
			<td class="{display.com_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.com_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END com_row -->
		</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_NAVI_MISC}</td>
			<td class="rowHead">{L_LANGUAGE}</td>
			<td class="rowHead">{L_SHOW}</td>
			<td class="rowHead" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN misc_row -->
		<tr>
			<td class="{display.misc_row.CLASS}" align="left" width="100%">{display.misc_row.NAVI_TITLE}</td>
			<td class="{display.misc_row.CLASS}" align="center">{display.misc_row.NAVI_LANG}</td>
			<td class="{display.misc_row.CLASS}" align="center">{display.misc_row.NAVI_SHOW}</td>
			<td class="{display.misc_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.misc_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.misc_row.CLASS}" align="center" nowrap="nowrap">{display.misc_row.MOVE_UP} {display.misc_row.MOVE_DOWN}</td>
			<td class="{display.misc_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.misc_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END misc_row -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_NAVI_USER}</td>
			<td class="rowHead">{L_LANGUAGE}</td>
			<td class="rowHead">{L_SHOW}</td>
			<td class="rowHead" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN user_row -->
		<tr>
			<td class="{display.user_row.CLASS}" align="left" width="100%">{display.user_row.NAVI_TITLE}</td>
			<td class="{display.user_row.CLASS}" align="center">{display.user_row.NAVI_LANG}</td>
			<td class="{display.user_row.CLASS}" align="center">{display.user_row.NAVI_SHOW}</td>
			<td class="{display.user_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.user_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.user_row.CLASS}" align="center" nowrap="nowrap">{display.user_row.MOVE_UP} {display.user_row.MOVE_DOWN}</td>
			<td class="{display.user_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.user_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END user_row -->
		</table>
	</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="navi_name" type="text" value=""></td>
	<td><input class="button" type="submit" name="navigation_add" value="{L_NAVI_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN navigation_set -->
<form action="{S_NAVI_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NAVI_ACTION}">{L_NAVI_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NAVI_SET}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<table class="edit" cellspacing="1">
<!-- letzte Nachrichten -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onClick="toggle('last_news'); return false;">{L_LAST_NEWS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody id="last_news" style="display:;">
<tr>
	<td class="row1" width="50%">{L_LAST_NEWS_LIMIT}:<br><span class="small">{L_LAST_NEWS_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_news_limit" value="{LAST_NEWS_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1" width="50%">{L_LAST_NEWS_LENGTH}:<br><span class="small">{L_LAST_NEWS_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_news_length" value="{LAST_NEWS_LENGTH}" /></td>
</tr>
</tbody>

<!-- letzte Begegnungen -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onClick="toggle('last_match'); return false;">{L_LAST_MATCH}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody id="last_match" style="display:;">
<tr>
	<td class="row1" width="50%">{L_LAST_MATCH_LIMIT}:<br><span class="small">{L_LAST_MATCH_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_match_limit" value="{LAST_MATCH_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1" width="50%">{L_LAST_MATCH_LENGTH}:<br><span class="small">{L_LAST_MATCH_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_match_length" value="{LAST_MATCH_LENGTH}" /></td>
</tr>
</tbody>

<!-- letzte Benutzer -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onClick="toggle('last_newusers'); return false;">{L_LAST_USER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody id="last_newusers" style="display:;">
<tr>
	<td class="row1">{L_LAST_USER_SHOW}:<br><span class="gensmall">{L_LAST_USER_SHOW_EXPLAIN}</span></td>
	<td class="row3"><input type="radio" name="subnavi_newusers_show" value="1" {LAST_USER_ON} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="subnavi_newusers_show" value="0" {LAST_USER_OFF} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_LAST_USER_LIMIT}:<br><span class="small">{L_LAST_USER_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_newusers_limit" value="{LAST_USER_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1" width="50%">{L_LAST_USER_LENGTH}:<br><span class="small">{L_LAST_USER_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_newusers_length" value="{LAST_USER_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1" width="50%">{L_LAST_USER_CACHE}:<br><span class="small">{L_LAST_USER_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="4" name="subnavi_newusers_cache" value="{LAST_USER_CACHE}" /></td>
</tr>
</tbody>

<!-- Teams -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onClick="toggle('teams'); return false;">{L_TEAMS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody id="teams" style="display:;">
<tr>
	<td class="row1">{L_TEAMS_SHOW}:<br><span class="gensmall">{L_LAST_USER_SHOW_EXPLAIN}</span></td>
	<td class="row3"><input type="radio" name="subnavi_teams_show" value="1" {TEAMS_ON} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="subnavi_teams_show" value="0" {TEAMS_OFF} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%">{L_TEAMS_LENGTH}:<br><span class="small">{L_LAST_USER_EXPLAIN}</span></td>
	<td class="row3" width="50%"><input class="post" type="text" size="2" name="subnavi_teams_length" value="{TEAMS_LENGTH}" /></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END navigation_set -->

<!-- BEGIN navigation_edit -->
<form action="{S_NAVI_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NAVI_ACTION}">{L_NAVI_HEAD}</a></li>
				<li><a href="{S_NAVI_SET_ACTION}">{L_NAVI_SET}</a></li>
				<li id="active"><a href="#" id="current">{L_NAVI_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NAVI_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="navi_name" value="{NAVI_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_NAVI_URL}:</td>
	<td class="row2">{S_FILENAME_LIST} <input class="post" type="text" name="navi_url" value="{NAVI_URL}" id="select" ></td>
</tr>
<tr>
	<td class="row1">{L_NAVI_TYPE}:</td>
	<td class="row2">
		<input type="radio" name="navi_type" value="1" {CHECKED_TYPE_MAIN} /> {L_TYPE_MAIN}<br>
		<input type="radio" name="navi_type" value="2" {CHECKED_TYPE_CLAN} /> {L_TYPE_CLAN}<br>
		<input type="radio" name="navi_type" value="3" {CHECKED_TYPE_COM} /> {L_TYPE_COM}<br>
		<input type="radio" name="navi_type" value="4" {CHECKED_TYPE_MISC} /> {L_TYPE_MISC}<br>
		<input type="radio" name="navi_type" value="5" {CHECKED_TYPE_USER} /> {L_TYPE_USER}<br>
	</td> 
</tr>
<tr>
	<td class="row1">{L_NAVI_LANGUAGE}:</td>
	<td class="row2">
		<input type="radio" name="navi_lang" value="1" {CHECKED_LANG_YES} /> {L_YES}
		<input type="radio" name="navi_lang" value="0" {CHECKED_LANG_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_SHOW}:</td>
	<td class="row2">
		<input type="radio" name="navi_show" value="1" {CHECKED_SHOW_YES} /> {L_YES}
		<input type="radio" name="navi_show" value="0" {CHECKED_SHOW_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_INTERN}:</td>
	<td class="row2">
		<input type="radio" name="navi_intern" value="1" {CHECKED_INTERN_YES} /> {L_YES}
		<input type="radio" name="navi_intern" value="0" {CHECKED_INTERN_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_TARGET}:</td>
	<td class="row2">
		<input type="radio" name="navi_target" value="0" {CHECKED_TARGET_SELF} /> {L_NAVI_SELF}
		<input type="radio" name="navi_target" value="1" {CHECKED_TARGET_NEW} /> {L_NAVI_NEW}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END navigation_edit -->

<!-- BEGIN navigation_list -->

<!-- END navigation_list -->