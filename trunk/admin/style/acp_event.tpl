<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li><li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<p>{L_EXPLAIN}</p>
<ul id="navopts"><li>{L_SORT}: {S_LEVEL}</li></ul>
</form>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>

<!-- BEGIN new -->
<tr>
	<td><span class="right">{display.new.DATE}</span>{display.new.TITLE}</td>
	<td>{display.new.UPDATE}{display.new.DELETE}</td>		
</tr>
<!-- END new -->
<!-- BEGIN new_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END new_empty -->
</table>

<form action="{S_ACTION}" method="post">
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
	<td><span class="right">{display.old.DATE}</span>{display.old.TITLE}</td>
	<td>{display.old.UPDATE}{display.old.DELETE}</td>		
</tr>
<!-- END old -->
<!-- BEGIN old_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
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