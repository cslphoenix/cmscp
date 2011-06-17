<!-- BEGIN _list -->
<table class="match" width="100%" cellspacing="0">
<tr>
	<td class="header" colspan="3">{L_UPCOMING}</td>
</tr>
<!-- BEGIN _new_row -->
<tr>
	<td class="{_list._new_row.CLASS}">{_list._new_row.GAME} {_list._new_row.NAME}</td>
	<td class="{_list._new_row.CLASS}">{_list._new_row.DATE}</td>
	<td class="{_list._new_row.CLASS}"><span class="{_list._new_row.CSS}">{_list._new_row.STATUS}</span></td>
</tr>
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_EXPIRED}</td>
</tr>
<!-- BEGIN _old_row -->
<tr>
	<td class="{_list._old_row.CLASS}">{_list._old_row.GAME} {_list._old_row.NAME}</td>
	<td class="{_list._old_row.CLASS}">{_list._old_row.DATE}</td>
	<td class="{_list._old_row.CLASS}"><span class="{_list._old_row.CSS}">{_list._old_row.STATUS}</span></td>
</tr>
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END _list -->

<!-- BEGIN _view -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">
		<!-- BEGIN _update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{_view._update.UPDATE}&nbsp;&bull;&nbsp;{_view._update.UPDATE_DETAIL}</span>
		<!-- END _update -->
		<span class="small" style="float:right;">{OVERVIEW}</span>
		{L_MAIN}
	</td>
</tr>
<tr>
	<td class="row4r" align="center">
		<table class="info" width="55%" cellspacing="2">
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
			<td>{SERVER} {SERVER_PW}</td>
		</tr>
		<!-- BEGIN hltv -->
		<tr>
			<td>{L_HLTV}</td>
			<td>{HLTV} {HLTV_PW}</td>
		</tr>
		<!-- END hltv -->
		<!-- BEGIN _lineup -->
		<!-- BEGIN _clan -->
		<tr>
			<td>{L_LINEUP_CLAN}</td>
			<td>{CLAN}</td>
		</tr>
		<!-- END _clan -->
		<!-- BEGIN _rival -->
		<tr>
			<td>{L_LINEUP_RIVAL}</td>
			<td>{RIVAL}</td>
		</tr>
		<!-- END _rival -->
		<!-- END _lineup -->
		</table>
	</td>
</tr>
<!-- BEGIN _status -->
<tr>
	<td colspan="2" align="center">
		<form action="{S_ACTION}" method="post" name="post">
		<table class="info" width="55%" cellspacing="2">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<!-- BEGIN _entry -->
		<tr>
			<td class="info_head" colspan="2">{L_ENTRY}</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="rowHead"><span class="right">{L_STATUS}</span>{L_USERNAME}</td>
				</tr>
				<!-- BEGIN _status_row -->
				<tr>
					<td><span class="right">{_view._status._entry._status_row.DATE}<span class="{_view._status._entry._status_row.CLASS}">{_view._status._entry._status_row.STATUS}</span></span><a href="{_view._status._entry._status_row.LINK}">{_view._status._entry._status_row.USER}</a></td>
				<!--
					<td width="40%" align="left" nowrap="nowrap"><a href="{_view._status._entry._status_row.LINK}">{_view._status._entry._status_row.USER}</a></td>
					<td width="60%" align="left" nowrap="nowrap"><span class="small">{_view._status._entry._status_row.DATE}</span></td>
					<td width="10%" align="left" nowrap="nowrap"><span class="{_view._status._entry._status_row.CLASS}">{_view._status._entry._status_row.STATUS}</span></td>
				-->
				</tr>
				<!-- END _status_row -->
				</table>
			</td>
		</tr>
		<!-- END _entry -->
		<!-- BEGIN _switch -->
		<tr>
			<td valign="top" align="center">
				<table class="out" cellspacing="0">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="row4"align="center"><input class="button" type="submit" name="submit" value="{L_SET_STATUS}" /></td>
					<td>
						<label><input type="radio" value="1" name="user_status" {S_YES} /> {L_STATUS_YES}</label><br />
						<label><input type="radio" value="2" name="user_status" {S_NO} /> {L_STATUS_NO}</label><br />
						<label><input type="radio" value="3" name="user_status" {S_REPLACE} /> {L_STATUS_REPLACE}</label>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- END _switch -->
  		</table>
		{S_FIELDE}
		</form>
	</td>
</tr>
<!-- END _status -->
</table>
<!-- END _view -->