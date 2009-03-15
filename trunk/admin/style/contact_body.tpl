<form method="post" action="{S_CONTACT_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_CONTACT_TITLE}</th>
</tr>
<tr>
	<td>{L_CONTACT_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="5">{L_CONTACT_NAME}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN contact_row -->
<tr>
	<td class="{contact_row.CLASS}" align="center" width="1%">{contact_row.CONTACT_GAME}</td>
	<td class="{contact_row.CLASS}" align="center" width="1%">{contact_row.CONTACT_TYPE}</td>
	<td class="{contact_row.CLASS}" align="left" width="1%">{contact_row.CONTACT_STATUS}</td>
	<td class="{contact_row.CLASS}" align="left" nowrap>{contact_row.CONTACT_FROM}</td>
	<td class="{contact_row.CLASS}" align="left" width="100%" nowrap>{contact_row.CONTACT_DATE}</td>
	
	<td class="{contact_row.CLASS}" align="center" width="1%"><a onClick="document.getElementById('id_{contact_row.CONTACT_ID}').style.display = '';" href="#">open</a></td>
	<td class="{contact_row.CLASS}" align="center" width="1%"><a onClick="document.getElementById('id_{contact_row.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
	
	<td class="{contact_row.CLASS}" align="center" width="1%"><a href="{contact_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{contact_row.CONTACT_ID}" style="display: none;">
	<td class="{contact_row.CLASS}" colspan="8">
	
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{contact_row.CONTACT_MAIL}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>

	
	</td>
</tr>

<!-- END contact_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right">{S_TEAMS}</td>
	<td><input class="button" type="submit" name="add" value="{L_CONTACT_CREATE}" /></td>
</tr>
</table>
</form>