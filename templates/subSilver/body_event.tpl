<!-- BEGIN list -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="3">{L_UPCOMING}</th>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td class="{_list._new_row.CLASS}">{_list._new_row.TITLE}</td>
	<td class="{_list._new_row.CLASS}">{_list._new_row.DATE}</td>
	<td class="{_list._new_row.CLASS}"><span class="{_list._new_row.CSS}">{_list._new_row.POS}</span></td>
</tr>
<!-- END new_row -->
<!-- BEGIN empty_new -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<th colspan="3">{L_EXPIRED}</th>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td class="{_list.old_row.CLASS}">{_list.old_row.TITLE}</td>
	<td class="{_list.old_row.CLASS}">{_list.old_row.DATE}</td>
	<td class="{_list.old_row.CLASS}"><span class="{_list.old_row.CSS}">{_list.old_row.POS}</span></td>
</tr>
<!-- END old_row -->
<!-- BEGIN empty_old -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty_old -->
<tr>
	<td class="footer" colspan="3"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END list -->

<!-- BEGIN view -->
<form action="{S_ACTION}" method="post" name="post">
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="2">
		<!-- BEGIN update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{_view._update.UPDATE}&nbsp;&bull;&nbsp;{_view._update.UPDATE_DETAIL}</span>
		<!-- END update -->
		<span class="small" style="float:right;">{EVENT_MAIN}</span>
		{L_EVENT_INFO}
	</th>
</tr>
</table>
{L_EVENT_TEXT}
{COMMENTS}
{S_FIELDS}
</form>
<!-- END view -->
