<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_NAME}</td>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN style_row -->
<tr>
	<td class="row_class1" align="left">{display.stylerow.NAME_STYLE}</td>
	<td class="row_class1" align="left">{display.stylerow.NAME_TEMPLATE}</td>
	<td class="row_class2" align="center">{display.stylerow.UPDATE} {display.stylerow.DELETE}</td>
</tr>
<!-- END style_row -->
<!-- BEGIN entry_empty -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
</tr>
<!-- END entry_empty -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" name="game_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
{UIMG}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1r"><label for="game_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="game_name" id="game_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="game_tag">{L_TAG}:</label></td>
	<td class="row2"><input type="text" name="game_tag" id="game_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="game_image">{L_IMAGE}:</label></td>
	<td>{S_IMAGE} <img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="game_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
</tbody>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->