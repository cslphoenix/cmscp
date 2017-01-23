<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
	<!-- BEGIN option -->
	{input.row.tab.option.DIV_START}
	<dl>			
		<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
		<dd>{input.row.tab.option.OPTION}</dd>
	</dl>
	{input.row.tab.option.DIV_END}
	<!-- END option -->
</fieldset>
<!-- END tab -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_LINK}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row_1 -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.row_1.LINK}</span>{display.row_1.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.row_1.SHOW}{display.row_1.MOVE_DOWN}{display.row_1.MOVE_UP}{display.row_1.UPDATE}{display.row_1.DELETE}</td>
</tr>
<!-- END row_1 -->
<!-- BEGIN no_entry_1 -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_1 -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="network_name[1]"></td>
	<td><input type="submit" class="button2" name="network_type[1]" value="{L_CREATE_LINK}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_PARTNER}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row_2 -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.row_2.LINK}</span>{display.row_2.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.row_2.SHOW}{display.row_2.MOVE_DOWN}{display.row_2.MOVE_UP}{display.row_2.UPDATE}{display.row_2.DELETE}</td>
</tr>
<!-- END row_2 -->
<!-- BEGIN no_entry_2 -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_2 -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="network_name[2]"></td>
	<td><input type="submit" class="button2" name="network_type[2]" value="{L_CREATE_PARTNER}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_SPONSOR}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row_3 -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.row_3.LINK}</span>{display.row_3.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.row_3.SHOW}{display.row_3.MOVE_DOWN}{display.row_3.MOVE_UP}{display.row_3.UPDATE}{display.row_3.DELETE}</td>
</tr>
<!-- END row_3 -->
<!-- BEGIN no_entry_3 -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_3 -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="network_name[3]"></td>
	<td><input type="submit" class="button2" name="network_type[3]" value="{L_CREATE_SPONSOR}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->