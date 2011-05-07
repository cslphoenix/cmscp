<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_FORUM}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SPECIAL}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_MIN}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _forum_row -->
<tr>
	<td class="row_class1" align="left" width="98%">{_display._forum_row.NAME}</td>
	<td class="row_class1" align="center" width="1%">{_display._forum_row.SPECIAL}</td>
	<td class="row_class1" align="center" width="1%">{_display._forum_row.MIN}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._forum_row.MOVE_UP} {_display._forum_row.MOVE_DOWN} <a href="{_display._forum_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._forum_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _forum_row -->
<!-- BEGIN _no_forum -->
<tr>
	<td class="row_class1" align="center" colspan="7">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_forum -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="rank_name[2]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="rank_type[2]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_PAGE}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _page_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._page_row.STANDARD}</span>{_display._page_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._page_row.MOVE_UP} {_display._page_row.MOVE_DOWN} <a href="{_display._page_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._page_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _page_row -->
<!-- BEGIN _no_page -->
<tr>
	<td class="row_class1" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_page -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="rank_name[1]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="rank_type[1]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_TEAM}</td>
	<td class="rowHead" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _team_row -->
<tr>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._team_row.STANDARD}</span>{_display._team_row.NAME}</td>
	<td class="row_class2" align="center" nowrap="nowrap">{_display._team_row.MOVE_UP} {_display._team_row.MOVE_DOWN} <a href="{_display._team_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._team_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _team_row -->
<!-- BEGIN _no_team -->
<tr>
	<td class="row_class1" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_team -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="rank_name[3]"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" name="rank_type[3]" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{AJAX}
{UIMG}
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="rank_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="rank_name" id="rank_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="rank_image">{L_IMAGE}:</label></td>
	<td class="row2">{S_LIST}<br /><img src="{PIC}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label>{L_TYPE}:</label></td>
	<td class="row2">
		<label><input type="radio" name="rank_type" value="1" onclick="setRequest('rank', '1'); document.getElementById('forms').style.display = 'none'; document.getElementById('normal').style.display = ''; document.getElementById('special').style.display = 'none';" {S_TYPE_PAGE} />&nbsp;{L_TYPE_PAGE}</label><br />
		<label><input type="radio" name="rank_type" value="2" onclick="setRequest('rank', '2'); document.getElementById('forms').style.display = ''; document.getElementById('normal').style.display = 'none'; document.getElementById('special').style.display = '';" {S_TYPE_FORUM} />&nbsp;{L_TYPE_FORUM}</label><br />
		<label><input type="radio" name="rank_type" value="3" onclick="setRequest('rank', '3'); document.getElementById('forms').style.display = 'none'; document.getElementById('normal').style.display = ''; document.getElementById('special').style.display = 'none';" {S_TYPE_TEAM} />&nbsp;{L_TYPE_TEAM}</label>
	</td> 
</tr>
<tbody id="forms" style="display:{SHOW_FORMS};">
<tr>
	<td class="row1"><label for="rank_special">{L_SPECIAL}:</label></td>
	<td class="row2"><label><input type="radio" name="rank_special" value="1" id="rank_special" onClick="this.form.rank_min.value=''; document.getElementById('special').style.display = 'none';" {S_SPECIAL_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="rank_special" value="0" onclick="document.getElementById('special').style.display = '';" {S_SPECIAL_NO} />&nbsp;{L_NO}</label></td>
</tr>
</tbody>
<tbody id="special" style="display:{SHOW_SPECIAL};">
<tr>
	<td class="row1"><label for="rank_min">{L_MIN}:</label></td>
	<td class="row2"><input type="text" class="post" name="rank_min" id="rank_min" value="{MIN}"></td>
</tr>
</tbody>
<tbody id="normal" style="display:{SHOW_NORMAL};">
<tr>
	<td class="row1"><label for="rank_standard">{L_STANDARD}:</label></td>
	<td class="row2"><label><input type="radio" name="rank_standard" value="1" id="rank_standard" {S_STANDARD_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="rank_standard" value="0" {S_STANDARD_NO} />&nbsp;{L_NO}</label></td>
</tr>
</tbody>
<tr>
	<td class="row1"><label for="navi_order">{L_ORDER}:</label></td>
	<td class="row2"><div id="close">{S_ORDER}</div><div id="content"></div></td>
</tr>
</tbody>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->