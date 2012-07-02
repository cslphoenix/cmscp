<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<!-- BEGIN cat_row -->
<table class="rows">
<tr>
	<th><span class="righti">{display.catrow.PATH}</span><span title="{display.catrow.TYPE}">{display.catrow.NAME}</span></th>
	<th>{display.catrow.MOVE_UP}{display.catrow.MOVE_DOWN}{display.catrow.RESYNC}{display.catrow.UPDATE}{display.catrow.DELETE}</th>
</tr>
<!-- BEGIN file_row -->
<tr> 
	<td>{display.catrow._filerow.NAME}</td>
	<td>{display.catrow._filerow.RESYNC}{display.catrow._filerow.MOVE_UP}{display.catrow._filerow.MOVE_DOWN}{display.catrow._filerow.UPDATE}{display.catrow._filerow.DELETE}</td>
</tr>
<!-- END file_row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.catrow.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.catrow.S_SUBMIT}" value="{L_CREATE}"></td>
</tr>
</table>

<br />
<!-- END cat_row -->

<table class="lfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

</form>
<!-- END display -->

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="file_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="file_name" id="file_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1">{L_UPLOAD}:</td>
	<td class="row2 top"><input type="file" name="ufile" id="ufile" /></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}"><span style="padding:2px;"></span><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="cat_icon">{L_ICON}:</label></td>
	<td class="row2">{S_ICONS}</td>
</tr>
<tr>
	<td class="row1"><label for="cat_path">{L_PATH}:</label></td>
	<td class="row2">{PATH}</td>
</tr>
<tr>
	<td class="row1r"><label for="cat_types">{L_TYPES}:</label></td>
	<td class="row2">{S_TYPES}</td>
</tr>
<tr>
	<td class="row1r"><label for="cat_desc">{L_DESC}:</label></td>
	<td class="row2"><textarea class="textarea" name="cat_desc" id="cat_desc" cols="40">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1r"><label for="cat_auth">{L_AUTH}:</label></td>
	<td class="row2">{S_AUTH}</td>
</tr>
<tr>
	<td class="row1r"><label for="cat_rate">{L_RATE}:</label></td>
	<td class="row2">{S_RATE}</td>
</tr>
<tr>
	<td class="row1r"><label for="cat_comment">{L_COMMENT}:</label></td>
	<td class="row2">{S_COMMENT}</td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
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
<!-- END cat -->