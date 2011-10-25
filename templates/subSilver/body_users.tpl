<!-- BEGIN list -->
<form action="{S_ACTION}" method="post" name="post">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<tr> 
	<th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
	<!-- BEGIN _groups -->
	<th class="thTop" nowrap="nowrap">Benutzergruppen</th>
	<!-- END _groups -->
	<!-- BEGIN _teams -->
	<th class="thTop" nowrap="nowrap">Teams</th>
	<!-- END _teams -->
</tr>
<!-- BEGIN _row -->
<tr> 
	<td class="{list._row.ROW_CLASS}" align="center">{list._row.USERNAME}</td>
	<!-- BEGIN _groups -->
	<td class="{list._row.ROW_CLASS}" align="left" valign="middle">{list._row.GROUPS}</td>
	<!-- END _groups -->
	<!-- BEGIN _teams -->
	<td class="{list._row.ROW_CLASS}" align="left" valign="middle">{list._row.TEAMS}</td>
	<!-- END _teams -->
</tr>
<!-- END _row -->
<!-- BEGIN _entry_empty -->
<tr> 
	<td class="row1" align="center" colspan="9"><span class="gen">&nbsp;{NO_USER_ID_SPECIFIED}&nbsp;</span></td>
</tr>
<!-- END _entry_empty -->
<tr>
	<td colspan="3" align="right">{S_LETTER_SELECT} {S_MODE} {S_ORDER} <input type="submit" name="submit" value="{L_SUBMIT}" class="button2" /></td>
</tr>
<tr> 
	<td class="catBottom" colspan="8" height="28">&nbsp;</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr> 
<td><span class="nav">{PAGE_NUMBER}{PAGINATION}</span></td>
</tr>
</table>

{S_LETTER_HIDDEN}
</form>
<!-- END list -->

<!-- BEGIN _block -->
<table>
<tr> 
	<td>test</td>
</tr>
</table>

<table border="0">
<tr>
	<td class="header" colspan="6">{L_GROUP_MODERATOR}</td>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_GROUPS}</td>
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
</tr>
<!-- BEGIN _mod -->
<tr>
	<td class="{_block._mod.ROW_CLASS}" align="center">{_block._mod.USERNAME}</td>
	<!-- BEGIN _switch_admin -->
	<td class="{_block._mod.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_block._mod.USER_ID}"></td>
	<!-- END _switch_admin -->
</tr>
<!-- END _mod -->
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
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<!-- BEGIN _switch_moderator -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END _switch_moderator -->
</tr>

<!-- BEGIN _member_row -->
<tr>
	<td class="{_block._member_row.ROW_CLASS}" align="center"><a href="{_block._member_row.U_VIEWPROFILE}">{_block._member_row.USERNAME}</a></td>
	<td class="{_block._member_row.ROW_CLASS}" align="center">{_block._member_row.PM_IMG} </td>
	<td class="{_block._member_row.ROW_CLASS}" align="center">{_block._member_row.EMAIL_IMG}</td>
	<td class="{_block._member_row.ROW_CLASS}" align="center">{_block._member_row.JOINED}</td>
	<!-- BEGIN _switch_moderator -->
	<td class="{_block._member_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_block._member_row.USER_ID}"></td>
	<!-- END _switch_moderator -->
</tr>
<!-- END _member_row -->
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
<!-- END _block -->

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

<!-- BEGIN _listg -->
<table class="out" width="100%" cellspacing="0">
<!-- BEGIN _in_groups -->
<!-- BEGIN _is_member -->
<tr>
	<td class="info_head" colspan="2">{L_CUR}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_listg._in_groups._is_member._row.NAME}{_listg._in_groups._is_member._row.DESC}</td>
	<td>{_listg._in_groups._is_member._row.TYPE}</td>
</tr>
<!-- END _row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END _is_member -->
<!-- BEGIN _is_pending -->
<tr>
	<td class="info_head" colspan="2">{L_PEN}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_listg._in_groups._is_pending._row.NAME}{_listg._in_groups._is_pending._row.DESC}</td>
	<td>{_listg._in_groups._is_pending._row.TYPE}</td>
</tr>
<!-- END _row -->
<!-- END pending -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END _in_groups -->
<!-- BEGIN _no_group -->
<tr>
	<td class="info_head" colspan="2">{L_NON}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_listg._no_group._row.NAME}{_listg._no_group._row.DESC}</td>
	<td>{_listg._no_group._row.TYPE}</td>
</tr>
<!-- END _row -->
<!-- END _no_group -->
</table>
<!-- END _listg -->

<!-- BEGIN _listt -->
<table class="info" width="100%" cellspacing="0">
<!-- BEGIN _game_row -->
<tr>
	<td colspan="5" class="info_head">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>{_listt._game_row.L_GAME}</td>
		</tr>
		</table>
	</td>
</td>
</tr>
<!-- BEGIN _team_row -->
<tr>
	<td>{_listt._game_row._team_row.GAME} {_listt._game_row._team_row.NAME}</td>
	<td>{_listt._game_row._team_row.FIGHTUS}</td>
	<td>{_listt._game_row._team_row.JOINUS}</td>
	<td>{_listt._game_row._team_row.MATCH}</td>
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
<!-- END _listt -->