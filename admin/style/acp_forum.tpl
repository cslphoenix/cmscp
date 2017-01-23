<li class="header">{L_HEADER}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('copy', -1);
		dE('forum_desc', -1);
		dE('forum_icons', -1);
		dE('forum_legend', -1);
		dE('forum_status', -1);
	}
	else 
	{
		dE('main', 1);
		dE('copy', 1);
		dE('forum_desc', 1);
		dE('forum_icons', 1);
		dE('forum_legend', 1);
		dE('forum_status', 1);
	}
}

</script>
<form action="{S_ACTION}" method="post">
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

<!-- BEGIN list -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th><span class="right">{UPDATE}{DELETE}</span></th>
</tr>
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="menu_label" /></td>
	<td><input type="submit" value="{L_CREATE_FORUM}"></td>
</tr>
</table>
<br />
<!-- BEGIN row -->
<table class="rows">
<tr>
	<td class="label">{list.row.NAME}</td>
	<td>{list.row.MOVE_DOWN}{list.row.MOVE_UP}{list.row.UPDATE}{list.row.DELETE}</td>
</tr>
<!-- BEGIN sub -->
<tr>
	<td>{list.row.sub.NAME}</td>
	<td>{list.row.sub.MOVE_DOWN}{list.row.sub.MOVE_UP}{list.row.sub.UPDATE}{list.row.sub.DELETE}</td>
</tr>
<!-- END sub -->
<!-- BEGIN empty_sub -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY_SUBFORUM}</td>
</tr>
<!-- END empty_sub -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{list.row.S_NAME}" /></td>
	<td><input type="submit" name="{list.row.S_SUBMIT}" value="{L_CREATE_SUBFORUM}" /></td>
</tr>
</table>
<br />
<!-- END row -->

<!-- BEGIN empty -->
<table class="rows">
<tr>
	<td class="empty" colspan="2">{L_EMPTY_FORUM}</td>
</tr>
</table>
<!-- END empty -->
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
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{INPUT_NAME}" /></td>
	<td><input type="submit" name="{INPUT_SUBMIT}" value="{CREATE}" /></td>
</tr>
</table>

<br />

<table class="rows">
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- BEGIN subrow -->
<tr>
	<td><span style="padding:0px 8px;"></span>{display.row.subrow.NAME}</td>
	<td><span style="padding:0px 8px;"></span>{display.row.subrow.MOVE_DOWN}{display.row.subrow.MOVE_UP}{display.row.subrow.UPDATE}{display.row.subrow.DELETE}</td>
</tr>
<!-- END subrow -->
<!-- BEGIN br_empty -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{display.row.S_NAME}" /></td>
	<td><input type="submit" name="{display.row.S_SUBMIT}" value="{L_CREATE_SUBFORUM}" /></td>
</tr>
</table>
<br />
<table class="rows">
<!-- END br_empty -->
<!-- BEGIN br_empty2 -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{display.row.S_NAME}" /></td>
	<td><input type="submit" name="{display.row.S_SUBMIT}" value="{L_CREATE_SUBFORUM}" /></td>
</tr>
<!-- END br_empty2 -->
<!-- END row -->
</table>

<!-- BEGIN empty -->
<table class="rows">
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
</table>
<!-- END empty -->
{S_FIELDS}
</form>
<!-- END display -->