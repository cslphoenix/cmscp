<!-- BEGIN _list -->
<table class="news">
<tr>
	<td class="header">{L_MAIN}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td><span class="right">{_list._row.AUTHOR}, {_list._row.DATE}</span><a href="{_list._row.U_NEWS}"><b>{_list._row.TITLE}</b></a></td>
</tr>
<tr>
	<td>
		<img src="{_list._row.NC_IMAGE}" title="{_list._row.NC_TITLE}" alt="{_list._row.NC_TITLE}" style="float:left; padding-right:5px;" />
		<!-- BEGIN _match -->
		<table class="news_match">
		<tr>
			<td>{_list._row._match.TEAM_LOGO}</td>
			<td>
				{_list._row._match.GAME} {_list._row._match.TEAM} vs. {_list._row._match.RIVAL}<br />
				{_list._row._match.WAR}: {_list._row._match.TYPE} {_list._row._match.LEAGUE}<br />
				{_list._row._match.MAPS}<br />
				<span class="{_list._row._match.CSS}">{_list._row._match.RESULT}</span>
			</td>
			<td>{_list._row._match.RIVAL_LOGO}</td>
		</tr>
		</table>
		<!-- END _match -->
		{_list._row.TEXT}
	</td>
</tr>
<tr>
	<td>{_list._row.COMMENTS}<br />
		<!-- BEGIN _urls -->
		{_list._row._urls.LINK}{_list._row._urls.URLS}
		<!-- END _urls -->
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<!-- END _row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="news">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END _list -->

<!-- BEGIN _view -->
<form action="{S_ACTION}" method="post" name="post">
<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="header">
		<!-- BEGIN _update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{_view._update.UPDATE}</span>
		<!-- END _update -->
		<span class="small" style="float:right;">{OVERVIEW}</span>
		{L_MAIN}</td>
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
<!-- END _view -->

<!-- BEGIN _archiv -->
<table class="news">
<tr>
	<td class="header" colspan="5">{L_MAIN}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td>{_archiv._row.TITLE}</td>
    <td>{_archiv._row.NC_CAT}</td>
    <td>{_archiv._row.AUTHOR}</td>
	<td>{_archiv._row.COMMENT}</td>
    <td>{_archiv._row.DATE}</td>
</tr>
<!-- END _row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>
<!-- END _archiv -->