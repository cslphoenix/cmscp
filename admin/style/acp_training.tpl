<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>
<ul id="navopts"><li>{L_SORT}: {S_SORT}</li></ul>
</form>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td><span class="right">{display.new_row.DATE}</span>{display.new_row.GAME} {display.new_row.NAME}</td>
	<td>{display.new_row.UPDATE}{display.new_row.DELETE}</td>		
</tr>
<!-- END new_row -->
<!-- BEGIN new_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END new_empty -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td><span class="right">{display.old_row.DATE}</span>{display.old_row.GAME} {display.old_row.NAME}</td>
	<td>{display.old_row.UPDATE}{display.old_row.DELETE}</td>		
</tr>
<!-- END old_row -->
<!-- BEGIN old_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END old_empty -->
</table>

<form action="{S_ACTION}" method="post">
<table class="footer">
<tr>
	<td><input type="text" name="training_vs" /></td>
	<td>{S_TEAMS}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
{AJAX}
{TINYMCE}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div>{ERROR_BOX}</div>

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

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="training_vs">{L_VS}:</label></td>
	<td class="row2"><input class="post" type="text" name="training_vs" id="training_vs" value="{VS}"></td>
</tr>
<tr>
	<td class="row1r"><label for="team_id">{L_TEAM}:</label></td>
	<td class="row2">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row2">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY}.{S_MONTH}.{S_YEAR} - {S_HOUR}:{S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DURATION}:</label></td>
	<td class="row2">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1r"><label>{L_MAPS}:</label></td>
	<td class="row2">
		<div id="close">
			<div>
			<!-- BEGIN maps_row -->
				<ul>{_input._mapsrow.MAPS}<input type="button" class="more" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></ul>
			<!-- END maps_row -->
			</div>
			{S_MAPS}
		</div>
		<div id="ajax_content"></div>
	</td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
</tr>
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