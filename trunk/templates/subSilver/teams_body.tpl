<table class="info" width="100%" cellspacing="0">


<!-- BEGIN game_row -->
<tr>
	<td colspan="5" class="info_head">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="20">{game_row.GAME_IMAGE}</td>
			<td>{game_row.L_GAME_NAME}</td>
		</tr>
		</table>
	</td>
</td>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td class="{game_row.team_row.CLASS}" align="left" style="vertical-align:middle">{game_row.team_row.TEAM_NAME}</td>
	<td class="{game_row.team_row.CLASS}" align="right" nowrap>{game_row.team_row.TEAM_FIGHTUS}</td>
	<td class="{game_row.team_row.CLASS}" align="right" nowrap>{game_row.team_row.TEAM_JOINUS}</td>
	<td class="{game_row.team_row.CLASS}" align="right" nowrap>{game_row.team_row.TEAM_MATCH}</td>
	<td class="{game_row.team_row.CLASS}" align="right" nowrap><a href="{game_row.team_row.TO_TEAM}">{L_TO_TEAM}</a></td>
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