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
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_UPCOMING}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _training_row_new -->
<tr>
	<td class="row_class1" align="center">{_display._training_row_new.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_row_new.DATE}</span>{_display._training_row_new.NAME}</td>
	<td class="row_class2" align="center"><a href="{_display._training_row_new.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._training_row_new.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END _training_row_new -->
<!-- BEGIN _no_entry_new -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{L_NOENTRY}</td>
</tr>
<!-- END _no_entry_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_EXPIRED}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _training_row_old -->
<tr>
	<td class="row_class1" align="center">{_display._training_row_old.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_row_old.DATE}</span>{_display._training_row_old.NAME}</td>
	<td class="row_class2" align="center"><a href="{_display._training_row_old.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._training_row_old.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END _training_row_old -->
<!-- BEGIN _no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{L_NOENTRY}</td>
</tr>
<!-- END _no_entry_old -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<form action="{S_ACTION}" method="post">
	<td align="left">{S_LIST}</td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right"><input type="text" class="post" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
	{S_FIELDS}
	</form>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END _display -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
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
	<td class="row1" width="150"><label for="training_vs">{L_VS}: *</label></td>
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
	<td class="row1"><label for="training_maps">{L_MAPS}: *</label></td>
	<td class="row2"><input class="post" type="text" name="training_maps" id="training_maps" value="{MAPS}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
</tr>
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