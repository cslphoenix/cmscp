<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN append -->
<form action="{S_ACTION}" method="post">

{ERROR_BOX}

<table class="row">
<tr>
	<th>{L_SYMBOL}L_SYMBOL</th>
	<!-- BEGIN option -->
	<th>{append.option.OPTION}</th>
	<!-- END option -->
</tr>
<!-- BEGIN rows -->
<tr>
	<td>{append.rows.SYMBOL}</td>
	<!-- BEGIN type_option -->
	<td>{append.rows.type_option.OPTION}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>


<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END append -->

<!-- BEGIN modify -->
<form action="{S_ACTION}" method="post">
<table class="update">
<tr>
	<!-- BEGIN name_option -->
	<th>{modify.name_option.NAME}</th>
	<!-- END name_option -->
</tr>
<!-- BEGIN row -->
<tr>
	<!-- BEGIN type_option -->
	<td align="center">{modify.row.type_option.TYPE}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}S_FIELDS
</form>
<!-- END modify -->

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.row.SHOW}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE} {display.row.DELETE}</td>
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
	<td><input type="text" name="game_name" /></td>
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="lfooter">
<tr>
	<td><input type="submit" name="append" value="{L_CREATE}"></td>
	<td><input type="submit" name="modify" value="{L_CREATE}"></td>
</tr>
</table>
{LIST}
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN list -->
<form action="{S_ACTION}" method="post">
<table class="update">
<tr>
	<!-- BEGIN name_option -->
	<th>{list.name_option.NAME}</th>
	<!-- END name_option -->
</tr>
<!-- BEGIN row -->
<tr>
	<!-- BEGIN type_option -->
	<td align="center">{list.row.type_option.TYPE}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END list -->