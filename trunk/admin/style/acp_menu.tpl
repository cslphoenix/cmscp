<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<!-- BEGIN row -->
<table class="rows">
<tr>
	<th>{display.row.NAME}</th>
	<th>{display.row.MOVE_UP}{display.row.MOVE_DOWN}{display.row.CREATE}{display.row.UPDATE}{display.row.DELETE}</th>
</tr>
<!-- BEGIN file -->
<tr>
	<td><span class="right">{display.row.file.FILE}</span>{display.row.file.NAME}</td>
	<td>{display.row.file.MOVE_UP}{display.row.file.MOVE_DOWN}{display.row.file.CREATE}{display.row.file.UPDATE}{display.row.file.DELETE}</td>
</tr>
<!-- END file -->
</table>
<br />
<!-- END row -->
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
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
	<td class="row1"><label for="file_name">{L_NAME}:</label></td>
	<td class="row2">{NAME}</td>
</tr>
<tr>
	<td class="row1"><label for="file_name">{L_LANG}:</label></td>
	<td class="row2">{LANG}</td>
</tr>
<tr>
	<td class="row1"><label for="file_order">{L_ORDER}:</label></td>
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
	<td class="row1"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2">{NAME}</td>
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