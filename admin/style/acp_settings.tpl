<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>
<form action="{S_ACTION}" method="post" name="sort" id="sort"><div align="right">{S_SORT}</div></form>
<form action="{S_ACTION}" method="post">

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEADING}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_HEADING_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update">
<tbody class="trhover">
<!-- BEGIN _default -->
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="server_name" title="{L_SERVER_NAME_EXPLAIN}">{L_SERVER_NAME}</label><span style="padding:4px;">:</span><label for="server_port" title="{L_SERVER_PORT_EXPLAIN}">{L_SERVER_PORT}</label></td>
	<td class="row2"><input type="text" class="post" size="25" name="server_name" id="server_name" value="{SERVER_NAME}" /><span style="padding:4px;">:</span><input type="text" class="post" size="5" name="server_port" id="server_port" value="{SERVER_PORT}"></td>
</tr>

<tr>
	<td class="row1"><label for="page_path" title="{L_PAGE_PATH_EXPLAIN}">{L_PAGE_PATH}:</label></td>
	<td class="row2"><input type="text" class="post" size="25" name="page_path" id="page_path" value="{PAGE_PATH}"></td>
</tr>
<tr>
	<td class="row1"><label for="page_name" title="{L_PAGE_NAME_EXPLAIN}">{L_PAGE_NAME}:</label></td>
	<td class="row2"><input type="text" class="post" size="25" name="page_name" id="page_name" value="{PAGE_NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="page_desc">{L_PAGE_DESC}:</label></td>
	<td class="row2"><textarea class="post" cols="35" rows="4" maxlength="255" name="page_desc" id="page_desc">{PAGE_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="page_disable" title="{L_DISABLE_PAGE_EXPLAIN}">{L_DISABLE_PAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="page_disable" id="page_disable" value="1" {S_DISABLE_PAGE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="page_disable" value="0" {S_DISABLE_PAGE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="page_disable_msg">{L_DISABLE_PAGE_REASON}:</label></td>
	<td class="row2"><textarea class="post" cols="35" rows="4" maxlength="255" name="page_disable_msg" id="page_disable_msg">{DISABLE_REASON}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="page_disable_mode">{L_DISABLE_PAGE_MODE}:</label></td>
	<td class="row2">{PAGE_DISABLE_MODE}</td>
</tr>
<!-- END _default -->
<!-- BEGIN _upload -->
<tr>
	<td class="row1_1"><label for="path_games" title="{L_PATH_GAMES_EXPLAIN}">{L_PATH_GAMES}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_games" id="path_games" value="{PATH_GAMES}" />&nbsp;{PATH_GAMES_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="path_ranks" title="{L_PATH_RANKS_EXPLAIN}">{L_PATH_RANKS}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_ranks" id="path_ranks" value="{PATH_RANKS}" />&nbsp;{PATH_RANKS_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="path_newscat" title="{L_PATH_NEWSCAT_EXPLAIN}">{L_PATH_NEWSCAT}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_newscat" id="path_newscat" value="{PATH_NEWSCAT}" />&nbsp;{PATH_NEWSCAT_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="path_gallery" title="{L_PATH_GALLERY_EXPLAIN}">{L_PATH_GALLERY}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_gallery" id="path_gallery" value="{PATH_GALLERY}" />&nbsp;{PATH_GALLERY_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="path_groups" title="{L_PATH_GROUPS_EXPLAIN}">{L_PATH_GROUPS}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_groups" id="path_groups" value="{PATH_GROUPS}" />&nbsp;{PATH_GROUPS_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="groups_filesize" title="{L_GROUPS_FILESIZE_EXPLAIN}">{L_GROUPS_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="groups_filesize" id="groups_filesize" value="{GROUPS_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><label title="{L_GROUPS_SIZE_EXPLAIN}">{L_GROUPS_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="groups_max_heigt" value="{GROUPS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="groups_max_width" value="{GROUPS_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="path_machs" title="{L_PATH_MATCHS_EXPLAIN}">{L_PATH_MATCHS}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_machs" id="path_machs" value="{PATH_MATCHS}" />&nbsp;{PATH_MATCHS_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="groups_filesize" title="{L_GROUPS_FILESIZE_EXPLAIN}">{L_GROUPS_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="groups_filesize" id="groups_filesize" value="{GROUPS_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><label title="{L_GROUPS_SIZE_EXPLAIN}">{L_GROUPS_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="groups_max_heigt" value="{GROUPS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="groups_max_width" value="{GROUPS_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label title="{L_GROUPS_PREV_EXPLAIN}">{L_GROUPS_PREV}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="groups_max_heigt" value="{GROUPS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="groups_max_width" value="{GROUPS_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="path_teams" title="{L_PATH_TEAMS_EXPLAIN}">{L_PATH_TEAMS}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_teams" id="path_teams" value="{PATH_TEAMS}" />&nbsp;{PATH_TEAMS_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="teams_filesize" title="{L_TEAMS_FILESIZE_EXPLAIN}">{L_TEAMS_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="teams_filesize" id="teams_filesize" value="{TEAMS_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><label title="{L_TEAMS_SIZE_EXPLAIN}">{L_TEAMS_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="teams_max_heigt" value="{TEAMS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="team_max_width" value="{TEAMS_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="path_users" title="{L_PATH_USERS_EXPLAIN}">{L_PATH_USERS}:</label></td>
	<td class="row2"><input type="text" class="post" size="20" maxlength="50" name="path_teams" id="path_users" value="{PATH_USERS}" />&nbsp;{PATH_USERS_CHECKED}</td>
</tr>
<tr>
	<td class="row1"><label for="teams_filesize" title="{L_TEAMS_FILESIZE_EXPLAIN}">{L_TEAMS_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="teams_filesize" id="teams_filesize" value="{TEAMS_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><label title="{L_TEAMS_SIZE_EXPLAIN}">{L_TEAMS_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="teams_max_heigt" value="{TEAMS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="team_max_width" value="{TEAMS_MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="teams_filesize" title="{L_TEAMS_FILESIZE_EXPLAIN}">{L_TEAMS_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="teams_filesize" id="teams_filesize" value="{TEAMS_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><label title="{L_TEAMS_SIZE_EXPLAIN}">{L_TEAMS_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" size="3" name="teams_max_heigt" value="{TEAMS_MAX_HEIGHT}" /> x <input type="text" class="post" size="3" name="team_max_width" value="{TEAMS_MAX_WIDTH}"></td>
</tr>
<!-- END _upload -->
<!-- BEGIN _session -->
<tr>
	<td class="row1_1"><label for="email_enabled" title="{L_EMAIL_ON-OFF_EXPLAIN}">{L_EMAIL_ON-OFF}:</label></td>
	<td class="row2"><label><input type="radio" name="email_enabled" id="email_enabled" value="1" {EMAIL_ON} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="email_enabled" value="0" {EMAIL_OFF} />&nbsp;{L_NO}</label></td>
</tr>
<!-- END _session -->
<!-- BEGIN _display -->
<tr>
	<td class="row1_1"><label for="subnavi_news_limit">{L_NEWS_LIMIT}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_news_limit" id="subnavi_news_limit" value="{NEWS_LIMIT}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_news_length">{L_NEWS_LENGTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_news_length" id="subnavi_news_length" value="{NEWS_LENGTH}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_match_limit">{L_MATCH_LIMIT}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_match_limit" id="subnavi_match_limit" value="{MATCH_LIMIT}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_match_length">{L_MATCH_LENGTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_match_length" id="subnavi_match_length" value="{MATCH_LENGTH}" size="5" /></td>
</tr>
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="subnavi_forum_limit">{L_FORUM_LIMIT}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_forum_limit" id="subnavi_forum_limit" value="{FORUM_LIMIT}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_forum_length">{L_FORUM_LENGTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_forum_length" id="subnavi_forum_length" value="{FORUM_LENGTH}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_download_limit">{L_DOWNLOAD_LIMIT}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_download_limit" id="subnavi_download_limit" value="{DOWNLOAD_LIMIT}" size="5" /></td>
</tr>
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="subnavi_download_length">{L_DOWNLOAD_LENGTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_download_length" id="subnavi_download_length" value="{DOWNLOAD_LENGTH}" size="5" /></td>
</tr>
<tr>
	<td class="row1_1" nowrap="nowrap"><label for="subnavi_newusers">{L_NEWUSERS}:</label></td>
	<td class="row2"><label><input type="radio" name="subnavi_newusers" id="subnavi_newusers" value="1" {S_NEWUSERS_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="subnavi_newusers" value="0" {S_NEWUSERS_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_cache">{L_NEWUSERS_CACHE}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_newusers_cache" id="subnavi_newusers_cache" value="{NEWUSERS_CACHE}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_limit">{L_NEWUSERS_LIMIT}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_newusers_limit" id="subnavi_newusers_limit" value="{NEWUSERS_LIMIT}" size="5" /></td>
</tr>
<tr>
	<td class="row1"><label for="subnavi_newusers_length">{L_NEWUSERS_LENGTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="subnavi_newusers_length" id="subnavi_newusers_length" value="{NEWUSERS_LENGTH}" size="5" /></td>
</tr>
<!-- END _display -->
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="button2">&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="button" /></td>
</tr>
</table>
{S_FIELDS}
</form>