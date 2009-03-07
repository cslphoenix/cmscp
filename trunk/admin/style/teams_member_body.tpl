<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE} - {L_TEAM_MEMBER}</th>
</tr>
<tr>
	<td>{L_TEAM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="center">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">{L_JOIN}</td>
	<td class="rowHead" align="center">{L_RANK}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN members_row -->
<tr>
	<td class="{members_row.CLASS}" align="left">{members_row.USERNAME}</td>
	<td class="{members_row.CLASS}" align="center">{members_row.REGISTER}</td>
	<td class="{members_row.CLASS}" align="center">{members_row.JOINED}</td>
	<td class="{members_row.CLASS}" align="left">{members_row.RANK}</td>
	<td class="{members_row.CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{members_row.USER_ID}" /></td>
</tr>
<!-- END members_row -->
<!-- BEGIN no_members_row -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_TEAMS}</td>
</tr>
<!-- END no_members_row -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		{S_ACTION_OPTIONS}
		<input type="submit" name="send" value="{L_SUBMIT}" class="button" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<form action="{S_TEAM_ACTION}" method="post" name="post" id="list">
<table class="head" cellspacing="0">
<tr>
	<th>{L_TEAM_TITLE} - {L_TEAM_ADD_MEMBER}</th>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>

<table class="edit" cellspacing="1">
	<tr>
		<td class="row1" width="40%"><b>{L_RANK}:</b></td>
		<td class="row3" width="60%">{S_RANK_SELECT}</td>
	</tr>
	<tr>
		<td class="row1" valign="top"><b>{L_TEAM_ADD}:</b><br /><span class="small">{L_TEAM_ADD_MEMBER_EX}</span></td>
		<td class="row3"><textarea class="textarea" name="members" cols="80" rows="5"></textarea></td>
	</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td align="right"><input type="submit" name="send" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>