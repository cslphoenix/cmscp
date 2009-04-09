<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_AUTH_TITLE}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_AUTH_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<th style="text-align:center;">{L_FORUM_NAME}</th>
	<!-- BEGIN forum_auth_titles -->
	<th style="text-align:center;">{forum_auth_titles.CELL_TITLE}</th>
	<!-- END forum_auth_titles -->
</tr>
<!-- BEGIN cat_row -->
<tr>
	<td colspan="{S_COLUMN_SPAN}"><a href="{cat_row.CAT_URL}">{cat_row.CAT_NAME}</a></td>
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td class="class_row2" align="center">{cat_row.forum_row.FORUM_NAME}</td>
	<!-- BEGIN forum_auth_data -->
	<td class="class_row2" align="center"><span title="{cat_row.forum_row.forum_auth_data.AUTH_EXPLAIN}">{cat_row.forum_row.forum_auth_data.CELL_VALUE}</span></td>
	<!-- END forum_auth_data -->
</tr>
<!-- END forum_row -->
<!-- END cat_row -->
</table>