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

function set_right(id)
{
	var obj = document.getElementById(id).checked = true;
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
	<td><img src="{_auth.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_auth.TITLE}</td>
	<!-- END _auth -->
</tr>
<tr>
	<!-- BEGIN _title -->
	<td><img src="{_title.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_title.TITLE}</td>
	<!-- END _title -->
</tr>
<tr>
	<!-- BEGIN _title2 -->
	<td><img src="{_title2.IMAGE}" width="20" height="20" alt="" /></td>
	<td>{_title2.TITLE}</td>
	<!-- END _title2 -->
</tr>
</tbody>
</table>

<br />

<table class="rows">
<!-- BEGIN cat_row -->
<tr>
	<td class="rowHead"><a href="{cat_row.CAT_URL}">{cat_row.CAT_NAME}</a></td>
	<!-- BEGIN forum_auth_titles -->
	<td class="rowHead" align="center"><img src="{cat_row.forum_auth_titles.CELL_IMAGE}" title="{cat_row.forum_auth_titles.CELL_TITLE}" width="24" height="24" alt="" /></td>
	<!-- END forum_auth_titles -->
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td class="{cat_row.forum_row.ROW_CLASS}">{cat_row.forum_row.FORUM_NAME}</td>
	<!-- BEGIN forum_auth_data -->
	<td class="{cat_row.forum_row.ROW_CLASS}" align="center"><img src="{cat_row.forum_row.forum_auth_data.CELL_VALUE}" title="{cat_row.forum_row.forum_auth_data.AUTH_EXPLAIN}" /></td>
	<!-- END forum_auth_data -->
</tr>
<!-- END forum_row -->
<!-- END cat_row -->
</table>


<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_OPTION}</a></li>
			</ul>
		</div>
	</td>
	<!-- BEGIN forum_auth_titles -->
	<td>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current"><img src="{forum_auth_titles.CELL_IMAGE}" title="{forum_auth_titles.CELL_TITLE}" width="12" height="12" alt="" /></a></li>
			</ul>
		</div>
	</td>
	<!-- END forum_auth_titles -->
</tr>
<!-- BEGIN _set -->
<tr class="hover">
	<td align="right">{_set.NAME}:&nbsp;</td>
	<!-- BEGIN _auth -->
	<td align="center">{_set._auth.SELECT}</td>
	<!-- END _auth -->
</tr>
<!-- END _set -->
<tr>
	<td colspan="12">&nbsp;</td>
</tr>
<tr>
	<th colspan="12">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_STANDARD}</a></li>
			</ul>
		</div>
	</td>
</tr>

<tr>
	<td colspan="12"><span class="tiny">{STANDARDS}</span></td>
</tr>
<tr>
	<td colspan="12">&nbsp;</td>
</tr>
<tr>
	<td colspan="12" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
