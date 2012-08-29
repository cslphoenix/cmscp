<!-- BEGIN input -->
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

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
<script type="text/javascript">
// <![CDATA[
function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}
// ]]>
</script>
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{cat.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{cat.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{cat.row.tab.option.CSS}"><label for="{cat.row.tab.option.LABEL}" {cat.row.tab.option.EXPLAIN}>{cat.row.tab.option.L_NAME}:</label><span>{cat.row.tab.option.AUTH}</span></td>
	<td class="row2">{cat.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END cat -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<p>{L_EXPLAIN}</p>

<br />

<!-- BEGIN cat -->
<table class="rows">
<tr>
	<th><span class="righti">{display.cat.PATH}</span><span title="{display.cat.TYPE}">{display.cat.NAME}</span></th>
	<th>{display.cat.MOVE_UP}{display.cat.MOVE_DOWN}{display.cat.RESYNC}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- BEGIN file -->
<tr> 
	<td>{display.cat.file.NAME}</td>
	<td>{display.cat.file.RESYNC}{display.cat.file.MOVE_UP}{display.cat.file.MOVE_DOWN}{display.cat.file.UPDATE}{display.cat.file.DELETE}</td>
</tr>
<!-- END file -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.cat.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.cat.S_SUBMIT}" value="{L_CREATE}"></td>
</tr>
</table>

<br />
<!-- END cat -->

<table class="lfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

</form>
<!-- END display -->