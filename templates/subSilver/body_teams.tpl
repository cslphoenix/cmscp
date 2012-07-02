<!-- BEGIN list -->
<table class="teams">
<!-- BEGIN game_row -->
<tr>
	<td colspan="5" class="header">{_list._gamerow.L_GAME}</td>
</td>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td>{_list._gamerow._teamrow.GAME} {_list._gamerow._teamrow.NAME}</td>
	<td>{_list._gamerow._teamrow.FIGHTUS}</td>
	<td>{_list._gamerow._teamrow.JOINUS}</td>
	<td>{_list._gamerow._teamrow.MATCH}</td>
</tr>
<!-- END team_row -->
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<!-- END game_row -->
</table>
<!-- END list -->

<!-- BEGIN view -->
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
	<td class="{_view.modrow.ROW_CLASS}" align="center"><a href="{_view.modrow.U_VIEWPROFILE}">{_view.modrow.USERNAME}</a></td>
	<td class="{_view.modrow.ROW_CLASS}" align="center">{_view.modrow.PM_IMG} </td>
	<td class="{_view.modrow.ROW_CLASS}" align="center">{_view.modrow.POSTS}</td>
	<td class="{_view.modrow.ROW_CLASS}" align="center">{_view.modrow.EMAIL_IMG}</td>
	<td class="{_view.modrow.ROW_CLASS}" align="center">{_view.modrow.JOINED}</td>
	<!-- BEGIN switch_mod_option -->
	<td class="{_view.modrow.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view.modrow.USER_ID}"></td>
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
	<td class="{_view.memberrow.ROW_CLASS}" align="center"><a href="{memberrow.U_VIEWPROFILE}">{_view.memberrow.USERNAME}</a></td>
	<td class="{_view.memberrow.ROW_CLASS}" align="center">{_view.memberrow.PM_IMG} </td>
	<td class="{_view.memberrow.ROW_CLASS}" align="center">{_view.memberrow.POSTS}</td>
	<td class="{_view.memberrow.ROW_CLASS}" align="center">{_view.memberrow.EMAIL_IMG}</td>
	<td class="{_view.memberrow.ROW_CLASS}" align="center">{_view.memberrow.JOINED}</td>
	<!-- BEGIN switch_mod_option -->
	<td class="{_view.memberrow.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view.memberrow.USER_ID}"></td>
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
<!-- END view -->