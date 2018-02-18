<!-- BEGIN _c -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th><a style="font-size:10px;">{_c.NAME}</a></th>
</tr>
</table>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<!-- BEGIN forum_row -->
<tr>
	<td><img src="{_c.forum_row.FORUM_FOLDER_IMG}" width="24" height="24" alt="{_c.forum_row.L_FORUM_FOLDER_ALT}" title="{_c.forum_row.L_FORUM_FOLDER_ALT}"></td>
	<td>
		<span style="float:right;">{L_TOPICS}: {_c.forum_row.TOPICS} / {L_POSTS}: {_c.forum_row.POSTS}</span>
		<span><a href="{_c.forum_row.U_VIEWFORUM}">{_c.forum_row.FORUM_NAME}</a><br />{_c.forum_row.FORUM_DESC}</span><br />
		<!-- BEGIN subforum_row -->
		&nbsp;&not;&nbsp;{_c.forum_row.subforum_row.NAME}<br />
		<!-- END subforum_row -->
	</td>
	<td><span class="small">{_c.forum_row.LAST_POST}</span></td>
</tr>
<!-- END forum_row -->
<!-- END _c -->
<!-- BEGIN no_forums -->
<tr>
	<td>{NO_FORUMS}</td>
</tr>
<!-- END no_forums -->
</table>