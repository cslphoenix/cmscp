<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a id="setting" href="{S_SET}">{L_SET}</a></li>
</ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_MAIN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN main_row -->
<tr>
	<td><span class="right">{display.main_row.URL}</span>{display.main_row.NAME}</td>
	<td>{display.main_row.LANG}{display.main_row.SHOW}{display.main_row.INTERN}{display.main_row.MOVE_UP}{display.main_row.MOVE_DOWN}{display.main_row.UPDATE}{display.main_row.DELETE}</td>
</tr>
<!-- END main_row -->
<!-- BEGIN no_entry_main -->
<tr>
	<td class="row_class1" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_main -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[1]"></td>
	<td><input type="submit" class="button2" name="navi_type[1]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_CLAN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN clan_row -->
<tr>
	<td><span class="right">{display.clan_row.URL}</span>{display.clan_row.NAME}</td>
	<td>{display.clan_row.LANG}{display.clan_row.SHOW}{display.clan_row.INTERN}{display.clan_row.MOVE_UP}{display.clan_row.MOVE_DOWN}{display.clan_row.UPDATE}{display.clan_row.DELETE}</td>
</tr>
<!-- END clan_row -->
<!-- BEGIN no_entry_clan -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_clan -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[2]"></td>
	<td><input type="submit" class="button2" name="navi_type[2]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
	
<table class="rows">
<tr>
	<th>{L_COM}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN com_row -->
<tr>
	<td><span class="right">{display.com_row.URL}</span>{display.com_row.NAME}</td>
	<td>{display.com_row.LANG}{display.com_row.SHOW}{display.com_row.INTERN}{display.com_row.MOVE_UP}{display.com_row.MOVE_DOWN}{display.com_row.UPDATE}{display.com_row.DELETE}</td>
</tr>
<!-- END com_row -->
<!-- BEGIN no_entry_com -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_com -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[3]"></td>
	<td><input type="submit" class="button2" name="navi_type[3]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_MISC}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN misc_row -->
<tr>
	<td><span class="right">{display.misc_row.URL}</span>{display.misc_row.NAME}</td>
	<td>{display.misc_row.LANG}{display.misc_row.SHOW}{display.misc_row.INTERN}{display.misc_row.MOVE_UP}{display.misc_row.MOVE_DOWN}{display.misc_row.UPDATE}{display.misc_row.DELETE}</td>
</tr>
<!-- END misc_row -->
<!-- BEGIN no_entry_misc -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_misc -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[4]"></td>
	<td><input type="submit" class="button2" name="navi_type[4]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_USER}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN user_row -->
<tr>
	<td><span class="right">{display.user_row.URL}</span>{display.user_row.NAME}</td>
	<td>{display.user_row.LANG}{display.user_row.SHOW}{display.user_row.INTERN}{display.user_row.MOVE_UP}{display.user_row.MOVE_DOWN}{display.user_row.UPDATE}{display.user_row.DELETE}</td>
</tr>
<!-- END user_row -->
<!-- BEGIN no_entry_user -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_user -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[5]"></td>
	<td><input type="submit" class="button2" name="navi_type[5]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
	<li><a href="{S_SET}" id="setting">{L_SET}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="navi_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="navi_name" id="navi_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="navi_url">{L_URL}:</label></td>
	<td class="row2">{S_LIST} <input type="text" name="navi_url" value="{URL}" id="select"></td>
</tr>
<tr>
	<td class="row1r"><label>{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="navi_type" value="1" onclick="setRequest('navi', 1, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_MAIN} />&nbsp;{L_TYPE_MAIN}</label><br />
		<label><input type="radio" name="navi_type" value="2" onclick="setRequest('navi', 2, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_CLAN} />&nbsp;{L_TYPE_CLAN}</label><br />
		<label><input type="radio" name="navi_type" value="3" onclick="setRequest('navi', 3, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_COM} />&nbsp;{L_TYPE_COM}</label><br />
		<label><input type="radio" name="navi_type" value="4" onclick="setRequest('navi', 4, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_MISC} />&nbsp;{L_TYPE_MISC}</label><br />
		<label><input type="radio" name="navi_type" value="5" onclick="setRequest('navi', 5, {CUR_TYPE}, {CUR_ORDER})" {S_TYPE_USER} />&nbsp;{L_TYPE_USER}</label>
	</td> 
</tr>
<tr>
	<td class="row1"><label>{L_TARGET}:</label></td>
	<td class="row2"><label><input type="radio" name="navi_target" value="0" {S_TARGET_SELF} />&nbsp;{L_TARGET_SELF}</label><br />
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
	<td><div id="close">{S_ORDER}</div><div id="ajax_content"></div></td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN settings -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="right" onclick="return false;">{L_SET}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

<br />

<!-- BEGIN row -->
<div style="float:left; width:50%;">
	<ul id="navlist"><li id="{_settings.row.LID}"><a href="#" id="{_settings.row.AID}">{_settings.row.LNG}</a></li></ul>
	<table class="update">
	<!-- BEGIN option -->
	<tr>
	<td class="row1"><label for="{_settings.row.KEY}_{_settings.row._option.KEYS}">{_settings.row._option.LNGS}:</label></td>
		<td>
			<!-- BEGIN input -->
			<input type="text" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_value]" id="{_settings.row.KEY}_{_settings.row._option.KEYS}" value="{_settings.row._option._input.VALUE}" />
			<input type="hidden" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_type]" value="{_settings.row._option._input.TYPE}" />{_settings.row._option._input.CHECK}
			<!-- END input -->
			<!-- BEGIN opt_switch -->
			<label><input type="radio" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_value]" id="{_settings.row.KEY}_{_settings.row._option.KEYS}" value="1" {_settings.row._option._opt_switch.S_YES}/>&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_value]" value="0" {_settings.row._option._opt_switch.S_NO}/>&nbsp;{L_NO}</label>
			<input type="hidden" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_type]" value="{_settings.row._option._opt_switch.TYPE}" />
			<!-- END opt_switch -->
			<!-- BEGIN opt_row -->
			<label><input type="radio" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_value]" value="{_settings.row._option._optrow.VALUE}" {_settings.row._option._optrow.S_OPT}/><span style="padding:4px;">{_settings.row._option._optrow.L_LNG}</span></label><br />
			<input type="hidden" name="{_settings.row.KEY}[{_settings.row._option.KEYS}_type]" value="{_settings.row._option._optrow.TYPE}" />
			<!-- END opt_row -->
		</td>
	</tr>
	<!-- END option -->
	<tr>
		<td colspan="2"></td>
	</tr>
	</table>
</div>
<!-- END row -->

<table class="update">
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END settings -->