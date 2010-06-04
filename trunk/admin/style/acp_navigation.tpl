<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_SET}">{L_HEAD_SET}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" width="100%">{L_MAIN}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN main_row -->
		<tr>
			<td class="row_class1" align="left" width="100%">{display.main_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.main_row.LANG} {display.main_row.SHOW} {display.main_row.MOVE_UP} {display.main_row.MOVE_DOWN} <a href="{display.main_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.main_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" width="100%">{L_CLAN}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN clan_row -->
		<tr>
			<td class="row_class1" align="left" width="100%">{display.clan_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.clan_row.LANG} {display.clan_row.SHOW} {display.clan_row.MOVE_UP} {display.clan_row.MOVE_DOWN} <a href="{display.clan_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.clan_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" width="100%">{L_COM}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN com_row -->
		<tr>
			<td class="row_class1" align="left" width="100%">{display.com_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.com_row.LANG} {display.com_row.SHOW} {display.com_row.MOVE_UP} {display.com_row.MOVE_DOWN} <a href="{display.com_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.com_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" width="100%">{L_MISC}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN misc_row -->
		<tr>
			<td class="row_class1" align="left" width="100%">{display.misc_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.misc_row.LANG} {display.misc_row.SHOW} {display.misc_row.MOVE_UP} {display.misc_row.MOVE_DOWN} <a href="{display.misc_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.misc_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" width="100%">{L_USER}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN user_row -->
		<tr>
			<td class="row_class1" align="left" width="100%">{display.user_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.user_row.LANG} {display.user_row.SHOW} {display.user_row.MOVE_UP} {display.user_row.MOVE_DOWN} <a href="{display.user_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.user_row.U_DELETE}">{I_DELETE}</a></td>
		</tr>
		<!-- END user_row -->
		</table>
	</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="navi_name" type="text" value=""></td>
	<td><input class="button" type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN navigation_edit -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
	<li><a href="{S_SET}" id="setting">{L_SET}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="navi_name">{L_NAME}: *</label></td>
	<td class="row3"><input type="text" class="post" name="navi_name" id="navi_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="navi_url">{L_URL}:</label></td>
	<td class="row3">{S_FILENAME_LIST} <input type="text" class="post" name="navi_url" value="{URL}" id="select"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_TYPE}:</label></td>
	<td class="row3">
		<label><input type="radio" name="navi_type" value="1" {S_TYPE_MAIN} />&nbsp;{L_TYPE_MAIN}</label><br />
		<label><input type="radio" name="navi_type" value="2" {S_TYPE_CLAN} />&nbsp;{L_TYPE_CLAN}</label><br />
		<label><input type="radio" name="navi_type" value="3" {S_TYPE_COM} />&nbsp;{L_TYPE_COM}</label><br />
		<label><input type="radio" name="navi_type" value="4" {S_TYPE_MISC} />&nbsp;{L_TYPE_MISC}</label><br />
		<label><input type="radio" name="navi_type" value="5" {S_TYPE_USER} />&nbsp;{L_TYPE_USER}</label>
	</td> 
</tr>
<tr>
	<td class="row1"><label>{L_LANGUAGE}:</label></td>
	<td class="row3"><label><input type="radio" name="navi_lang" value="1" {S_LANG_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="navi_lang" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_SHOW}:</label></td>
	<td class="row3"><label><input type="radio" name="navi_show" value="1" {S_SHOW_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="navi_show" value="0" {S_SHOW_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_INTERN}:</label></td>
	<td class="row3"><label><input type="radio" name="navi_intern" value="1" {S_INTERN_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="navi_intern" value="0" {S_INTERN_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_TARGET}:</label></td>
	<td class="row3"><label><input type="radio" name="navi_target" value="0" {S_TARGET_SELF} />&nbsp;{L_TARGET_SELF}</label>&nbsp;&nbsp;<label><input type="radio" name="navi_target" value="1" {S_TARGET_NEW} />&nbsp;{L_TARGET_NEW}</label></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END navigation_edit -->

<!-- BEGIN navigation_set -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="right">{L_SET}</a></li>
</ul>
</div>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<!-- letzte Nachrichten -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_NEWS}</a></li>
		</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="46%"><label for="subnavi_news_limit" title="{L_NEWS_LIMIT_EXPLAIN}">{L_NEWS_LIMIT}:</label></td>
	<td class="row3"><input class="post" type="text" name="subnavi_news_limit" id="subnavi_news_limit" value="{NEWS_LIMIT}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_news_length" title="{L_NEWS_LENGTH_EXPLAIN}">{L_NEWS_LENGTH}:</label></td>
	<td class="row3"><input class="post" type="text" name="subnavi_news_length" id="subnavi_news_length" value="{NEWS_LENGTH}" size="2"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- letzte Begegnungen -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_MATCH}</a></li>
		</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="subnavi_match_limit" title="{L_MATCH_LIMIT_EXPLAIN}">{L_MATCH_LIMIT}:</label></td>
	<td class="row3"><input class="post" type="text" name="subnavi_match_limit" id="subnavi_match_limit" value="{MATCH_LIMIT}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_match_length" title="{L_MATCH_LENGTH_EXPLAIN}">{L_MATCH_LENGTH}:</label></td>
	<td class="row3"><input class="post" type="text" name="subnavi_match_length" id="subnavi_match_length" value="{MATCH_LENGTH}" size="2"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- letzte Benutzer -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_NEWUSERS}</a></li>
		</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_show" title="{L_NEWUSERS_SHOW_EXPLAIN}">{L_NEWUSERS_SHOW}:</label></td>
	<td class="row3"><input type="radio" name="subnavi_newusers_show" id="subnavi_newusers_show" value="1" {USER_ON} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="subnavi_newusers_show" value="0" {USER_OFF} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_USER_LIMIT}:<br><span class="small">{L_USER_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" size="2" name="subnavi_newusers_limit" value="{USER_LIMIT}"></td>
</tr>
<tr>
	<td class="row1">{L_USER_LENGTH}:<br><span class="small">{L_USER_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" size="2" name="subnavi_newusers_length" value="{USER_LENGTH}"></td>
</tr>
<tr>
	<td class="row1">{L_USER_CACHE}:<br><span class="small">{L_USER_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" size="4" name="subnavi_newusers_cache" value="{USER_CACHE}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- Teams -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_TEAMS}</a></li>
		</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1">{L_TEAMS_SHOW}:<br><span class="gensmall">{L_USER_SHOW_EXPLAIN}</span></td>
	<td class="row3"><input type="radio" name="subnavi_teams_show" value="1" {TEAMS_ON} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="subnavi_teams_show" value="0" {TEAMS_OFF} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_TEAMS_LENGTH}:<br><span class="small">{L_USER_EXPLAIN}</span></td>
	<td class="row3"><input class="post" type="text" size="2" name="subnavi_teams_length" value="{TEAMS_LENGTH}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END navigation_set -->