<!-- BEGIN display -->
<form action="{S_TRAINING_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_TRAINING_HEAD}</a></li>
	<li><a href="{S_TRAINING_CREATE}">{L_TRAINING_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_TRAINING_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">

<tr>
	<td class="rowHead" colspan="2">{L_TRAINING}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<tr>
	<td class="rowHead" colspan="4">{L_TRAINING_UPCOMING}</td>
</tr>
<!-- BEGIN training_row_n -->
<tr>
	<td class="{display.training_row_n.CLASS}" align="center" width="1%">{display.training_row_n.TRAINING_IMAGE}</td>
	<td class="{display.training_row_n.CLASS}" align="left" width="100%"><span style="float:right;">{display.training_row_n.TRAINING_DATE}</span>{display.training_row_n.TRAINING_NAME}</td>
	<td class="{display.training_row_n.CLASS}" align="center" width="1%"><a href="{display.training_row_n.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.training_row_n.CLASS}" align="center" width="1%"><a href="{display.training_row_n.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END training_row_n -->

<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->

<tr>
	<td class="rowHead" colspan="4">{L_TRAINING_EXPIRED}</td>
</tr>
<!-- BEGIN training_row_o -->
<tr>
	<td class="{display.training_row_o.CLASS}" align="center" width="1%">{display.training_row_o.TRAINING_IMAGE}</td>
	<td class="{display.training_row_o.CLASS}" align="left" width="100%"><span style="float:right;">{display.training_row_o.TRAINING_DATE}</span>{display.training_row_o.TRAINING_NAME}</td>
	<td class="{display.training_row_o.CLASS}" align="center" width="1%"><a href="{display.training_row_o.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.training_row_o.CLASS}" align="center" width="1%"><a href="{display.training_row_o.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END training_row_o -->
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
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_TRAINING_CREATE}"></td>
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
<form action="{S_TRAINING_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_TRAINING_ACTION}" method="post">{L_TRAINING_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_TRAINING_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="training_vs">{L_TRAINING_VS}: *</label></td>
	<td class="row3"><input class="post" type="text" name="training_vs" id="training_vs" value="{TRAINING_VS}"></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_TEAM}: *</td>
	<td class="row3">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MATCH}:</td>
	<td class="row3">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_DURATION}:</td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="training_maps">{L_TRAINING_MAPS}: *</label></td>
	<td class="row3"><input class="post" type="text" name="training_maps" id="training_maps" value="{TRAINING_MAPS}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="training_text">{L_TRAINING_TEXT}:</label></td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TRAINING_TEXT}</textarea></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END training_edit -->