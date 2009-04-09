<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<td class="info_box" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_box" style="text-align:center;">{L_PM}</td>
	<td class="info_box" style="text-align:center;">{L_POSTS}</td>
	<td class="info_box" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_box" style="text-align:center;">{L_JOINED}</td>
	<td class="info_box" style="text-align:center;">{L_SELECT}</td>
</tr>
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<td class="info_head" colspan="6">{L_PENDING_MEMBERS}</td>
</tr>
<!-- BEGIN pending_members_row -->
<tr>
	<td class="{pending_members_row.ROW_CLASS}" align="center"><a href="{pending_members_row.U_VIEWPROFILE}">{pending_members_row.USERNAME}</a></td>
	<td class="{pending_members_row.ROW_CLASS}" align="center"> {pending_members_row.PM_IMG}</td>
	<td class="{pending_members_row.ROW_CLASS}" align="center">{pending_members_row.POSTS}</td>
	<td class="{pending_members_row.ROW_CLASS}" align="center">{pending_members_row.EMAIL_IMG}</td>
	<td class="{pending_members_row.ROW_CLASS}" align="center">{pending_members_row.JOINED}</td>
	<td class="{pending_members_row.ROW_CLASS}" align="center"><input type="checkbox" name="pending_members[]" value="{pending_members_row.USER_ID}" checked="checked" /></td>
</tr>
<!-- END pending_members_row -->
<tr>
	<td class="cat" colspan="8" align="right"><input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="mainoption" />
		&nbsp; 
		<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="liteoption" />
		</span></td>
	</tr>
</table>
