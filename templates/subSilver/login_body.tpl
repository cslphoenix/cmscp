<form action="{S_LOGIN_ACTION}" method="post" target="_top">

<table class="out" width="100%" cellspacing="0">
<tr>
	<th>{L_ENTER_PASSWORD}</th>
</tr>
<tr>
	<td align="center">
		<table class="out" width="100%" cellspacing="1">
		<tr>
			<td colspan="2" align="center">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" align="right">{L_USERNAME}:</td>
			<td><input type="text" class="post" name="user_name" size="25" maxlength="40" value="{USERNAME}"></td>
		</tr>
		<tr>
			<td align="right">{L_PASSWORD}:</td>
			<td><input type="password" class="post" name="password" size="25" maxlength="32"></td>
		</tr>
		<!-- BEGIN switch_allow_autologin -->
		<tr align="center">
			<td colspan="2"><span class="small">{L_AUTO_LOGIN}: <input type="checkbox" name="autologin" /></span></td>
		</tr>
		<!-- END switch_allow_autologin -->
		<tr>
			<td colspan="2" align="center">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="login" class="button2" value="{L_LOGIN}" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="2"><span class="gensmall"><a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a></span></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>