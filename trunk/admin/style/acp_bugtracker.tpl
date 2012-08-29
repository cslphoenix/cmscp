<!-- BEGIN display -->
<form action="{S_ACTION}" method="post" id="bugtracker_sort" name="bugtracker_sort">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<td class="rowHead" colspan="3">{L_NAME}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_bugtracker -->
<tr>
	<td class="{_display.row_bugtracker.CLASS}" nowrap="nowrap">{_display.row_bugtracker.STATUS}</td>
	<td class="{_display.row_bugtracker.CLASS}" width="97%"><span class="right">{_display.row_bugtracker.CREATOR} {_display.row_bugtracker.DATE}</span>{_display.row_bugtracker.TITLE}</td>
	<td class="{_display.row_bugtracker.CLASS}" nowrap="nowrap">{_display.row_bugtracker.TYPE}</td>
	<td class="{_display.row_bugtracker.CLASS}"><a href="{_display.row_bugtracker.U_DETAIL}">{L_DETAIL}</a></td>
	<td class="{_display.row_bugtracker.CLASS}"><a href="{_display.row_bugtracker.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END row_bugtracker -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry2" align="center" colspan="5">{L_EMPTY}</td>
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
<!-- END display -->

<!-- BEGIN detail -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_PROC}</a></li></ul>

<table class="header">
<tr>
	<td class="info">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>{L_TITLE}:</td>
	<td>{TITLE}</td>
</tr>
<tr>
	<td>{L_CREATOR}:</td>
	<td>{CREATOR} / {DATE} {DATE_CHANGE}</td>
</tr>
<tr>
	<td>{L_EDITOR}:</td>
	<td>{EDITOR}</td>
</tr>
<tr>
	<td>{L_STATUS}:</td>
	<td><span class="right">{S_STATUS}</span>{STATUS}</td>
</tr>
<tr>
	<td>{L_TYPE}:</td>
	<td><span class="right">{S_TYPE}</span>{TYPE}</td>
</tr>
<tr>
	<td>{L_DESC}:</td>
	<td>{DESC}</td>
</tr>
<tr>
	<td>{L_MESSAGE}:</td>
	<td>{MESSAGE}</td>
</tr>
<tr>
	<td>{L_PHP_SQL}:</td>
	<td>{PHP_SQL}</td>
</tr>
<tr>
	<td>{L_VERSION}:</td>
	<td>{VERSION}</td>
</tr>
<tr>
	<td>{L_REPORT}:</td>
	<td><textarea class="textarea" name="report" rows="5" style="width:100%">{REPORT}</textarea></td>
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
<!-- END detail -->