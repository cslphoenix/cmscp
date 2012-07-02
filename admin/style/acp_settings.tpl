<script type="text/javascript">
// <![CDATA[

function SelectAll(id)
{
	document.getElementById(id).focus();
	document.getElementById(id).select();
}
	
// ]]>
</script>

<form action="{S_ACTION}" method="post" name="mode" id="mode">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>
<ul id="navopts"><li>{S_MODE} <input type="submit" value="{L_GO}" /></li></ul>
</form>

<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEADING}</a></li></ul>
<ul id="navinfo"><li>{L_HEADING_EXPLAIN}</li></ul>

<br />

<!-- BEGIN default -->
<!-- BEGIN row -->
<table class="update2">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{default.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{default.row.tab.option.CSS}"><label for="{default.row.tab.option.LABEL}" {default.row.tab.option.EXPLAIN}>{default.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{default.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END tab -->
</table>
<!-- END row -->
<!-- END default -->

<!-- BEGIN calendar -->
<!-- BEGIN row -->
<div style="float:left; width:49%;">
	<table class="update2">
	<!-- BEGIN tab -->
	<tr>
		<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{calendar.row.tab.L_LANG}</a></li></ul></th>
	</tr>
	<!-- BEGIN option -->
	<tr>
		<td class="row1"><label for="{calendar.row.KEY}_{calendar.row.tab.option.KEYS}">{calendar.row.tab.option.L_NAME}:</label></td>
		<td class="row2">{calendar.row.tab.option.OPTION}</td>
	</tr>
	<!-- END option -->
	<!-- END tab -->
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</table>
</div>
<!-- END row -->
<!-- END calendar -->

<!-- BEGIN gallery -->
<!-- BEGIN row -->
<table class="update2">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{gallery.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1"><label for="{gallery.row.KEY}_{gallery.row.tab.option.KEYS}">{gallery.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{gallery.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->
<!-- END gallery -->

<!-- BEGIN module -->
<!-- BEGIN row -->
<div style="float:left; width:49%;">
	<table class="update2">
	<!-- BEGIN tab -->
	<tr>
		<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{module.row.tab.L_LANG}</a></li></ul></th>
	</tr>
	<!-- BEGIN option -->
	<tr>
		<td class="row1"><label for="{module.row.KEY}_{module.row.tab.option.KEYS}">{module.row.tab.option.L_NAME}:</label></td>
		<td class="row2">{module.row.tab.option.OPTION}</td>
	</tr>
	<!-- END option -->
	<!-- END tab -->
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</table>
</div>
<!-- END row -->
<!-- END module -->

<!-- BEGIN subnavi -->
<!-- BEGIN row -->
<table class="update2">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{subnavi.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1"><label for="{subnavi.row.KEY}_{subnavi.row.tab.option.KEYS}">{subnavi.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{subnavi.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>

<!-- END row -->
<!-- END subnavi -->


<!-- BEGIN upload -->
<!-- BEGIN row -->
<div style="float:left; width:49%;">
	<table class="update2">
	<!-- BEGIN tab -->
	<tr>
		<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{upload.row.tab.L_LANG}</a></li></ul></th>
	</tr>
	<!-- BEGIN option -->
	<tr>
		<td class="row1"><label for="{upload.row.KEY}_{upload.row.tab.option.KEYS}">{upload.row.tab.option.L_NAME}:</label></td>
		<td class="row2">{upload.row.tab.option.OPTION}</td>
	</tr>
	<!-- END option -->
	<!-- END tab -->
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	</table>
</div>
<!-- END row -->
<!-- END upload -->

<!-- BEGIN other -->
<!-- BEGIN row -->
<div style="float:left;width:49%;">
	<table class="update">
	<!-- BEGIN tab -->
	<tr>
		<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{other.row.tab.L_LANG}</a></li></ul></th>
	</tr>
	<!-- BEGIN option -->
	<tr>
		<td class="row1"><label for="{other.row.KEY}_{other.row.tab.option.KEYS}">{other.row.tab.option.L_NAME}:</label></td>
		<td class="row2">{other.row.tab.option.OPTION}</td>
	</tr>
	<!-- END option -->
	<!-- END tab -->
	<tr>
		<td colspan="2"></td>
	</tr>
	</table>
</div>
<!-- END row -->
<!-- END other -->

<!-- BEGIN cache -->
<!-- END cache -->

<!-- BEGIN ftp -->
<table class="update">
<tr>
	<td class="row1_1">{L_HOSTNAME}: / {L_PORT}:</td>
	<td class="row3"><input class="post" type="text" maxlength="255" size="25" name="server" id="server" value="{HOSTNAME}" /> : <input class="post" type="text" maxlength="5" size="5" name="port" value="{PORT}"></td>
</tr>
<tr>
	<td>{L_FTP_PATH}:</td>
	<td class="row3">{S_PATH_PAGE}</td>
</tr>
<tr>
	<td>{L_FTP_PERMS}:</td>
	<td class="row3">{S_PATH_PERMS}</td>
</tr>
<tr>
	<td>{L_FTP_USER}:</td>
	<td class="row3"><input class="post" type="text" size="25" maxlength="100" name="user" value="{USER}" autocomplete="off"></td>
</tr>
<tr>
	<td>{L_FTP_PASS}:</td>
	<td class="row3"><input class="post" type="password" size="25" maxlength="100" name="pass" value="{PASS}" autocomplete="off"></td>
</tr>
</table>
<!-- END ftp -->

<!-- BEGIN match -->
<!-- BEGIN row -->
<table class="update2">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{match.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1"><label for="{match.row.KEY}_{match.row.tab.option.KEYS}">{match.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{match.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->
<!-- END match -->

<!-- BEGIN phpinfo -->
<table class="update3 list">
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_SUPPORT_COMMON}</a></li></ul></th>
</tr>
<tr>
	<td class="row1">{L_VERSION}</td>
	<td class="row2">{VERSION}</td>
</tr>
<tr>
	<td class="row1">{L_DOMAIN}</td>
	<td class="row2">{DOMAIN}</td>
</tr>
<tr>
	<td class="row1">{L_BROWSER}</td>
	<td class="row2">{BROWSER}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_SUPPORT_VERSION}</a></li></ul></th>
</tr>
<tr>
	<td class="row1">{L_SERVER_OS}</td>
	<td class="row2">{SERVER_OS}</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_APACHE}</td>
	<td class="row2">{SERVER_APACHE}</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_PHP}</td>
	<td class="row2">{SERVER_PHP}</td>
