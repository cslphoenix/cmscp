<form method="post" action="{S_TEAM_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_RANK_TITLE}</th>
	</tr>
	<tr>
		<td>{L_RANK_EXPLAIN}</td>
	</tr>
	</table>
	
	<br />
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table class="row" cellspacing="1">
			<tr>
				<td class="rowHead">{L_RANK_PAGE}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN page_row -->
			<tr>
				<td class="{page_row.CLASS}" align="left">{page_row.RANK_TITLE}</td>
				<td class="{page_row.CLASS}" align="center" width="1%"><a href="{page_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{page_row.CLASS}" align="center" width="5%"><a href="{page_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{page_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{page_row.CLASS}" align="center" width="1%"><a href="{page_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END page_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
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
				<td class="rowHead">{L_RANK_FORUM}</td>
				<td class="rowHead">{L_RANK_SPECIAL}</td>
				<td class="rowHead">{L_RANK_MIN}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN forum_row -->
			<tr>
				<td class="{forum_row.CLASS}" align="left">{forum_row.RANK_TITLE}</td>
				<td class="{forum_row.CLASS}" align="left">{forum_row.RANK_SPECIAL}</td>
				<td class="{forum_row.CLASS}" align="left">{forum_row.RANK_MIN}</td>
				<td class="{forum_row.CLASS}" align="center" width="1%"><a href="{forum_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{forum_row.CLASS}" align="center" width="5%"><a href="{forum_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{forum_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{forum_row.CLASS}" align="center" width="1%"><a href="{forum_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END forum_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
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
				<td class="rowHead">{L_RANK_TEAM}</td>
				<td class="rowHead" colspan="3">{L_SETTINGS}</td>
			</tr>
			<!-- BEGIN team_row -->
			<tr>
				<td class="{team_row.CLASS}" align="left">{team_row.RANK_TITLE}</td>
				<td class="{team_row.CLASS}" align="center" width="1%"><a href="{team_row.U_EDIT}">{L_SETTING}</a></td>
				<td class="{team_row.CLASS}" align="center" width="5%"><a href="{team_row.U_MOVE_UP}">{ICON_MOVE_UP}</a> <a href="{team_row.U_MOVE_DOWN}">{ICON_MOVE_DOWN}</a></td>
				<td class="{team_row.CLASS}" align="center" width="1%"><a href="{team_row.U_DELETE}">{L_DELETE}</a></td>
			</tr>
			<!-- END team_row -->
			<!-- BEGIN no_ranks -->
			<tr>
				<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
			</tr>
			<!-- END no_ranks -->
			</table>
		</td>
	</tr>
	</table>
	
	<table class="foot" cellspacing="2">
	<tr>
		<td width="100%" align="right"><input class="post" name="rank_title" type="text" value=""></td>
		<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_RANK_ADD}" /></td>
	</tr>
	</table>
</form>