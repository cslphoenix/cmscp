<form method="post" action="{S_GAME_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_GAME_TITLE}</th>
	</tr>
	<tr>
		<td class="row2">{L_GAME_EXPLAIN}</td>
	</tr>
	</table>
	
	<br />
	
	<table class="row" cellspacing="1">
	<tr>
		<td class="rowHead" colspan="2">{L_GAME_TEAM}</td>
		<td class="rowHead" colspan="3">{L_SETTINGS}</td>
	</tr>
	<!-- BEGIN game_row -->
	<tr>
		<td class="{game_row.CLASS}" align="center">{game_row.I_IMAGE}</td>
		<td class="{game_row.CLASS}" align="left">{game_row.NAME}</td>
		<td class="{game_row.CLASS}" align="center" width="1%"><a href="{game_row.U_EDIT}">{L_SETTING}</a></td>		
		<td class="{game_row.CLASS}" align="center" width="5%"><a href="{game_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{game_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
		<td class="{game_row.CLASS}" align="center" width="1%"><a href="{game_row.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END game_row -->
	<!-- BEGIN no_games -->
	<tr>
		<td class="row_class1" align="center" colspan="7">{NO_GAMES}</td>
	</tr>
	<!-- END no_games -->
	</table>
	
	<table class="foot" cellspacing="2">
	<tr>
		<td width="100%" align="right"><input class="post" name="game_name" type="text" value=""></td>
		<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_GAME_ADD}" /></td>
	</tr>
	</table>
</form>