<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a id="setting" href="{S_SET}">{L_SET}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_MAIN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _main_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._main_row.URL}</span>{_display._main_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._main_row.LANG} {_display._main_row.SHOW} {_display._main_row.INTERN}{_display._main_row.MOVE_UP}{_display._main_row.MOVE_DOWN}{_display._main_row.UPDATE} {_display._main_row.DELETE}</td>
</tr>
<!-- END _main_row -->
<!-- BEGIN _no_entry_main -->
<tr>
	<td class="row_class1" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_main -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="navi_name[1]"></td>
	<td><input type="submit" class="button2" name="navi_type[1]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_CLAN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _clan_row -->
<tr>
	<td><span style="float:right;">{_display._clan_row.URL}</span>{_display._clan_row.NAME}</td>
	<td>{_display._clan_row.LANG} {_display._clan_row.SHOW} {_display._clan_row.INTERN}{_display._clan_row.MOVE_UP}{_display._clan_row.MOVE_DOWN}{_display._clan_row.UPDATE} {_display._clan_row.DELETE}</td>
</tr>
<!-- END _clan_row -->
<!-- BEGIN _no_entry_clan -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_clan -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="navi_name[2]"></td>
	<td><input type="submit" class="button2" name="navi_type[2]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
	
<table class="rows">
<tr>
	<th>{L_COM}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _com_row -->
<tr>
	<td><span style="float:right;">{_display._com_row.URL}</span>{_display._com_row.NAME}</td>
	<td>{_display._com_row.LANG} {_display._com_row.SHOW} {_display._com_row.INTERN}{_display._com_row.MOVE_UP}{_display._com_row.MOVE_DOWN}{_display._com_row.UPDATE} {_display._com_row.DELETE}</td>
</tr>
<!-- END _com_row -->
<!-- BEGIN _no_entry_com -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_com -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="navi_name[3]"></td>
	<td><input type="submit" class="button2" name="navi_type[3]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_MISC}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _misc_row -->
<tr>
	<td><span style="float:right;">{_display._misc_row.URL}</span>{_display._misc_row.NAME}</td>
	<td>{_display._misc_row.LANG} {_display._misc_row.SHOW} {_display._misc_row.INTERN}{_display._misc_row.MOVE_UP}{_display._misc_row.MOVE_DOWN}{_display._misc_row.UPDATE} {_display._misc_row.DELETE}</td>
</tr>
<!-- END _misc_row -->
<!-- BEGIN _no_entry_misc -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_misc -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="navi_name[4]"></td>
	<td><input type="submit" class="button2" name="navi_type[4]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_USER}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _user_row -->
<tr>
	<td><span style="float:right;">{_display._user_row.URL}</span>{_display._user_row.NAME}</td>
	<td>{_display._user_row.LANG} {_display._user_row.SHOW} {_display._user_row.INTERN}{_display._user_row.MOVE_UP}{_display._user_row.MOVE_DOWN}{_display._user_row.UPDATE} {_display._user_row.DELETE}</td>
</tr>
<!-- END _user_row -->
<!-- BEGIN _no_entry_user -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_user -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="navi_name[5]"></td>
	<td><input type="submit" class="button2" name="navi_type[5]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<li><a href="{S_SET}" id="setting">{L_SET}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1" width="155"><label for="navi_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="navi_name" id="navi_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="navi_url">{L_URL}: *</label></td>
	<td class="row2">{S_LIST} <input type="text" class="post" name="navi_url" value="{URL}" id="select"></td>
</tr>
<tr>
	<td class="row1"><label>{L_TYPE}: *</label></td>
	<td class="row2">
		<label><input type="radio" name="navi_type" value="1" onclick="setRequest('navi', 1, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_MAIN} />&nbsp;{L_TYPE_MAIN}</label><br />
		<label><input type="radio" name="navi_type" value="2" onclick="setRequest('navi', 2, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_CLAN} />&nbsp;{L_TYPE_CLAN}</label><br />
		<label><input type="radio" name="navi_type" value="3" onclick="setRequest('navi', 3, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_COM} />&nbsp;{L_TYPE_COM}</label><br />
		<label><input type="radio" name="navi_type" value="4" onclick="setRequest('navi', 4, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_MISC} />&nbsp;{L_TYPE_MISC}</label><br />
		<label><input type="radio" name="navi_type" value="5" onclick="setRequest('navi', 5, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_USER} />&nbsp;{L_TYPE_USER}</label>
	</td> 