</tr>
<tr>
	<td class="row1">{L_SERVER_SQL}</td>
	<td class="row2">{SERVER_SQL}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_SUPPORT_SERVER}</a></li></ul></th>
</tr>
<tr>
	<td class="row1">{L_OPTION_A}</td>
	<td class="row2">{OPTION_A}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_B}</td>
	<td class="row2">{OPTION_B}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_C}</td>
	<td class="row2">{OPTION_C}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_D}</td>
	<td class="row2">{OPTION_D}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_E}</td>
	<td class="row2">{OPTION_E}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_F}</td>
	<td class="row2">{OPTION_F}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_G}</td>
	<td class="row2">{OPTION_G}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_H}</td>
	<td class="row2">{OPTION_H}</td>
</tr>
<tr>
	<td class="row1">{L_OPTION_I}</td>
	<td class="row2">{OPTION_I}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">Copy and Paste ;)</a></li></ul></td>
</tr>
<tr>
	<td colspan="2" class="row3">
<textarea class="selectsmall" name="phpinfo" style="width:97%;height:430px" readonly="readonly" id="txtarea" onClick="SelectAll('txtarea');">
/**********************************************
 * {L_SUPPORT_COMMON}
 **********************************************
 * {L_VERSION}&nbsp;{VERSION}
 * {L_DOMAIN}&nbsp;{DOMAIN}
 * {L_BROWSER}&nbsp;{BROWSER}
 **********************************************
 * {L_SUPPORT_VERSION}
 **********************************************
 * {L_SERVER_OS}&nbsp;{SERVER_OS}
 * {L_SERVER_APACHE}&nbsp;{SERVER_APACHE}
 * {L_SERVER_PHP}&nbsp;{SERVER_PHP}
 * {L_SERVER_SQL}&nbsp;{SERVER_SQL}
 **********************************************
 * {L_SUPPORT_SERVER}
 **********************************************
 * {L_OPTION_A}&nbsp;{OPTION_A}
 * {L_OPTION_B}&nbsp;{OPTION_B}
 * {L_OPTION_C}&nbsp;{OPTION_C}
 * {L_OPTION_D}&nbsp;{OPTION_D}
 * {L_OPTION_E}&nbsp;{OPTION_E}
 * {L_OPTION_F}&nbsp;{OPTION_F}
 * {L_OPTION_G}&nbsp;{OPTION_G}
 * {L_OPTION_H}&nbsp;{OPTION_H}
 * {L_OPTION_I}&nbsp;{OPTION_I}
 **********************************************/
</textarea>
	</td>
</tr>
</table>
<!-- END phpinfo --> 

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>

