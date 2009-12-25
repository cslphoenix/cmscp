<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_LINK}</td>
	<td class="rowHead">{L_VISIBLE}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN link_row -->
<tr>
	<td class="{display.link_row.CLASS}" align="left" width="100%">{display.link_row.NAME}</td>
	<td class="{display.link_row.CLASS}" align="center"><img src="{display.link_row.VISIBLE}" alt=""></td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap">{display.link_row.MOVE_UP} {display.link_row.MOVE_DOWN}</td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.link_row.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="{display.link_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.link_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END link_row -->
<!-- BEGIN no_entry_link -->
<tr>
	<td class="row_noentry" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_link -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_PARTNER}</td>
	<td class="rowHead">{L_VISIBLE}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN partner_row -->
<tr>
	<td class="{display.partner_row.CLASS}" align="left" width="100%">{display.partner_row.NAME}</td>
	<td class="{display.partner_row.CLASS}" align="center"><img src="{display.partner_row.VISIBLE}" alt=""></td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap">{display.partner_row.MOVE_UP} {display.partner_row.MOVE_DOWN}</td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.partner_row.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="{display.partner_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.partner_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END partner_row -->
<!-- BEGIN no_entry_partner -->
<tr>
	<td class="row_noentry" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_partner -->
</table>
	
<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_SPONSOR}</td>
	<td class="rowHead">{L_VISIBLE}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN sponsor_row -->
<tr>
	<td class="{display.sponsor_row.CLASS}" align="left" width="100%">{display.sponsor_row.NAME}</td>
	<td class="{display.sponsor_row.CLASS}" align="center"><img src="{display.sponsor_row.VISIBLE}" alt=""></td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap">{display.sponsor_row.MOVE_UP} {display.sponsor_row.MOVE_DOWN}</td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.sponsor_row.U_UPDATE}">{L_UPDATE}</a></td>
	<td class="{display.sponsor_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.sponsor_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END sponsor_row -->
<!-- BEGIN no_entry_sponsor -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_sponsor -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="network_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
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

<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}" method="post">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="network_name">{L_NAME}: *</label></td>
	<td class="row3"><input type="text" class="post" name="network_name" id="network_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="network_url">{L_URL}: *</label></td>
	<td class="row3"><input type="text" class="post" name="network_url" id="network_url" value="{URL}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="network_image">{L_IMAGE}:</label></td>
	<td class="row3">
		<!-- BEGIN network_image -->
		<img src="{IMAGE}" alt="" />&nbsp;<input type="checkbox" name="network_image_delete">&nbsp;{L_IMAGE_DELETE}<br />
		<!-- END network_image -->
		<input type="file" class="post" name="network_image" id="network_image">
	</td>
</tr>
<tr>
	<td class="row1 top"><label>{L_TYPE}:</label></td>
	<td class="row3"><label><input type="radio" name="network_type" value="1" {S_TYPE_LINK} />&nbsp;{L_TYPE_LINK}</label><br /><label><input type="radio" name="network_type" value="2" {S_TYPE_PARTNER} />&nbsp;{L_TYPE_PARTNER}</label><br /><label><input type="radio" name="network_type" value="3" {S_TYPE_SPONSOR} />&nbsp;{L_TYPE_SPONSOR}</label></td> 
</tr>
<tr>
	<td class="row1"><label for="network_view">{L_VIEW}:</label></td>
	<td class="row3"><label><input type="radio" name="network_view" id="network_view" value="1" {S_VIEW_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="network_view" value="0" {S_VIEW_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END network_edit -->