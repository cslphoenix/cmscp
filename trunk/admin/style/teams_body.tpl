<form method="post" action="{S_TEAM_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE}</th>
</tr>
<tr>
	<td>{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_TEAM_NAME}</td>
	<td class="rowHead" nowrap="nowrap">{L_TEAM_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4">{L_TEAM_SETTINGS}</td>
</tr>
<!-- BEGIN teams_row -->
<tr>
	<td class="row_class1" align="center">{teams_row.TEAM_GAME}</td>
	<td class="row_class1" align="left" width="100%">{teams_row.TEAM_NAME}</td>
	<td class="row_class1" align="center" width="1%">{teams_row.TEAM_MEMBER_COUNT}</td>
	<td class="row_class2" align="center" width="1%"><a href="{teams_row.U_MEMBER}">{L_TEAM_MEMBER}</a></td>
	<td class="row_class2" align="center" width="1%"><a href="{teams_row.U_EDIT}">{L_TEAM_SETTING}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{teams_row.U_MOVE_UP}">{teams_row.ICON_UP}</a> <a href="{teams_row.U_MOVE_DOWN}">{teams_row.ICON_DOWN}</a></td>
	<td class="row_class2" align="center" width="1%"><a href="{teams_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END teams_row -->
<!-- BEGIN no_teams -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
</tr>
<!-- END no_teams -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="team_name" type="text" value=""></td>
	<td><input class="button" type="submit" name="add" value="{L_TEAM_CREATE}" /></td>
</tr>
</table>
</form>