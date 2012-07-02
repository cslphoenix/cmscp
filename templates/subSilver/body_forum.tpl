<!-- BEGIN cat_row -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th><a style="font-size:10px;" href="{catrow.U_VIEWCAT}">{catrow.CAT_DESC}</a></th>
</tr>
</table>
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<!-- BEGIN forum_row -->
<tr>
	<td><img src="{catrow.forumrow.FORUM_FOLDER_IMG}" width="24" height="24" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}"></td>
	<td>
		<span style="float:right;">{L_TOPICS}: {catrow.forumrow.TOPICS} / {L_POSTS}: {catrow.forumrow.POSTS}</span>
		<span><a href="{catrow.forumrow.U_VIEWFORUM}">{catrow.forumrow.FORUM_NAME}</a><br />{catrow.forumrow.FORUM_DESC}</span><br />
		<!-- BEGIN subforum_row -->
		&nbsp;&not;&nbsp;{catrow.forumrow._subforumrow.NAME}<br />
		<!-- END subforum_row -->
	</td>
	<td><span class="small">{catrow.forumrow.LAST_POST}</span></td>
</tr>
<!-- END forum_row -->
<!-- END cat_row -->
<!-- BEGIN no_forums -->
<tr>
	<td>{NO_FORUMS}</td>
</tr>
<!-- END no_forums -->
</table>