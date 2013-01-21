<h1>{L_HEAD}</h1>
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
	<dt class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
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
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span style="float: right;">{display.row.USERS} {display.row.STATUS}</span>{display.row.TYPE} {display.row.NAME}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE} {display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="server_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN type_display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_VOICE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN voice_row -->
<tr>
	<td><span class="righti">{type_display.voice_row.GAME} &bull; {type_display.voice_row.DPORT}</span>{type_display.voice_row.NAME}</td>
	<td>{type_display.voice_row.UPDATE} {type_display.voice_row.DELETE}</td>
</tr>
<!-- END voice_row -->
<!-- BEGIN voice_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END game_empty -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_GAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td><span class="righti">{type_display.game_row.GAME} &bull; {type_display.game_row.DPORT}</span>{type_display.game_row.NAME}</td>
	<td>{type_display.game_row.UPDATE} {type_display.game_row.DELETE}</td>
</tr>
<!-- END game_row -->
<!-- BEGIN game_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END game_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="type_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END type_display -->