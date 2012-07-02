<!-- BEGIN list -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>{L_MAIN}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{list.row.AUTHOR}, {list.row.DATE}</span><a href="{list.row.U_NEWS}"><b>{list.row.TITLE}</b></a></td>
</tr>
<tr>
	<td>
		<img src="{list.row.NC_IMAGE}" title="{list.row.NC_TITLE}" alt="{list.row.NC_TITLE}" style="float:left; padding-right:5px;" />
		<!-- BEGIN match -->
		<table>
		<tr>
			<td>{list.row.match.TEAM_LOGO}</td>
			<td>
				{list.row.match.GAME} {list.row.match.TEAM} vs. {list.row.match.RIVAL}<br />
				{list.row.match.WAR}: {list.row.match.TYPE} {list.row.match.LEAGUE}<br />
				{list.row.match.MAPS}<br />
				<span class="{list.row.match.CSS}">{list.row.match.RESULT}</span>
			</td>
			<td>{list.row.match.RIVAL_LOGO}</td>
		</tr>
		</table>
		<!-- END match -->
		{list.row.TEXT}
	</td>
</tr>
<tr>
	<td>{list.row.COMMENTS}<br />
		<!-- BEGIN urls -->
		{list.row.urls.LINK}{list.row.urls.URLS}
		<!-- END urls -->
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td align="center">{L_EMPTY}</td>
</tr>
<!-- END empty -->
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END list -->

<!-- BEGIN view -->
<form action="{S_ACTION}" method="post" name="post">
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
		<!-- BEGIN update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{_view._update.UPDATE}</span>
		<!-- END update -->
		<span class="small" style="float:right;">{OVERVIEW}</span>
		{L_MAIN}
	</th>
</tr>
<tr>
	<td>{TEXT}</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>
{COMMENTS}
{S_FIELDS}
</form>
<!-- END view -->

<!-- BEGIN archiv -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="5">{L_MAIN}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{archiv.row.CLASS}">{archiv.row.TITLE}</td>
    <td class="{archiv.row.CLASS}">{archiv.row.CAT}</td>
    <td class="{archiv.row.CLASS}">{archiv.row.AUTHOR}</td>
	<td class="{archiv.row.CLASS}">{archiv.row.COMMENTS}</td>
    <td class="{archiv.row.CLASS}">{archiv.row.DATE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td align="center">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>
<!-- END archiv -->