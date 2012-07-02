<!-- BEGIN display -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_UPCOMING}</td>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN training_new_row -->
<tr>
	<td class="row_class1" align="center">{display.training_new_row.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.training_new_row.DATE}</span>{display.training_new_row.NAME}</td>
	<td class="row_class2" align="center">{display.training_new_row.UPDATE} {display.training_new_row.DELETE}</td>		
</tr>
<!-- END training_new_row -->
<!-- BEGIN entry_empty_new -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_new -->
</table>

<br />

<table class="rows">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_EXPIRED}</td>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN training_old_row -->
<tr>
	<td class="row_class1" align="center">{display.trainingold_row.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span class="right">{display.trainingold_row.DATE}</span>{display.trainingold_row.NAME}</td>
	<td class="row_class2" align="center">{display.trainingold_row.UPDATE} {display.trainingold_row.DELETE}</td>		
</tr>
<!-- END training_old_row -->
<!-- BEGIN entry_empty_old -->
<tr>
	<td class="empty" colspan="3" align="center">{L_EMPTY}</td>
</tr>
<!-- END entry_empty_old -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<form action="{S_ACTION}" method="post">
	<td align="left">{S_SORT}</td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right"><input type="text" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
	{S_FIELDS}
	</form>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td class="row5 small"><span class="right">{PAGE_PAGING}</span>{PAGE_NUMBER}</td>
</tr>
</table>
<!-- END display -->

<!-- BEGIN input -->
{AJAX}
{TINYMCE}
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
</div>
<table class="header">
<tr>
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br /><div>{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="training_vs">{L_VS}:</label></td>
	<td class="row2"><input class="post" type="text" name="training_vs" id="training_vs" value="{VS}"></td>
</tr>
<tr>
	<td class="row1"><label for="team_id">{L_TEAM}:</label></td>
	<td>{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td>{S_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td>{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DURATION}:</label></td>
	<td>{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label>{L_MAPS}:</label></td>
	<td>
		<div id="close">
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN maps_row -->
		<tr>
			<td>{_input._mapsrow.MAPS}<input type="button" class="more" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END maps_row -->
		</table>
		{S_MAPS}</div><div id="ajax_content"></div>
	</td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
</tr>
</tbody>
<tr>
	<td colspan="2"></td>
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
<!-- END input -->