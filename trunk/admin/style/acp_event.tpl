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
	<td align="right">{L_SORT}: {S_LEVEL}</td>
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
	<td><span style="float:right;">{_display._new_row.DATE}</span>{_display._new_row.TITLE}</td>
	<td>{_display._new_row.UPDATE} {_display._new_row.DELETE}</td>		
</tr>
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
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
	<td><span style="float:right;">{_display._old_row.DATE}</span>{_display._old_row.TITLE}</td>
	<td>{_display._old_row.UPDATE} {_display._old_row.DELETE}</td>		
</tr>
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<form action="{S_ACTION}" method="post">
<table class="footer">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td><input type="text" class="post" name="event_title" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
{TINYMCE}
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
	<td class="row1"><label for="event_title">{L_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="event_title" id="event_title" value="{TITLE}" /></td>
</tr>
<tr>
	<td class="row1"><label for="event_level">{L_LEVEL}:</label></td>
	<td class="row2">{S_LEVEL}</td>
</tr>
<tr>
	<td class="row1"><label for="event_desc">{L_DESC}: *</label></td>
	<td class="row2"><textarea class="textarea" name="event_desc" id="event_desc" rows="20" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label for="duration">{L_DURATION}:</label></td>
	<td class="row2">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="event_comments">{L_COMMENTS}:</label></td>
	<td class="row2"><label><input type="radio" name="event_comments" id="event_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="event_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
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