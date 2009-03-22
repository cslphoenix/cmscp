<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="4">Wars von {TEAM_NAME}</td>
</tr>
<!-- BEGIN match_teams -->
<tr>
	<td class="{match_teams.CLASS}" align="center" width="1%">{match_teams.MATCH_GAME}</td>
	<td class="{match_teams.CLASS}" align="left" width="100%">{match_teams.MATCH_NAME}</td>
	<td class="{match_teams.CLASS}" align="center" nowrap>{match_teams.MATCH_DATE}</td>
	<td class="{match_teams.CLASS}" align="center" width="1%"><a href="{match_teams.U_DETAILS}">{L_DETAILS}</a></td>
</tr>
<!-- END match_teams -->
<!-- BEGIN no_entry -->
<tr>
	<td class="noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
<tr>
	<td align="left" colspan="4"><span style="float:right;">{PAGE_NUMBER}</span>{PAGINATION}</td>
</tr>
</table>







