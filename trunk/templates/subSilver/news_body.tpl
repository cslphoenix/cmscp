<table class="out" width="100%" cellspacing="3">
<tr>
	<td class="info_head" colspan="3">News-&Uuml;bersicht</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<!-- BEGIN news_row -->
<tr>
	<td width="2%">&nbsp;</td>
	<td width="96%" align="center"><img src="{news_row.NEWSCAT_IMAGE}" alt="{news_row.NEWSCAT_TITLE}" title="{news_row.NEWSCAT_TITLE}"></td>
	<td width="2%">&nbsp;</td>
</tr>
<tr>
	<td width="2%">&nbsp;</td>
	<td width="96%" align="left">
		<a href="{news_row.U_NEWS}"><b>{news_row.NEWS_TITLE}</b></a>
		Kommentare: {news_row.NEWS_COMMENTS}
	</td>
	<td width="2%">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>{news_row.NEWS_TEXT}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><span style="float: right;">{news_row.NEWS_AUTHOR}, {news_row.NEWS_PUBLIC_TIME}</span></td>
	<td>&nbsp;</td>
</tr>
<!-- BEGIN links -->
<tr>
	<td>&nbsp;</td>
	<td>{news_row.links.L_LINK}{news_row.links.NEWS_LINK}</td>
	<td>&nbsp;</td>
</tr>
<!-- END links -->
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<!-- END news_row -->
<tr>
	<td>&nbsp;</td>
	<td><span style="float:right;">{PAGE_NUMBER}</span>{PAGINATION}</td>
	<td>&nbsp;</td>
</tr>
<!-- BEGIN no_entry -->
<tr>
	<td colspan="3" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END no_entry -->
</table>
