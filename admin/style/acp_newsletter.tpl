<!-- BEGIN _display -->
<form action="{S_NL_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_NL_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_NL_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_NL_EMAIL}</td>
	<td class="rowHead" colspan="4">{L_SETTINGS}</td>
</tr>
<!-- BEGIN newsletter_row -->
<tr>
	<td class="{_display.newsletter_row.CLASS}" align="left" nowrap="nowrap" width="100%">{_display.newsletter_row.NL_MAIL}</td>
	<td class="{_display.newsletter_row.CLASS}" align="center" nowrap="nowrap">{_display.newsletter_row.STATUS}</td>
	<td class="{_display.newsletter_row.CLASS}" align="center" nowrap="nowrap">{_display.newsletter_row.NEW}</td>
	<td class="{_display.newsletter_row.CLASS}" align="center" nowrap="nowrap"><a href="{_display.newsletter_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{_display.newsletter_row.CLASS}" align="center" nowrap="nowrap"><a href="{_display.newsletter_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END newsletter_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" cellspacing="2">
<tr>
	<td align="left"><input type="hidden" name="mode" value="alldelete" /><input class="button" type="submit" value="{L_ALLDELETE}"></td>
	<td align="right"><input class="post" name="newsletter_mail" type="text"> <input type="hidden" name="mode" value="add" /><input class="button" type="submit" value="{L_NL_ADD}"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN newsletter_edit -->
<form action="{S_NL_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NL_ACTION}">{L_NL_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NL_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="20%">{L_NL_EMAIL}: *</td>
	<td class="row2" width="80%"><input type="text" class="post" name="newsletter_mail" value="{NL_MAIL}"></td>
</tr>
<tr>
	<td class="row1">{L_NL_TYPE}:<br><span class="small">{L_NL_TYPE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="send_type" value="1">&nbsp;{L_ACTIVE}&nbsp;&nbsp;<input type="radio" name="send_type" value="0" checked="checked" /> {L_NEW}</td> 
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END newsletter_edit -->
