<form method="post" action="{S_MATCH_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_MATCH_TITLE}</th>
</tr>
<tr>
	<td>{L_MATCH_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_MATCH_NAME}</td>
	<td class="rowHead">{L_TRAINING}</td>
	<td class="rowHead" colspan="3">{L_MATCH_SETTINGS}</td>
</tr>
<tr>
		<td class="rowHead" colspan="7">{L_UPCOMING}</td>
	</tr>
<!-- BEGIN match_row_n -->
<tr>
	<td class="{match_row_n.CLASS}" align="center" width="1%">{match_row_n.MATCH_GAME}</td>
	<td class="{match_row_n.CLASS}" align="left" width="100%">{match_row_n.MATCH_NAME}</td>
	<td class="{match_row_n.CLASS}" align="center" nowrap>{match_row_n.MATCH_DATE}</td>
	<td class="{match_row_n.CLASS}" align="center"><a href="{match_row_n.U_TRAINING}">{match_row_n.TRAINING}</a></td>
	<td class="{match_row_n.CLASS}" align="center" width="1%"><a href="{match_row_n.U_EDIT}">{L_MATCH_SETTING}</a></td>
	<td class="{match_row_n.CLASS}" align="center" width="1%"><a href="{match_row_n.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	
	<td class="{match_row_n.CLASS}" align="center" width="1%"><a href="{match_row_n.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_n -->
<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	<tr>
		<td class="rowHead" colspan="7">{L_EXPIRED}</td>
	</tr>
<!-- BEGIN match_row_o -->
<tr>
	<td class="{match_row_o.CLASS}" align="center" width="1%">{match_row_o.MATCH_GAME}</td>
	<td class="{match_row_o.CLASS}" align="left" width="100%">{match_row_o.MATCH_NAME}</td>
	<td class="{match_row_o.CLASS}" align="center" nowrap>{match_row_n.MATCH_DATE}</td>
	<td class="{match_row_o.CLASS}" align="center"> - </td>
	<td class="{match_row_o.CLASS}" align="center" width="1%"><a href="{match_row_o.U_EDIT}">{L_MATCH_SETTING}</a></td>
	<td class="{match_row_o.CLASS}" align="center" width="1%"><a href="{match_row_o.U_DETAILS}">{L_MATCH_DETAILS}</a></td>
	
	<td class="{match_row_o.CLASS}" align="center" width="1%"><a href="{match_row_o.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END match_row_o -->
<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right">{S_TEAMS}</td>
	<td><input class="button" type="submit" name="add" value="{L_MATCH_CREATE}" /></td>
</tr>
</table>
</form>