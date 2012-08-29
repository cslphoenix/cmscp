<script type="text/javascript">  
// <![CDATA[
	function set_right(id)
	{
		var obj = document.getElementById(id).checked = true;
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<!-- BEGIN display -->
<h1>{L_HEAD}</h1>

<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN cats_row -->
<table class="rows">
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{display.catsrow.NAME}</td>
	<!-- BEGIN image -->
	<td class="rowHead" align="center"><img src="{display.catsrow._image.IMAGE}" title="{display.catsrow._image.TITLE}" width="24" height="24" /></td>
	<!-- END image -->
</tr>
<!-- BEGIN forms_row -->
<tr> 
	<td class="{display.catsrow._formsrow.CLASS}"><span style="float:right">{display.catsrow._formsrow.SUBS}</span>{display.catsrow._formsrow.NAME}</td>
	<!-- BEGIN forms_auth -->
	<td class="{display.catsrow._formsrow.CLASS}" align="center"><img src="{display.catsrow._formsrow._forms_auth.IMAGE}" title="{display.catsrow._formsrow._forms_auth.EXPLAIN}" /></td>
	<!-- END forms_auth -->
</tr>
<!-- BEGIN subs_row -->
<tr> 
	<td class="{display.catsrow._formsrow._subsrow.CLASS}">{display.catsrow._formsrow._subsrow.NAME}</td>
	<!-- BEGIN subs_auth -->
	<td class="{display.catsrow._formsrow._subsrow.CLASS}" align="center"><img src="{display.catsrow._formsrow._subsrow._subs_auth.IMAGE}" title="{display.catsrow._formsrow._subsrow._subs_auth.EXPLAIN}" /></td>
	<!-- END subs_auth -->
</tr>
<!-- END subs_row -->
<!-- END forms_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="empty" colspan="12">{L_EMPTY}</td>
</tr>
<!-- END no_entry -->
</table>

<br />
<!-- END cats_row -->
<!-- END display -->

<!-- BEGIN cats -->
<h1>{L_HEAD}</h1>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<!-- BEGIN cats_row -->
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{_cats._catsrow.NAME}</td>
	<!-- BEGIN image -->
	<td class="rowHead" align="center"><img src="{_cats._catsrow._image.IMAGE}" title="{_cats._catsrow._image.TITLE}" width="24" height="24" /></td>
	<!-- END image -->
</tr>
<!-- BEGIN forms_row -->
<tr> 
	<td class="{_cats._catsrow._formsrow.CLASS}"><span style="float:right">{_cats._catsrow._formsrow.SUBS}</span>{_cats._catsrow._formsrow.NAME}</td>
	<!-- BEGIN forms_auth -->
	<td class="{_cats._catsrow._formsrow.CLASS}" align="center"><img src="{_cats._catsrow._formsrow._forms_auth.IMAGE}" title="{_cats._catsrow._formsrow._forms_auth.EXPLAIN}" /></td>
	<!-- END forms_auth -->
</tr>
<!-- END forms_row -->
</table>
<!-- END cats_row -->
<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="12">
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="clip('settings')"><img src="style/images/expand.gif" id="img_settings" border="0" /> {L_INPUT_STANDARD}</a></li></ul>
		</div>
	</td>
</tr>
<tbody id="tbody_settings" style="display:none">
<tr>
	<td colspan="12" class="row4"><span class="small">{STANDARDS}</span></td>
</tr>
</tbody>
<tr>
	<td colspan="12">&nbsp;</td>
</tr>
<tr>
	<td>
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_OPTION}</a></li></ul>
		</div>
	</td>
	<!-- BEGIN image -->
	<td>
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;"><img src="{_cats._image.IMAGE}" title="{_cats._image.TITLE}" width="12" height="12" alt="" /></a></li></ul>
		</div>
	</td>
	<!-- END image -->
</tr>
<!-- BEGIN set -->
<tr>
	<td class="row4">{_cats._set.NAME}:&nbsp;</td>
	<!-- BEGIN auth -->
	<td align="center">{_cats._set._auth.SELECT}</td>
	<!-- END auth -->
</tr>
<!-- END set -->
<tr>
	<td colspan="12">&nbsp;</td>
</tr>

<tr>
	<td colspan="12" align="center"><input type="submit" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
<!-- END cats -->
</form>

<!-- BEGIN auth_list -->
<script type="text/javascript">  

/*
	Einfacher Klapptext, wird mit jquery noch erweitert!
*/

function clip(id)
{
	if ( document.getElementById("tbody_" + id).style.display == 'none' )
	{
		document.getElementById("img_" + id).src = "style/images/collapse.gif";
		document.getElementById("tbody_" + id).style.display = "";
	}
	else
	{
		document.getElementById("img_" + id).src = "style/images/expand.gif";
		document.getElementById("tbody_" + id).style.display = "none";
	}
}

</script>
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li id="active"><a href="#" onclick="clip('legend')" id="right"><img src="style/images/expand.gif" id="img_legend" border="0" /> {L_LEGEND}</a></li></ul>

<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
<tbody id="tbody_legend" style="display:none">
<tr>
	<!-- BEGIN auth -->
	<td><img src="{_auth_list._auth.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._auth.TITLE}</td>
	<!-- END auth -->
</tr>
<tr>
	<!-- BEGIN title -->
	<td><img src="{_auth_list._title.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._title.TITLE}</td>
	<!-- END title -->
</tr>
<tr>
	<!-- BEGIN title2 -->
	<td><img src="{_auth_list._title2.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._title2.TITLE}</td>
	<!-- END title2 -->
</tr>
</tbody>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_FORUM_NAME}</td>
	<!-- BEGIN titles -->
	<td class="rowHead" align="center"><img src="{_auth_list._titles.IMAGE}" title="{_auth_list._titles.TITLE}" width="24" height="24" /></td>
	<!-- END titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{_auth_list.catrow.CAT_NAME}</td>
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td class="{_auth_list.catrow.forumrow.ROW_CLASS}">{_auth_list.catrow.forumrow.FORUM_NAME}</td>
	<!-- BEGIN forum_auth_data -->
	<td class="{_auth_list.catrow.forumrow.ROW_CLASS}" align="center"><img src="{_auth_list.catrow.forumrow.forum_auth_data.CELL_VALUE}" title="{_auth_list.catrow.forumrow.forum_auth_data.AUTH_EXPLAIN}" /></td>
	<!-- END forum_auth_data -->
</tr>
<!-- BEGIN sub_row -->
<tr>
	<td class="{_auth_list.catrow.forumrow._subrow.ROW}">{_auth_list.catrow.forumrow._subrow.NAME}</td>
	<!-- BEGIN auth_sub -->
	<td class="{_auth_list.catrow.forumrow._subrow.ROW}" align="center"><img src="{_auth_list.catrow.forumrow._subrow._authsub.CELL_VALUE}" title="{_auth_list.catrow.forumrow._subrow._authsub.AUTH_EXPLAIN}" /></td>
	<!-- END auth_sub -->
</tr>
<!-- END sub_row -->
<!-- END forum_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="empty" colspan="{S_COLUMN_SPAN}">{L_EMPTY}</td>
</tr>
<!-- END no_entry -->
<!-- END cat_row -->
</table>
{S_FIELDS}
</form>
<!-- END auth_list -->

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1r"><label for="game_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="game_name" id="game_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="game_tag">{L_TAG}:</label></td>
	<td class="row2"><input type="text" name="game_tag" id="game_tag" value="{TAG}"></td>
</tr>
<tr>
	<td>{L_IMAGE}:</td>
	<td>{S_IMAGE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="game_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
</tbody>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->