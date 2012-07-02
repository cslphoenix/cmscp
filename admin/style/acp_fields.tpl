<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

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
	<th>{display.cat.MOVE_UP}{display.cat.MOVE_DOWN}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- BEGIN field -->
<tr>
	<td><span class="right">{display.cat.field.TYPE}</span>{display.cat.field.NAME}</td>
	<td>{display.cat.field.MOVE_UP}{display.cat.field.MOVE_DOWN}{display.cat.field.UPDATE}{display.cat.field.DELETE}</td>
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

<!-- BEGIN input -->
<script type="text/javascript">  
// <![CDATA[
	function set_right(id)
	{
		var obj = document.getElementById(id).checked = true;
	}
// ]]>
</script>
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
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
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input_cat -->