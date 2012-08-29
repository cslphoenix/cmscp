<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<th>{L_LINK}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN link_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.link_row.LINK}</span>{display.link_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.link_row.SHOW}{display.link_row.MOVE_DOWN}{display.link_row.MOVE_UP}{display.link_row.UPDATE}{display.link_row.DELETE}</td>
</tr>
<!-- END link_row -->
<!-- BEGIN no_entry_link -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_link -->
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
<!-- BEGIN partner_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.partner_row.LINK}</span>{display.partner_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.partner_row.SHOW}{display.partner_row.MOVE_DOWN}{display.partner_row.MOVE_UP}{display.partner_row.UPDATE}{display.partner_row.DELETE}</td>
</tr>
<!-- END partner_row -->
<!-- BEGIN no_entry_partner -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_partner -->
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
<!-- BEGIN sponsor_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.sponsor_row.LINK}</span>{display.sponsor_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{display.sponsor_row.SHOW}{display.sponsor_row.MOVE_DOWN}{display.sponsor_row.MOVE_UP}{display.sponsor_row.UPDATE}{display.sponsor_row.DELETE}</td>
</tr>
<!-- END sponsor_row -->
<!-- BEGIN no_entry_sponsor -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_sponsor -->
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

<!-- BEGIN input -->
{AJAX}
<form action="{S_ACTION}" method="post" name="post" id="post" enctype="multipart/form-data">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

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