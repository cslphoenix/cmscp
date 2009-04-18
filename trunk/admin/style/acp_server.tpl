<!-- BEGIN display -->
<form action="{S_SERVER_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_SERVER_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_SERVER_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_SERVER_TEAM}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td class="{display.game_row.CLASS}" align="center" width="1%">{display.game_row.I_IMAGE}</td>
	<td class="{display.game_row.CLASS}" align="left">{display.game_row.NAME}</td>
	<td class="{display.game_row.CLASS}" align="center" width="1%"><a href="{display.game_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.game_row.CLASS}" align="center" width="6%"><a href="{display.game_row.U_MOVE_UP}">{display.game_row.ICON_UP}</a> <a href="{display.game_row.U_MOVE_DOWN}">{display.game_row.ICON_DOWN}</a></td>
	<td class="{display.game_row.CLASS}" align="center" width="1%"><a href="{display.game_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_games -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_SERVERS}</td>
</tr>
<!-- END no_games -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="game_name" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_SERVER_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->


<!-- BEGIN _edit -->

<!-- END _edit -->

<!-- BEGIN _other -->

<!-- END _other -->