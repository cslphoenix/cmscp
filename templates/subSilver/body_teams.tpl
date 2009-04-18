<!-- BEGIN select -->
<table class="info" width="100%" cellspacing="0">
<!-- BEGIN game_row -->
<tr>
	<td colspan="5" class="info_head">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="20">{select.game_row.GAME_IMAGE}</td>
			<td>{select.game_row.L_GAME_NAME}</td>
		</tr>
		</table>
	</td>
</td>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td class="{select.game_row.team_row.CLASS}" align="left" style="vertical-align:middle">{select.game_row.team_row.TEAM_NAME}</td>
	<td class="{select.game_row.team_row.CLASS}" align="right" nowrap="nowrap" width="1%">{select.game_row.team_row.TEAM_FIGHTUS}</td>
	<td class="{select.game_row.team_row.CLASS}" align="right" nowrap="nowrap" width="1%">{select.game_row.team_row.TEAM_JOINUS}</td>
	<td class="{select.game_row.team_row.CLASS}" align="right" nowrap="nowrap" width="1%">{select.game_row.team_row.TEAM_MATCH}</td>
	<td class="{select.game_row.team_row.CLASS}" align="right" nowrap="nowrap" width="1%"><a href="{select.game_row.team_row.TO_TEAM}">{L_TO_TEAM}</a></td>
</tr>
<!-- END team_row -->
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_entry_team -->
<tr>
	<td class="row1" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_team -->
</table>
<!-- END select -->

<!-- BEGIN details -->
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
	<!-- BEGIN switch_admin_option -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_admin_option -->
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
	<!-- BEGIN switch_mod_option -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_mod_option -->
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
<!-- END details -->