
<h1>{L_AUTH_TITLE}</h1>

<p>{L_AUTH_EXPLAIN}</p>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<td>{L_FORUM_NAME}</td>
		<!-- BEGIN forum_auth_titles -->
		<td align="center"><img src="{forum_auth_titles.IMAGE}" title="{forum_auth_titles.TITLE}" width="24" height="24" /></td>
		<!-- END forum_auth_titles -->
	</tr>
	<!-- BEGIN cat_row -->
	<tr>
		<td colspan="{S_COLUMN_SPAN}"><a href="{cat_row.CAT_URL}">{cat_row.CAT_NAME}</a></td>
	</tr>
	<!-- BEGIN forum_row -->
	<tr>
		<td class="{cat_row.forum_row.ROW_CLASS}" align="center">{cat_row.forum_row.FORUM_NAME}</td>
		<!-- BEGIN forum_auth_data -->
		<td class="{cat_row.forum_row.ROW_CLASS}" align="center"><img src="{cat_row.forum_row.forum_auth_data.CELL_VALUE}" title="{cat_row.forum_row.forum_auth_data.AUTH_EXPLAIN}" /></td>
		<!-- END forum_auth_data -->
	</tr>
	<!-- END forum_row -->
	<!-- END cat_row -->
</table>
<br />