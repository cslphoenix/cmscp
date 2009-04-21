<!-- BEGIN select -->
<table class="out" width="100%" cellspacing="0">
<!-- BEGIN joined -->
<!-- BEGIN member -->
<tr>
	<td class="info_head" colspan="3">{L_YOU_BELONG_GROUPS}</td>
</tr>
<!-- BEGIN grouprow -->
<tr>
	<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.joined.member.grouprow.GROUP_NAME}" title="{select.joined.member.grouprow.GROUP_NAME}" /></td>
	<td class="row1" height="50">
		<span class="forumlink">
			<a href="{select.joined.member.grouprow.U_GROUP}" class="forumlink">{select.joined.member.grouprow.GROUP_NAME}</a><br>
			</span> <span class="genmed">{select.joined.member.grouprow.GROUP_DESC}</span>
	</td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.joined.member.grouprow.GROUP_TYPE}</span></td>
</tr>
<!-- END grouprow -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END member -->
<!-- BEGIN pending -->
<tr>
	<td class="info_head" colspan="3">{L_PENDING_GROUPS}</td>
</tr>
<!-- BEGIN grouprow -->
<tr>
	<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.joined.pending.grouprow.GROUP_NAME}" title="{select.joined.pending.grouprow.GROUP_NAME}" /></td>
	<td class="row1" height="50">
		<span class="forumlink">
			<a href="{select.joined.pending.grouprow.U_GROUP}" class="forumlink">{select.joined.pending.grouprow.GROUP_NAME}</a><br>
			</span> <span class="genmed">{select.joined.pending.grouprow.GROUP_DESC}
		</span>
	</td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.joined.pending.grouprow.GROUP_TYPE}</span></td>
</tr>
<!-- END grouprow -->
<!-- END pending -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END joined -->
<!-- BEGIN remaining -->
<tr>
	<td class="info_head" colspan="3">{L_SELECT_A_GROUP}</td>
</tr>
      <!-- BEGIN grouprow -->
	  <tr>
		<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.remaining.grouprow.GROUP_NAME}" title="{select.remaining.grouprow.GROUP_NAME}" /></td>
	   <td class="row1" height="50"><span class="forumlink">
 			<a href="{select.remaining.grouprow.U_GROUP}" class="forumlink">{select.remaining.grouprow.GROUP_NAME}</a><br>
		  </span> <span class="genmed">{select.remaining.grouprow.GROUP_DESC}
		  </span>
	   </td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.remaining.grouprow.GROUP_TYPE}</span></td>
	  </tr>
      <!-- END grouprow -->
  <!-- END remaining -->
</table>
<!-- END select -->

<!-- BEGIN details -->
<form action="{S_GROUPS_ACTION}" method="post">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="7">{L_GROUP_INFORMATION}</td>
</tr>
<tr>
	<td class="row1" width="25%">{L_GROUP_NAME}:</td>
	<td class="row2" width="75%"><b>{GROUP_NAME}</b></td>
</tr>
<tr> 
	<td class="row1">{L_GROUP_DESC}:</td>
	<td class="row2">{GROUP_DESC}</td>
</tr>
<tr> 
	<td class="row1" width="20%">{L_GROUP_MEMBERSHIP}:</td>
	<td class="row2">
		{GROUP_DETAILS} &nbsp;&nbsp;
		<!-- BEGIN switch_subscribe_group_input -->
		<input class="button2" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
		<!-- END switch_subscribe_group_input -->
		<!-- BEGIN switch_unsubscribe_group_input -->
		<input class="button2" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
		<!-- END switch_unsubscribe_group_input -->
		
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
	<th colspan="6">{L_GROUP_MODERATOR}</th>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_POSTS}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<!-- BEGIN switch_h_admin_option -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_h_admin_option -->
</tr>
<!-- BEGIN mod_row -->
<tr>
	<td class="{details.mod_row.ROW_CLASS}" align="center"><a href="{details.mod_row.U_VIEWPROFILE}">{details.mod_row.USERNAME}</a></td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.PM_IMG} </td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.POSTS}</td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.EMAIL_IMG}</td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.JOINED}</td>
	<!-- BEGIN switch_admin_option -->
	<td class="{details.mod_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{details.mod_row.USER_ID}" /></td>
	<!-- END switch_admin_option -->
</tr>
<!-- END mod_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td class="row3" colspan="6" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<th colspan="6">{L_GROUP_MEMBERS}</th>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_POSTS}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<!-- BEGIN switch_h_mod_option -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_h_mod_option -->
</tr>

<!-- BEGIN member_row -->
<tr>
	<td class="{details.member_row.ROW_CLASS}" align="center"><a href="{member_row.U_VIEWPROFILE}">{details.member_row.USERNAME}</a></td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.PM_IMG} </td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.POSTS}</td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.EMAIL_IMG}</td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.JOINED}</td>
	<!-- BEGIN switch_mod_option -->
	<td class="{details.member_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{details.member_row.USER_ID}" /></td>
	<!-- END switch_mod_option -->
</tr>
<!-- END member_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td class="row3" colspan="6" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN switch_hidden_group -->
<tr>
	<td class="row1" colspan="6" align="center"><span class="gen">{L_HIDDEN_MEMBERS}</span></td>
</tr>
<!-- END switch_hidden_group -->
</table>
<!-- BEGIN switch_add_member -->
<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left" colspan="3">{S_SELECT_USERS} <input type="submit" name="add" value="{L_ADD_MEMBER}" class="button2" /></td>
	<td align="right" colspan="3">{S_SELECT_OPTION} <input type="submit" value="Absenden" class="button2" /></td>
</tr>
</table>
<!-- END switch_add_member -->

<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left">{PAGE_NUMBER}</td>
	<td align="right">{PAGINATION}</td>
</tr>
</table>

<!-- BEGIN pending -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<th colspan="6">{L_PENDING_MEMBERS}</th>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_POSTS}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center"><a href="{details.pending.pending_row.U_VIEWPROFILE}">{details.pending.pending_row.USERNAME}</a></td>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center">{details.pending.pending_row.PM_IMG}</td>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center">{details.pending.pending_row.POSTS}</td>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center">{details.pending.pending_row.EMAIL_IMG}</td>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center">{details.pending.pending_row.JOINED}</td>
	<td class="{details.pending.pending_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="pending_members[]" value="{details.pending.pending_row.USER_ID}" checked="checked" /></td>
</tr>
<!-- END pending_row -->
<tr>
	<td class="row3" colspan="6" align="right"><input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="button2" />&nbsp;<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="button" /></td>
	</tr>
</table>
<!-- END pending -->
{S_HIDDEN_FIELDS}
</form>
<!-- END details -->