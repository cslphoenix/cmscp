<!-- BEGIN list -->
<table class="match" width="100%" cellspacing="0">
<tr>
	<td class="header" colspan="3">{L_UPCOMING}</td>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td class="{list.new_row.CLASS}">{list.new_row.GAME} {list.new_row.NAME}</td>
	<td class="{list.new_row.CLASS}">{list.new_row.DATE}</td>
	<td class="{list.new_row.CLASS}"><span class="{list.new_row.CSS}">{list.new_row.STATUS}</span></td>
</tr>
<!-- END new_row -->
<!-- BEGIN entry_empty_new -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
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
	<td class="{list.old_row.CLASS}">{list.old_row.GAME} {list.old_row.NAME}</td>
	<td class="{list.old_row.CLASS}">{list.old_row.DATE}</td>
	<td class="{list.old_row.CLASS}"><span class="{list.old_row.CSS}">{list.old_row.STATUS}</span></td>
</tr>
<!-- END old_row -->
<!-- BEGIN entry_empty_old -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
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