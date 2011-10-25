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
<<<<<<< .mine
	<td class="row_class1" align="center">{_display._training_new_row.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_new_row.DATE}</span>{_display._training_new_row.NAME}</td>
	<td class="row_class2" align="center">{_display._training_new_row.UPDATE} {_display._training_new_row.DELETE}</td>		
=======
	<td><span style="float:right;">{_display._new_row.DATE}</span>{_display._new_row.GAME} {_display._new_row.NAME}</td>
	<td>{_display._new_row.UPDATE} {_display._new_row.DELETE}</td>		
>>>>>>> .r85
</tr>
<<<<<<< .mine
<!-- END _training_new_row -->
<!-- BEGIN _entry_empty_new -->
=======
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
>>>>>>> .r85
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
<<<<<<< .mine
	<td class="row_class1" align="center">{_display._training_old_row.GAME}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_old_row.DATE}</span>{_display._training_old_row.NAME}</td>
	<td class="row_class2" align="center">{_display._training_old_row.UPDATE} {_display._training_old_row.DELETE}</td>		
=======
	<td><span style="float:right;">{_display._old_row.DATE}</span>{_display._old_row.GAME} {_display._old_row.NAME}</td>
	<td>{_display._old_row.UPDATE} {_display._old_row.DELETE}</td>		
>>>>>>> .r85
</tr>
<<<<<<< .mine
<!-- END _training_old_row -->
<!-- BEGIN _entry_empty_old -->
=======
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
>>>>>>> .r85
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<form action="{S_ACTION}" method="post">
<table class="footer">
<tr>
<<<<<<< .mine
	<form action="{S_ACTION}" method="post">
	<td align="left">{S_SORT}</td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right"><input type="text" class="post" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
	{S_FIELDS}
	</form>
=======
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
	<td><input type="text" class="post" name="training_vs"> {S_TEAMS}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	
>>>>>>> .r85
</tr>
</table>
<<<<<<< .mine

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td class="row5 small"><span class="show_right">{PAGE_PAGING}</span>{PAGE_NUMBER}</td>
</tr>
</table>
=======
{S_FIELDS}
</form>
>>>>>>> .r85
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
<<<<<<< .mine
	<td class="row4 small"colspan="2">{L_REQUIRED}</td>
=======
	<td>{L_REQUIRED}</td>
>>>>>>> .r85
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
<<<<<<< .mine
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _maps_row -->
		<tr>
			<td>{_input._maps_row.MAPS}<input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END _maps_row -->
		</table>
		{S_MAPS}</div><div id="content"></div>
=======
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
>>>>>>> .r85
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