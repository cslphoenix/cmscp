<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head"c colspan="4">{L_UPCOMING}</td>
</tr>
<!-- BEGIN training_row_new -->
<tr>
	<td class="{training_row_new.CLASS}" align="center" width="1%">{training_row_new.TRAINING_GAME}</td>
	<td class="{training_row_new.CLASS}" align="left" width="100%">{training_row_new.TRAINING_NAME}</td>
	<td class="{training_row_new.CLASS}" align="center" nowrap>{training_row_new.TRAINING_DATE}</td>
	<td class="{training_row_new.CLASS}" align="center" width="1%"><a href="{training_row_new.U_DETAILS}">{L_DETAILS}</a></td>
</tr>
<!-- END training_row_new -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
</table>

<br />

<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="4">{L_EXPIRED}</td>
</tr>
<!-- BEGIN training_row_old -->
<tr>
	<td class="{training_row_old.CLASS}" align="center" width="1%">{training_row_old.TRAINING_GAME}</td>
	<td class="{training_row_old.CLASS}" align="left" width="100%">{training_row_old.TRAINING_NAME}</td>
	<td class="{training_row_old.CLASS}" align="center" nowrap>{training_row_old.TRAINING_DATE}</td>
	<td class="{training_row_old.CLASS}" align="center" width="1%"><a href="{training_row_old.U_DETAILS}">{L_DETAILS}</a></td>
</tr>
<!-- END training_row_old -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<br />

<div align="center">
	<table class="info" width="60%" cellspacing="0">
	<tr>
		<td colspan="5" class="info_head" >{L_TEAMS}</td>
	</tr>
	<!-- BEGIN teams_row -->
	<tr>
		<td class="{teams_row.CLASS}" align="center" width="1%">{teams_row.TEAM_GAME}</td>
		<td class="{teams_row.CLASS}" align="left" style="vertical-align:middle">{teams_row.TEAM_NAME}</td>
		<td class="{teams_row.CLASS}" align="right" width="1%" nowrap><a href="{teams_row.ALL_MATCHES}">{L_ALL_MATCHES}</a></td>
		<td class="{teams_row.CLASS}" align="right" width="1%" nowrap><a href="{teams_row.TO_TEAM}">{L_TO_TEAM}</a></td>
	</tr>
	<!-- END teams_row -->
	<!-- BEGIN no_entry_team -->
	<tr>
		<td class="row1" align="center" colspan="4">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry_team -->
	</table>
</div>