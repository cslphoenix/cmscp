<!-- BEGIN _cat_row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="info_head"><a style="font-size:10px;" href="{_cat_row.U_VIEWCAT}">{_cat_row.CAT_DESC}</a></td>
</tr>
</table>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<!-- BEGIN _forum_row -->
<tr>
	<td class="row1" align="center"><img src="{_cat_row._forum_row.FORUM_FOLDER_IMG}" width="24" height="24" alt="{_cat_row._forum_row.L_FORUM_FOLDER_ALT}" title="{_cat_row._forum_row.L_FORUM_FOLDER_ALT}"></td>
	<td class="row1" width="100%">
		<span style="float:right;">{L_TOPICS}: {_cat_row._forum_row.TOPICS} / {L_POSTS}: {_cat_row._forum_row.POSTS}</span>
		<span><a href="{_cat_row._forum_row.U_VIEWFORUM}">{_cat_row._forum_row.FORUM_NAME}</a><br />{_cat_row._forum_row.FORUM_DESC}</span><br />
		<!-- BEGIN _subforum_row -->
		&nbsp;&not;&nbsp;{_cat_row._forum_row._subforum_row.NAME}<br />
		<!-- END _subforum_row -->
	</td>
	<td class="row2" align="center" valign="middle" nowrap="nowrap"><span class="small">{_cat_row._forum_row.LAST_POST}</span></td>
</tr>
<!-- END _forum_row -->
<!-- END _cat_row -->
<!-- BEGIN _no_forums -->
<tr>
	<td>{NO_FORUMS}</td>
</tr>
<!-- END _no_forums -->
</table>