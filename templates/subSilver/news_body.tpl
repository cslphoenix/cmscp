<table class="out" width="100%" cellspacing="0">
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
	<td>&nbsp;</td>
	<td><a href="{news_row.U_NEWS}"><b>{news_row.NEWS_TITLE}</b></a> {news_row.NEWS_ID}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>{news_row.NEWS_TEXT}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><span style="float: right;">{news_row.NEWS_AUTHOR}, {news_row.NEWS_PUBLIC_TIME}</span>Kommentare: {news_row.NEWS_COMMENTS}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<!-- BEGIN links -->
<tr>
	<td>&nbsp;</td>
	<td> Links: <a href="{news_row.NEWS_URL1}">{news_row.NEWS_LINK1}</a> <a href="{news_row.links.NEWS_URL2}">{news_row.links.NEWS_LINK2}</a>  </td>
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
	<td colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>
