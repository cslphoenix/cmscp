<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _row -->
<table class="rows">
<tr>
	<th>{_display._row.NAME}</th>
	<th>{_display._row.MOVE_UP}{_display._row.MOVE_DOWN}{_display._row.CREATE}{_display._row.UPDATE}{_display._row.DELETE}</th>
</tr>
<!-- BEGIN _file -->
<tr>
	<td>{_display._row._file.NAME}</td>
	<td>{_display._row._file.MOVE_UP}{_display._row._file.MOVE_DOWN}{_display._row._file.CREATE}{_display._row._file.UPDATE}{_display._row._file.DELETE}</td>
</tr>
<!-- END _file -->
</table>
<br />
<!-- END _row -->
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _cat -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_DATA}</a></li>
</ul>
</div>

<table class="update">
<tr>
	<td class="row1"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2">{NAME}</td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
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
<!-- END _cat -->

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
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_DATA}</a></li>
</ul>
</div>

<table class="update">
<tr>
	<td class="row1"><label for="file_name">{L_NAME}:</label></td>
	<td class="row2">{NAME}</td>
</tr>
<tr>
	<td class="row1"><label for="file_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
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