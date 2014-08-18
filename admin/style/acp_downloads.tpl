<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('dl_desc', 1);
		dE('dl_file', -1);
		dE('dl_icon', 1);
		dE('dl_types', 1);
		dE('dl_size', 1);
	}
	else
	{
		dE('main', 1);
		dE('dl_desc', -1);
		dE('dl_file', 1);
		dE('dl_icon', -1);
		dE('dl_types', -1);
		dE('dl_size', -1);
	}
}

</script>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div>
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
	<!-- BEGIN option -->
	{input.row.tab.option.DIV_START}
	<dl>			
		<dt class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
		<dd>{input.row.tab.option.OPTION}</dd>
	</dl>
	{input.row.tab.option.DIV_END}
	<!-- END option -->
</fieldset>
<!-- END tab -->
</div>
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

<!-- BEGIN list -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th><span class="right">{UPDATE}{DELETE}</span></th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{list.row.NAME}</td>
	<td>{list.row.MOVE_DOWN}{list.row.MOVE_UP}{list.row.UPDATE}{list.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="dl_file" /></td>
	<td><input type="submit" value="{L_CREATE_FILE}" /></td>
</tr>
</table>
<br />

{S_FIELDS}
</form>
<!-- END list -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="dl_name" /></td>
	<td><input type="submit" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->