<form action="{S_GROUPS_ACTION}" method="post">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="7">{L_GROUP_INFORMATION}</td>
</tr>
<tr>
	<td class="row1" width="20%"><span class="gen">{L_GROUP_NAME}:</span></td>
	<td class="row2"><span class="gen"><b>{GROUP_NAME}</b></span></td>
</tr>
<tr> 
	<td class="row1" width="20%"><span class="gen">{L_GROUP_DESC}:</span></td>
	<td class="row2"><span class="gen">{GROUP_DESC}</span></td>
</tr>
<tr> 
	<td class="row1" width="20%"><span class="gen">{L_GROUP_MEMBERSHIP}:</span></td>
	<td class="row2">
		<span class="gen">{GROUP_DETAILS} &nbsp;&nbsp;
		<!-- BEGIN switch_subscribe_group_input -->
		<input class="button2" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
		<!-- END switch_subscribe_group_input -->
		<!-- BEGIN switch_unsubscribe_group_input -->
		<input class="button2" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
		<!-- END switch_unsubscribe_group_input -->
		</span>
	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}

</form>

<form action="{S_GROUPS_ACTION}" method="post" name="post">
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
	<!-- BEGIN switch_mod_option -->
	<td class="info_box" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_mod_option -->
</tr>
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<td class="info_head" colspan="6">{L_GROUP_MODERATOR}</td>
</tr>
<!-- BEGIN mod_row -->
<tr>
	<td class="{mod_row.ROW_CLASS}" align="center"><span class="gen"><a href="{mod_row.U_VIEWPROFILE}" class="gen">{mod_row.USERNAME}</a></span></td>
	<td class="{mod_row.ROW_CLASS}" align="center"> {mod_row.PM_IMG} </td>
	<td class="{mod_row.ROW_CLASS}" align="center"><span class="gen">{mod_row.POSTS}</span></td>
	<td class="{mod_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{mod_row.EMAIL_IMG}</span></td>
	<td class="{mod_row.ROW_CLASS}" align="center"> {mod_row.JOINED}</td>
	<td class="{mod_row.ROW_CLASS}" align="center">
		<!-- BEGIN switch_admin_option -->
		<input type="checkbox" name="members[]" value="{mod_row.USER_ID}" />
		<!-- END switch_admin_option -->
	</td>
</tr>
<!-- END mod_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td colspan="6" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<td class="info_head" colspan="6">{L_GROUP_MEMBERS}</td>
</tr>
<!-- BEGIN member_row -->
<tr>
	<td class="{member_row.ROW_CLASS}" align="center"><span class="gen"><a href="{member_row.U_VIEWPROFILE}" class="gen">{member_row.USERNAME}</a></span></td>
	<td class="{member_row.ROW_CLASS}" align="center"> {member_row.PM_IMG} </td>
	<td class="{member_row.ROW_CLASS}" align="center"><span class="gen">{member_row.POSTS}</span></td>
	<td class="{member_row.ROW_CLASS}" align="center" valign="middle"><span class="gen">{member_row.EMAIL_IMG}</span></td>
	<td class="{member_row.ROW_CLASS}" align="center"> {member_row.JOINED}</td>
	<td class="{member_row.ROW_CLASS}" align="center">
		<!-- BEGIN switch_mod_option -->
		<input type="checkbox" name="members[]" value="{member_row.USER_ID}" />
		<!-- END switch_mod_option -->
	</td>
</tr>
<!-- END member_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td colspan="6" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN switch_hidden_group -->
<tr>
	<td class="row1" colspan="6" align="center"><span class="gen">{L_HIDDEN_MEMBERS}</span></td>
</tr>
<!-- END switch_hidden_group -->
</table>
<!-- BEGIN switch_mod_option -->
<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left" colspan="3">{S_SELECT_USERS} <input type="submit" name="add" value="{L_ADD_MEMBER}" class="button2" /></td>
	<td align="right" colspan="3">{S_SELECT_OPTION} <input type="submit" value="Absenden" class="button2" /></td>
</tr>
</table>
<!-- END switch_mod_option -->

<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left">{PAGE_NUMBER}</td>
	<td align="right">{PAGINATION}</td>
</tr>
</table>

{PENDING_USER_BOX}

{S_HIDDEN_FIELDS}</form>
