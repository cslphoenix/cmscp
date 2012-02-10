<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
	<td class="rowHead" width="99%" colspan="2">{L_NAME}</td>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _style_row -->
<tr>
	<td class="row_class1" align="left">{_display._style_row.NAME_STYLE}</td>
	<td class="row_class1" align="left">{_display._style_row.NAME_TEMPLATE}</td>
	<td class="row_class2" align="center">{_display._style_row.UPDATE} {_display._style_row.DELETE}</td>
</tr>
<!-- END _style_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="game_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{UIMG}
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
	<td class="row1"><label for="game_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_name" id="game_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="game_tag">{L_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_tag" id="game_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="game_image">{L_IMAGE}:</label></td>
	<td class="row2">{S_IMAGE} <img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="game_order">{L_ORDER}:</label></td>
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
<!-- END _input -->