<!-- BEGIN defaulta -->
<table class="update">
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEADING}</a></li></ul></th>
</tr>
<tr>
	<td class="row1"><label for="server_name" title="{L_SERVER_NAME_EXPLAIN}">{L_SERVER_NAME}</label>&nbsp;/&nbsp;<label for="server_port" title="{L_SERVER_PORT_EXPLAIN}">{L_SERVER_PORT}:</label></td>
	<td class="row2"><input type="text" size="25" name="server_name" id="server_name" value="{SERVER_NAME}" />&nbsp;/&nbsp;<input type="text" size="5" name="server_port" id="server_port" value="{SERVER_PORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="page_path" title="{L_PAGE_PATH_EXPLAIN}">{L_PAGE_PATH}:</label></td>
	<td class="row2"><input type="text" size="25" name="page_path" id="page_path" value="{PAGE_PATH}"></td>
</tr>
<tr>
	<td class="row1"><label for="page_name" title="{L_PAGE_NAME_EXPLAIN}">{L_PAGE_NAME}:</label></td>
	<td class="row2"><input type="text" size="25" name="page_name" id="page_name" value="{PAGE_NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="page_desc">{L_PAGE_DESC}:</label></td>
	<td class="row2"><textarea name="page_desc" id="page_desc" cols="55" rows="6" maxlength="255">{PAGE_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="gzip" title="{L_PAGE_GZIP_EXPLAIN}">{L_PAGE_GZIP}:</label></td>
	<td class="row2"><label><input type="radio" name="gzip_compress" id="gzip" value="1" {S_GZIP_YES} />&nbsp;{L_ACTIVE}</label><span style="padding:4px;"></span><label><input type="radio" name="gzip_compress" value="0" {S_GZIP_NO} />&nbsp;{L_DEACTIVE}</label></td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_CLAN}</a></li></ul></th>
</tr>
<tr>
	<td class="row1"><label for="clan_name" title="{L_CLAN_NAME_EXPLAIN}">{L_CLAN_NAME}:</label></td>
	<td class="row2"><input type="text" size="25" name="clan_name" id="clan_name" value="{CLAN_NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="clan_tag" title="{L_CLAN_TAG_EXPLAIN}">{L_CLAN_TAG}:</label></td>
	<td class="row2"><input type="text" size="25" name="clan_tag" id="clan_tag" value="{CLAN_TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="clan_tag_show" title="{L_CLAN_TAG_SHOW_EXPLAIN}">{L_CLAN_TAG_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="clan_tag_show" id="clan_tag_show" value="1" {S_SHOW_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="clan_tag_show" value="0" {S_SHOW_NO} />&nbsp;{L_NOSHOW}</label></td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_STANDARDS}</a></li></ul></th>
</tr>
<tr>
	<td class="row1"><label for="default_dateformat" title="{L_DATEFORMAT_EXPLAIN}">{L_DATEFORMAT}:</label></td>
	<td class="row2"><input type="text" size="25" name="default_dateformat" id="default_dateformat" value="{DATEFORMAT}"></td>
</tr>
<tr>
	<td class="row1"><label for="default_timezone" title="{L_TIMEZONE_EXPLAIN}">{L_TIMEZONE}:</label></td>
	<td class="row2">{S_TIMEZONE}</td>
</tr>
<tr>
	<td class="row1"><label for="default_lang" title="{L_LANG_EXPLAIN}">{L_LANG}:</label></td>
	<td class="row2">{S_LANG}</td>
</tr>
<tr>
	<td class="row1"><label for="default_style" title="{L_STYLE_EXPLAIN}">{L_STYLE}:</label></td>
	<td class="row2">{S_STYLE}</td>
</tr>
<tr>
	<td class="row1"><label for="style_override" title="{L_OVERRIDE_EXPLAIN}">{L_OVERRIDE}:</label></td>
	<td class="row2"><label><input type="radio" name="override_user_style" id="style_override" value="1" {S_OVERRIDE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="override_user_style" value="0" {S_OVERRIDE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_MAINTENANCE}</a></li></ul></th>
</tr>
<tr>
	<td class="row1"><label for="page_disable" title="{L_DISABLE_PAGE_EXPLAIN}">{L_DISABLE_PAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="page_disable" id="page_disable" value="1" {S_DISABLE_PAGE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="page_disable" value="0" {S_DISABLE_PAGE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="page_disable_msg">{L_DISABLE_PAGE_REASON}:</label></td>
	<td class="row2"><textarea cols="35" rows="4" maxlength="255" name="page_disable_msg" id="page_disable_msg">{DISABLE_REASON}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="page_disable_mode">{L_DISABLE_PAGE_MODE}:</label></td>
	<td>{PAGE_DISABLE_MODE}<br /><a href="#" class="small" onclick="selector(true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" class="small" onclick="selector(false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
<!-- END defaulta -->