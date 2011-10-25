<!-- BEGIN _list -->
<table class="out" width="100%" cellspacing="0">
<!-- BEGIN _in_groups -->
<!-- BEGIN _is_member -->
<tr>
	<td class="info_head" colspan="2">{L_CUR}</td>
</tr>
<!-- BEGIN _row -->
<tr>
<<<<<<< .mine
	<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.joined.member.grouprow.GROUP_NAME}" title="{select.joined.member.grouprow.GROUP_NAME}"></td>
	<td class="row1" height="50">
		<span class="forumlink">
			<a href="{select.joined.member.grouprow.U_GROUP}" class="forumlink">{select.joined.member.grouprow.NAME}</a><br>
			</span> <span class="genmed">{select.joined.member.grouprow.DESC}</span>
	</td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.joined.member.grouprow.TYPE}</span></td>
=======
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._in_groups._is_member._row.NAME}{_list._in_groups._is_member._row.DESC}</td>
	<td>{_list._in_groups._is_member._row.TYPE}</td>
>>>>>>> .r85
</tr>
<!-- END _row -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<!-- END _is_member -->
<!-- BEGIN _is_pending -->
<tr>
	<td class="info_head" colspan="2">{L_PEN}</td>
</tr>
<!-- BEGIN _row -->
<tr>
<<<<<<< .mine
	<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.joined.pending.grouprow.GROUP_NAME}" title="{select.joined.pending.grouprow.GROUP_NAME}"></td>
	<td class="row1" height="50">
		<span class="forumlink">
			<a href="{select.joined.pending.grouprow.U_GROUP}" class="forumlink">{select.joined.pending.grouprow.NAME}</a><br>
			</span> <span class="genmed">{select.joined.pending.grouprow.DESC}
		</span>
	</td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.joined.pending.grouprow.TYPE}</span></td>
=======
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._in_groups._is_pending._row.NAME}{_list._in_groups._is_pending._row.DESC}</td>
	<td>{_list._in_groups._is_pending._row.TYPE}</td>
>>>>>>> .r85
</tr>
<!-- END _row -->
<!-- END pending -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END _in_groups -->
<!-- BEGIN _no_group -->
<tr>
	<td class="info_head" colspan="2">{L_NON}</td>
</tr>
<<<<<<< .mine
      <!-- BEGIN grouprow -->
	  <tr>
		<td class="row1" align="center" valign="middle" height="50" width="5%"><img src="images/get_info.png" alt="{select.remaining.grouprow.GROUP_NAME}" title="{select.remaining.grouprow.GROUP_NAME}"></td>
	   <td class="row1" height="50"><span class="forumlink">
 			<a href="{select.remaining.grouprow.U_GROUP}" class="forumlink">{select.remaining.grouprow.NAME}</a><br>
		  </span> <span class="genmed">{select.remaining.grouprow.DESC}
		  </span>
	   </td>
	<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{select.remaining.grouprow.TYPE}</span></td>
	  </tr>
      <!-- END grouprow -->
  <!-- END remaining -->
=======
<!-- BEGIN _row -->
<tr>
	<td><img class="icon" src="images/get_info.png" alt=""> {_list._no_group._row.NAME}{_list._no_group._row.DESC}</td>
	<td>{_list._no_group._row.TYPE}</td>
</tr>
<!-- END _row -->
<!-- END _no_group -->
>>>>>>> .r85
</table>
<!-- END _list -->

<!-- BEGIN _view -->
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
	<!-- BEGIN _switch_admin -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END _switch_admin -->
</tr>
<!-- BEGIN _moderator_row -->
<tr>
	<td class="{_view._moderator_row.ROW_CLASS}" align="center"><a href="{_view._moderator_row.U_VIEWPROFILE}">{_view._moderator_row.USERNAME}</a></td>
	<td class="{_view._moderator_row.ROW_CLASS}" align="center">{_view._moderator_row.PM_IMG} </td>
	<td class="{_view._moderator_row.ROW_CLASS}" align="center">{_view._moderator_row.EMAIL_IMG}</td>
	<td class="{_view._moderator_row.ROW_CLASS}" align="center" {COLSPAN}>{_view._moderator_row.JOINED}</td>
	<!-- BEGIN _switch_admin -->
	<td class="{_view._moderator_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view._moderator_row.USER_ID}"></td>
	<!-- END _switch_admin -->
</tr>
<!-- END _moderator_row -->
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
	<!-- BEGIN _switch_moderator -->
	<td class="info_head" style="text-align:center;">{L_SELECT}</td>
	<!-- END _switch_moderator -->
</tr>

<!-- BEGIN _member_row -->
<tr>
	<td class="{_view._member_row.ROW_CLASS}" align="center"><a href="{_view._member_row.U_VIEWPROFILE}">{_view._member_row.USERNAME}</a></td>
	<td class="{_view._member_row.ROW_CLASS}" align="center">{_view._member_row.PM_IMG} </td>
	<td class="{_view._member_row.ROW_CLASS}" align="center">{_view._member_row.EMAIL_IMG}</td>
	<td class="{_view._member_row.ROW_CLASS}" align="center">{_view._member_row.JOINED}</td>
	<!-- BEGIN _switch_moderator -->
	<td class="{_view._member_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="members[]" value="{_view._member_row.USER_ID}"></td>
	<!-- END _switch_moderator -->
</tr>
<!-- END _member_row -->
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
		<input type="text" class="post" name="user_name" id="user_name" onkeyup="lookup(this.value);" onblur="fill();" autocomplete="off" style="width:165px;">
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

<!-- BEGIN _pending -->
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
<!-- BEGIN _pending_row -->
<tr>
	<td class="{_view._pending._pending_row.ROW_CLASS}" align="center"><a href="{_view._pending._pending_row.U_VIEWPROFILE}">{_view._pending._pending_row.USERNAME}</a></td>
	<td class="{_view._pending._pending_row.ROW_CLASS}" align="center">{_view._pending._pending_row.PM_IMG}</td>
	<td class="{_view._pending._pending_row.ROW_CLASS}" align="center">{_view._pending._pending_row.EMAIL_IMG}</td>
	<td class="{_view._pending._pending_row.ROW_CLASS}" align="center">{_view._pending._pending_row.JOINED}</td>
	<td class="{_view._pending._pending_row.ROW_CLASS}" align="center" width="1%"><input type="checkbox" name="pending_members[]" value="{_view._pending._pending_row.USER_ID}" checked="checked"></td>
</tr>
<!-- END _pending_row -->
<tr>
	<td class="row3" colspan="6" align="right"><input type="submit" name="approve" value="{L_APPROVE_SELECTED}" class="button2">&nbsp;<input type="submit" name="deny" value="{L_DENY_SELECTED}" class="button"></td>
	</tr>
</table>
<!-- END _pending -->
{S_FIELDS}
</form>
<!-- END _view -->