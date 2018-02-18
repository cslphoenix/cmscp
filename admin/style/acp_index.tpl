<li class="header">{L_WELCOME}</li>
<p>{L_EXPLAIN}</p>

{ERROR_BOX}

<fieldset class="full">
	<legend>{L_STATS}</legend>
	<table class="info">
	<thead>
	<tr>
		<th>{L_INFO}</th>
		<th>{L_VALUE}</th>
		<th>{L_DIR}</th>
		<th>{L_SIZE}</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>{L_PAGE_STARTED}:</td>
		<td>{PAGE_STARTED}</td>
		<td>{L_SIZE_CACHE}:</td>
		<td>{SIZE_CACHE}</td>
	</tr>
	<tr>
		<td>{L_PAGE_VERSION}:</td>
		<td>{PAGE_VERSION}</td>
		<td>{L_SIZE_FILES}:</td>
		<td>{SIZE_FILES}</td>
	</tr>
	<tr>
		<td>{L_PAGE_BACKUP}:</td>
		<td><span title="{PAGE_BACKUP_INFO}">{PAGE_BACKUP}</span></td>
		<td>{L_SIZE_DOWNLOADS}:</td>
		<td>{SIZE_DOWNLOADS}</td>
	</tr>
	<tr>
		<td>{L_PAGE_GZIP}:</td>
		<td>{PAGE_GZIP}</td>
		<td>{L_SIZE_GALLERY}:</td>
		<td>{SIZE_GALLERY}</td>
	</tr>
	<tr>
		<td>{L_SERVER}:</td>
		<td>{SERVER}</td>
		<td>{L_SIZE_MATCHS}:</td>
		<td>{SIZE_MATCHS}</td>
	</tr>
	<tr>
		<td>{L_DB_INFO}:</td>
		<td><span title="{DB_INFO_INFO}">{DB_INFO}</span></td>
		<td>{L_SIZE_USERS}:</td>
		<td>{SIZE_USERS}</td>
	</tr>
	<tr>
		<td></td>
		<td>{FILE_UPLOAD}</td>
		<td>{L_SIZE_GROUPS}:</td>
		<td>{SIZE_GROUPS}</td>
	</tr>
	<tr>
		<td></td>
		<td>{FILE_UPLOAD_MAX}</td>
		<td>{L_SIZE_TEAMS}:</td>
		<td>{SIZE_TEAMS}</td>
	</tr>
	<tr>
		<td></td>
		<td>{HTTP_ACCEPT_ENC}</td>
		<td>{L_SIZE_NETWORK}:</td>
		<td>{SIZE_NETWORK}</td>
	</tr>
	</tbody>
	</table>
</fieldset>

<fieldset class="full">
	<legend>{L_VIEWONLINE}</legend>
	{L_EXPLAIN2}
	<table class="online">
	<thead>
	<tr>
		<th>{L_USERNAME}</th>
		<th>{L_STARTED}</th>
		<th>{L_LAST_UPDATE}</th>
		<th>{L_FORUM_LOCATION}</th>
		<th>{L_IP_ADDRESS}</th>
	</tr>
	</thead>
	<tbody>
	<!-- BEGIN online_users -->
	<tr> 
		<td>{online_users.USERNAME}</td>
		<td>{online_users.STARTED}</td>
		<td>{online_users.LASTUPDATE}</td>
		<td>{online_users.FORUM_LOCATION}</td>
		<td><a href="{online_users.U_WHOIS_IP}" target="_phpbbwhois">{online_users.IP_ADDRESS}</a></td>
	</tr>
	<!-- END online_users -->
	<!-- BEGIN online_guests -->
	<tr> 
		<td>{online_guests.USERNAME}</td>
		<td>{online_guests.STARTED}</td>
		<td>{online_guests.LASTUPDATE}</td>
		<td>{online_guests.FORUM_LOCATION}</td>
		<td><a href="{online_guests.U_WHOIS_IP}" target="_phpbbwhois">{online_guests.IP_ADDRESS}</a></td>
	</tr>
	<!-- END online_guests -->
	</tbody>
	</table>
