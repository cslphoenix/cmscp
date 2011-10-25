<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_ERROR}">{L_ERROR}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table>
<tr>
<<<<<<< .mine
	<td class="rowHead" align="center">{L_LOGS_USERNAME}</td>
	<td class="rowHead" align="center">{L_LOGS_IP}</td>
	<td class="rowHead" align="center">{L_LOGS_TIME}</td>
	<td class="rowHead" align="center">{L_LOG_SEKTION}</td>
	<td class="rowHead" align="center">{L_LOGS_MESSAGE}</td>
	<td class="rowHead" align="center">{L_LOGS_CHANGE}</td>
	<td class="rowHead" align="center">{L_DELETE}</td>
=======
	<th>{L_LOGS_USERNAME}</th>
	<th>{L_LOGS_IP}</th>
	<th>{L_LOGS_TIME}</th>
	<th>{L_LOG_SEKTION}</th>
	<th>{L_LOGS_MESSAGE}</th>
	<th>{L_LOGS_CHANGE}</th>
	<th>{L_DELETE}</th>
>>>>>>> .r85
</tr>
<!-- BEGIN _logs_row -->
<tr class="{_display._logs_row.CLASS}" onclick="checked({_display._logs_row.LOG_ID})">
	<td class="{_display._logs_row.CLASS}" align="left">{_display._logs_row.USERNAME}</td>
	<td class="{_display._logs_row.CLASS}" align="center">{_display._logs_row.IP}</td>
	<td class="{_display._logs_row.CLASS}" align="center">{_display._logs_row.DATE}</td>
	<td class="{_display._logs_row.CLASS}" align="center">{_display._logs_row.SEKTION}</td>
	<td class="{_display._logs_row.CLASS}" align="center">{_display._logs_row.MESSAGE}</td>
	<td class="{_display._logs_row.CLASS}" align="left">{_display._logs_row.DATA}</td>
	<td class="{_display._logs_row.CLASS}" align="center" width="1%"><input type="checkbox" name="log_id[]" id="check_{_display._logs_row.LOG_ID}" value="{_display._logs_row.LOG_ID}"></td>
</tr>
<!-- END _logs_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="entry_empty" colspan="7">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td><input type="submit" name="delete_all" value="Alle {L_DELETE}" class="button" /></td>
	<td><input type="submit" name="delete" value="{L_DELETE}" class="button" /></td>
</tr>
</table>

<table class="footer">
<tr>
	<td align="right"><a href="#" onclick="marklist('list', 'log', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'log', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<<<<<<< .mine
<!-- BEGIN _error -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_ERROR}</a></li>
	</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
=======
<!-- BEGIN _error -->
<form action="{S_ACTION}" method="post" id="list" name="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_ERROR}</a></li>
	</ul>
</div>

<table class="header">
>>>>>>> .r85
<tr>
<<<<<<< .mine
	<td class="row4 small">{L_EXPLAIN}</td>
=======
	<td>{L_EXPLAIN}</td>
>>>>>>> .r85
</tr>
</table>

<br />

<table class="row" cellspacing="1">

<!-- BEGIN _error_row -->
<tr>
	<td class="{_error._error_row.CLASS}" rowspan="6" align="left" valign="top" nowrap="nowrap"><b>{_error._error_row.TIME}</b><br>Error ID: {_error._error_row.ID}<br>		User ID: {_error._error_row.USER}</td>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Line:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.FILE_LINE}<b> in File: </b>{_error._error_row.FILE}</td>
	<td class="{_error._error_row.CLASS}" rowspan="6" align="center"><input type="checkbox" name="log_id[]" value="{_error._error_row.ERROR_ID}"></td>
</tr>
<tr>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.MSG_TITLE}</td>
	</tr>
<tr>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error Text:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.MSG_TEXT}</td>
	</tr>
<tr>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Code:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.SQL_CODE}</td>
	</tr>
<tr>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Text:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.SQL_TEXT}</td>
</tr>
<tr>
	<td class="{_error._error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Store:</b></td>
	<td class="{_error._error_row.CLASS}" align="left" valign="top">{_error._error_row.SQL_STORE}</td>
</tr>
<!-- END _error_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" colspan="4" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" width="100%" cellspacing="2">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
</table>

<table class="footer" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		<input type="hidden" name="mode" value="deleteerror" />
		<input type="submit" name="send" value="{L_DELETE}" class="button" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'log_id', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'log_id', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END error -->