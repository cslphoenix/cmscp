<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_UPCOMING}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_training_n -->
<tr>
	<td class="row_class1" align="center">{display.row_training_n.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{display.row_training_n.DATE}</span>{display.row_training_n.NAME}</td>
	<td class="row_class2" align="center"><a href="{display.row_training_n.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_training_n.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END row_training_n -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_EXPIRED}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_training_o -->
<tr>
	<td class="row_class1" align="center">{display.row_training_o.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{display.row_training_o.DATE}</span>{display.row_training_o.NAME}</td>
	<td class="row_class2" align="center"><a href="{display.row_training_o.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_training_o.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END row_training_o -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="left">{S_LIST}</td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right"><input type="text" class="post" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN training_edit -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="training_vs">{L_VS}: *</label></td>
	<td class="row3"><input class="post" type="text" name="training_vs" id="training_vs" value="{VS}"></td>
</tr>
<tr>
	<td class="row1"><label for="team_id">{L_TEAM}: *</label></td>
	<td class="row3">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row3">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DURATION}:</label></td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="training_maps">{L_MAPS}: *</label></td>
	<td class="row3"><input class="post" type="text" name="training_maps" id="training_maps" value="{MAPS}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END training_edit -->

<!-- BEGIN training_list -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li id="active"><a href="#" id="right">{L_LIST}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_UPCOMING}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_training_n -->
<tr>
	<td class="row_class1" align="center">{training_list.row_training_n.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{training_list.row_training_n.DATE}</span>{training_list.row_training_n.NAME}</td>
	<td class="row_class2" align="center"><a href="{training_list.row_training_n.U_UPDATE}">{I_UPDATE}</a> <a href="{training_list.row_training_n.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END row_training_n -->
<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_EXPIRED}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_training_o -->
<tr>
	<td class="row_class1" align="center">{training_list.row_training_o.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{training_list.row_training_o.DATE}</span>{training_list.row_training_o.NAME}</td>
	<td class="row_class2" align="center"><a href="{training_list.row_training_o.U_UPDATE}">{I_UPDATE}</a> <a href="{training_list.row_training_o.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END row_training_o -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END training_list -->