</fieldset>

<br>
{USERAUTH}
<br>


<fieldset>
	<legend>{L_ACP_LOG}</legend>
	<p>{L_EXPLAIN3}</p>
	<div>
		<!-- BEGIN logrow -->
		<dl>
			<dt>{logrow.USERNAME} :: {logrow.DATE}</dt>
			<dd><strong>{logrow.SEKTION}&nbsp;&raquo;&nbsp;{logrow.MESSAGE}</strong><br>{logrow.DATA}</dd>
		</dl>
		<!-- END logrow -->
	</div>
</fieldset>

<fieldset class="full">
	<legend>{L_FASTVIEW}</legend>
	{L_EXPLAIN4}<br>

<div class="index">
	<fieldset class="info">
		<legend><img src="{I_NEWS}" width="12" height="12" alt="" /> <a href="{U_NEWS}">{L_NEWS}</a></legend>
		<!-- BEGIN news_row -->
		<dl>
			<dt><span class="right">{news_row.DATE}</span>{news_row.GAME} {news_row.TITLE}</dt>
			<dd>{news_row.PUBLIC}{news_row.UPDATE}{news_row.DELETE}</dd>
		</dl>
		<!-- END news_row -->
		<!-- BEGIN news_empty -->
		<ul align="center">{L_NONE}</ul>
		<!-- END news_empty -->
	</fieldset>
</div>

<div class="index right">
	<fieldset class="info">
		<legend><img src="{I_EVENT}" width="12" height="12" alt="" /> <a href="{U_EVENT}">{L_EVENT}</a></legend>
		<!-- BEGIN event_row -->
		<dl>
			<dt><span class="right">{event_row.DATE}</span>{event_row.LEVEL} {event_row.TITLE}</dt>
			<dd>{event_row.UPDATE}{event_row.DELETE}</dd>
		</dl>
		<!-- END event_row -->
		<!-- BEGIN event_empty -->
		<ul align="center">{L_NONE}</ul>
		<!-- END event_empty -->
	</fieldset>
</div>

<div class="index">
	<fieldset class="info">
		<legend><img src="{I_MATCH}" width="12" height="12" alt="" /> <a href="{U_MATCH}">{L_MATCH}</a></legend>
		<!-- BEGIN match_row -->
		<dl>
			<dt><span class="right">{match_row.DATE}</span>{match_row.GAME} {match_row.RIVAL}</dt>
			<dd>{match_row.DETAIL}{match_row.UPDATE}{match_row.DELETE}</dd>
		</dl>
		<!-- END match_row -->
		<!-- BEGIN match_empty -->
		<ul align="center">{L_NONE}</ul>
		<!-- END match_empty -->
	</fieldset>
</div>

<div class="index right">
	<fieldset class="info">
		<legend><img src="{I_TRAIN}" width="12" height="12" alt="" /> <a href="{U_TRAIN}">{L_TRAIN}</a></legend>
		<!-- BEGIN training_row -->
		<dl>
			<dt><span class="right">{training_row.DATE}</span>{training_row.GAME} {training_row.VS}</dt>
			<dd>{training_row.UPDATE}{training_row.DELETE}</dd>
		</dl>
		<!-- END training_row -->
		<!-- BEGIN training_empty -->
		<ul align="center">{L_NONE}</ul>
		<!-- END training_empty -->
	</fieldset>
</div>

<div class="index large">
	<fieldset class="full">
		<legend><img src="{I_USERS}" width="12" height="12" alt="" /> <a href="{U_USERS}">{L_USERS}</a></legend>
		<!-- BEGIN user_row -->
		<dl>
			<dt><span class="right">{user_row.REGDATE}</span>{user_row.LEVEL} {user_row.NAME}</dt>
			<dd>{user_row.AUTH}{user_row.UPDATE}{user_row.DELETE}</dd>
		</dl>
		<!-- END user_row -->
	</fieldset>
</div>
</fieldset>