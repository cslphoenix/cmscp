<script type="text/javascript">  
// <![CDATA[
	function set_right(id)
	{
		var obj = document.getElementById(id).checked = true;
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<!-- BEGIN _display -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _cats_row -->
<table class="rows">
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{_display._cats_row.NAME}</td>
	<!-- BEGIN _image -->
	<td class="rowHead" align="center"><img src="{_display._cats_row._image.IMAGE}" title="{_display._cats_row._image.TITLE}" width="24" height="24" /></td>
	<!-- END _image -->
</tr>
<!-- BEGIN _forms_row -->
<tr> 
	<td class="{_display._cats_row._forms_row.CLASS}"><span style="float:right">{_display._cats_row._forms_row.SUBS}</span>{_display._cats_row._forms_row.NAME}</td>
	<!-- BEGIN _forms_auth -->
	<td class="{_display._cats_row._forms_row.CLASS}" align="center"><img src="{_display._cats_row._forms_row._forms_auth.IMAGE}" title="{_display._cats_row._forms_row._forms_auth.EXPLAIN}" /></td>
	<!-- END _forms_auth -->
</tr>
<!-- BEGIN _subs_row -->
<tr> 
	<td class="{_display._cats_row._forms_row._subs_row.CLASS}">{_display._cats_row._forms_row._subs_row.NAME}</td>
	<!-- BEGIN _subs_auth -->
	<td class="{_display._cats_row._forms_row._subs_row.CLASS}" align="center"><img src="{_display._cats_row._forms_row._subs_row._subs_auth.IMAGE}" title="{_display._cats_row._forms_row._subs_row._subs_auth.EXPLAIN}" /></td>
	<!-- END _subs_auth -->
</tr>
<!-- END _subs_row -->
<!-- END _forms_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="entry_empty" align="center" colspan="12">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry -->
</table>

<br />
<!-- END _cats_row -->
<!-- END _display -->

<!-- BEGIN _cats -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<!-- BEGIN _cats_row -->
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{_cats._cats_row.NAME}</td>
	<!-- BEGIN _image -->
	<td class="rowHead" align="center"><img src="{_cats._cats_row._image.IMAGE}" title="{_cats._cats_row._image.TITLE}" width="24" height="24" /></td>
	<!-- END _image -->
</tr>
<!-- BEGIN _forms_row -->
<tr> 
	<td class="{_cats._cats_row._forms_row.CLASS}"><span style="float:right">{_cats._cats_row._forms_row.SUBS}</span>{_cats._cats_row._forms_row.NAME}</td>
	<!-- BEGIN _forms_auth -->
	<td class="{_cats._cats_row._forms_row.CLASS}" align="center"><img src="{_cats._cats_row._forms_row._forms_auth.IMAGE}" title="{_cats._cats_row._forms_row._forms_auth.EXPLAIN}" /></td>
	<!-- END _forms_auth -->
</tr>
<!-- END _forms_row -->
</table>
<!-- END _cats_row -->
<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="12">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current" onclick="clip('settings')"><img src="style/images/expand.gif" id="img_settings" border="0" /> {L_INPUT_STANDARD}</a></li>
			</ul>
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
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_OPTION}</a></li>
			</ul>
		</div>
	</td>
	<!-- BEGIN _image -->
	<td>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current"><img src="{_cats._image.IMAGE}" title="{_cats._image.TITLE}" width="12" height="12" alt="" /></a></li>
			</ul>
		</div>
	</td>
	<!-- END _image -->
</tr>
<!-- BEGIN _set -->
<tr class="hover">
	<td class="row4">{_cats._set.NAME}:&nbsp;</td>
	<!-- BEGIN _auth -->
	<td align="center">{_cats._set._auth.SELECT}</td>
	<!-- END _auth -->
</tr>
<!-- END _set -->
<tr>
	<td colspan="12">&nbsp;</td>
</tr>

<tr>
	<td colspan="12" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
<!-- END _cats -->
</form>

<!-- BEGIN _auth_list -->
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
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li id="active"><a href="#" onclick="clip('legend')" id="right"><img src="style/images/expand.gif" id="img_legend" border="0" /> {L_LEGEND}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
<tbody id="tbody_legend" style="display:none">
<tr>
	<!-- BEGIN _auth -->
	<td><img src="{_auth_list._auth.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._auth.TITLE}</td>
	<!-- END _auth -->
</tr>
<tr>
	<!-- BEGIN _title -->
	<td><img src="{_auth_list._title.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._title.TITLE}</td>
	<!-- END _title -->
</tr>
<tr>
	<!-- BEGIN _title2 -->
	<td><img src="{_auth_list._title2.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth_list._title2.TITLE}</td>
	<!-- END _title2 -->
</tr>
</tbody>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_FORUM_NAME}</td>
	<!-- BEGIN _titles -->
	<td class="rowHead" align="center"><img src="{_auth_list._titles.IMAGE}" title="{_auth_list._titles.TITLE}" width="24" height="24" /></td>
	<!-- END _titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}">{_auth_list.cat_row.CAT_NAME}</td>
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td class="{_auth_list.cat_row.forum_row.ROW_CLASS}">{_auth_list.cat_row.forum_row.FORUM_NAME}</td>
	<!-- BEGIN forum_auth_data -->
	<td class="{_auth_list.cat_row.forum_row.ROW_CLASS}" align="center"><img src="{_auth_list.cat_row.forum_row.forum_auth_data.CELL_VALUE}" title="{_auth_list.cat_row.forum_row.forum_auth_data.AUTH_EXPLAIN}" /></td>
	<!-- END forum_auth_data -->
</tr>
<!-- BEGIN _sub_row -->
<tr>
	<td class="{_auth_list.cat_row.forum_row._sub_row.ROW}">{_auth_list.cat_row.forum_row._sub_row.NAME}</td>
	<!-- BEGIN _auth_sub -->
	<td class="{_auth_list.cat_row.forum_row._sub_row.ROW}" align="center"><img src="{_auth_list.cat_row.forum_row._sub_row._auth_sub.CELL_VALUE}" title="{_auth_list.cat_row.forum_row._sub_row._auth_sub.AUTH_EXPLAIN}" /></td>
	<!-- END _auth_sub -->
</tr>
<!-- END _sub_row -->
<!-- END forum_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="entry_empty" align="center" colspan="{S_COLUMN_SPAN}">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry -->
<!-- END cat_row -->
</table>
{S_FIELDS}
</form>
<!-- END _auth_list -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="game_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_name" id="game_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="game_tag">{L_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_tag" id="game_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1">{L_IMAGE}:</td>
	<td class="row2">{S_IMAGE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td class="row1"><label for="game_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
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