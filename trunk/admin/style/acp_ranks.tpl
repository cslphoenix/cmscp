<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead">{L_FORUM}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_SPECIAL}</td>
			<td class="rowHead" align="center" nowrap="nowrap">{L_MIN}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN forum_row -->
		<tr>
			<td class="row_class1" align="left" width="98%">{display.forum_row.TITLE}</td>
			<td class="row_class1" align="center" width="1%">{display.forum_row.SPECIAL}</td>
			<td class="row_class1" align="center" width="1%">{display.forum_row.MIN}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.forum_row.MOVE_UP} {display.forum_row.MOVE_DOWN} <a href="{display.forum_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.forum_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead">{L_PAGE}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN page_row -->
		<tr>
			<td class="row_class1" align="left" width="100%"><span style="float:right;">{display.page_row.STANDARD}</span>{display.page_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.page_row.MOVE_UP} {display.page_row.MOVE_DOWN} <a href="{display.page_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.page_row.U_DELETE}">{I_DELETE}</a></td>
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
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead">{L_TEAM}</td>
			<td class="rowHead" align="center">{L_SETTINGS}</td>
		</tr>
		<!-- BEGIN team_row -->
		<tr>
			<td class="row_class1" align="left" width="100%"><span style="float:right;">{display.team_row.STANDARD}</span>{display.team_row.TITLE}</td>
			<td class="row_class2" align="center" nowrap="nowrap">{display.team_row.MOVE_UP} {display.team_row.MOVE_DOWN} <a href="{display.team_row.U_UPDATE}">{I_UPDATE}</a> <a href="{display.team_row.U_DELETE}">{I_DELETE}</a></td>
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

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="rank_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN ranks_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{IMAGE_PATH}/" + encodeURI(newimage) : "{IMAGE_DEFAULT}";
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="rank_title">{L_NAME}: *</label></td>
	<td class="row3"><input type="text" class="post" name="rank_title" id="rank_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="rank_image">{L_IMAGE}:</label></td>
	<td class="row3 top">{IMAGE_LIST}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1 top"><label for="rank_type">{L_TYPE}:</label></td>
	<td class="row3">
		<label><input type="radio" name="rank_type" value="2" id="rank_type" {S_TYPE_FORUM} />&nbsp;{L_TYPE_FORUM} <sup>1</sup></label><br />
		<label><input type="radio" name="rank_type" value="1" {S_TYPE_PAGE} />&nbsp;{L_TYPE_PAGE} <sup>2</sup></label><br />
		<label><input type="radio" name="rank_type" value="3" {S_TYPE_TEAM} />&nbsp;{L_TYPE_TEAM} <sup>2</sup></label>
	</td> 
</tr>
<tr>
	<td class="row1"><label for="rank_special">{L_SPECIAL}: <sup>1</sup></label></td>
	<td class="row3"><label><input type="radio" name="rank_special" value="1" id="rank_special" onClick="this.form.rank_min.value='';" {S_SPECIAL_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="rank_special" value="0" {S_SPECIAL_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="rank_min">{L_MIN}: <sup>1</sup></label></td>
	<td class="row3"><input type="text" class="post" name="rank_min" id="rank_min" value="{MIN}"></td>
</tr>
<tr>
	<td class="row1"><label for="rank_standard">{L_STANDARD}: <sup>2</sup></label></td>
	<td class="row3"><label><input type="radio" name="rank_standard" value="1" id="rank_standard" {S_STANDARD_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="rank_standard" value="0" {S_STANDARD_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!--
<tr>
	<td class="row1 top"><label for="rank_type">{L_TYPE}:</label></td>
	<td class="row3">
		<label><input type="radio" name="rank_type" value="1" onChange="document.getElementById('forum').style.display = 'none'; document.getElementById('other').style.display = ''; document.getElementById('special').style.display = 'none';" id="rank_type" {S_TYPE_PAGE} />&nbsp;{L_TYPE_PAGE}</label><br />
		<label><input type="radio" name="rank_type" value="2" onChange="document.getElementById('forum').style.display = ''; document.getElementById('other').style.display = 'none';" {S_TYPE_FORUM} />&nbsp;{L_TYPE_FORUM}</label><br />
		<label><input type="radio" name="rank_type" value="3" onChange="document.getElementById('forum').style.display = 'none'; document.getElementById('other').style.display = ''; document.getElementById('special').style.display = 'none';" {S_TYPE_TEAM} />&nbsp;{L_TYPE_TEAM}</label>
	</td> 
</tr>
<tbody id="forum" style="display:none;">
<tr>
	<td class="row1">{L_SPECIAL}:</td>
	<td class="row3"><input type="radio" name="rank_special" value="1" onClick="this.form.rank_min.value=''; document.getElementById('special').style.display = 'none';" {S_SPECIAL_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="rank_special" value="0" onClick="document.getElementById('special').style.display = '';" {S_SPECIAL_NO} />&nbsp;{L_NO}</td>
</tr>
<tbody id="special" style="display:none;">
<tr>
	<td class="row1">{L_MIN}:</td>
	<td class="row3"><input type="text" class="post" name="rank_min" value="{MIN}"></td>
</tr>
</tbody>
</tbody>
<tbody id="other" style="display:;">
<tr>
	<td class="row1">{L_STANDARD}:</td>
	<td class="row3"><input type="radio" name="rank_standard" value="1" {S_STANDARD_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="rank_standard" value="0" {S_STANDARD_NO} />&nbsp;{L_NO}</td>
</tr>
</tbody>
-->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END ranks_edit -->