<form method="post" action="{S_TEAM_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_SERVER_TITLE}a</th>
	</tr>
	<tr>
		<td>{L_SERVER_EXPLAIN}b</td>
	</tr>
	</table>
	
	<br />
	
	<table class="row" cellspacing="1">
	<tr>
		<td class="rowHead">{L_SERVER_PAGE}</td>
		<td class="rowHead" colspan="3">{L_SETTINGS}</td>
	</tr>
	<!-- BEGIN game_row -->
	<tr>
		<td class="{game_row.CLASS}" align="left">{game_row.SERVER_NAME}</td>
		<td class="{game_row.CLASS}" align="center" width="1%"><a href="{game_row.U_EDIT}">{L_SETTING}</a></td>
		<td class="{game_row.CLASS}" align="center" width="5%"><a href="{game_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{game_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
		<td class="{game_row.CLASS}" align="center" width="1%"><a href="{game_row.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END game_row -->
	<!-- BEGIN no_entry_game -->
	<tr>
		<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry_game -->
	</table>
	
	<table class="row" cellspacing="1">
	<tr>
		<td class="rowHead">{L_SERVER_PAGE}</td>
		<td class="rowHead" colspan="3">{L_SETTINGS}</td>
	</tr>
	<!-- BEGIN voice_row -->
	<tr>
		<td class="{voice_row.CLASS}" align="left">{voice_row.SERVER_TITLE}</td>
		<td class="{voice_row.CLASS}" align="center" width="1%"><a href="{voice_row.U_EDIT}">{L_SETTING}</a></td>
		<td class="{voice_row.CLASS}" align="center" width="5%"><a href="{voice_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{voice_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
		<td class="{voice_row.CLASS}" align="center" width="1%"><a href="{voice_row.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END voice_row -->
	<!-- BEGIN no_entry_voice -->
	<tr>
		<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry_voice -->
	</table>
	
	<table class="foot" cellspacing="2">
	<tr>
		<td width="100%" align="right"><input class="post" name="rank_title" type="text" value=""></td>
		<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_SERVER_ADD}" /></td>
	</tr>
	</table>
</form>