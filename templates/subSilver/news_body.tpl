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
	<td width="96%" align="center"><img src="{newsrow.NEWS_CAT_IMAGE}" alt="{newsrow.NEWS_CAT_TITLE}" title="{newsrow.NEWS_CAT_TITLE}"></td>
	<td width="2%">&nbsp;</td>
</tr>
<tr>
	<td width="2%">&nbsp;</td>
	<td width="96%" align="left">
		<a href="{newsrow.U_NEWS}"><b>{newsrow.NEWS_TITLE}</b></a>
		Kommentare: {newsrow.NEWS_COMMENTS}
	</td>
	<td width="2%">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>{newsrow.NEWS_TEXT}</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><span style="float: right;">{newsrow.NEWS_AUTHOR}, {newsrow.NEWS_PUBLIC_TIME}</span></td>
	<td>&nbsp;</td>
</tr>
<!-- BEGIN links -->
<tr>
	<td>&nbsp;</td>
	<td>{newsrow.links.L_LINK}{newsrow.links.NEWS_LINK}</td>
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
	<td colspan="3" align="center">{L_NONE}</td>
</tr>
<!-- END no_entry -->
</table>
