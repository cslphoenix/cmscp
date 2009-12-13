<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_WELCOME}</a></li>
</ul>
</div>

<br />
<br />

<div id="navcontainer">
<ul id="navlist">
	<li><a href="#" id="current"><img src="{ICON_CAL}" width="12" height="12" alt="" />&nbsp;{L_CAL}</a></li>
	<li><a href="#" id="right"><img src="{ICON_USER}" width="12" height="12" alt="" />&nbsp;{L_USER}</a></li>
</ul>
</div>
<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="top" width="48%">
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN event_row -->
		<tr>
			<td align="left" width="97%"><span style="float:right;">{event_row.EVENT_DATE}</span>{event_row.EVENT_TITLE}</td>
			<td><a href="{event_row.U_UPDATE}"><img src="{ICON_CAL_UPDATE}" title="{L_UPDATE}" alt="" /></a></td>
			<td><a href="{event_row.U_DELETE}"><img src="{ICON_CAL_DELETE}" title="{L_DELETE}" alt="" /></a></td>
		</tr>
		<!-- END event_row -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row_noentry" colspan="3" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry -->
		</table>
	</td>
	<td width="4%">&nbsp;</td>
	<td class="top" width="48%">
		<table class="small" border="0" cellspacing="2" cellpadding="1">
		<!-- BEGIN users_row -->
		<tr>
			<td align="left" width="97%"><span style="float:right;">{users_row.USER_REG}</span>{users_row.USERNAME}</td>
			<td><a href="{users_row.U_AUTH}"><img src="{ICON_USER_GO}" title="{L_AUTH}" alt="" /></a></td>
			<td><a href="{users_row.U_UPDATE}"><img src="{ICON_USER_UPDATE}" title="{L_UPDATE}" alt="" /></a></td>
			<td><a href="{users_row.U_DELETE}"><img src="{ICON_USER_DELETE}" title="{L_DELETE}" alt="" /></a></td>
		</tr>
		<!-- END users_row -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row_noentry" colspan="3" align="center">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry -->
		</table>
	</td>
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
