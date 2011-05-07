<!-- BEGIN _display -->
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
<tr>
	<td class="small" align="right"><form action="{S_ACTION}" method="post">{L_SORT}: {S_LEVEL}</form></td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%">{L_UPCOMING}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _event_new_row -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{_display._event_new_row.DATE}</span>{_display._event_new_row.TITLE}</td>
	<td class="row_class2" align="center">{_display._event_new_row.UPDATE} {_display._event_new_row.DELETE}</td>		
</tr>
<!-- END _event_new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="2" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%">{L_EXPIRED}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _event_old_row -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{_display._event_old_row.DATE}</span>{_display._event_old_row.TITLE}</td>
	<td class="row_class2" align="center">{_display._event_old_row.UPDATE} {_display._event_old_row.DELETE}</td>		
</tr>
<!-- END _event_old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="2" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<form action="{S_ACTION}" method="post">
<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="event_title"></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
<tr>
	<td class="row4" colspan="2"><span class="show_right">{PAGE_PAGING}</span>{PAGE_NUMBER}</td>
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

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="event_title">{L_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="event_title" id="event_title" value="{TITLE}"></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->