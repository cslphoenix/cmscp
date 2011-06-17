<!-- BEGIN _list -->
<table class="info" width="100%" cellspacing="0">
<!-- BEGIN _game_row -->
<tr>
	<td colspan="5" class="info_head">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>{_list._game_row.L_GAME}</td>
		</tr>
		</table>
	</td>
</td>
</tr>
<!-- BEGIN _team_row -->
<tr>
	<td>{_list._game_row._team_row.GAME} {_list._game_row._team_row.NAME}</td>
	<td>{_list._game_row._team_row.FIGHTUS}</td>
	<td>{_list._game_row._team_row.JOINUS}</td>
	<td>{_list._game_row._team_row.MATCH}</td>
</tr>
<!-- END _team_row -->
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<!-- END _game_row -->
<!-- BEGIN no_entry_team -->
<tr>
	<td class="row1" align="center" colspan="5">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry_team -->
</table>
<!-- END _list -->

<!-- BEGIN details -->
<form action="{S_GROUPS_ACTION}" method="post" name="post">
<table class="info" width="100%" cellspacing="0">
<tr>
	<td colspan="6" class="info_head">{L_TEAM_VIEW}</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td colspan="6">Teaminfos wie wars und trains und krams ^^, aber später</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="info_head" colspan="6">{L_TEAM_MODERATOR}</td>
</tr>
<!-- BEGIN mod_row -->
<tr>
	<td class="{details.mod_row.ROW_CLASS}" align="center"><a href="{details.mod_row.U_VIEWPROFILE}">{details.mod_row.USERNAME}</a></td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.PM_IMG} </td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.POSTS}</td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.EMAIL_IMG}</td>
	<td class="{details.mod_row.ROW_CLASS}" align="center">{details.mod_row.JOINED}</td>
	<!-- BEGIN switch_mod_option -->
	<td class="{details.mod_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{details.mod_row.USER_ID}"></td>
	<!-- END switch_mod_option -->
</tr>
<!-- END mod_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td class="row3" colspan="6" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td class="info_head" colspan="6">{L_TEAM_MEMBERS}</td>
</tr>
<!-- BEGIN member_row -->
<tr>
	<td class="{details.member_row.ROW_CLASS}" align="center"><a href="{member_row.U_VIEWPROFILE}">{details.member_row.USERNAME}</a></td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.PM_IMG} </td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.POSTS}</td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.EMAIL_IMG}</td>
	<td class="{details.member_row.ROW_CLASS}" align="center">{details.member_row.JOINED}</td>
	<!-- BEGIN switch_mod_option -->
	<td class="{details.member_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{details.member_row.USER_ID}"></td>
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
	<td align="left" colspan="3">{S_SELECT_USERS} <input type="submit" name="add" value="{L_ADD_MEMBER}" class="button2"></td>
	<td align="right" colspan="3">{S_SELECT_OPTION} <input type="submit" value="Absenden" class="button2"></td>
</tr>
</table>
<!-- END switch_mod_option -->

<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left">{PAGE_NUMBER}</td>
	<td align="right">{PAGINATION}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END details -->