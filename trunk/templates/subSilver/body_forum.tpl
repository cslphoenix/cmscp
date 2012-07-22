<!-- BEGIN cat_row -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th><a style="font-size:10px;" href="{cat_row.U_VIEWCAT}">{cat_row.CAT_DESC}</a></th>
</tr>
</table>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<!-- BEGIN forum_row -->
<tr>
	<td><img src="{cat_row.forum_row.FORUM_FOLDER_IMG}" width="24" height="24" alt="{cat_row.forum_row.L_FORUM_FOLDER_ALT}" title="{cat_row.forum_row.L_FORUM_FOLDER_ALT}"></td>
	<td>
		<span style="float:right;">{L_TOPICS}: {cat_row.forum_row.TOPICS} / {L_POSTS}: {cat_row.forum_row.POSTS}</span>
		<span><a href="{cat_row.forum_row.U_VIEWFORUM}">{cat_row.forum_row.FORUM_NAME}</a><br />{cat_row.forum_row.FORUM_DESC}</span><br />
		<!-- BEGIN subforum_row -->
		&nbsp;&not;&nbsp;{cat_row.forum_row.subforum_row.NAME}<br />
		<!-- END subforum_row -->
	</td>
	<td><span class="small">{cat_row.forum_row.LAST_POST}</span></td>
</tr>
<!-- END forum_row -->
<!-- END cat_row -->
<!-- BEGIN no_forums -->
<tr>
	<td>{NO_FORUMS}</td>
</tr>
<!-- END no_forums -->
</table>