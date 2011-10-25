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

<<<<<<< .mine
<table border="0" cellspacing="1" cellpadding="0">
=======
<table class="footer">
>>>>>>> .r85
<tr>
<<<<<<< .mine
	<td align="right"><input type="text" class="post" name="cat_name" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
=======
	<td></td>
	<td></td>
	<td><input type="text" class="post" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
>>>>>>> .r85
</tr>
<<<<<<< .mine
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
=======
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="rows">
>>>>>>> .r85
<tr>
<<<<<<< .mine
	<td class="rowHead" align="left" width="99%">{_display._cat_row.NAME}</td>
	<td class="rowHead" align="left" width="99%">{L_AUTH}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} <a href="{_display._cat_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row.U_RESYNC}">{I_RESYNC}</a> <a href="{_display._cat_row.U_DELETE}">{I_DELETE}</a></td>
=======
	<th>{_display._cat_row.NAME}</th>
	<th>{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN}{_display._cat_row.RESYNC} {_display._cat_row.UPDATE} {_display._cat_row.DELETE}</th>
>>>>>>> .r85
</tr>
<<<<<<< .mine
<!-- BEGIN _file_row -->
<tr> 
	<td class="row_class1" align="left" width="99%">
		<span style="float:right;">{_display._cat_row._file_row.TOPICS} / {_display._cat_row._file_row.POSTS}</span>
		<span class="gen"><a href="{_display._cat_row._file_row.U_VIEWFORUM}">{_display._cat_row._file_row.NAME}</a></span><br />
		<span class="small">{_display._cat_row._file_row.DESC}</span>
	</td>
	<td class="row_class1" align="center" nowrap="nowrap">{_display._cat_row._file_row.AUTH}</td>
	<td class="row_class2" align="center">{_display._cat_row._file_row.MOVE_UP}{_display._cat_row._file_row.MOVE_DOWN} <a href="{_display._cat_row._file_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row._file_row.U_RESYNC}">{I_RESYNC}</a> <a href="{_display._cat_row._file_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _file_row -->
<!-- BEGIN _entry_empty -->
=======
<!-- BEGIN _file_row -->
<tr> 
	<td>{_display._cat_row._file_row.NAME}</td>
	<td>{_display._cat_row._file_row.MOVE_UP}{_display._cat_row._file_row.MOVE_DOWN}{_display._cat_row._file_row.RESYNC} {_display._cat_row._file_row.UPDATE} {_display._cat_row._file_row.DELETE}</td>
</tr>
<!-- END _file_row -->
<!-- BEGIN _entry_empty -->
>>>>>>> .r85
<tr>
<<<<<<< .mine
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
=======
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
>>>>>>> .r85
</tr>
<!-- END _entry_empty -->
</table>
<br />
<!-- END _cat_row -->

<<<<<<< .mine
<!-- BEGIN _entry_empty -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
=======
<table class="footer">
>>>>>>> .r85
<tr>
<<<<<<< .mine
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
=======
	<td></td>
	<td></td>
	<td><input type="text" class="post" name="{_display._cat_row.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{_display._cat_row.S_SUBMIT}" value="{L_CREATE_FORUM}"></td>
>>>>>>> .r85
</tr>
</table>
<<<<<<< .mine
<!-- END _entry_empty -->
=======
<br />
<!-- END _cat_row -->
>>>>>>> .r85
</form>
<!-- END _display -->

<!-- BEGIN _input -->
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
	<td class="row1"><label for="cat_title">{L_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_title" id="cat_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_DESC}:</label></td>
	<td class="row2"><textarea class="textarea" name="cat_desc" rows="5" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label>{L_ICON}:</label></td>
	<td class="row2">{S_SELECT}</td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:2px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _input_cat -->
{TINYMCE}
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
	<td class="row1"><label for="cat_icon">{L_ICON}: *</label></td>
	<td class="row2">{S_ICONS}</td>
</tr>
<tr>
	<td class="row1"><label for="cat_desc">{L_DESC}: *</label></td>
	<td class="row2"><textarea class="textarea" name="cat_desc" id="cat_desc" rows="10" style="width:100%">{DESC}</textarea></td>
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
<<<<<<< .mine
<!-- END _input -->

<!-- BEGIN _input_cat -->
{TINYMCE}
<form action="{S_ACTION}" method="post">
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
<tbody class="trhover">
<tr>
	<td class="row1"><label for="cat_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_icon">{L_ICON}: *</label></td>
	<td class="row2">{S_ICONS}</td>
</tr>
<tr>
	<td class="row1"><label for="cat_desc">{L_DESC}: *</label></td>
	<td class="row2"><textarea class="textarea" name="cat_desc" id="cat_desc" rows="10" style="width:100%">{DESC}</textarea></td>
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
<!-- END _input_cat -->=======
<!-- END _input_cat -->>>>>>>> .r85
