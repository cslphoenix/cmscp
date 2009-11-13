<!-- BEGIN display -->
<form action="{S_NETWORK_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_NETWORK_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_NETWORK_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" width="100%" colspan="2">{L_NETWORK_LINK}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN link_row -->
<tr>
	<td class="{display.link_row.CLASS}" align="left" width="100%">{display.link_row.NETWORK_NAME}</td>
	<td class="{display.link_row.CLASS}"><img src="{display.link_row.SHOW}" alt=""></td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.link_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap">{display.link_row.MOVE_UP} {display.link_row.MOVE_DOWN}</td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.link_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END link_row -->
<!-- BEGIN no_entry_link -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_link -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="network_name[1]" type="text" value=""></td>
	<td><input class="button" type="submit" name="_add[1]" value="{L_NETWORK_ADD_LINK}" /></td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" width="100%" colspan="2">{L_NETWORK_PARTNER}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN partner_row -->
<tr>
	<td class="{display.partner_row.CLASS}" align="left" width="100%">{display.partner_row.NETWORK_NAME}</td>
	<td class="{display.partner_row.CLASS}"><img src="{display.partner_row.SHOW}" alt=""></td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.partner_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap">{display.partner_row.MOVE_UP} {display.partner_row.MOVE_DOWN}</td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.partner_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END partner_row -->
<!-- BEGIN no_entry_partner -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_partner -->
</table>
	
<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="network_name[2]" type="text" value=""></td>
	<td><input class="button" type="submit" name="_add[2]" value="{L_NETWORK_ADD_PARTNER}" /></td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" width="100%" colspan="2">{L_NETWORK_SPONSOR}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN sponsor_row -->
<tr>
	<td class="{display.sponsor_row.CLASS}" align="left" width="100%">{display.sponsor_row.NETWORK_NAME}</td>
	<td class="{display.sponsor_row.CLASS}"><img src="{display.sponsor_row.SHOW}" alt=""></td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.sponsor_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap">{display.sponsor_row.MOVE_UP} {display.sponsor_row.MOVE_DOWN}</td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.sponsor_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END sponsor_row -->
<!-- BEGIN no_entry_sponsor -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_sponsor -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="network_name[3]" type="text" value=""></td>
	<td><input class="button" type="submit" name="_add[3]" value="{L_NETWORK_ADD_SPONSOR}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN network_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NETWORKS_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_NETWORK_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NETWORK_ACTION}">{L_NETWORK_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NETWORK_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NETWORK_NAME}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="network_name" value="{NETWORK_NAME}" ></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_NETWORK_URL}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="network_url" value="{NETWORK_URL}" ></td>
</tr>
<tr>
	<td class="row1">{L_NETWORK_TYPE}:</td>
	<td class="row3">
		<input type="radio" name="network_type" value="1" {S_CHECKED_TYPE_LINK} /> {L_TYPE_LINK}
		<input type="radio" name="network_type" value="2" {S_CHECKED_TYPE_PARTNER} /> {L_TYPE_PARTNER}
		<input type="radio" name="network_type" value="3" {S_CHECKED_TYPE_SPONSOR} /> {L_TYPE_SPONSOR}
	</td> 
</tr>
<tr>
	<td class="row1">{L_NETWORK_VIEW}:</td>
	<td class="row3"><input type="radio" name="network_view" value="1" {S_CHECKED_VIEW_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="network_view" value="0" {S_CHECKED_VIEW_NO} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END network_edit -->