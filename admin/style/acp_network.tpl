<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_LINK}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _link_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_display._link_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._link_row.SHOW} {_display._link_row.MOVE_UP} {_display._link_row.MOVE_DOWN} <a href="{_display._link_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._link_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _link_row -->
<!-- BEGIN _no_entry_link -->
<tr>
	<td class="entry_empty" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_link -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="network_name[1]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="network_type[1]" value="{L_CREATE_LINK}"></td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_PARTNER}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _partner_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_display._partner_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._partner_row.SHOW} {_display._partner_row.MOVE_UP} {_display._partner_row.MOVE_DOWN} <a href="{_display._partner_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._partner_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _partner_row -->
<!-- BEGIN _no_entry_partner -->
<tr>
	<td class="entry_empty" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_partner -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="network_name[2]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="network_type[2]" value="{L_CREATE_PARTNER}"></td>
</tr>
</table>
	
<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="100%">{L_SPONSOR}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _sponsor_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{_display._sponsor_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._sponsor_row.SHOW} {_display._sponsor_row.MOVE_UP} {_display._sponsor_row.MOVE_DOWN} <a href="{_display._sponsor_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._sponsor_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _sponsor_row -->
<!-- BEGIN _no_entry_sponsor -->
<tr>
	<td class="entry_empty" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_sponsor -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="network_name[3]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="network_type[3]" value="{L_CREATE_SPONSOR}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="network_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="network_name" id="network_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="network_url">{L_URL}: *</label></td>
	<td class="row2"><input type="text" class="post" name="network_url" id="network_url" value="{URL}"></td>
</tr>
<tr>
	<td class="row1"><label for="network_image">{L_IMAGE}:</label></td>
	<td class="row3">
		<!-- BEGIN _image -->
		<img src="{IMAGE}" alt="" /><br /><input type="checkbox" name="network_image_delete">&nbsp;{L_IMAGE_DELETE}<br />
		<!-- END _image -->
		<input type="file" class="post" name="network_image">
	</td>
</tr>
<tr>
	<td class="row1"><label>{L_TYPE}: *</label></td>
	<td class="row2">
		<label><input type="radio" name="network_type" value="1" onclick="setRequest('network', '1')" {S_TYPE_LINK} />&nbsp;{L_TYPE_LINK}</label><br />
		<label><input type="radio" name="network_type" value="2" onclick="setRequest('network', '2')" {S_TYPE_PARTNER} />&nbsp;{L_TYPE_PARTNER}</label><br />
		<label><input type="radio" name="network_type" value="3" onclick="setRequest('network', '3')" {S_TYPE_SPONSOR} />&nbsp;{L_TYPE_SPONSOR}</label>
	</td> 
</tr>
<tr>
	<td class="row1"><label for="network_view">{L_VIEW}:</label></td>
	<td class="row2"><label><input type="radio" name="network_view" id="network_view" value="1" {S_VIEW_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="network_view" value="0" {S_VIEW_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="network_order">{L_ORDER}:</label></td>
	<td class="row2"><div id="close">{S_ORDER}</div><div id="content"></div></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->