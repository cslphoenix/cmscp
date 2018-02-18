<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="4">{L_UPCOMING}</td>
</tr>
<!-- BEGIN match_row_n -->
<tr>
	<td class="{match_row_n.CLASS}" align="center" width="1%">{match_row_n.MATCH_GAME}</td>
	<td class="{match_row_n.CLASS}" align="left" width="100%">{match_row_n.MATCH_NAME}</td>
	<td class="{match_row_n.CLASS}" align="center" nowrap="nowrap">{match_row_n.MATCH_DATE}</td>
	<td class="{match_row_n.CLASS}" align="center" width="1%"><a href="{match_row_n.U_DETAILS}">{L_DETAILS}</a></td>
</tr>
<!-- END match_row_n -->
<!-- BEGIN no_entry_n -->
<tr>
	<td class="noentry" align="center" colspan="4">{L_NONE}</td>
</tr>
<!-- END no_entry_n -->
</table>

<br>

<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="4">{L_EXPIRED}</td>
</tr>
<!-- BEGIN match_row_o -->
<tr>
	<td class="{match_row_o.CLASS}" align="center" width="1%">{match_row_o.MATCH_GAME}</td>
	<td class="{match_row_o.CLASS}" align="left" width="100%">{match_row_o.MATCH_NAME}</td>
	<td class="{match_row_o.CLASS}" align="center" nowrap="nowrap">{match_row_o.MATCH_DATE}</td>
	<td class="{match_row_o.CLASS}" align="center" width="1%"><a href="{match_row_o.U_DETAILS}">{L_DETAILS}</a></td>
</tr>
<!-- END match_row_o -->
<!-- BEGIN no_entry_o -->
<tr>
	<td class="noentry" align="center" colspan="4">{L_NONE}</td>
</tr>
<!-- END no_entry_o -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<br>

<div align="center">
	<table class="info" width="60%" cellspacing="0">
	<tr>
		<td colspan="5" class="info_head" >{L_TEAMS}</td>
	</tr>
	<!-- BEGIN teams_row -->
	<tr>
		<td class="{teamsrow.CLASS}" align="center" width="1%">{teamsrow.TEAM_GAME}</td>
		<td class="{teamsrow.CLASS}" align="left" style="vertical-align:middle">{teamsrow.TEAM_NAME}</td>
		<td class="{teamsrow.CLASS}" align="right" width="1%" nowrap="nowrap">{teamsrow.FIGHTUS}</td>
		<td class="{teamsrow.CLASS}" align="right" width="1%" nowrap="nowrap">{teamsrow.ALL_MATCHES}</td>
		<td class="{teamsrow.CLASS}" align="right" width="1%" nowrap="nowrap"><a href="{teamsrow.TO_TEAM}">{L_TO_TEAM}</a></td>
	</tr>
	<!-- END teams_row -->
	<!-- BEGIN no_entry_team -->
	<tr>
		<td class="row1" align="center" colspan="5">{L_NONE}</td>
	</tr>
	<!-- END no_entry_team -->
	</table>
</div>