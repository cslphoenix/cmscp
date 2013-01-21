<!-- BEGIN list -->
<table class="type1" width="100%" cellspacing="0">
<tr>
	<th colspan="3">{L_UPCOMING}</th>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td class="{list.new_row.CLASS}">{list.new_row.GAME} {list.new_row.NAME}</td>
	<td class="{list.new_row.CLASS}">{list.new_row.DATE}</td>
	<td class="{list.new_row.CLASS}"><span class="{list.new_row.CSS}">{list.new_row.STATUS}</span></td>
</tr>
<!-- END new_row -->
<!-- BEGIN entry_empty_new -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<th colspan="3">{L_EXPIRED}</th>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td class="{list.old_row.CLASS}">{list.old_row.GAME} {list.old_row.NAME}</td>
	<td class="{list.old_row.CLASS}">{list.old_row.DATE}</td>
	<td class="{list.old_row.CLASS}"><span class="{list.old_row.CSS}">{list.old_row.STATUS}</span></td>
</tr>
<!-- END old_row -->
<!-- BEGIN entry_empty_old -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_old -->
<tr>
	<td class="footer" colspan="3"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END list -->

<!-- BEGIN view -->
<table class="type1" width="100%" cellspacing="0">
<tr>
	<th colspan="2">
		<!-- BEGIN update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{view.update.UPDATE}&nbsp;&bull;&nbsp;{view.update.UPDATE_DETAIL}</span>
		<!-- END update -->
		<span class="small" style="float:right;">{OVERVIEW}</span>
		{L_MAIN}
	</th>
</tr>
<tr>
	<td align="center">
		<table class="type2" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="35%">{L_NAME}</td>
			<td width="65%">{RIVAL_NAME} <a href="{U_RIVAL_URL}">{RIVAL_URL}</a></td>
		</tr>
		<tr>
			<td>{L_TAG}</td>
			<td>{RIVAL_TAG}</td>
		</tr>
		<tr>
			<td>{L_DETAILS}</td>
			<td>{MATCH_CATEGORIE}: {MATCH_TYPE} - {MATCH_LEAGUE_INFO}</td>
		</tr>
		<tr>
			<td>{L_SERVER}</td>
			<td>{SERVER_IP} {SERVER_PW}</td>
		</tr>
		<!-- BEGIN hltv -->
		<tr>
			<td>{L_HLTV}</td>
			<td>{view.hltv.HLTV} {view.hltv.HLTV_PW}</td>
		</tr>
		<!-- END hltv -->
		<!-- BEGIN lineup -->
		<!-- BEGIN clan -->
		<tr>
			<td>{L_LINEUP_CLAN}</td>
			<td>{CLAN}</td>
		</tr>
		<!-- END clan -->
		<!-- BEGIN rival -->
		<tr>
			<td>{L_LINEUP_RIVAL}</td>
			<td>{RIVAL}</td>
		</tr>
		<!-- END rival -->
		<!-- END lineup -->
		</table>
	
		<!-- BEGIN maps -->
		<br />
		<table class="type2" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<th>{view.maps.ROUND}</th>
			<th>{view.maps.POINTS}</th>
		</tr>
		
		<tr>
			<td>{view.maps.PICS}</td>
			<td class="top">
				<!-- BEGIN row -->
				{view.maps.row.POINTS_HOME}:{view.maps.row.POINTS_RIVAL}<br />
				<!-- END row -->
			</td>
		</tr>
		</table>
		<!-- END maps -->
	</td>
</tr>
<!-- BEGIN status -->
<tr>
	<td colspan="2" align="center">
		<form action="{S_ACTION}" method="post" name="post">
		<table class="type2" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="2"></td>
		</tr>
		<!-- BEGIN entry -->
		<tr>
			<td class="info_head" colspan="2">{L_ENTRY}</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="type2" width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th><span class="right">{L_STATUS}</span>{L_USERNAME}</th>
				</tr>
				<!-- BEGIN row -->
				<tr>
					<td><span class="right">{view.status.entry.row.DATE} <span class="{view.status.entry.row.CLASS}">{view.status.entry.row.STATUS}</span></span><a href="{view.status.entry.row.LINK}">{view.status.entry.row.USER}</a></td>
				</tr>
				<!-- END row -->
				</table>
			</td>
		</tr>
		<!-- END entry -->
		<!-- BEGIN switch -->
		<tr>
			<td valign="top" align="center">
				<table class="type2" width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td><input class="button" type="submit" name="submit" value="{L_SET_STATUS}" /></td>
					<td><label><input type="radio" value="1" name="user_status" {S_YES} /> {L_STATUS_YES}</label><span style="padding:4px;"></span><label><input type="radio" value="2" name="user_status" {S_NO} /> {L_STATUS_NO}</label><span style="padding:4px;"></span><label><input type="radio" value="3" name="user_status" {S_REPLACE} /> {L_STATUS_REPLACE}</label></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- END switch -->
  		</table>
		{S_FIELDE}
		</form>
	</td>
</tr>
<!-- END status -->
</table>

<!-- END view -->
<form action="{S_ACTION}" method="post" name="post">
{COMMENTS}
{S_FIELDS}
</form>