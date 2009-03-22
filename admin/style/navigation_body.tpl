<form method="post" action="{S_TEAM_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_NAVI_TITLE}</th>
	</tr>
	<tr>
		<td>{L_NAVI_EXPLAIN}</td>
	</tr>
	</table>
	
	<br />
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead" colspan="3">{L_NAVI_MAIN}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN main_row -->
			<tr>
				<td class="{main_row.CLASS}" align="left">{main_row.NAVI_TITLE}</td>
				<td class="{main_row.CLASS}" align="center" width="1%">{main_row.NAVI_LANG}</td>
				<td class="{main_row.CLASS}" align="center" width="1%">{main_row.NAVI_SHOW}</td>
				<td class="{main_row.CLASS}" align="center" width="1%"><a href="{main_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{main_row.CLASS}" align="center" width="5%"><a href="{main_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{main_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{main_row.CLASS}" align="center" width="1%"><a href="{main_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END main_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_NAVIS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead" colspan="3">{L_NAVI_CLAN}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN clan_row -->
			<tr>
				<td class="{clan_row.CLASS}" align="left">{clan_row.NAVI_TITLE}</td>
				<td class="{clan_row.CLASS}" align="center" width="1%">{clan_row.NAVI_LANG}</td>
				<td class="{clan_row.CLASS}" align="center" width="1%">{clan_row.NAVI_SHOW}</td>
				<td class="{clan_row.CLASS}" align="center" width="1%"><a href="{clan_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{clan_row.CLASS}" align="center" width="5%"><a href="{clan_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{clan_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{clan_row.CLASS}" align="center" width="1%"><a href="{clan_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END clan_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_NAVIS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead" colspan="3">{L_NAVI_COM}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN com_row -->
			<tr>
				<td class="{com_row.CLASS}" align="left">{com_row.NAVI_TITLE}</td>
				<td class="{com_row.CLASS}" align="center" width="1%">{com_row.NAVI_LANG}</td>
				<td class="{com_row.CLASS}" align="center" width="1%">{com_row.NAVI_SHOW}</td>
				<td class="{com_row.CLASS}" align="center" width="1%"><a href="{com_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{com_row.CLASS}" align="center" width="5%"><a href="{com_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{com_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{com_row.CLASS}" align="center" width="1%"><a href="{com_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END com_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_NAVIS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead" colspan="3">{L_NAVI_MISC}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN misc_row -->
			<tr>
				<td class="{misc_row.CLASS}" align="left">{misc_row.NAVI_TITLE}</td>
				<td class="{misc_row.CLASS}" align="center" width="1%">{misc_row.NAVI_LANG}</td>
				<td class="{misc_row.CLASS}" align="center" width="1%">{misc_row.NAVI_SHOW}</td>
				<td class="{misc_row.CLASS}" align="center" width="1%"><a href="{misc_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{misc_row.CLASS}" align="center" width="5%"><a href="{misc_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{misc_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{misc_row.CLASS}" align="center" width="1%"><a href="{misc_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END misc_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_NAVIS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead" colspan="3">{L_NAVI_USER}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN user_row -->
			<tr>
				<td class="{user_row.CLASS}" align="left">{user_row.NAVI_TITLE}</td>
				<td class="{user_row.CLASS}" align="center" width="1%">{user_row.NAVI_LANG}</td>
				<td class="{user_row.CLASS}" align="center" width="1%">{user_row.NAVI_SHOW}</td>
				<td class="{user_row.CLASS}" align="center" width="1%"><a href="{user_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{user_row.CLASS}" align="center" width="5%"><a href="{user_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{user_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{user_row.CLASS}" align="center" width="1%"><a href="{user_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END user_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_NAVIS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	</table>
	
	<table class="foot" cellspacing="2">
	<tr>
		<td width="100%" align="right"><input class="post" name="rank_title" type="text" value=""></td>
		<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_NAVI_ADD}" /></td>
	</tr>
	</table>
</form>