<!-- BEGIN input -->
{AJAX}
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

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input_cat.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input_cat.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input_cat.row.tab.option.CSS}"><label for="{input_cat.row.tab.option.LABEL}" {input_cat.row.tab.option.EXPLAIN}>{input_cat.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input_cat.row.tab.option.OPTION}</td>
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
<!-- END input_cat -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="lfooter">
<tr>
	<td><input type="text" name="field_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}" /></td>
</tr>
</table>

<br />

<!-- BEGIN cat -->
<table class="rows">
<tr>
	<th>{display.cat.NAME}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- BEGIN field -->
<tr>
	<td><span class="right">{display.cat.field.TYPE}</span>{display.cat.field.NAME}</td>
	<td>{display.cat.field.MOVE_DOWN}{display.cat.field.MOVE_UP}{display.cat.field.UPDATE}{display.cat.field.DELETE}</td>
</tr>
<!-- END field -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.cat.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.cat.S_SUBMIT}" value="{L_CREATE_FIELD}" /></td>
</tr>
</table>
<br />
<!-- END cat -->
</form>
<!-- END display -->