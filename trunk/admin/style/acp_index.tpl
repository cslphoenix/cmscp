<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_WELCOME}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table>
<tr>
	<td width="50%" class="top">
		<table class="info">
		<th><ul id="navlist"><li><a href="#" id="right" onclick="return false;">{L_INFO}</a></li></ul></th>
		<th><ul id="navlist"><li><a href="#" id="current" onclick="return false;">{L_VALUE}</a></li></ul></th>
		<tr>
			<td>{L_PAGE_STARTED}:</td>
			<td>{PAGE_STARTED}</td>
		</tr>
		<tr>
			<td>{L_PAGE_VERSION}:</td>
			<td>{PAGE_VERSION}</td>
		</tr>
		<tr>
			<td>{L_PAGE_BACKUP}:</td>
			<td><span title="{PAGE_BACKUP_INFO}">{PAGE_BACKUP}</span></td>
		</tr>
		<tr>
			<td>{L_PAGE_GZIP}:</td>
			<td>{PAGE_GZIP}</td>
		</tr>
		<tr>
			<td>{L_SERVER}:</td>
			<td>{SERVER}</td>
		</tr>
		<tr>
			<td>{L_DB_INFO}:</td>
			<td><span title="{DB_INFO_INFO}">{DB_INFO}</span></td>
		</tr>
		</table>
	</td>
	<td width="50%" class="top">
		<table class="info">
		<th><ul id="navlist"><li><a href="#" id="right" onclick="return false;">{L_DIR}</a></li></ul></th>
		<th><ul id="navlist"><li><a href="#" id="current" onclick="return false;">{L_SIZE}</a></li></ul></th>
		<tr>
			<td>{L_SIZE_CACHE}:</td>
			<td>{SIZE_CACHE}</td>
		</tr>
		<tr>
			<td>{L_SIZE_FILES}:</td>
			<td>{SIZE_FILES}</td>
		</tr>
		<tr>
			<td>{L_SIZE_DOWNLOADS}:</td>
			<td>{SIZE_DOWNLOADS}</td>
		</tr>
		<tr>
			<td>{L_SIZE_GALLERY}:</td>
			<td>{SIZE_GALLERY}</td>
		</tr>
		<tr>
			<td>{L_SIZE_MATCH}:</td>
			<td>{SIZE_MATCH}</td>
		</tr>
		<tr>
			<td>{L_SIZE_USER}:</td>
			<td>{SIZE_USER}</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<br />
{ERROR_BOX}

<table>
<tr>
	<td class="top" width="50%">
		<ul id="navlist"><li><a href="{U_NEWS}" id="current"><img src="{I_NEWS}" width="12" height="12" alt="" />&nbsp;{L_NEWS}</a></li></ul>
		<table class="index">
		<!-- BEGIN news_row -->
		<tr>
			<td><span class="right">{news_row.DATE}</span>{news_row.GAME} {news_row.TITLE}</td>
			<td>{news_row.PUBLIC}{news_row.UPDATE}{news_row.DELETE}</td>
		</tr>
		<!-- END news_row -->
		<!-- BEGIN news_empty -->
		<tr>
			<td class="empty" colspan="4" align="center">{L_EMPTY}</td>
		</tr>
		<!-- END news_empty -->
		</table>
	</td>
	<td class="top" width="50%">
		<ul id="navlist"><li><a href="{U_EVENT}" id="right"><img src="{I_EVENT}" width="12" height="12" alt="" />&nbsp;{L_EVENT}</a></li></ul>
		<table class="index">
		<!-- BEGIN event_row -->
		<tr>
			<td><span class="right">{event_row.DATE}</span>{event_row.LEVEL} {event_row.TITLE}</td>
			<td>{event_row.UPDATE}{event_row.DELETE}</td>
		</tr>
		<!-- END event_row -->
		<!-- BEGIN event_empty -->
		<tr>
			<td class="empty" colspan="4" align="center">{L_EMPTY}</td>
		</tr>
		<!-- END event_empty -->
		</table>
	</td>
</tr>
</table>

<br />

<table>
<tr>
	<td class="top" width="50%">
		<ul id="navlist"><li><a href="{U_MATCH}" id="current"><img src="{I_MATCH}" width="12" height="12" alt="" />&nbsp;{L_MATCH}</a></li></ul>
		<table class="index">
		<!-- BEGIN match_row -->
		<tr>
			<td><span class="right">{match_row.DATE}</span>{match_row.GAME} {match_row.RIVAL}</td>
			<td>{match_row.DETAIL}{match_row.UPDATE}{match_row.DELETE}</td>
		</tr>
		<!-- END match_row -->
		<!-- BEGIN match_empty -->
		<tr>
			<td class="empty" align="center">{L_EMPTY}</td>
		</tr>
		<!-- END match_empty -->
		</table>
	</td>
	<td class="top" width="50%">
		<ul id="navlist"><li><a href="{U_TRAIN}" id="right"><img src="{I_TRAIN}" width="12" height="12" alt="" />&nbsp;{L_TRAIN}</a></li></ul>
		<table class="index">
		<!-- BEGIN training_row -->
		<tr>
			<td><span class="right">{training_row.DATE}</span>{training_row.GAME} {training_row.VS}</td>
			<td>{training_row.UPDATE}{training_row.DELETE}</td>
		</tr>
		<!-- END training_row -->
		<!-- BEGIN training_empty -->
		<tr>
			<td class="empty">{L_EMPTY}</td>
		</tr>
		<!-- END training_empty -->
		</table>
	</td>
</tr>
</table>

<br />

<ul id="navlist"><li><a href="{U_USERS}" id="current"><img src="{I_USERS}" width="12" height="12" alt="" />&nbsp;{L_USERS}</a></li></ul>
<table class="index">
<!-- BEGIN user_row -->
<tr>
	<td><span class="right">{user_row.REGDATE}</span>{user_row.LEVEL} {user_row.NAME}</td>
	<td>{user_row.AUTH}{user_row.UPDATE}{user_row.DELETE}</td>
</tr>
<!-- END user_row -->
</table>
