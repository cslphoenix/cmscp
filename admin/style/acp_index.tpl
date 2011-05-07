<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_WELCOME}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_WELCOME_EXPLAIN}</td>
</tr>
</table>

<br />
<br />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top" width="49%">
		<table class="index" border="0" cellspacing="0" cellpadding="0">
		<th colspan="4">
			<div id="navcontainer">
				<ul id="navlist">
					<li><a href="{U_NEWS}" id="current"><img src="{I_NEWS}" width="12" height="12" alt="" />&nbsp;{L_NEWS}</a></li>
				</ul>
			</div>
		</th>
		<!-- BEGIN row_news -->
		<tr class="hover">
			<td>{row_news.GAME}</td>
			<td>{row_news.TITLE}</td>
			<td>{row_news.DATE}</td>
			<td>{row_news.PUBLIC} {row_news.UPDATE} {row_news.DELETE}</td>
		</tr>
		<!-- END row_news -->
		<!-- BEGIN _entry_empty_news -->
		<tr>
			<td class="entry_empty" colspan="4" align="center">{L_ENTRY_NO}</td>
		</tr>
		<!-- END _entry_empty_news -->
		</table>
	</td>
	<td class="top" width="49%">
		<table class="index" border="0" cellspacing="0" cellpadding="0">
		<th colspan="4">
			<div id="navcontainer">
				<ul id="navlist">
					<li><a href="{U_EVENT}" id="right"><img src="{I_EVENT}" width="12" height="12" alt="" />&nbsp;{L_EVENT}</a></li>
				</ul>
			</div>
		</th>
		<!-- BEGIN _event_row -->
		<tr class="hover">
			<td><img src="{_event_row.LEVEL}" title="{_event_row.LEVEL_EXP}" alt="" /></td>
			<td>{_event_row.TITLE}</td>
			<td>{_event_row.DATE}</td>
			<td>{_event_row.UPDATE} {_event_row.DELETE}</td>
		</tr>
		<!-- END _event_row -->
		<!-- BEGIN _entry_empty_event -->
		<tr>
			<td class="entry_empty" colspan="4" align="center">{L_ENTRY_NO}</td>
		</tr>
		<!-- END _entry_empty_event -->
		</table>
	</td>
</tr>
</table>

<br />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top" width="49%">
		<table class="index" border="0" cellspacing="0" cellpadding="0">
		<th colspan="4">
			<div id="navcontainer">
				<ul id="navlist">
					<li><a href="{U_MATCH}" id="current">{L_MATCH}&nbsp;<img src="{I_MATCH}" width="12" height="12" alt="" /></a></li>
				</ul>
			</div>
		</th>
		<!-- BEGIN _match_row -->
		<tr class="hover">
			<td>{_match_row.GAME}</td>
			<td>{_match_row.RIVAL}</td>
			<td>{_match_row.DATE}</td>
			<td>{_match_row.DETAIL} {_match_row.UPDATE} {_match_row.DELETE}</td>
		</tr>
		<!-- END _match_row -->
		<!-- BEGIN no_entry_match -->
		<tr>
			<td class="entry_empty" colspan="4" align="center">{L_ENTRY_NO}</td>
		</tr>
		<!-- END no_entry_match -->
		</table>
	</td>
	<td class="top" width="49%">
		<table class="index" border="0" cellspacing="0" cellpadding="0">
		<th colspan="4">
			<div id="navcontainer">
				<ul id="navlist">
					<li><a href="{U_TRAIN}" id="right">{L_TRAIN}&nbsp;<img src="{I_TRAIN}" width="12" height="12" alt="" /></a></li>
				</ul>
			</div>
		</th>
		<!-- BEGIN _training_row -->
		<tr class="hover">
			<td>{_training_row.GAME}</td>
			<td>{_training_row.VS}</td>
			<td>{_training_row.DATE}</td>
			<td>{_training_row.UPDATE} {_training_row.DELETE}</td>
		</tr>
		<!-- END _training_row -->
		<!-- BEGIN _entry_empty_training -->
		<tr>
			<td class="entry_empty">{L_ENTRY_NO}</td>
		</tr>
		<!-- END _entry_empty_training -->
		</table>
	</td>
