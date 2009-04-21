<!-- BEGIN display -->
<form method="post" action="{S_CONTACT_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_CONTACT_TITLE}</a></li>
				<!-- BEGIN contact -->
				<li><a href="{S_CONTACT_NORMAL}">{L_CONTACT_HEAD_NORMAL}</a></li>
				<!-- END contact -->
				<!-- BEGIN joinus -->
				<li><a href="{S_CONTACT_JOINUS}">{L_CONTACT_HEAD_JOINUS}</a></li>
				<!-- END joinus -->
				<!-- BEGIN fightus -->
				<li><a href="{S_CONTACT_FIGHTUS}">{L_CONTACT_HEAD_FIGHTUS}</a></li>
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
	<td class="rowHead" colspan="5">{L_CONTACT_NAME}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN contact_row -->
<tr onClick="document.getElementById('id_{display.contact_row.CONTACT_ID}').style.display = '';">
	<td class="{display.contact_row.CLASS}" align="center" width="1%">{display.contact_row.CONTACT_GAME}</td>
	<td class="{display.contact_row.CLASS}" align="center" width="1%">{display.contact_row.CONTACT_TYPE}</td>
	<td class="{display.contact_row.CLASS}" align="left" width="1%">{display.contact_row.CONTACT_STATUS}</td>
	<td class="{display.contact_row.CLASS}" align="left" width="100%" nowrap="nowrap">{display.contact_row.CONTACT_FROM}</td>
	<td class="{display.contact_row.CLASS}" align="left" width="1%" nowrap="nowrap">{display.contact_row.CONTACT_DATE}</td>

	<td class="{display.contact_row.CLASS}" align="center" width="1%"><a href="{display.contact_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{display.contact_row.CONTACT_ID}" style="display: none;">
	<td class="{display.contact_row.CLASS}" colspan="8">
	
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{display.contact_row.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{display.contact_row.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
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
</form>
<!-- END display -->

<!-- BEGIN _edit -->

<!-- END _edit -->

<!-- BEGIN categorie -->
<form method="post" action="{S_CONTACT_ACTION}">
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
	<td class="rowHead" colspan="5">{L_CONTACT_NAME}</td>
	<td class="rowHead" colspan="3">{L_CONTACT_SETTINGS}</td>
</tr>
<!-- BEGIN contact_row -->
<tr onClick="document.getElementById('id_{categorie.contact_row.CONTACT_ID}').style.display = '';">
	<td class="{categorie.contact_row.CLASS}" align="center" width="1%">{categorie.contact_row.CONTACT_GAME}</td>
	<td class="{categorie.contact_row.CLASS}" align="center" width="1%">{categorie.contact_row.CONTACT_TYPE}</td>
	<td class="{categorie.contact_row.CLASS}" align="left" width="1%">{categorie.contact_row.CONTACT_STATUS}</td>
	<td class="{categorie.contact_row.CLASS}" align="left" width="100%" nowrap="nowrap">{categorie.contact_row.CONTACT_FROM}</td>
	<td class="{categorie.contact_row.CLASS}" align="left" width="1%" nowrap="nowrap">{categorie.contact_row.CONTACT_DATE}</td>

	<td class="{categorie.contact_row.CLASS}" align="center" width="1%"><a href="{categorie.contact_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<tr id="id_{categorie.contact_row.CONTACT_ID}" style="display: none;">
	<td class="{categorie.contact_row.CLASS}" colspan="8">
		<table width="10%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>Mail:</td>
			<td>{categorie.contact_row.CONTACT_MAIL}</td>
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
			<td><a onClick="document.getElementById('id_{categorie.contact_row.CONTACT_ID}').style.display = 'none';" href="#">close</a></td>
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
<!-- END categorie -->