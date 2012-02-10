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
<tr>
	<td align="right">{L_SORT}: {S_SORT}</td>
</tr>
</table>
</form>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _new_row -->
<tr>
	<td><span style="float:right;">{_display._new_row.DATE}</span>{_display._new_row.GAME} {_display._new_row.NAME}</td>
	<td>{_display._new_row.UPDATE} {_display._new_row.DELETE}</td>		
</tr>
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _old_row -->
<tr>
	<td><span style="float:right;">{_display._old_row.DATE}</span>{_display._old_row.GAME} {_display._old_row.NAME}</td>
	<td>{_display._old_row.UPDATE} {_display._old_row.DELETE}</td>		
</tr>
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<form action="{S_ACTION}" method="post">
<table class="footer">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td><input type="text" class="post" name="training_vs"> {S_TEAMS}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
{TINYMCE}
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

<br /><div>{ERROR_BOX}</div>

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
	<td class="row1"><label for="training_vs">{L_VS}: *</label></td>
	<td class="row2"><input class="post" type="text" name="training_vs" id="training_vs" value="{VS}"></td>
</tr>
<tr>
	<td class="row1"><label for="team_id">{L_TEAM}: *</label></td>
	<td class="row2">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row2">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DURATION}:</label></td>
	<td class="row2">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label>{L_MAPS}: *</label></td>
	<td class="row2">
		<div id="close">
			<table border="0" cellspacing="0" cellpadding="0">
			<!-- BEGIN _maps_row -->
			<tr>
				<td>{_input._maps_row.MAPS}<input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
			</tr>
			<!-- END _maps_row -->
			</table>
			{S_MAPS}
		</div>
		<div id="content"></div>
	</td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
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