<!-- BEGIN list -->
<table class="out" width="100%" cellspacing="0">
<!-- BEGIN in_groups -->
<!-- BEGIN is_member -->
<tr>
	<td class="info_head" colspan="2">{L_CUR}</td>
</tr>
<!-- BEGIN row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._in_groups._is_member.row.NAME}{_list._in_groups._is_member.row.DESC}</td>
	<td>{_list._in_groups._is_member.row.TYPE}</td>
</tr>
<!-- END row -->
<tr>
	<td colspan="2"></td>
</tr>
<!-- END is_member -->
<!-- BEGIN is_pending -->
<tr>
	<td class="info_head" colspan="2">{L_PEN}</td>
</tr>
<!-- BEGIN row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._in_groups._is_pending.row.NAME}{_list._in_groups._is_pending.row.DESC}</td>
	<td>{_list._in_groups._is_pending.row.TYPE}</td>
</tr>
<!-- END row -->
<!-- END pending -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END in_groups -->
<!-- BEGIN no_group -->
<tr>
	<td class="info_head" colspan="2">{L_NON}</td>
</tr>
<!-- BEGIN row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._no_group.row.NAME}{_list._no_group.row.DESC}</td>
	<td>{_list._no_group.row.TYPE}</td>
</tr>
<!-- END row -->
<!-- END no_group -->
</table>
<!-- END list -->

<!-- BEGIN view -->
<script type="text/JavaScript">

function lookup(user_name)
{
	if ( user_name.length == 0 )
	{
		// Hide the suggestion box.
		$('#suggestions').hide();
	}
	else
	{
		$.post("./includes/ajax/ajax_user.php", {user_name: ""+user_name+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			}
		);
	}
}

function fill(thisValue)
{
	$('#user_name').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

</script>
<form action="{S_ACTION}" method="post">
<table class="group">
<tr>
	<td class="header" colspan="2">{L_MAIN}</td>
</tr>
<tr>
	<td>{L_GROUP_NAME}:</td>
	<td>{GROUP_NAME}</td>
</tr>
<tr> 
	<td>{L_GROUP_DESC}:</td>
	<td>{GROUP_DESC}</td>
</tr>
<tr> 
	<td>{L_GROUP_MEMBERSHIP}:</td>
	<td>{GROUP_DETAILS}&nbsp;&nbsp;
		<!-- BEGIN switch_subscribe_group_input -->
		<input class="button2" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
		<!-- END switch_subscribe_group_input -->
		<!-- BEGIN switch_unsubscribe_group_input -->
		<input class="button2" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
		<!-- END switch_unsubscribe_group_input -->
	</td>
</tr>
</table>
{S_FIELDS}
</form>

<br />

<form action="{S_ACTION}" method="post" name="post">
<table class="row">
<tr>
	<td class="header" colspan="6">{L_GROUP_MODERATOR}</td>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;" {COLSPAN}>{L_JOINED}</td>
	<!-- BEGIN switch_admin -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_admin -->
</tr>
<!-- BEGIN moderator_row -->
<tr>
	<td class="{_view._moderatorrow.ROW_CLASS}" align="center"><a href="{_view._moderatorrow.U_VIEWPROFILE}">{_view._moderatorrow.USERNAME}</a></td>
	<td class="{_view._moderatorrow.ROW_CLASS}" align="center">{_view._moderatorrow.PM_IMG} </td>
	<td class="{_view._moderatorrow.ROW_CLASS}" align="center">{_view._moderatorrow.EMAIL_IMG}</td>
	<td class="{_view._moderatorrow.ROW_CLASS}" align="center" {COLSPAN}>{_view._moderatorrow.JOINED}</td>
	<!-- BEGIN switch_admin -->
	<td class="{_view._moderatorrow.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view._moderatorrow.USER_ID}"></td>
	<!-- END switch_admin -->
</tr>
<!-- END moderator_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td class="row3" colspan="6" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<th colspan="6">{L_GROUP_MEMBERS}</th>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<!-- BEGIN switch_moderator -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END switch_moderator -->
</tr>

<!-- BEGIN member_row -->
<tr>
	<td class="{_view._memberrow.ROW_CLASS}" align="center"><a href="{_view._memberrow.U_VIEWPROFILE}">{_view._memberrow.USERNAME}</a></td>
	<td class="{_view._memberrow.ROW_CLASS}" align="center">{_view._memberrow.PM_IMG} </td>
	<td class="{_view._memberrow.ROW_CLASS}" align="center">{_view._memberrow.EMAIL_IMG}</td>
	<td class="{_view._memberrow.ROW_CLASS}" align="center">{_view._memberrow.JOINED}</td>
	<!-- BEGIN switch_moderator -->
	<td class="{_view._memberrow.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view._memberrow.USER_ID}"></td>
	<!-- END switch_moderator -->
</tr>
<!-- END member_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td class="row3" colspan="6" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN switch_hidden_group -->
<tr>
	<td class="row1" colspan="6" align="center"><span class="gen">{L_HIDDEN_MEMBERS}</span></td>
</tr>
<!-- END switch_hidden_group -->
</table>
<!-- BEGIN switch_add_member -->
<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left" colspan="3">
		<input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value);" onblur="fill();" autocomplete="off" style="width:165px;">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<img src="style/images/upArrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div> <input type="submit" name="add" value="{L_ADD_MEMBER}" class="button2"></td>
	<td align="right" colspan="3">{S_SELECT_OPTION} <input type="submit" value="Absenden" class="button2"></td>
</tr>
</table>
<!-- END switch_add_member -->

<table class="out" width="100%" cellspacing="3">
<tr>
	<td align="left">{PAGE_NUMBER}</td>
	<td align="right">{PAGINATION}</td>
</tr>
</table>

<!-- BEGIN pending -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="row3" colspan="6">&nbsp;</td>
</tr>
<tr>
	<th colspan="6">{L_PENDING_MEMBERS}</th>
</tr>
<tr>
	<td class="info_head" style="text-align:center;">{L_USERNAME}</td>
	<td class="info_head" style="text-align:center;">{L_PM}</td>
	<td class="info_head" style="text-align:center;">{L_EMAIL}</td>
	<td class="info_head" style="text-align:center;">{L_JOINED}</td>
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td class="{_view._pending._pendingrow.ROW_CLASS}" align="center"><a href="{_view._pending._pendingrow.U_VIEWPROFILE}">{_view._pending._pendingrow.USERNAME}</a></td>
	<td class="{_view._pending._pendingrow.ROW_CLASS}" align="center">{_view._pending._pendingrow.PM_IMG}</td>
	<td class="{_view._pending._pendingrow.ROW_CLASS}" align="center">{_view._pending._pendingrow.EMAIL_IMG}</td>
	<td class="{_view._pending._pendingrow.ROW_CLASS}" align="center">{_view._pending._pendingrow.JOINED}</td>
	<td class="{_view._pending._pendingrow.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="pending_members[]" value="{_view._pending._pendingrow.USER_ID}" checked="checked"></td>
</tr>
<!-- END pending_row -->
<tr>
	<td class="row3" colspan="6" align="right"><input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="button2">&nbsp;<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="button"></td>
	</tr>
</table>
<!-- END pending -->
{S_FIELDS}
</form>
<!-- END view -->