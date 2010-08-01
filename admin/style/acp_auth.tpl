<!-- BEGIN _display -->
<form action="{S_AUTH_ACTION}" method="get">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_AUTH_TITLE}</a></li>
	</ul>
</div>
<table class="head" cellspacing="0">
<tr>
	<td class="row2">{L_AUTH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_AUTH_SELECT}</td>
</tr>
<tr>
	<td class="row2">{S_FIELDS}{S_AUTH_SELECT}&nbsp;&nbsp;<input type="submit" value="{L_LOOK_UP}" class="button2"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN auth_forum -->
<form action="{S_FORUMAUTH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_ACTION}">{L_AUTH_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_FORUM}: {FORUM_NAME}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_AUTH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="out" cellspacing="0">
<!-- BEGIN forum_auth_data -->
<tr>
	<th>{auth_forum.forum_auth_data.CELL_TITLE}</th>
	<td>{auth_forum.forum_auth_data.S_AUTH_LEVELS_SELECT}</td>
</tr>
<!-- END forum_auth_data -->
<tr>
	<td colspan="2" align="center" class="row3"><span class="gensmall">{U_SWITCH_MODE}</span></td>
</tr>
<tr>
	<td colspan="2" align="center">{S_FIELDS} <input type="submit" name="submit" value="{L_SUBMIT}" class="button2" /> <input type="reset" value="{L_RESET}" name="reset" class="button"></td>
</tr>
</table>
</form>
<!-- END auth_forum -->