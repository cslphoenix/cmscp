<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
	<td class="rowHead" width="99%">{L_TITLE}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _dl_cat_row -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{_display._dl_cat_row.FILES}&nbsp;<br /><input type="submit" class="button" name="_create_file[{_display._dl_cat_row.ID}]" value="{L_CREATE_FILE}"></span><b>{_display._dl_cat_row.TITLE}</b><br />{_display._dl_cat_row.DESC}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._dl_cat_row.MOVE_UP}{_display._dl_cat_row.MOVE_DOWN} {_display._dl_cat_row.OVERVIEW} <a href="{_display._dl_cat_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._dl_cat_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _dl_cat_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="2">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="cat_title"></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
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
				<li id="active"><a href="#" id="current">{L_INFOS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="cat_title">{L_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_title" id="cat_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_DESC}:</label></td>
	<td class="row2"><textarea class="textarea" name="cat_desc" rows="5" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_ICON}:</label></td>
	<td class="row2">{S_SELECT}</td>
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