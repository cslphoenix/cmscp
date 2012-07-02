<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_VOICE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN voice_row -->
<tr>
	<td><span class="righti">{display.voice_row.GAME} &bull; {display.voice_row.DPORT}</span>{display.voice_row.NAME}</td>
	<td>{display.voice_row.UPDATE} {display.voice_row.DELETE}</td>
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
	<td><span class="righti">{display.game_row.GAME} &bull; {display.game_row.DPORT}</span>{display.game_row.NAME}</td>
	<td>{display.game_row.UPDATE} {display.game_row.DELETE}</td>
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
<!-- END display -->

<!-- BEGIN input -->
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