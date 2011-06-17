<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post" id="bugtracker_sort" name="bugtracker_sort">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<td class="rowHead" colspan="3">{L_NAME}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_bugtracker -->
<tr>
	<td class="{_display.row_bugtracker.CLASS}" nowrap="nowrap">{_display.row_bugtracker.STATUS}</td>
	<td class="{_display.row_bugtracker.CLASS}" width="97%"><span style="float:right;">{_display.row_bugtracker.CREATOR} {_display.row_bugtracker.DATE}</span>{_display.row_bugtracker.TITLE}</td>
	<td class="{_display.row_bugtracker.CLASS}" nowrap="nowrap">{_display.row_bugtracker.TYPE}</td>
	<td class="{_display.row_bugtracker.CLASS}"><a href="{_display.row_bugtracker.U_DETAIL}">{L_DETAIL}</a></td>
	<td class="{_display.row_bugtracker.CLASS}"><a href="{_display.row_bugtracker.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END row_bugtracker -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry2" align="center" colspan="5">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right" colspan="2">{S_SORT}</td>
</tr>
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN detail -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_PROC}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155">{L_TITLE}:</td>
	<td class="row2">{TITLE}</td>
</tr>
<tr>
	<td class="row1">{L_CREATOR}:</td>
	<td class="row2">{CREATOR} / {DATE} {DATE_CHANGE}</td>
</tr>
<tr>
	<td class="row1">{L_EDITOR}:</td>
	<td class="row2">{EDITOR}</td>
</tr>
<tr>
	<td class="row1">{L_STATUS}:</td>
	<td class="row2"><span style="float:right;">{S_STATUS}</span>{STATUS}</td>
</tr>
<tr>
	<td class="row1">{L_TYPE}:</td>
	<td class="row2"><span style="float:right;">{S_TYPE}</span>{TYPE}</td>
</tr>
<tr>
	<td class="row1">{L_DESC}:</td>
	<td class="row2">{DESC}</td>
</tr>
<tr>
	<td class="row1">{L_MESSAGE}:</td>
	<td class="row2">{MESSAGE}</td>
</tr>
<tr>
	<td class="row1">{L_PHP_SQL}:</td>
	<td class="row2">{PHP_SQL}</td>
</tr>
<tr>
	<td class="row1">{L_VERSION}:</td>
	<td class="row2">{VERSION}</td>
</tr>
<tr>
	<td class="row1">{L_REPORT}:</td>
	<td class="row2"><textarea class="textarea" name="report" rows="5" style="width:100%">{REPORT}</textarea></td>
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
<!-- END detail -->