<table class="info" width="100%" cellspacing="0">


<!-- BEGIN game_row -->
<tr>
	<td colspan="5" class="info_head">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="20">{gamerow.GAME_IMAGE}</td>
			<td>{gamerow.L_GAME_NAME}</td>
		</tr>
		</table>
	</td>
</td>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td class="{gamerow.teamrow.CLASS}" align="left" style="vertical-align:middle">{gamerow.teamrow.TEAM_NAME}</td>
	<td class="{gamerow.teamrow.CLASS}" align="right" nowrap="nowrap">{gamerow.teamrow.TEAM_FIGHTUS}</td>
	<td class="{gamerow.teamrow.CLASS}" align="right" nowrap="nowrap">{gamerow.teamrow.TEAM_JOINUS}</td>
	<td class="{gamerow.teamrow.CLASS}" align="right" nowrap="nowrap">{gamerow.teamrow.TEAM_MATCH}</td>
	<td class="{gamerow.teamrow.CLASS}" align="right" nowrap="nowrap"><a href="{gamerow.teamrow.TO_TEAM}">{L_TO_TEAM}</a></td>
</tr>
<!-- END team_row -->
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_entry_team -->
<tr>
	<td class="row1" align="center" colspan="5">{L_EMPTY}</td>
</tr>
<!-- END no_entry_team -->
</table>