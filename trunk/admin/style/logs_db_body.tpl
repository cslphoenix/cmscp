<form method="post" action="{S_TEAM_ACTION}">
<table class="row" cellspacing="0">
<!-- BEGIN logs_row -->
<tr>
	<td class="{logs_row.CLASS}" rowspan="6" align="left" valign="top" nowrap="nowrap"><b>{logs_row.TIME}</b><br>Error ID: {logs_row.ERROR_ID}<br>User ID: {logs_row.ERROR_USERID}</td>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Line:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_FILE_LINE}<b> in File: </b>{logs_row.ERROR_FILE}</td>
	<td class="{logs_row.CLASS}" rowspan="6" width="80" align="center"><a href="{DELETE}">{L_DELETE}</a></td>
</tr>
<tr>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_MSG_TITLE}</td>
	</tr>
<tr>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>Error Text:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_MSG_TEXT}</td>
	</tr>
<tr>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Code:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_SQL_CODE}</td>
	</tr>
<tr>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Text:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_SQL_TEXT}</td>
</tr>
<tr>
	<td class="{logs_row.CLASS}" align="right" valign="top" nowrap="nowrap"><b>SQL Store:</b></td>
	<td class="{logs_row.CLASS}" align="left" valign="top">{logs_row.ERROR_SQL_STORE}</td>
</tr>
<!-- END logs_row -->
<!-- BEGIN no_entry -->
<tr>
	<td colspan="4" align="center" valign="top">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->

</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
</table>
</form>