</tr>
<tr>
	<td class="row1"><label>{L_TARGET}:</label></td>
	<td class="row2">
		<label><input type="radio" name="navi_target" value="0" {S_TARGET_SELF} />&nbsp;{L_TARGET_SELF}</label><br />
		<label><input type="radio" name="navi_target" value="1" {S_TARGET_NEW} />&nbsp;{L_TARGET_NEW}</label>
	</td>
</tr>
<tr>
	<td class="row1"><label>{L_INTERN}:</label></td>
	<td class="row2"><label><input type="radio" name="navi_intern" value="1" {S_INTERN_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="navi_intern" value="0" {S_INTERN_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="navi_show" value="1" {S_SHOW_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="navi_show" value="0" {S_SHOW_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_LANGUAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="navi_lang" value="1" {S_LANG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="navi_lang" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="navi_order">{L_ORDER}:</label></td>
	<td class="row2"><div id="close">{S_ORDER}</div><div id="content"></div></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _settings -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="right">{L_SET}</a></li>
	</ul>
</div>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- letzte News (Nachrichten) -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_NEWS}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="subnavi_news_limit" title="{L_NEWS_LIMIT_EXPLAIN}">{L_NEWS_LIMIT}:</label></td>
	<td class="row2"><input class="post" type="text" name="subnavi_news_limit" id="subnavi_news_limit" value="{NEWS_LIMIT}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_news_length" title="{L_NEWS_LENGTH_EXPLAIN}">{L_NEWS_LENGTH}:</label></td>
	<td class="row2"><input class="post" type="text" name="subnavi_news_length" id="subnavi_news_length" value="{NEWS_LENGTH}" size="2"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- letzte Wars (Begegnungen) -->
<tr>
	<th colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_MATCH}</a></li>
		</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="subnavi_match_limit" title="{L_MATCH_LIMIT_EXPLAIN}">{L_MATCH_LIMIT}:</label></td>
	<td class="row2"><input class="post" type="text" name="subnavi_match_limit" id="subnavi_match_limit" value="{MATCH_LIMIT}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_match_length" title="{L_MATCH_LENGTH_EXPLAIN}">{L_MATCH_LENGTH}:</label></td>
	<td class="row2"><input class="post" type="text" name="subnavi_match_length" id="subnavi_match_length" value="{MATCH_LENGTH}" size="2"></td>
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
	</td>
</tr>
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="subnavi_newusers_show" title="{L_NEWUSERS_SHOW_EXPLAIN}">{L_NEWUSERS_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="subnavi_newusers_show" id="subnavi_newusers_show" value="1" {NEWUSERS_ON} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="subnavi_newusers_show" value="0" {NEWUSERS_OFF} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_limit" title="{L_NEWUSERS_LIMIT_EXPLAIN}">{L_NEWUSERS_LIMIT}:</label></td>
	<td class="row2"><input class="post" type="text" size="2" name="subnavi_newusers_limit" value="{NEWUSERS_LIMIT}"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_length" title="{L_NEWUSERS_LENGTH_EXPLAIN}">{L_NEWUSERS_LENGTH}:</label></td>
	<td class="row2"><input class="post" type="text" size="2" name="subnavi_newusers_length" value="{NEWUSERS_LENGTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_cache" title="{L_NEWUSERS_CACHE_EXPLAIN}">{L_NEWUSERS_CACHE}:</label></td>
	<td class="row2"><input class="post" type="text" size="4" name="subnavi_newusers_cache" value="{NEWUSERS_CACHE}"></td>
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
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="subnavi_teams_show" title="{L_TEAMS_SHOW_EXPLAIN}">{L_TEAMS_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="subnavi_teams_show" value="1" {TEAMS_ON} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="subnavi_teams_show" value="0" {TEAMS_OFF} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_teams_limit" title="{L_TEAMS_LIMIT_EXPLAIN}">{L_TEAMS_LIMIT}:</label></td>
	<td class="row2"><input class="post" type="text" size="2" name="subnavi_teams_limit" value="{TEAMS_LIMIT}"></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_teams_length" title="{L_TEAMS_LENGTH_EXPLAIN}">{L_TEAMS_LENGTH}:</label></td>
	<td class="row2"><input class="post" type="text" size="2" name="subnavi_teams_length" value="{TEAMS_LENGTH}"></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _settings -->