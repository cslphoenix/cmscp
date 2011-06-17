<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li>
		<li><a id="setting" href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_COUNT}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _row_groups -->
<tr>
	<td>{_display._row_groups.NAME}</td>
	<td>{_display._row_groups.COUNT}</td>
	<td>{_display._row_groups.MOVE_UP} {_display._row_groups.MOVE_DOWN} <a href="{_display._row_groups.U_MEMBER}">{I_MEMBER}</a> <a href="{_display._row_groups.U_UPDATE}">{I_UPDATE}</a> {_display._row_groups.DELETE}</td>
</tr>
<!-- END _row_groups -->
<!-- BEGIN _no_groups -->
<tr>
	<td class="entry_empty" colspan="3">{NO_GROUPS}</td>
</tr>
<!-- END _no_groups -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="group_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
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
		$.post("./ajax/ajax_user.php", {user_name: ""+user_name+""}, function(data) {
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
<script type="text/javascript">
// <![CDATA[
	
function activated()
{
	<!-- BEGIN _auth -->
	document.getElementById("{_auth.FIELDS}").checked = true;
	<!-- END _auth -->
}

function deactivated()
{
	var radios = document.forms["post"].elements["deactivated"];
	
	for ( var i = 0; i < radios.length; i++ )
	{
		radios[i].checked = true;
	}
}

// ]]>
</script>
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _update -->
	<li><a href="{S_MEMBER}">{L_VIEWMEMBER}</a></li>
	<!-- END _update -->
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA}</a></li>
				<li><a href="#" id="right">{L_AUTH}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tr>
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="0">
		<tbody class="trhover">
		<tr>
			<td class="row1" width="155"><label for="group_name">{L_NAME}: *</label></td>
			<td class="row2"><input type="text" class="post" name="group_name" id="group_name" value="{NAME}"></td>
		</tr>
		<!-- BEGIN _create -->
		<tr>
			<td class="row1"><label for="user_name">{L_MOD}: *</label></td>
			<td class="row2"><input type="text" class="post" name="user_id" id="user_name" value="{MOD}" onkeyup="lookup(this.value);" onblur="fill();" autocomplete="off" style="width:165px;">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<img src="style/images/upArrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div></td>
		</tr>
		<!-- END _create -->
		<tr>
			<td class="row1"><label for="group_access">{L_ACCESS}:</label></td>
			<td class="row2">{S_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_type">{L_TYPE}:</label></td>
			<td class="row2">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_desc">{L_DESC}:</label></td>
			<td class="row2"><textarea class="textarea" name="group_desc" id="group_desc" rows="5" cols="40">{GROUP_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_color">{L_COLOR}:</label></td>
			<td class="row2"><input size="7" class="color post" type="text" name="group_color" id="group_color" value="{COLOR}"></td>
		</tr>
		<tr>
			<td class="row1"><label for="group_legend">{L_LEGEND}:</label></td>
			<td class="row2"><label><input type="radio" name="group_legend" id="group_legend" value="1" {S_LEGEND_YES} />&nbsp;{L_SHOW}</label><span style="padding:4px;"></span><label><input type="radio" name="group_legend" value="0" {S_LEGEND_NO} />&nbsp;{L_NOSHOW}</label></td>
		</tr>
		<tr>
			<td class="row1"><label for="rank_id">{L_RANK}:</label></td>
			<td class="row2">{S_RANK}</td>
		</tr>
		<tr>
			<td class="row1"><label for="group_order">{L_ORDER}:</label></td>
			<td class="row2">{S_ORDER}</td>
		</tr>
		</tbody>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th colspan="2">
				<div id="navcontainer">
				<ul id="navlist">
					<li id="active"><a href="#" id="current">{L_IMAGE}</a></li>
				</ul>
		</div>
	</td>
</tr>
		<tbody class="trhover">
		<!-- BEGIN _image -->
		<tr>
			<td class="row1">{L_IMAGE_CURRENT}:</td>
			<td class="row2"><img src="{IMAGE}" alt="" /><br /><input type="checkbox" name="group_image_delete">&nbsp;{L_IMAGE_DELETE}</td>
		</tr>
		<!-- END _image -->
		<tr>
			<td class="row1"><label for="ufile">{L_IMAGE_UPLOAD}:</label></td>
			<td class="row2"><input type="file" class="post" name="group_img" /></td>
		</tr>
		</tbody>
		</table>
	</td>
	<td valign="top" align="right">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<tbody class="trhover">
		<!-- BEGIN _auth -->
		<tr>
			<td class="row1"><label for="{_input._auth.FIELDS}">{_input._auth.TITLE}</label></td>
			<td class="row2">{_input._auth.SELECT}</td>
		</tr>
		<!-- END _auth -->
		<tr>
			<td class="row3" colspan="2" align="right"><a class="small" href="#" onclick="activated();">{L_MARK_YES}</a>&nbsp;&bull;&nbsp;<a class="small" href="#" onclick="deactivated();">{L_MARK_NO}</a></td>
		</tr>
		</tbody>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _member -->
<form action="{S_ACTION}" method="post" name="post" id="list">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_UPDATE}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_VIEWMEMBER}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MODERATOR}</a></li>
</ul>
</div>
<table class="rows">
<tr>
	<td class="rowHead" align="left" width="100%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN _mod_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_member._mod_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mod_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{_member._mod_row.USER_ID}"></td>
