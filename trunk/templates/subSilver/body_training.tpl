<!-- BEGIN _list -->
<table class="match" width="100%" cellspacing="0">
<tr>
	<td class="header" colspan="3">{L_UPCOMING}</td>
</tr>
<!-- BEGIN _new_row -->
<tr>
	<td class="{_list._new_row.CLASS}">{_list._new_row.GAME} {_list._new_row.NAME}</td>
	<td class="{_list._new_row.CLASS}">{_list._new_row.DATE}</td>
	<td class="{_list._new_row.CLASS}"><span class="{_list._new_row.CSS}">{_list._new_row.STATUS}</span></td>
</tr>
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_EXPIRED}</td>
</tr>
<!-- BEGIN _old_row -->
<tr>
	<td class="{_list._old_row.CLASS}">{_list._old_row.GAME} {_list._old_row.NAME}</td>
	<td class="{_list._old_row.CLASS}">{_list._old_row.DATE}</td>
	<td class="{_list._old_row.CLASS}"><span class="{_list._old_row.CSS}">{_list._old_row.STATUS}</span></td>
</tr>
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END _list -->