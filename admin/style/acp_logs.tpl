<!-- BEGIN display -->
<form action="{S_LOG_ACTION}" method="post" id="list" name="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_LOG_ERROR}">{L_ERROR}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="center">{L_LOG_USERNAME}</td>
	<td class="rowHead" align="center">{L_LOG_IP}</td>
	<td class="rowHead" align="center">{L_LOG_TIME}</td>
	<td class="rowHead" align="center">{L_LOG_SEKTION}</td>
	<td class="rowHead" align="center">{L_LOG_MESSAGE}</td>
	<td class="rowHead" align="center">{L_LOG_CHANGE}</td>
	<td class="rowHead" align="center">{L_DELETE}</td>
</tr>
<!-- BEGIN logs_row -->
<tr>
	<td class="{display.logs_row.CLASS}" align="left">{display.logs_row.USERNAME}</td>
	<td class="{display.logs_row.CLASS}" align="center">{display.logs_row.IP}</td>
	<td class="{display.logs_row.CLASS}" align="center">{display.logs_row.DATE}</td>

	<td class="{display.logs_row.CLASS}" align="center">{display.logs_row.SEKTION}</td>
	<td class="{display.logs_row.CLASS}" align="center">{display.logs_row.MESSAGE}</td>
	<td class="{display.logs_row.CLASS}" align="center"><span class="small">{display.logs_row.DATA}</span></td>
	<td class="{display.logs_row.CLASS}" align="center" width="1%"><input type="checkbox" name="log_id[]" value="{display.logs_row.LOG_ID}"></td>
</tr>
<!-- END logs_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" width="100%" cellspacing="2">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		<input type="submit" name="delete_all" value="Alle {L_DELETE}" class="button" /> <input type="submit" name="delete" value="{L_DELETE}" class="button" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'log', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'log', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN error -->
<form action="{S_LOG_ERROR}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_LOG_ACTION}">{L_LOG_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_LOG_ERROR}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_LOG_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">

<!-- BEGIN error_row -->
<tr>
	<td class="{error.error_row.CLASS}" rowspan="6" align="left" valign="top" nowrap="nowrap"><b>{error.error_row.TIME}</b><br>Error ID: {error.error_row.ERROR_ID}<br>User ID: {error.error_row.ERROR_USERID}</td>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Line:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_FILE_LINE}<b> in File: </b>{error.error_row.ERROR_FILE}</td>
	<td class="{error.error_row.CLASS}" rowspan="6" align="center"><input type="checkbox" name="log_id[]" value="{error.error_row.ERROR_ID}"></td>
</tr>
<tr>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_MSG_TITLE}</td>
	</tr>
<tr>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error Text:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_MSG_TEXT}</td>
	</tr>
<tr>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Code:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_SQL_CODE}</td>
	</tr>
<tr>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Text:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_SQL_TEXT}</td>
</tr>
<tr>
	<td class="{error.error_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Store:</b></td>
	<td class="{error.error_row.CLASS}" align="left" valign="top">{error.error_row.ERROR_SQL_STORE}</td>
</tr>
<!-- END error_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" colspan="4" align="center">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" width="100%" cellspacing="2">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
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