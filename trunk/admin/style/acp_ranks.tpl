<!-- BEGIN display -->
<form action="{S_RANKS_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_RANK_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_RANK_EXPLAIN}</td>
</tr>
</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_RANK_FORUM}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_RANK_SPECIAL}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_RANK_MIN}</td>
			<td class="rowHead" align="right" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN forum_row -->
		<tr>
			<td class="{display.forum_row.CLASS}" align="left" width="98%">{display.forum_row.RANK_TITLE}</td>
			<td class="{display.forum_row.CLASS}" align="center" width="1%">{display.forum_row.RANK_SPECIAL}</td>
			<td class="{display.forum_row.CLASS}" align="center" width="1%">{display.forum_row.RANK_MIN}</td>
			<td class="{display.forum_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.forum_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.forum_row.CLASS}" align="center" nowrap="nowrap">{display.forum_row.MOVE_UP} {display.forum_row.MOVE_DOWN}</td>
			<td class="{display.forum_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.forum_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END forum_row -->
		<!-- BEGIN no_ranks -->
		<tr>
			<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
		</tr>
		<!-- END no_ranks -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_RANK_PAGE}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_RANK_STANDARD}</td>
			<td class="rowHead" align="right" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN page_row -->
		<tr>
			<td class="{display.page_row.CLASS}" align="left" width="100%">{display.page_row.RANK_TITLE}</td>
			<td class="{display.page_row.CLASS}" align="center" nowrap="nowrap">{display.page_row.STANDARD}</td>
			<td class="{display.page_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.page_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.page_row.CLASS}" align="center" nowrap="nowrap">{display.page_row.MOVE_UP} {display.page_row.MOVE_DOWN}</td>
			<td class="{display.page_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.page_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END page_row -->
		<!-- BEGIN no_ranks -->
		<tr>
			<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
		</tr>
		<!-- END no_ranks -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="row" cellspacing="1">
		<tr>
			<td class="rowHead">{L_RANK_TEAM}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_RANK_STANDARD}</td>
			<td class="rowHead" align="right" colspan="3">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN team_row -->
		<tr>
			<td class="{display.team_row.CLASS}" align="left" width="100%">{display.team_row.RANK_TITLE}</td>
			<td class="{display.team_row.CLASS}" align="center" nowrap="nowrap">{display.team_row.STANDARD}</td>
			<td class="{display.team_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.team_row.U_EDIT}">{L_EDIT}</a></td>
			<td class="{display.team_row.CLASS}" align="center" nowrap="nowrap">{display.team_row.MOVE_UP} {display.team_row.MOVE_DOWN}</td>
			<td class="{display.team_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.team_row.U_DELETE}">{L_DELETE}</a></td>
		</tr>
		<!-- END team_row -->
		<!-- BEGIN no_ranks -->
		<tr>
			<td class="row_class1" align="center" colspan="7">{NO_RANKS}</td>
		</tr>
		<!-- END no_ranks -->
		</table>
	</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="rank_title" type="text" value=""></td>
	<td><input class="button" type="submit" name="rank_add" value="{L_RANK_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN ranks_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{RANKS_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_RANKS_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_RANKS_ACTION}">{L_RANK_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_RANK_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_RANK_NAME}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="rank_title" value="{RANK_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_RANK_IMAGE}:</td>
	<td class="row3">{S_FILENAME_LIST}&nbsp;<img src="{RANK_IMAGE}" id="image" alt="" />
	</td>
</tr>
<tr>
	<td class="row1">{L_RANK_TYPE}:</td>
	<td class="row3">
		<input type="radio" name="rank_type" value="2" {CHECKED_TYPE_FORUM} />&nbsp;{L_TYPE_FORUM}<br />
		<input type="radio" name="rank_type" value="1" {CHECKED_TYPE_PAGE} />&nbsp;{L_TYPE_PAGE}<br />
		<input type="radio" name="rank_type" value="3" {CHECKED_TYPE_TEAM} />&nbsp;{L_TYPE_TEAM}
	</td> 
</tr>
<tr>
	<td class="row1">{L_RANK_SPECIAL}:</td>
	<td class="row3"><input type="radio" name="rank_special" value="0" {CHECKED_SPECIAL_NO} />&nbsp;{L_NO}&nbsp;&nbsp;<input type="radio" name="rank_special" onClick="this.form.rank_min.value=''" value="1" {CHECKED_SPECIAL_YES} />&nbsp;{L_YES}</td>
</tr>
<tr>
	<td class="row1">{L_RANK_MIN}:</td>
	<td class="row3"><input class="post" type="text" name="rank_min" value="{RANK_MIN}" ></td>
</tr>
<tr>
	<td class="row1">{L_RANK_STANDARD}:</td>
	<td class="row3"><input type="radio" name="rank_standard" value="0" {CHECKED_STANDARD_NO} />&nbsp;{L_NO}&nbsp;&nbsp;<input type="radio" name="rank_standard" value="1" {CHECKED_STANDARD_YES} />&nbsp;{L_YES}</td>
</tr>
<!--
<tr>
	<td class="row1">{L_RANK_TYPE}:</td>
	<td class="row3">
		<input type="radio" name="rank_type" onChange="document.getElementById('trainbox').style.display = '';" value="2" {CHECKED_TYPE_FORUM} /> {L_TYPE_FORUM}<br />
		<input type="radio" name="rank_type" onChange="document.getElementById('trainbox').style.display = 'none';" value="1" {CHECKED_TYPE_PAGE} /> {L_TYPE_PAGE}<br />
		<input type="radio" name="rank_type" onChange="document.getElementById('trainbox').style.display = 'none';" value="3" {CHECKED_TYPE_TEAM} /> {L_TYPE_TEAM}
	</td> 
</tr>
<tbody id="trainbox" style="display: none;">
<tr>
	<td class="row1">{L_RANK_SPECIAL}:</td>
	<td class="row3">
		<input type="radio" name="rank_special" value="0" {CHECKED_SPECIAL_NO} />&nbsp;{L_NO}
		<input type="radio" name="rank_special" onClick="this.form.rank_min.value=''" value="1" {CHECKED_SPECIAL_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_RANK_MIN}:</td>
	<td class="row3"><input class="post" type="text" name="rank_min" value="{RANK_MIN}" ></td>
</tr>
</tbody>
-->
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END ranks_edit -->