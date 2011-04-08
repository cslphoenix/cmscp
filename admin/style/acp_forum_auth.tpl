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
	<li id="active"><a href="#" onclick="clip('legend')" id="right"><img src="style/images/expand.gif" id="img_legend" width="9" height="9" border="0" /> {L_LEGEND}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
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

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_FORUM_NAME}</td>
	<!-- BEGIN _titles -->
	<td class="rowHead" align="center"><img src="{_auth_list._titles.IMAGE}" title="{_auth_list._titles.TITLE}" width="24" height="24" /></td>
	<!-- END _titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<td class="rowHead" colspan="{S_COLUMN_SPAN}"><a href="{_auth_list.cat_row.CAT_URL}">{_auth_list.cat_row.CAT_NAME}</a></td>
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
<!-- END cat_row -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="game_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _auth_list -->

<!-- BEGIN _input -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{PATH}" + encodeURI(newimage) : "./images/spacer.gif";
	}
// ]]>
</script>

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
<tr>
	<td class="row1"><label for="game_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_name" id="game_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="game_tag">{L_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_tag" id="game_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1 top">{L_IMAGE}:</td>
	<td class="row2 top">{S_IMAGE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td class="row1 top"><label for="game_order">{L_ORDER}:</label></td>
	<td class="row2 top">{S_ORDER}</td>
</tr>
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