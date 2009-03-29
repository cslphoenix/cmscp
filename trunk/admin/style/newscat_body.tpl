<form method="post" action="{S_NEWS_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_NEWS_TITLE}</th>
	</tr>
	<tr>
		<td class="row2">{L_NEWS_EXPLAIN}</td>
	</tr>
	</table>
	
	<br />
	
	<table class="row" cellspacing="1">
	<tr>
		<td class="rowHead" colspan="2">{L_NEWS_TEAM}</td>
		<td class="rowHead" colspan="3">{L_SETTINGS}</td>
	</tr>
	<!-- BEGIN newscat_row -->
	<tr>
		<td class="{newscat_row.CLASS}" align="left">{newscat_row.NEWSCAT_NAME}</td>
		<td class="{newscat_row.CLASS}" align="center"><img src="{NEWSCAT_PATH}/{newscat_row.NEWSCAT_IMAGE}" alt=""></td>
		<td class="{newscat_row.CLASS}" align="center" width="1%"><a href="{newscat_row.U_EDIT}">{L_SETTING}</a></td>		
		<td class="{newscat_row.CLASS}" align="center" width="5%"><a href="{newscat_row.U_MOVE_UP}">{newscat_row.ICON_UP}</a> <a href="{newscat_row.U_MOVE_DOWN}">{newscat_row.ICON_DOWN}</a></td>
		<td class="{newscat_row.CLASS}" align="center" width="1%"><a href="{newscat_row.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END newscat_row -->
	<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	</table>
	
	<table class="foot" cellspacing="2">
	<tr>
		<td width="100%" align="right"><input class="post" name="news_title" type="text" value=""></td>
		<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_NEWS_ADD}" /></td>
	</tr>
	</table>
</form>