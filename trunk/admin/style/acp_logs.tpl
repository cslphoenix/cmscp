<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<table class="logs">
<tr>
	<th>{L_LOGS_USERNAME}</th>
	<th>{L_LOGS_IP}</th>
	<th>{L_LOGS_TIME}</th>
	<th>{L_LOG_SEKTION}</th>
	<th><input type="checkbox" name="log_id[]" onclick="checkbox(this.name,this.checked)" value="" /></th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{display.row.CLASS}"><label for="check_{display.row.LOG_ID}">{display.row.USERNAME}</label></td>
	<td class="{display.row.CLASS}"><label for="check_{display.row.LOG_ID}">{display.row.IP}</label></td>
	<td class="{display.row.CLASS}"><label for="check_{display.row.LOG_ID}">{display.row.DATE}</label></td>
	<td class="{display.row.CLASS}"><label for="check_{display.row.LOG_ID}"><strong>{display.row.SEKTION}</strong>&nbsp;&raquo;&nbsp;<strong>{display.row.MESSAGE}</strong>{display.row.DATA}</label></td>
	<td align="center" width="1%"><input type="checkbox" name="log_id[]" id="check_{display.row.LOG_ID}" value="{display.row.LOG_ID}"></td>
</tr>
<!-- END row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="empty" colspan="7">{L_EMPTY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
<br />
<table class="footer">
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td><input type="submit" name="delete_all" value="{L_DELETE_ALL}" class="button2" /> <input type="submit" name="delete" value="{L_DELETE}" class="button2" /></td>
</tr>
</table>
<br />
<table class="footer">
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td align="right"><a href="#" onclick="marklist('list', 'log', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'log', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN error -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<table class="errors" cellspacing="1">
<!-- BEGIN row -->
<tr>
	<td class="{error.row.CLASS}" rowspan="6" align="left" valign="top" nowrap="nowrap"><b>{error.row.TIME}</b><br>Error ID: {error.row.ID}<br>		User ID: {error.row.USER}</td>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Line:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.FILE_LINE}<b> in File: </b>{error.row.FILE}</td>
	<td class="{error.row.CLASS}" rowspan="6" align="center"><input type="checkbox" name="log_id[]" value="{error.row.ERROR_ID}"></td>
</tr>
<tr>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.MSG_TITLE}</td>
	</tr>
<tr>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error Text:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.MSG_TEXT}</td>
	</tr>
<tr>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Code:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.SQL_CODE}</td>
	</tr>
<tr>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Text:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.SQL_TEXT}</td>
</tr>
<tr>
	<td class="{error.row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Store:</b></td>
	<td class="{error.row.CLASS}" align="left" valign="top">{error.row.SQL_STORE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="row_class1" colspan="4" align="center">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="footer">
<tr>
	<td><input type="submit" name="send" value="{L_DELETE}" class="button" /><input type="hidden" name="mode" value="deleteerror" /></td>
	<td></td>
    <td></td>
    <td>{PAGE_NUMBER}<br />{PAGE_PAGING}<br /><a href="#" onclick="marklist('list', 'log_id', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'log_id', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END error -->