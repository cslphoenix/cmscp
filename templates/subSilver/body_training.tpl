<!-- BEGIN list -->
<table class="match" width="100%" cellspacing="0">
<tr>
	<td class="header" colspan="3">{L_UPCOMING}</td>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td class="{_list._new_row.CLASS}">{_list._new_row.GAME} {_list._new_row.NAME}</td>
	<td class="{_list._new_row.CLASS}">{_list._new_row.DATE}</td>
	<td class="{_list._new_row.CLASS}"><span class="{_list._new_row.CSS}">{_list._new_row.STATUS}</span></td>
</tr>
<!-- END new_row -->
<!-- BEGIN entry_empty_new -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_EXPIRED}</td>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td class="{_list.old_row.CLASS}">{_list.old_row.GAME} {_list.old_row.NAME}</td>
	<td class="{_list.old_row.CLASS}">{_list.old_row.DATE}</td>
	<td class="{_list.old_row.CLASS}"><span class="{_list.old_row.CSS}">{_list.old_row.STATUS}</span></td>
</tr>
<!-- END old_row -->
<!-- BEGIN entry_empty_old -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_old -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END list -->