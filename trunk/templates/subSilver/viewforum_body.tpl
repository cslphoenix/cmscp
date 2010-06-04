<form action="{S_POST_DAYS_ACTION}" method="post">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="info_head" colspan="5"><span class="right">{PAGINATION}</span>
		<a href="{U_FORUM}">{L_FORUM}</a>
		<!-- BEGIN sub -->
		 <a href="{sub.U_VIEW_FORUM}">{sub.FORUM_NAME}</a> ::
		<!-- END sub -->
		 <a href="{U_VIEW_FORUM}">{FORUM_NAME}</a>
	</td>
</tr>
</table>

	<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" colspan="3"><span class="small"><b>{L_MODERATOR}: {MODERATORS}</b></span><span class="gensmall"><b>{PAGINATION}</b></span></td>
		</tr>
	<tr>
		<td align="left" colspan="3"><a href="{U_POST_NEW_TOPIC}">Neues Thema</a></td>
	</tr>
	</table>
	
	<table border="0" cellpadding="4" cellspacing="1" width="100%">
	<tr>
		<td class="info_head" colspan="2" align="left">{L_TOPICS}</td>
		<!--
		<td class="info_head" width="50" align="center" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</td>
		<td class="info_head" width="100" align="center" nowrap="nowrap">&nbsp;{L_AUTHOR}&nbsp;</td>
		<td class="info_head" width="50" align="center" nowrap="nowrap">&nbsp;{L_VIEWS}&nbsp;</td>
		-->
		<td class="info_head" style="text-align:right;">{L_LASTPOST}</td>
	</tr>
	<!-- BEGIN topicrow -->
	<tr>
		<td class="row1" align="center" valign="middle" width="20"><img src="{topicrow.TOPIC_FOLDER_IMG}" width="19" height="18" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}"></td>
		<td class="row1" width="100%"><span class="topictitle">{topicrow.NEWEST_POST_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" class="topictitle">{topicrow.TOPIC_TITLE}</a></span><span class="gensmall"><br>{topicrow.GOTO_PAGE}</span></td>
		<td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
		<td class="row3" align="center" valign="middle"><span class="name">{topicrow.TOPIC_AUTHOR}</span></td>
		<td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.VIEWS}</span></td>
		<td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{topicrow.LAST_POST_TIME}<br>{topicrow.LAST_POST_AUTHOR} {topicrow.LAST_POST_IMG}</span></td>
	</tr>
	<!-- END topicrow -->
	<!-- BEGIN switch_no_topics -->
	<tr>
		<td class="row1" colspan="6" align="center"><span class="small">{L_NO_TOPICS}</span></td>
	</tr>
	<!-- END switch_no_topics -->
	</table>
	
	<table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr>
		<td align="left" colspan="3"><span class="nav">{PAGE_NUMBER}</span></td>
	</tr>
	</table>
	</form>
	
	<br/>
	<!--
	<table width="100%" cellspacing="0" border="0" align="center" cellpadding="0">
	<tr>
		<td align="left" nowrap="nowrap"><span class="small">{S_AUTH_LIST}</span></td>
		<td align="left" valign="top">
			<table cellspacing="3" cellpadding="0" border="0">
			<tr>
				<td width="20" align="left"><img src="{FOLDER_NEW_IMG}" alt="{L_NEW_POSTS}" width="19" height="18"></td>
				<td class="gensmall">{L_NEW_POSTS}</td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="{FOLDER_IMG}" alt="{L_NO_NEW_POSTS}" width="19" height="18"></td>
				<td class="gensmall">{L_NO_NEW_POSTS}</td>
			</tr>
			<tr> 
				<td width="20" align="center"><img src="{FOLDER_HOT_NEW_IMG}" alt="{L_NEW_POSTS_HOT}" width="19" height="18"></td>
				<td class="gensmall">{L_NEW_POSTS_HOT}</td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="{FOLDER_HOT_IMG}" alt="{L_NO_NEW_POSTS_HOT}" width="19" height="18"></td>
				<td class="gensmall">{L_NO_NEW_POSTS_HOT}</td>
			</tr>
			<tr>
				<td class="gensmall"><img src="{FOLDER_LOCKED_NEW_IMG}" alt="{L_NEW_POSTS_LOCKED}" width="19" height="18"></td>
				<td class="gensmall">{L_NEW_POSTS_LOCKED}</td>
				<td>&nbsp;&nbsp;</td>
				<td class="gensmall"><img src="{FOLDER_LOCKED_IMG}" alt="{L_NO_NEW_POSTS_LOCKED}" width="19" height="18"></td>
				<td class="gensmall">{L_NO_NEW_POSTS_LOCKED}</td>
			</tr>
			<tr>
				<td width="20" align="center"><img src="{FOLDER_ANNOUNCE_IMG}" alt="{L_ANNOUNCEMENT}" width="19" height="18"></td>
				<td class="gensmall">{L_ANNOUNCEMENT}</td>
				<td>&nbsp;&nbsp;</td>
				<td width="20" align="center"><img src="{FOLDER_STICKY_IMG}" alt="{L_STICKY}" width="19" height="18"></td>
				<td class="gensmall">{L_STICKY}</td>
			</tr>
			</table>
		</td>
		
	</tr>
	</table>
	-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="info_foot" colspan="5">{LOGGED_IN_USER_LIST}</td>
</tr>
</table>

