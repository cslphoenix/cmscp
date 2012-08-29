<!-- BEGIN display -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_ERROR}">{L_ERROR}</a></li></ul>
<p>{L_EXPLAIN}</p>

<br />

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
	<td align="center" width="1%"><input type="checkbox" name="log_id[]" id="check_{display.row.LOG_ID}" value="{display.row.LOG_ID}"> {display.row.LOG_ID}</td>
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
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_ERROR}</a></li></ul>
<p>{L_EXPLAIN}</p>

<br />

<table class="row" cellspacing="1">

<!-- BEGIN error_row -->
<tr>
	<td class="{_error._errorrow.CLASS}" rowspan="6" align="left" valign="top" nowrap="nowrap"><b>{_error._errorrow.TIME}</b><br>Error ID: {_error._errorrow.ID}<br>		User ID: {_error._errorrow.USER}</td>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Line:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.FILE_LINE}<b> in File: </b>{_error._errorrow.FILE}</td>
	<td class="{_error._errorrow.CLASS}" rowspan="6" align="center"><input type="checkbox" name="log_id[]" value="{_error._errorrow.ERROR_ID}"></td>
</tr>
<tr>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.MSG_TITLE}</td>
	</tr>
<tr>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error Text:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.MSG_TEXT}</td>
	</tr>
<tr>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Code:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.SQL_CODE}</td>
	</tr>
<tr>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Text:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.SQL_TEXT}</td>
</tr>
<tr>
	<td class="{_error._errorrow.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Store:</b></td>
	<td class="{_error._errorrow.CLASS}" align="left" valign="top">{_error._errorrow.SQL_STORE}</td>
</tr>
<!-- END error_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" colspan="4" align="center">{L_EMPTY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer">
<tr>
	<td><input type="submit" name="send" value="{L_DELETE}" class="button" /><input type="hidden" name="mode" value="deleteerror" /></td>
	<td></td>
    <td></td>
    <td>{PAGE_NUMBER}<br />{PAGE_PAGING}<br /><a href="#" onclick="marklist('list', 'log_id', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'log_id', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>

<table class="footer" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		
		
	</td>
</tr>
<tr>
	<td colspan="2" align="right"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END error -->