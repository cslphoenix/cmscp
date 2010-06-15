<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_WELCOME}</a></li>
</ul>
</div>

<br />
<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top" width="49%">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="{U_NEWS}" id="current"><img src="{I_NEWS}" width="12" height="12" alt="" />&nbsp;{L_NEWS}</a></li>
		</ul>
		</div>
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN row_news -->
		<tr class="wihte">
			<td width="90%"><span style="float:right;">{row_news.NEWS_DATE}</span>{row_news.NEWS_TITLE}</td>
			<td nowrap="nowrap">{row_news.NEWS_PUBLIC} {row_news.NEWS_UPDATE} {row_news.NEWS_DELETE}</td>
		</tr>
		<!-- END row_news -->
		<!-- BEGIN no_entry_news -->
		<tr>
			<td class="row_noentry1" colspan="2" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry_news -->
		</table>
	</td>
	<td width="2%"></td>
	<td class="top" width="49%">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="{U_MATCH}" id="right">{L_MATCH}&nbsp;<img src="{I_MATCH}" width="12" height="12" alt="" /></a></li>
		</ul>
		</div>
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN row_match -->
		<tr class="wihte">
			<td width="90%"><span style="float:right;">{row_match.MATCH_DATE}</span>{row_match.MATCH_RIVAL}</td>
			<td nowrap="nowrap">{row_match.MATCH_DETAILS} {row_match.MATCH_UPDATE} {row_match.MATCH_DELETE}</td>
		</tr>
		<!-- END row_match -->
		<!-- BEGIN no_entry_match -->
		<tr>
			<td class="row_noentry1" colspan="2" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry_match -->
		</table>
	</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top" width="49%">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="{U_EVENT}" id="current"><img src="{I_EVENT}" width="12" height="12" alt="" />&nbsp;{L_EVENT}</a></li>
		</ul>
		</div>
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN row_event -->
		<tr class="wihte">
			<td width="90%"><span style="float:right;">{row_event.EVENT_DATE}</span>{row_event.EVENT_TITLE}</td>
			<td>{row_event.EVENT_UPDATE} {row_event.EVENT_DELETE}</td>
		</tr>
		<!-- END row_event -->
		<!-- BEGIN no_entry_event -->
		<tr>
			<td class="row_noentry1" colspan="2" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry_event -->
		</table>
	</td>
	<td width="2%"></td>
	<td class="top" width="49%">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="{U_TRAIN}" id="right">{L_TRAIN}&nbsp;<img src="{I_TRAIN}" width="12" height="12" alt="" /></a></li>
		</ul>
		</div>
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN row_training -->
		<tr class="wihte">
			<td width="90%"><span style="float:right;">{row_training.TRAINING_DATE}</span>{row_training.TRAINING_VS}</td>
			<td nowrap="nowrap">{row_training.TRAINING_UPDATE} {row_training.TRAINING_DELETE}</td>
		</tr>
		<!-- END row_training -->
		<!-- BEGIN no_entry_training -->
		<tr>
			<td class="row_noentry1" colspan="2" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry_training -->
		</table>
	</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top">
		<div id="navcontainer">
		<ul id="navlist">
			<li><a href="{U_USERS}" id="right">{L_USERS}&nbsp;<img src="{I_USERS}" width="12" height="12" alt="" /></a></li>
		</ul>
		</div>
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN row_user -->
		<tr class="wihte">
			<td align="left" width="97%"><span style="float:right;">{row_user.USER_REGDATE}</span>{row_user.USER_NAME}</td>
			<td nowrap="nowrap">{row_user.USER_AUTH} {row_user.USER_UPDATE} {row_user.USER_DELETE}</td>
		</tr>
		<!-- END row_user -->
		<!-- BEGIN no_entry_user -->
		<tr>
			<td class="row_noentry" colspan="3" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry_user -->
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
