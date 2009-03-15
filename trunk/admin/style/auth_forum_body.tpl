
<h1>{L_AUTH_TITLE}</h1>

<p>{L_AUTH_EXPLAIN}</p>

<h2>{L_FORUM}: {FORUM_NAME}</h2>

<form method="post" action="{S_FORUMAUTH_ACTION}">
<table class="out" cellspacing="0">
<tr>
	<!-- BEGIN forum_auth_titles -->
	<th>{forum_auth_titles.CELL_TITLE}</th>
	<!-- END forum_auth_titles -->
</tr>
<tr>
	<!-- BEGIN forum_auth_data -->
	<td>{forum_auth_data.S_AUTH_LEVELS_SELECT}</td>
	<!-- END forum_auth_data -->
</tr>
<tr>
	<td colspan="{S_COLUMN_SPAN}" align="center" class="row3"><span class="gensmall">{U_SWITCH_MODE}</span></td>
</tr>
<tr>
	<td colspan="{S_COLUMN_SPAN}" align="center">{S_HIDDEN_FIELDS} <input type="submit" name="submit" value="{L_SUBMIT}" class="button2" /> <input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
</form>