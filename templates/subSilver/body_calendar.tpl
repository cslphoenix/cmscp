<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="3"><span class="right">{CAL_CACHE}</span><span class="right"><a href="calendar.php{PREV}">&laquo;</a> {CAL_MONTH} <a href="calendar.php{NEXT}">&raquo;</a>&nbsp;</span>{L_CAL}</th>
</tr>
<!-- BEGIN list -->
<!-- BEGIN rows -->
<tr>
	<td class="{list.rows.CLASS} top" align="center" width="3%"><a name="{list.rows.CAL_ID}"></a>{list.rows.CAL_DAY}</td>
	<td class="{list.rows.CLASS} top" align="center" width="3%">{list.rows.CAL_WEEKDAY}</td>
	<td class="{list.rows.CLASS} top" width="95%">{list.rows.CAL_EVENT}</td>
</tr>
<!-- END rows -->
<!-- END list -->

<!-- BEGIN month -->
<tr>
	<td colspan="3">
	<table class="cal">
		{DAY}
		{NUM}
	</table>
	</td>
</tr>
<!-- END month -->

<tr>
	<td class="footer" colspan="3">{L_LEGEND}</td>
</tr>
</table>