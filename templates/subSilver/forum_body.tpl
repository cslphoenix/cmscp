<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<!-- BEGIN catrow -->
		<tr>
			<td class="info_head" colspan="5"><a href="{catrow.U_VIEWCAT}">{catrow.CAT_DESC}</a></td>
		</tr>
		<!-- BEGIN forumrow -->
		<tr>
			<td class="row1" align="center" valign="middle" height="50"><img src="{catrow.forumrow.FORUM_FOLDER_IMG}" width="24" height="24" alt="{catrow.forumrow.L_FORUM_FOLDER_ALT}" title="{catrow.forumrow.L_FORUM_FOLDER_ALT}" /></td>
			<td class="row1" width="100%" height="50">
				<span style="float:right;">{L_TOPICS}: {catrow.forumrow.TOPICS} / {L_POSTS}: {catrow.forumrow.POSTS}</span>
				<span>
					<a href="{catrow.forumrow.U_VIEWFORUM}">{catrow.forumrow.FORUM_NAME}</a><br />
				</span>
				<span>
					{catrow.forumrow.FORUM_DESC}<br />
				</span>
				<!-- BEGIN parent -->
				{L_SUBFORUMS}: <a href="{catrow.forumrow.parent.U_VIEWFORUM}">{catrow.forumrow.parent.FORUM_NAME}</a>
				<!-- END parent -->
			</td>
			<td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"><span class="small">{catrow.forumrow.LAST_POST}</span></td>
		</tr>
		<!-- END forumrow -->
		<!-- END catrow -->
		<!-- BEGIN switch_no_forums -->
		<tr>
			<td>{NO_FORUMS}</td>
		</tr>
		<!-- END switch_no_forums -->
		</table>
	</td>
</tr>
</table>