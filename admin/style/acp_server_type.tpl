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
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_VOICE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _voice_row -->
<tr class="hover">
	<td><span class="righti">{_display._voice_row.GAME} &bull; {_display._voice_row.DPORT}</span>{_display._voice_row.NAME}</td>
	<td>{_display._voice_row.UPDATE} {_display._voice_row.DELETE}</td>
</tr>
<!-- END _voice_row -->
<!-- BEGIN _game_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END _game_empty -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_GAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _game_row -->
<tr class="hover">
	<td><span class="righti">{_display._game_row.GAME} &bull; {_display._game_row.DPORT}</span>{_display._game_row.NAME}</td>
	<td>{_display._game_row.UPDATE} {_display._game_row.DELETE}</td>
</tr>
<!-- END _game_row -->
<!-- BEGIN _voice_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END _voice_empty -->
</table>

<table class="footer">
<tr>
	<td><input type="text" class="post" name="type_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td></td>
	<td></td>
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
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="type_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="type_name" id="type_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="type_game">{L_GAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="type_game" id="type_game" value="{GAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="type_dport">{L_DPORT}: *</label></td>
	<td class="row2"><input type="text" class="post" name="type_dport" id="type_dport" value="{DPORT}" size="4"></td>
</tr>
<tr>
	<td class="row1"><label for="type_dport">{L_SORT}:</label></td>
	<td class="row2">
		<label><input type="radio" name="type_dport" id="type_dport" value="0" {S_GAME} />&nbsp;{L_GAMESERVER}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="type_dport" value="1" {S_VOICE} />&nbsp;{L_VOICESERVER}</label></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->