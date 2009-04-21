<form action="{S_SET_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_SET_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_SET_EXPLAIN}:</td>
</tr>
</table>

<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
	<tr>
		<th colspan="2"><a href="#" onClick="toggle('site_settings'); return false;">{L_GENERAL_SETTINGS}</a></th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="small">{L_GENERAL_SETTINGS_EXPLAIN}</span></td>
	</tr>
	<tbody id="site_settings" style="display:none;">
	<tr>
		<td class="row1" width="50%">{L_SERVER_NAME}: / {L_SERVER_PORT}:<br><span class="small">{L_SERVER_NAME_EXPLAIN}<br>{L_SERVER_PORT_EXPLAIN}</span></td>
		<td class="row3" width="50%"><input id="server_name" onBlur="javascript:checkEntry(this)" class="post" type="text" maxlength="255" size="25" name="server_name" value="{SERVER_NAME}" /> : <input id="server_port" onBlur="javascript:checkEntry(this)" class="post" type="text" maxlength="5" size="5" name="server_port" value="{SERVER_PORT}" /></td>
	</tr>
	
	<tr>
		<td class="row1">{L_SCRIPT_PATH}:<br><span class="small">{L_SCRIPT_PATH_EXPLAIN}</span></td>
		<td class="row3"><input id="script_path" onBlur="javascript:checkEntry(this)" class="post" type="text" maxlength="255" size="25" name="script_path" value="{SCRIPT_PATH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SITE_NAME}:<br><span class="small">{L_SITE_NAME_EXPLAIN}</span></td>
		<td class="row3"><input id="sitename" onBlur="javascript:checkEntry(this)" class="post" type="text" size="25" maxlength="100" name="sitename" value="{SITENAME}" /></td>
	</tr>
	
	<tr>
		<td class="row1">{L_SITE_DESCRIPTION}:</td>
		<td class="row3"><textarea class="post" cols="35" rows="4" maxlength="255" name="site_description">{SITE_DESCRIPTION}</textarea></td>
	</tr>
	<tr>
		<td class="row1">{L_DISABLE_PAGE}:<br><span class="small">{L_DISABLE_PAGE_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="page_disable" value="1" {S_DISABLE_PAGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="page_disable" value="0" {S_DISABLE_PAGE_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_DISABLE_PAGE_REASON}:</td>
		<td class="row3"><textarea class="post" cols="35" rows="4" maxlength="255" name="page_disable_msg">{DISABLE_REASON}</textarea></td>
	</tr>
	<tr>
		<td class="row1">{L_DISABLE_PAGE_MODE}:</td>
		<td class="row3">{BOARD_DISABLE_MODE}</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td class="row1">{L_EMAIL_ON-OFF}<br><span class="gensmall">{L_EMAIL_ON-OFF_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="email_enabled" value="1" {EMAIL_ON} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="email_enabled" value="0" {EMAIL_OFF} /> {L_NO}</td>
	</tr>
	</tbody>
	
	<tr>
		<th colspan="2"><a href="#" onClick="toggle('upload'); return false;">{L_GENERAL_UPLOAD}</a></th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="small">{L_GENERAL_UPLOAD_EXPLAIN}</span></td>
	</tr>
	<tbody id="upload" style="display:none;">
	<tr>
		<td class="row1">{L_GAMES_STORAGE_PATH}:<br><span class="small">{L_GAMES_STORAGE_PATH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="20" maxlength="50" name="game_path" value="{GAMES_PATH}" /> {GAMES_PATH_CHECKED}</td>
	</tr>
	<tr>
		<td class="row1">{L_RANKS_STORAGE_PATH}:<br><span class="small">{L_RANKS_STORAGE_PATH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="20" maxlength="50" name="ranks_path" value="{RANKS_PATH}" /> {RANKS_PATH_CHECKED}</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGO_STORAGE_PATH}:<br><span class="small">{L_TEAM_LOGO_STORAGE_PATH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="20" maxlength="50" name="team_logo_path" value="{TEAM_LOGO_PATH}" /> {TEAM_LOGO_PATH_CHECKED}</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGOS_STORAGE_PATH}:<br><span class="small">{L_TEAM_LOGOS_STORAGE_PATH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="20" maxlength="50" name="team_logos_path" value="{TEAM_LOGOS_PATH}" /> {TEAM_LOGOS_PATH_CHECKED}</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</tbody>

	<tr>
		<th colspan="2"><a href="#" onClick="toggle('team_logos'); return false;">{L_TEAM_LOGO_SETTINGS}</a></th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="small">{L_TEAM_LOGO_SETTINGS_EXPLAIN}</span></td>
	</tr>
	<tbody id="team_logos" style="display:none;">
	<tr>
		<td class="row1">{L_TEAM_LOGO_UPLOAD}:<br><span class="small">{L_TEAM_LOGO_UPLOAD_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="team_logo_upload" value="1" {S_TEAM_LOGO_UPLOAD_YES} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="team_logo_upload" value="0" {S_TEAM_LOGO_UPLOAD_NO} /> {L_DISABLED}</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGO_MAX_FILESIZE}:<br><span class="small">{L_TEAM_LOGO_MAX_FILESIZE_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="4" maxlength="10" name="team_logo_filesize" value="{TEAM_LOGO_FILESIZE}" /> Bytes</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGO_MAX_SIZE}:<br><span class="small">{L_TEAM_LOGO_MAX_SIZE_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="team_logo_max_height" value="{TEAM_LOGO_MAX_HEIGHT}" /> x <input class="post" type="text" size="3" maxlength="4" name="team_logo_max_width" value="{TEAM_LOGO_MAX_WIDTH}"></td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGOS_UPLOAD}:<br><span class="small">{L_TEAM_LOGOS_UPLOAD_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="team_logos_upload" value="1" {S_TEAM_LOGOS_UPLOAD_YES} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="team_logos_upload" value="0" {S_TEAM_LOGOS_UPLOAD_NO} /> {L_DISABLED}</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGO_MAX_FILESIZE}:<br><span class="small">{L_TEAM_LOGO_MAX_FILESIZE_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="4" maxlength="10" name="team_logos_filesize" value="{TEAM_LOGOS_FILESIZE}" /> Bytes</td>
	</tr>
	<tr>
		<td class="row1">{L_TEAM_LOGO_MAX_SIZE}:<br><span class="small">{L_TEAM_LOGO_MAX_SIZE_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="team_logos_max_height" value="{TEAM_LOGOS_MAX_HEIGHT}" /> x <input class="post" type="text" size="3" maxlength="4" name="team_logos_max_width" value="{TEAM_LOGOS_MAX_WIDTH}"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</tbody>

	<tr>
		<th colspan="2"><a href="#" onClick="toggle('cookie'); return false;">{L_COOKIE_SETTINGS}</a></th>
	</tr>
	<tr>
		<td class="row2" colspan="2"><span class="small">{L_COOKIE_SETTINGS_EXPLAIN}</span></td>
	</tr>
	<tbody id="cookie" style="display:none;">
	<tr>
		<td class="row1">{L_COOKIE_DOMAIN}:</td>
		<td class="row3"><input class="post" type="text" maxlength="255" name="cookie_domain" value="{COOKIE_DOMAIN}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_COOKIE_NAME}:</td>
		<td class="row3"><input id="cookie_name" onBlur="javascript:checkEntry(this)" class="post" type="text" maxlength="16" name="cookie_name" value="{COOKIE_NAME}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_COOKIE_PATH}:</td>
		<td class="row3"><input id="cookie_path" onBlur="javascript:checkEntry(this)" class="post" type="text" maxlength="255" name="cookie_path" value="{COOKIE_PATH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_COOKIE_SECURE}:<br><span class="small">{L_COOKIE_SECURE_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="cookie_secure" value="0" {S_COOKIE_SECURE_DISABLED} /> {L_DISABLED}&nbsp; &nbsp;<input type="radio" name="cookie_secure" value="1" {S_COOKIE_SECURE_ENABLED} /> {L_ENABLED}</td>
	</tr>
	<tr>
		<td class="row1">{L_SESSION_LENGTH}:</td>
		<td class="row3"><input class="post" type="text" maxlength="5" size="5" name="session_length" value="{SESSION_LENGTH}" /></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</tbody>
<!--	
	<tr>
		<td class="row1">{L_ACCT_ACTIVATION}:</td>
		<td class="row3"><input type="radio" name="require_activation" value="{ACTIVATION_NONE}" {ACTIVATION_NONE_CHECKED} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_USER}" {ACTIVATION_USER_CHECKED} />{L_USER}&nbsp; &nbsp;<input type="radio" name="require_activation" value="{ACTIVATION_ADMIN}" {ACTIVATION_ADMIN_CHECKED} />{L_ADMIN}:</td>
	</tr>
	<tr>
		<td class="row1">{L_VISUAL_CONFIRM}<br><span class="small">{L_VISUAL_CONFIRM_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="enable_confirm" value="1" {CONFIRM_ENABLE} />{L_YES}&nbsp; &nbsp;<input type="radio" name="enable_confirm" value="0" {CONFIRM_DISABLE} />{L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_AUTOLOGIN}<br><span class="small">{L_ALLOW_AUTOLOGIN_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="allow_autologin" value="1" {ALLOW_AUTOLOGIN_YES} />{L_YES}&nbsp; &nbsp;<input type="radio" name="allow_autologin" value="0" {ALLOW_AUTOLOGIN_NO} />{L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_AUTOLOGIN_TIME} <br><span class="small">{L_AUTOLOGIN_TIME_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="max_autologin_time" value="{AUTOLOGIN_TIME}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_BOARD_EMAIL_FORM}<br><span class="small">{L_BOARD_EMAIL_FORM_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="board_email_form" value="1" {BOARD_EMAIL_FORM_ENABLE} /> {L_ENABLED}&nbsp;&nbsp;<input type="radio" name="board_email_form" value="0" {BOARD_EMAIL_FORM_DISABLE} /> {L_DISABLED}:</td>
	</tr>
	<tr>
		<td class="row1">{L_FLOOD_INTERVAL} <br><span class="small">{L_FLOOD_INTERVAL_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="flood_interval" value="{FLOOD_INTERVAL}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SEARCH_FLOOD_INTERVAL} <br><span class="small">{L_SEARCH_FLOOD_INTERVAL_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="search_flood_interval" value="{SEARCH_FLOOD_INTERVAL}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MAX_LOGIN_ATTEMPTS}<br><span class="small">{L_MAX_LOGIN_ATTEMPTS_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="max_login_attempts" value="{MAX_LOGIN_ATTEMPTS}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_LOGIN_RESET_TIME}<br><span class="small">{L_LOGIN_RESET_TIME_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="3" maxlength="4" name="login_reset_time" value="{LOGIN_RESET_TIME}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_TOPICS_PER_PAGE}:</td>
		<td class="row3"><input class="post" type="text" name="topics_per_page" size="3" maxlength="4" value="{TOPICS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_POSTS_PER_PAGE}:</td>
		<td class="row3"><input class="post" type="text" name="posts_per_page" size="3" maxlength="4" value="{POSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_HOT_THRESHOLD}:</td>
		<td class="row3"><input class="post" type="text" name="hot_threshold" size="3" maxlength="4" value="{HOT_TOPIC}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_DEFAULT_STYLE}:</td>
		<td class="row3">{STYLE_SELECT}:</td>
	</tr>
	<tr>
		<td class="row1">{L_OVERRIDE_STYLE}<br><span class="small">{L_OVERRIDE_STYLE_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="override_user_style" value="1" {OVERRIDE_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_user_style" value="0" {OVERRIDE_STYLE_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_DEFAULT_LANGUAGE}:</td>
		<td class="row3">{LANG_SELECT}:</td>
	</tr>
	<tr>
		<td class="row1">{L_DATE_FORMAT}<br><span class="small">{L_DATE_FORMAT_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" name="default_dateformat" value="{DEFAULT_DATEFORMAT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SYSTEM_TIMEZONE}:</td>
		<td class="row3">{TIMEZONE_SELECT}:</td>
	</tr>
	<tr>
		<td class="row1">{L_ENABLE_GZIP}:</td>
		<td class="row3"><input type="radio" name="gzip_compress" value="1" {GZIP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gzip_compress" value="0" {GZIP_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_ENABLE_PRUNE}:</td>
		<td class="row3"><input type="radio" name="prune_enable" value="1" {PRUNE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="prune_enable" value="0" {PRUNE_NO} /> {L_NO}:</td>
	</tr>
	
	
	<tr>
		<th class="thHead" colspan="2">{L_PRIVATE_MESSAGING}</th>
	</tr>
	<tr>
		<td class="row1">{L_DISABLE_PRIVATE_MESSAGING}:</td>
		<td class="row3"><input type="radio" name="privmsg_disable" value="0" {S_PRIVMSG_ENABLED} />{L_ENABLED}&nbsp; &nbsp;<input type="radio" name="privmsg_disable" value="1" {S_PRIVMSG_DISABLED} />{L_DISABLED}:</td>
	</tr>
	<tr>
		<td class="row1">{L_INBOX_LIMIT}:</td>
		<td class="row3"><input class="post" type="text" maxlength="4" size="4" name="max_inbox_privmsgs" value="{INBOX_LIMIT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SENTBOX_LIMIT}:</td>
		<td class="row3"><input class="post" type="text" maxlength="4" size="4" name="max_sentbox_privmsgs" value="{SENTBOX_LIMIT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SAVEBOX_LIMIT}:</td>
		<td class="row3"><input class="post" type="text" maxlength="4" size="4" name="max_savebox_privmsgs" value="{SAVEBOX_LIMIT}" /></td>
	</tr>
	<tr>
	  <th class="thHead" colspan="2">{L_ABILITIES_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1">{L_MAX_POLL_OPTIONS}:</td>
		<td class="row3"><input class="post" type="text" name="max_poll_options" size="4" maxlength="4" value="{MAX_POLL_OPTIONS}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_HTML}:</td>
		<td class="row3"><input type="radio" name="allow_html" value="1" {HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {HTML_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOWED_TAGS}<br><span class="small">{L_ALLOWED_TAGS_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="30" maxlength="255" name="allow_html_tags" value="{HTML_TAGS}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_BBCODE}:</td>
		<td class="row3"><input type="radio" name="allow_bbcode" value="1" {BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {BBCODE_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_SMILIES}:</td>
		<td class="row3"><input type="radio" name="allow_smilies" value="1" {SMILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {SMILE_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_SMILIES_PATH} <br><span class="small">{L_SMILIES_PATH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="20" maxlength="255" name="smilies_path" value="{SMILIES_PATH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_SIG}:</td>
		<td class="row3"><input type="radio" name="allow_sig" value="1" {SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_sig" value="0" {SIG_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_MAX_SIG_LENGTH}<br><span class="small">{L_MAX_SIG_LENGTH_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" size="5" maxlength="4" name="max_sig_chars" value="{SIG_SIZE}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_ALLOW_NAME_CHANGE}:</td>
		<td class="row3"><input type="radio" name="allow_namechange" value="1" {NAMECHANGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_namechange" value="0" {NAMECHANGE_NO} /> {L_NO}:</td>
	</tr>
	<tr>
	  <th class="thHead" colspan="2">{L_EMAIL_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1">{L_ADMIN_EMAIL}:</td>
		<td class="row3"><input class="post" type="text" size="25" maxlength="100" name="board_email" value="{EMAIL_FROM}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_EMAIL_SIG}<br><span class="small">{L_EMAIL_SIG_EXPLAIN}</span></td>
		<td class="row3"><textarea name="board_email_sig" row_classs="5" cols="30">{EMAIL_SIG}</textarea></td>
	</tr>
	<tr>
		<td class="row1">{L_USE_SMTP}<br><span class="small">{L_USE_SMTP_EXPLAIN}</span></td>
		<td class="row3"><input type="radio" name="smtp_delivery" value="1" {SMTP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="smtp_delivery" value="0" {SMTP_NO} /> {L_NO}:</td>
	</tr>
	<tr>
		<td class="row1">{L_SMTP_SERVER}:</td>
		<td class="row3"><input class="post" type="text" name="smtp_host" value="{SMTP_HOST}" size="25" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SMTP_USERNAME}<br><span class="small">{L_SMTP_USERNAME_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="text" name="smtp_username" value="{SMTP_USERNAME}" size="25" maxlength="255" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SMTP_PASSWORD}<br><span class="small">{L_SMTP_PASSWORD_EXPLAIN}</span></td>
		<td class="row3"><input class="post" type="password" name="smtp_password" value="{SMTP_PASSWORD}" size="25" maxlength="255" /></td>
	</tr>
-->
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="button" />
		</td>
	</tr>
</table></form>

<br clear="all" />