</tr>
</table>

<br />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		
		<table class="index" border="0" cellspacing="0" cellpadding="0">
		<th colspan="4">
			<div id="navcontainer">
				<ul id="navlist">
					<li><a href="{U_USERS}" id="right">{L_USERS}&nbsp;<img src="{I_USERS}" width="12" height="12" alt="" /></a></li>
				</ul>
			</div>
		</th>
		<!-- BEGIN _user_row -->
		<tr class="hover">
			<td><img src="{_user_row.LEVEL}" title="{_user_row.LEVEL_EXP}" alt="" /></td>
			<td>{_user_row.NAME}</td>
			<td>{_user_row.REGDATE}</td>
			<td>{_user_row.AUTH} {_user_row.UPDATE} {_user_row.DELETE}</td>
		</tr>
		<!-- END _user_row -->
		</table>
	</td>
</tr>
</table>

<!--
<table class="head" cellspacing="0">
<tr>
	<th>{L_VERSION_INFORMATION}</th>
</tr>
<tr>
	<td>{VERSION_INFO}</td>
</tr>
</table>

<table class="head" cellspacing="0">
<tr>
	<th>{L_STATISTIC}</th>
</tr>
<tr>
	<td></td>
</tr>
</table>
-->
<!--
<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
  <tr> 
	<th width="25%" nowrap="nowrap" height="25" class="thCornerL">{L_STATISTIC}</th>
	<th width="25%" height="25" class="thTop">{L_VALUE}</th>
	<th width="25%" nowrap="nowrap" height="25" class="thTop">{L_STATISTIC}</th>
	<th width="25%" height="25" class="thCornerR">{L_VALUE}</th>
  </tr>
  <tr> 
	<td class="row1" nowrap="nowrap">{L_NUMBER_POSTS}:</td>
	<td class="row2"><b>{NUMBER_OF_POSTS}</b></td>
	<td class="row1" nowrap="nowrap">{L_POSTS_PER_DAY}:</td>
	<td class="row2"><b>{POSTS_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row1" nowrap="nowrap">{L_NUMBER_TOPICS}:</td>
	<td class="row2"><b>{NUMBER_OF_TOPICS}</b></td>
	<td class="row1" nowrap="nowrap">{L_TOPICS_PER_DAY}:</td>
	<td class="row2"><b>{TOPICS_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row1" nowrap="nowrap">{L_NUMBER_USERS}:</td>
	<td class="row2"><b>{NUMBER_OF_USERS}</b></td>
	<td class="row1" nowrap="nowrap">{L_USERS_PER_DAY}:</td>
	<td class="row2"><b>{USERS_PER_DAY}</b></td>
  </tr>
  <tr> 
	<td class="row1" nowrap="nowrap">{L_BOARD_STARTED}:</td>
	<td class="row2"><b>{START_DATE}</b></td>
	<td class="row1" nowrap="nowrap">{L_AVATAR_DIR_SIZE}:</td>
	<td class="row2"><b>{AVATAR_DIR_SIZE}</b></td>
  </tr>
  <tr> 
	<td class="row1" nowrap="nowrap">{L_DB_SIZE}:</td>
	<td class="row2"><b>{DB_SIZE}</b></td>
	<td class="row1" nowrap="nowrap">{L_GZIP_COMPRESSION}:</td>
	<td class="row2"><b>{GZIP_COMPRESSION}</b></td>
  </tr>
</table>


<br>

<table class="head" cellspacing="0">
<tr>
	<th></th>
</tr>
<tr>
	<td></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><a href="{U_NEWS}">{ICON_NEWS}</a></td>
		<td><a href="{U_TEAM}">{ICON_TEAM}</a></td>
		<td><a href="{U_USER}">{ICON_USER}</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
-->
<br>
