<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li {TAB_AKTIV0} href="{S_ACTION}">{L_HEAD}</a></li>
	<!-- BEGIN _contact -->
	<li {TAB_AKTIV1} href="{S_NORMAL}">{L_CONTACT}</a></li>
	<!-- END _contact -->
	<!-- BEGIN _joinus -->
	<li {TAB_AKTIV2} href="{S_JOINUS}">{L_JOINUS}</a></li>
	<!-- END _joinus -->
	<!-- BEGIN _fightus -->
	<li {TAB_AKTIV3} href="{S_FIGHTUS}">{L_FIGHTUS}</a></li>
	<!-- END _fightus -->
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
	<td class="rowHead" colspan="5" width="100%">{L_CONTACT_DETAILS}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN _contact_row -->
<tr onClick="document.getElementById('id_{_display._contact_row.CONTACT_ID}').style.display = '';">
	<td class="{_display._contact_row.CLASS}" align="center" width="1%">{_display._contact_row.CONTACT_GAME}</td>
	<td class="{_display._contact_row.CLASS}" align="center" width="1%">{_display._contact_row.CONTACT_TYPE}</td>
	<td class="{_display._contact_row.CLASS}" align="left" width="1%">{_display._contact_row.CONTACT_STATUS}</td>
	<td class="{_display._contact_row.CLASS}" align="left" width="100%" nowrap="nowrap">{_display._contact_row.CONTACT_FROM}</td>
	<td class="{_display._contact_row.CLASS}" align="left" width="1%" nowrap="nowrap">{_display._contact_row.CONTACT_DATE}</td>

	<td class="{_display._contact_row.CLASS}" align="center" width="1%"><a href="{_display._contact_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{_display._contact_row.CONTACT_ID}" style="display: none;">
	<td class="{_display._contact_row.CLASS}" colspan="8">
	
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{_display._contact_row.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{_display._contact_row.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
		</tr>
		</table>

	
	</td>
</tr>
<!-- END _contact_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="row_class1" align="center" colspan="8">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="footer" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
</form>
<!-- END _display -->

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
	</th>
</tr>
<tr>
	<td class="row2">{L_CONTACT_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="5" width="100%">{L_CONTACT_DETAILS}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN _contact_row -->
<tr onClick="document.getElementById('id_{categorie._contact_row.CONTACT_ID}').style.display = '';">
	<td class="{categorie._contact_row.CLASS}" align="center" width="1%">{categorie._contact_row.CONTACT_GAME}</td>
	<td class="{categorie._contact_row.CLASS}" align="center" width="1%">{categorie._contact_row.CONTACT_TYPE}</td>
	<td class="{categorie._contact_row.CLASS}" align="left" width="1%">{categorie._contact_row.CONTACT_STATUS}</td>
	<td class="{categorie._contact_row.CLASS}" align="left" width="100%" nowrap="nowrap">{categorie._contact_row.CONTACT_FROM}</td>
	<td class="{categorie._contact_row.CLASS}" align="left" width="1%" nowrap="nowrap">{categorie._contact_row.CONTACT_DATE}</td>

	<td class="{categorie._contact_row.CLASS}" align="center" width="1%"><a href="{categorie._contact_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{categorie._contact_row.CONTACT_ID}" style="display: none;">
	<td class="{categorie._contact_row.CLASS}" colspan="8">
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{categorie._contact_row.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{categorie._contact_row.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
		</tr>
		</table>
	</td>
</tr>
<!-- END _contact_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="8">{L_ENTRY_NO}</td>
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