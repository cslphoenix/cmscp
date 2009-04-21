<form method="post" action="{S_TEAM_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE}</th>
</tr>
<tr>
	<td>{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_TEAM_NAME}</td>
	<td class="rowHead">{L_TEAM_GAME}</td>
	<td class="rowHead">{L_TEAM_MEMBERCOUNT}</td>
	<td class="rowHead">{L_TEAM_SETTINGS}</td>
	<td class="rowHead">{L_TEAM_SETTINGS}</td>
	<td class="rowHead">{L_TEAM_SETTINGS}</td>
</tr>
<!-- BEGIN logs_row -->
<tr>
	<td class="{logs_row.CLASS}" align="left">{logs_row.USERNAME}</td>
	<td class="{logs_row.CLASS}" align="center">{logs_row.IP}</td>
	<td class="{logs_row.CLASS}" align="center">{logs_row.DATE}</td>

	<td class="{logs_row.CLASS}" align="center">{logs_row.SEKTION}</td>
	<td class="{logs_row.CLASS}" align="center">{logs_row.MESSAGE}</td>
	<td class="{logs_row.CLASS}" align="center"><span class="small">{logs_row.DATA}</span></td>
</tr>
<!-- END logs_row -->
<!-- BEGIN no_teams -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
</tr>
<!-- END no_teams -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
</table>
</form>