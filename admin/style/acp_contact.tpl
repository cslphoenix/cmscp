<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<!-- BEGIN type -->
<table class="rows">
<!-- BEGIN row -->
<tr>
	<td>{display.type.row.CONTACT_FROM}</td>
	<td>{display.type.row.CONTACT_GAME}</td>
	<td>{display.type.row.CONTACT_TYPE}</td>
	<td>{display.type.row.CONTACT_STATUS}</td>
	<td>{display.type.row.CONTACT_FROM}</td>
	<td>{display.type.row.CONTACT_DATE}</td>
	
</tr>
<!-- END row -->
</table>
<br />
<!-- END type -->
<table class="rows">
<tr>
	<td class="rowHead" colspan="5" width="100%">{L_CONTACT_DETAILS}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN contact_row -->
<tr onClick="document.getElementById('id_{display.contactrow.CONTACT_ID}').style.display = '';">
	<td class="{display.contactrow.CLASS}" align="center" width="1%">{display.contactrow.CONTACT_GAME}</td>
	<td class="{display.contactrow.CLASS}" align="center" width="1%">{display.contactrow.CONTACT_TYPE}</td>
	<td class="{display.contactrow.CLASS}" align="left" width="1%">{display.contactrow.CONTACT_STATUS}</td>
	<td class="{display.contactrow.CLASS}" align="left" width="100%" nowrap="nowrap">{display.contactrow.CONTACT_FROM}</td>
	<td class="{display.contactrow.CLASS}" align="left" width="1%" nowrap="nowrap">{display.contactrow.CONTACT_DATE}</td>

	<td class="{display.contactrow.CLASS}" align="center" width="1%"><a href="{display.contactrow.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{display.contactrow.CONTACT_ID}" style="display: none;">
	<td class="{display.contactrow.CLASS}" colspan="8">
	
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{display.contactrow.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{display.contactrow.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
		</tr>
		</table>

	
	</td>
</tr>
<!-- END contact_row -->
<!-- BEGIN none -->
<tr>
	<td class="row_class1" align="center" colspan="8">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="footer" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN categorie -->
<form action="{S_CONTACT_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_CONTACT_ACTION}">{L_CONTACT_TITLE}</a></li>
				<!-- BEGIN contact -->
				<li {TAB_AKTIV1} href="{S_CONTACT_NORMAL}">{L_CONTACT_HEAD_NORMAL}</a></li>
				<!-- END contact -->
				<!-- BEGIN joinus -->
				<li {TAB_AKTIV2} href="{S_CONTACT_JOINUS}">{L_CONTACT_HEAD_JOINUS}</a></li>
				<!-- END joinus -->
				<!-- BEGIN fightus -->
				<li {TAB_AKTIV3} href="{S_CONTACT_FIGHTUS}">{L_CONTACT_HEAD_FIGHTUS}</a></li>
				<!-- END fightus -->
			</ul>
		</div>
	</td>
</tr>
<tr>
	<td>{L_CONTACT_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="5" width="100%">{L_CONTACT_DETAILS}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN contact_row -->
<tr onClick="document.getElementById('id_{categorie._contactrow.CONTACT_ID}').style.display = '';">
	<td class="{categorie._contactrow.CLASS}" align="center" width="1%">{categorie._contactrow.CONTACT_GAME}</td>
	<td class="{categorie._contactrow.CLASS}" align="center" width="1%">{categorie._contactrow.CONTACT_TYPE}</td>
	<td class="{categorie._contactrow.CLASS}" align="left" width="1%">{categorie._contactrow.CONTACT_STATUS}</td>
	<td class="{categorie._contactrow.CLASS}" align="left" width="100%" nowrap="nowrap">{categorie._contactrow.CONTACT_FROM}</td>
	<td class="{categorie._contactrow.CLASS}" align="left" width="1%" nowrap="nowrap">{categorie._contactrow.CONTACT_DATE}</td>

	<td class="{categorie._contactrow.CLASS}" align="center" width="1%"><a href="{categorie._contactrow.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{categorie._contactrow.CONTACT_ID}" style="display: none;">
	<td class="{categorie._contactrow.CLASS}" colspan="8">
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{categorie._contactrow.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{categorie._contactrow.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END contact_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="8">{L_NONE}</td>
</tr>
<!-- END no_entry -->
</table>
<table class="footer" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END categorie -->