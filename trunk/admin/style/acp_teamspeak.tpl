<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.SHOW}</span> {display.row.NAME}</td>
	<td>{display.row.UPDATE} {display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="teamspeak_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>

<table class="update">
<tr>
	<td class="row1_1"><label for="teamspeak_type">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="teamspeak_type" value="0" onChange="document.getElementById('ts_2').style.display = ''; document.getElementById('ts_3').style.display = 'none'; document.getElementById('teamspeak_qport').value = '51234'; document.getElementById('teamspeak_port').value = '8797'; document.forms[0].teamspeak_viewer[1].checked = true;" {S_TYPE_TS2} />&nbsp;{L_TS2}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="teamspeak_type" value="1" onChange="document.getElementById('ts_2').style.display = 'none'; document.getElementById('ts_3').style.display = ''; document.getElementById('teamspeak_qport').value = '10011'; document.getElementById('teamspeak_port').value = '9987'; document.forms[0].teamspeak_viewer[3].checked = true;" {S_TYPE_TS3} />&nbsp;{L_TS3}</label></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_viewer">{L_VIEWERS}:</label></td>
	<td id="ts_2" style="display:none;"><label><input type="radio" name="teamspeak_viewer" value="0" {S_VIEWER_CYTS} disabled="disabled" />&nbsp;{L_CYTS}</label><span style="padding:4px;"></span><label><input type="radio" name="teamspeak_viewer" value="1" {S_VIEWER_VIEWER} />&nbsp;{L_VIEWER}</label></td>
	<td id="ts_3" style="display:;"><label><input type="radio" name="teamspeak_viewer" value="2" {S_VIEWER_GAMEQ} disabled="disabled" />&nbsp;{L_GAMEQ}</label><span style="padding:4px;"></span><label><input type="radio" name="teamspeak_viewer" value="3" {S_VIEWER_TSSTATUS} />&nbsp;{L_TSSTATUS}</label></td>
</tr>
<tr>
	<td class="row1r"><label for="teamspeak_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_name" id="teamspeak_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="teamspeak_ip">{L_IP}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_ip" id="teamspeak_ip" value="{IP}"></td>
</tr>
<tr>
	<td class="row1r"><label for="teamspeak_port" title="{L_PORT_EXPLAIN}">{L_PORT}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_port" id="teamspeak_port" value="{PORT}"></td>
</tr>
<tr>
	<td class="row1r"><label for="teamspeak_qport" title="{L_QPORT_EXPLAIN}">{L_QPORT}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_qport" id="teamspeak_qport" value="{QPORT}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_pass">{L_PASS}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_pass" id="teamspeak_pass" value="{PASS}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_pass">{L_OPTION}:</label></td>
	<td class="row2"><input type="text" name="teamspeak_option" id="teamspeak_option" value="{OPTION}"></td>
</tr>
<tr>
	<td class="row1"><label for="teamspeak_show">{L_SHOW}:</label></td>
	<td class="row2"><label><input type="radio" name="teamspeak_show" value="1" {S_SHOW_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="teamspeak_show" value="0" {S_SHOW_NO} />&nbsp;{L_NOSHOW}</label></td>
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
</table>{S_FIELDS}
<!-- END input -->