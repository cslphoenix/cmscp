<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>
<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" class="post" name="cat_name" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="rows">
<tr>
	<th>{_display._cat_row.CAT_NAME}<span style="float:right;">{_display._cat_row.CAT_TAG}</span></th>
	<th>{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN}{_display._cat_row.UPDATE} {_display._cat_row.DELETE}</th>
</tr>
<!-- BEGIN _map_row -->
<tr>
<<<<<<< .mine
	<td class="row_class1"><span class="gen">{_display._cat_row._map_row.NAME}</span><span style="float:right;">{_display._cat_row._map_row.FILE} :: {_display._cat_row._map_row.TYPE}</span></td>
	<td class="row_class2" align="center" valign="middle" nowrap="nowrap">{_display._cat_row._map_row.MOVE_UP}{_display._cat_row._map_row.MOVE_DOWN} {_display._cat_row._map_row.UPDATE} {_display._cat_row._map_row.DELETE}</td>
=======
	<td><span style="float:right;">{_display._cat_row._map_row.FILE} :: {_display._cat_row._map_row.TYPE}</span>{_display._cat_row._map_row.NAME}</td>
	<td>{_display._cat_row._map_row.MOVE_UP}{_display._cat_row._map_row.MOVE_DOWN}{_display._cat_row._map_row.UPDATE} {_display._cat_row._map_row.DELETE}</td>
>>>>>>> .r85
</tr>
<!-- END _map_row -->
<<<<<<< .mine
<!-- </tbody> -->
<!-- BEGIN _entry_empty -->
=======
<!-- BEGIN _entry_empty -->
>>>>>>> .r85
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="{_display._cat_row.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{_display._cat_row.S_SUBMIT}" value="{L_CREATE_MAP}"></td>
</tr>
</table>

<br />
<!-- END _cat_row -->
</form>
<!-- END _display -->

<!-- BEGIN _input_cat -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
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
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="cat_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_tag">{L_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_tag" id="cat_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input_cat -->

<!-- BEGIN _input -->
{AJAX}
<script type="text/javascript">
<!-- BEGIN _update_image -->
function update_{_input._update_image.NAME}(newimage)
{
	document.getElementById('image').src = (newimage) ? "{_input._update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}
<!-- END _cat -->

<!-- BEGIN _update_image -->
function update_ajax_{_input._update_image.NAME}(newimage)
{
	document.getElementById('image2').src = (newimage) ? "{_input._update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}
<!-- END _cat -->
</script>
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
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
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="map_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="map_name" id="map_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_CAT}</label></td>
	<td class="row2">
		<!-- BEGIN _cat -->
		<label><input type="radio" name="cat_id" value="{_input._cat.CAT_ID}" onclick="setRequest('{_input._cat.CAT_ID}')" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END _cat -->
	</td>
</tr>
<tr>
	<td class="row1"><label for="map_type">{L_TYPE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="map_type" id="map_type" value="{TYPE}"></td>
</tr>
</tbody>
<tbody id="close" class="trhover">
<tr>
	<td class="row1"><label for="map_file">{L_FILE}: *</label></td>
	<td class="row2">{S_FILE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="map_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
</tbody>
<tbody id="content" class="trhover">
</tbody>
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