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
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _cat_row -->
<tr>
	<td class="row_class1" align="left">{_display._cat_row.NAME}</td>
	<td class="row_class2" align="center">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} {_display._cat_row.UPDATE} {_display._cat_row.DELETE}</td>		
</tr>
<!-- END _cat_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="cat_title"></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
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
<tr>
	<td class="row1" width="155"><label for="cat_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_image">{L_IMAGE}:</label></td>
	<td class="row2">{S_IMAGE}<br><img src="{IMAGE}" id="image" alt=""></td>
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
<!-- END _input -->