<!-- BEGIN display -->
<form action="{S_AUTHLIST_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_AUTHLIST_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_AUTHLIST_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_AUTHLIST_NAME}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN auth_row -->
<tr>
	<td class="{display.auth_row.CLASS}" align="left" width="100%">{display.auth_row.AUTHNAME}</td>
	<td class="{display.auth_row.CLASS}" align="center" width="1%"><a href="{display.auth_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.auth_row.CLASS}" align="center" width="1%"><a href="{display.auth_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END auth_row -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="auth_name" type="text" value=""></td>
	<td><input class="button" type="submit" value="{L_AUTHLIST_ADD}" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<!-- END display -->

<!-- BEGIN authlist_edit -->
<form action="{S_AUTHLIST_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_AUTHLIST_ACTION}">{L_AUTHLIST_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_AUTHLIST_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_AUTHLIST_NAME}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="auth_name" value="{AUTH_NAME}" ></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END authlist_edit -->