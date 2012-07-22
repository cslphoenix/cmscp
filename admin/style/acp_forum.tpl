<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">
{TINYMCE}
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

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

<br /><div align="center">{ERROR_BOX}</div>

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
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE_FORUM}">{L_CREATE_FORUM}</a></li>
	<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li></ul>

<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="lfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

<br />

<!-- BEGIN cat -->
<table class="rows">
<tr>
	<th>{display.cat.NAME}</th>
	<th>{L_AUTH}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.RESYNC}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- BEGIN form -->
<tr> 
	<td>
		<span class="right">{display.cat.form.TOPICS} / {display.cat.form.POSTS}</span>
		<span class="gen"><a href="{display.cat.form.U_VIEWFORUM}">{display.cat.form.NAME}</a></span><br />
		<span class="small">{display.cat.form.DESC}</span>
	</td>
	<td>{display.cat.form.AUTH}</td>
	<td>{display.cat.form.MOVE_DOWN}{display.cat.form.MOVE_UP}{display.cat.form.RESYNC}{display.cat.form.UPDATE}{display.cat.form.DELETE}</td>
</tr>
<!-- BEGIN sub -->
<tr> 
	<td>
		<span class="right">{display.cat.form.sub.TOPICS} / {display.cat.form.sub.POSTS}</span>
		<span class="gen">&nbsp;&not;&nbsp;{display.cat.form.sub.NAME}</span>
	</td>
	<td>{display.cat.form.sub.AUTH}</td>
	<td>{display.cat.form.sub.MOVE_DOWN}{display.cat.form.sub.MOVE_UP}{display.cat.form.sub.RESYNC}{display.cat.form.sub.UPDATE}{display.cat.form.sub.DELETE}</a></td>
</tr>
<!-- END sub -->
<!-- END form -->
<!-- BEGIN empty_cat -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty_cat -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.cat.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.cat.S_SUBMIT}" value="{L_CREATE_FORUM}"></td>
</tr>
</table>

<br />
<!-- END cat -->
</form>
<!-- END display -->