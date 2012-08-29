<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
}

function rank_posts(row)
{
	if ( row == '1' || row == '3' )
	{
		document.getElementById('rank_min').style.display = "none";
	}
	else if ( row == '2' )
	{
		document.getElementById('rank_min').style.display = "";
	}
}

// ]]>
</script>
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
<!-- END tab -->
</div>
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}

<!--
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="rank_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="rank_name" id="rank_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="rank_image">{L_IMAGE}:</label></td>
	<td>{S_LIST} <img src="{PIC}" id="image" alt="" border="0" /></td>
</tr>
<tr>
	<td class="row1"><label>{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="rank_type" value="2" onclick="setRequest('rank', 2, {CUR_TYPE}, {CUR_ORDER}); document.getElementById('forms').style.display = ''; document.getElementById('normal').style.display = 'none'; document.getElementById('posts').style.display = '';" {S_TYPE_FORUM} />&nbsp;{L_TYPE_FORUM}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="rank_type" value="1" onclick="setRequest('rank', 1, {CUR_TYPE}, {CUR_ORDER}); document.getElementById('forms').style.display = 'none'; document.getElementById('normal').style.display = ''; document.getElementById('posts').style.display = 'none';" {S_TYPE_PAGE} />&nbsp;{L_TYPE_PAGE}</label><span style="padding:4px;"></span>
		<label><input type="radio" name="rank_type" value="3" onclick="setRequest('rank', 3, {CUR_TYPE}, {CUR_ORDER}); document.getElementById('forms').style.display = 'none'; document.getElementById('normal').style.display = ''; document.getElementById('posts').style.display = 'none';" {S_TYPE_TEAM} />&nbsp;{L_TYPE_TEAM}</label>
	</td> 
</tr>
<tbody id="forms" style="display:{SHOW_FORMS};">
<tr>
	<td class="row1"><label for="rank_special">{L_SPECIAL}:</label></td>
	<td class="row2"><label><input type="radio" name="rank_special" value="1" id="rank_special" onClick="this.form.rank_min.value=''; document.getElementById('posts').style.display = 'none';" {S_SPECIAL_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="rank_special" value="0" onclick="document.getElementById('posts').style.display = '';" {S_SPECIAL_NO} />&nbsp;{L_NO}</label></td>
</tr>
</tbody>
<tbody id="posts" style="display:{SHOW_POSTS};">
<tr>
	<td class="row1"><label for="rank_min">{L_MIN}:</label></td>
	<td class="row2"><input type="text" name="rank_min" id="rank_min" value="{MIN}"></td>
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
	<td><div id="close">{S_ORDER}</div><div id="ajax_content"></div></td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
</table>
-->
</form>
<!-- END input -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<th>{L_FORUM}</th>
	<th>{L_SPECIAL}</th>
	<th>{L_MIN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td>{display.forum_row.NAME}</td>
	<td>{display.forum_row.SPECIAL}</td>
	<td>{display.forum_row.MIN}</td>
	<td>{display.forum_row.IMAGE}{display.forum_row.MOVE_DOWN}{display.forum_row.MOVE_UP}{display.forum_row.UPDATE}{display.forum_row.DELETE}</td>
</tr>
<!-- END forum_row -->
<!-- BEGIN forum_empty -->
<tr>
	<td class="empty" colspan="4">{L_EMPTY}</td>
</tr>
<!-- END forum_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[2]"></td>
	<td><input type="submit" class="button2" name="rank_type[2]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_PAGE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN page_row -->
<tr>
	<td><span class="right">{display.page_row.STANDARD}</span>{display.page_row.NAME}</td>
	<td>{display.page_row.IMAGE}{display.page_row.MOVE_DOWN}{display.page_row.MOVE_UP}{display.page_row.UPDATE}{display.page_row.DELETE}</td>
</tr>
<!-- END page_row -->
<!-- BEGIN page_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END page_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[1]"></td>
	<td><input type="submit" class="button2" name="rank_type[1]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_TEAM}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td><span class="right">{display.team_row.STANDARD}</span>{display.team_row.NAME}</td>
	<td>{display.team_row.IMAGE}{display.team_row.MOVE_DOWN}{display.team_row.MOVE_UP}{display.team_row.UPDATE}{display.team_row.DELETE}</td>
</tr>
<!-- END team_row -->
<!-- BEGIN team_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END team_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[3]"></td>
	<td><input type="submit" class="button2" name="rank_type[3]" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->