</tr>
<!-- END _mod_row -->
<!-- BEGIN _mod_no -->
<tr>
	<td class="entry_empty" colspan="3" align="center">{L_MODERATOR_NO}</td>
</tr>
<!-- END _mod_no -->
</table>

<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_MEMBER}</a></li>
</ul>
</div>
<table class="rows">
<tr>
	<td class="rowHead" align="left" width="100%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN _mem_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_member._mem_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._mem_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{_member._mem_row.USER_ID}"></td>
</tr>
<!-- END _mem_row -->
<!-- BEGIN _mem_no -->
<tr>
	<td class="entry_empty" colspan="3" align="center">{L_MEMBER_NO}</td>
</tr>
<!-- END _mem_no -->
</table>
<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td colspan="2" align="right">{S_OPTIONS}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
<tr>
	<td colspan="2" align="right" class="row5"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>

<!-- BEGIN _pending -->
<br />

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_PENDING}</a></li>
</ul>
</div>
<table class="rows">
<tr>
	<td class="rowHead" align="left" width="98%">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<!-- BEGIN _pending_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_member._pending._pending_row.USERNAME}</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_member._pending._pending_row.REGISTER}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><input type="checkbox" name="pending_members[]" value="{_member._pending._pending_row.USER_ID}" checked="checked"></td>
</tr>
<!-- END _pending_row -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td colspan="2" align="right">{S_PENDING}&nbsp;<input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
<!-- END _pending -->
{S_FIELDS}
</form>

<form action="{S_ACTION}" method="post" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_ADD_MEMBER}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tr>
	<td class="row2"><span class="small">{L_ADD_MEMBER_EX}</span></td>
</tr>
</table>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2" width="50%"><textarea class="textarea" name="textarea" style="width:100%" rows="5"></textarea></td>
	<td class="row2">{S_USERS}</td>
</tr>
<tr>
	<td class="row2" colspan="2"><input type="checkbox" name="mod" /> Moderatorstatus</td>
</tr>
<tr>
	<td class="row2" colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}"></td>
</tr>
</table>
<input type="hidden" name="smode" value="_add" />
{S_FIELDS}
</form>
<!-- END _member -->

<!-- BEGIN _overview -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a href="#" id="right">{L_OVERVIEW}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_OVERVIEW_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_OPTION}</a></li>
			</ul>
		</div>
	</td>
	<!-- BEGIN _grp_name -->
	<td>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{_overview._grp_name.NAME}</a></li>
			</ul>
		</div>
	</td>
	<!-- END _grp_name -->
</tr>
<!-- BEGIN _grp_auth -->
<tr class="hover">
	<td class="row1_2"><label>{_overview._grp_auth.NAME}:</label></td>
	<!-- BEGIN _auth -->
	<td class="row2">{_overview._grp_auth._auth.INFO}</td>
	<!-- END _auth -->
</tr>
<!-- END _grp_auth -->
<tr>
	<td colspan="{COLSPAN}" class="row5 small"><span class="show_right">{PAGE_PAGING}</span>{PAGE_NUMBER}</td>
</tr>
<tr>
	<td colspan="{COLSPAN}" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _overview -->