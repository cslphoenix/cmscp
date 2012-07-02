<!-- BEGIN forum -->
<form action="{S_AUTH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_AUTH_TITLE}</a></li></ul>
		</div>
	</td>
</tr>
<tr>
	<td>{L_AUTH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<th>{L_AUTH_SELECT}</td>
</tr>
<tr>
	<td>{S_AUTH_SELECT}&nbsp;&nbsp;<input type="submit" value="{L_LOOK_UP}" class="button2"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END forum -->

<!-- BEGIN group -->
<form action="{S_GROUP_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_GROUP_TITLE}</a></li></ul>
		</div>
	</td>
</tr>
<tr>
	<td>{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<th>{L_GROUP_SELECT}</td>
</tr>
<tr>
	<td>{S_GROUPS_SELECT}&nbsp;&nbsp;<input type="submit" value="{L_LOOK_UP}" class="button2"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END group -->

<!-- BEGIN user -->
<form action="{S_USER_ACTION}" method="post" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_USER_TITLE}</a></li></ul>
		</div>
	</td>
</tr>
<tr>
	<td>{L_USER_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<th>{L_USER_SELECT}</td>
</tr>
<tr>
	<td>
		{S_USERS_SELECT}
		<input type="text" name="user_name" maxlength="50" size="20" />
		<input type="hidden" name="mode" value="edit" />{S_FIELDS}
		<input type="submit" name="submituser" value="{L_LOOK_UP}" class="button2"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END user -->

<!-- BEGIN other -->

<!-- END other -->