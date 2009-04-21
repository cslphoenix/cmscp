<!-- BEGIN display -->
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
	<td class="{display.newsletter_row.CLASS}" align="left" nowrap="nowrap" width="100%">{display.newsletter_row.NL_MAIL}</td>
	<td class="{display.newsletter_row.CLASS}" align="center" nowrap="nowrap">{display.newsletter_row.STATUS}</td>
	<td class="{display.newsletter_row.CLASS}" align="center" nowrap="nowrap">{display.newsletter_row.NEW}</td>
	<td class="{display.newsletter_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.newsletter_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{display.newsletter_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.newsletter_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END newsletter_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td align="left"><input type="hidden" name="mode" value="alldelete" /><input class="button" type="submit" value="{L_ALLDELETE}" /></td>
	<td align="right"><input class="post" name="newsletter_mail" type="text"> <input type="hidden" name="mode" value="add" /><input class="button" type="submit" value="{L_NL_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN newsletter_edit -->
<form action="{S_NL_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NL_ACTION}">{L_NL_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NL_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NL_EMAIL}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="newsletter_mail" value="{NL_MAIL}" ></td>
</tr>
<tr>
	<td class="row1">{L_NL_TYPE}:<br><span class="small">{L_NL_TYPE_EXPLAIN}</span></td>
	<td class="row3"><input type="radio" name="send_type" value="1" />&nbsp;{L_ACTIVE}&nbsp;&nbsp;<input type="radio" name="send_type" value="0" checked="checked" /> {L_NEW}</td> 
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END newsletter_edit -->
