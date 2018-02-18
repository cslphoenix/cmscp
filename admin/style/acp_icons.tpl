<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>

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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th style="width:85%;">{L_NAME}</th>
	<!-- BEGIN smilies -->
	<th>{L_CODE}</th>
	<th>{L_EMOTION}</th>
	<!-- END smilies -->
	<th>{L_SETTINGS}</th>
	
</tr>
<!-- BEGIN row -->
<tr>
	<td style="text-align:center;">{display.row.SHOW}</td>
	<!-- BEGIN smilies -->
	<td>{display.row.CODE}</td>
	<td>{display.row.EMOTION}</td>
	<!-- END smilies -->
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}&nbsp;{display.row.UPDATE}&nbsp;{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="rfooter">
<tr>
	<td><input type="submit" name="append" value="{L_CREATE} append"></td>
	<td><input type="submit" name="modify" value="{L_CREATE} modify"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->