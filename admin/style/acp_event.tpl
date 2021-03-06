<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>

<!-- BEGIN new -->
<tr>
	<td><span class="right">{display.new.DATE}</span>{display.new.TITLE}<br /><span class="small">&nbsp;<b>&raquo;</b>&nbsp;{display.new.GROUPS}</span></td>
	<td>{display.new.UPDATE}{display.new.DELETE}</td>		
</tr>
<!-- END new -->
<!-- BEGIN new_empty -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END new_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="event_title" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}" /></td>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN old -->
<tr>
	<td><span class="right">{display.old.DATE}</span>{display.old.TITLE}<br /><span class="small">&nbsp;<b>&raquo;</b>&nbsp;{display.old.GROUPS}</span></td>
	<td>{display.old.UPDATE}{display.old.DELETE}</td>		
</tr>
<!-- END old -->
<!-- BEGIN old_empty -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END old_empty -->
</table>

<table class="rfooter">
    <td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->