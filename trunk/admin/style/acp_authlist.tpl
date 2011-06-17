<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _authlist_row -->
<tr>
	<td>{_display._authlist_row.NAME}</td>
	<td>{_display._authlist_row.UPDATE} {_display._authlist_row.DELETE}</td>		
</tr>
<!-- END _authlist_row -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="authlist_name" value="" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="authlist_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="authlist_name" id="authlist_name" value="{NAME}" /></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}" /><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->