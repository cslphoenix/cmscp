<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head">{L_CONTACT}</td>
</tr>
<tr>
	<td class="row4"><span class="small">{L_CONTACT_INFO}<br />{L_REQUIRED}</span></td>
</tr>
</table>

{ERROR_BOX}<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="out" width="100%" cellspacing="0">
<tr>
	<td align="center">
		<form action="{S_CONTACT_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
		<table class="info" cellspacing="0">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">Informationen eintragen</td>
		</tr>
		<tr>
			<td class="row1_form" width="25%">Nickname: *</td>
			<td class="row2" width="75%"><input id="contact_nick" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{CONTACT_NICK}" name="contact_nick"></td>
		</tr>
		<tr>
			<td class="row1_form">Mail: *</td>
			<td class="row2"><input id="contact_mail" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{CONTACT_MAIL}" name="contact_mail"></td>
		</tr>
		<!-- BEGIN joinus -->
		<tr>
			<td class="row1_form">Alter:</td>
			<td class="row2"><input class="post" type="text" value="{CONTACT_AGE}" name="contact_age"></td>
		</tr>
		<tr>
			<td class="row1_form">für welches Team?: *</td>
			<td class="row2">{S_TEAM}</td>
		</tr>
		<!-- END joinus -->
		<!-- BEGIN contact -->
		<tr>
			<td class="row1_form">Homepage:</td>
			<td class="row2"><input class="post" type="text" value="{CONTACT_HP}" name="contact_hp"></td>
		</tr>
		<!-- END contact -->
		<!-- BEGIN fightus -->
		<tr>
			<td class="row1_form">Clanname: *</td>
			<td class="row2"><input id="contact_rival_name" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{CONTACT_RIVAL_NAME}" name="contact_rival_name"></td>
		</tr>
		<tr>
			<td class="row1_form">Clantag: *</td>
			<td class="row2"><input id="contact_rival_tag" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{CONTACT_RIVAL_TAG}" name="contact_rival_tag"></td>
		</tr>
		<tr>
			<td class="row1_form">Map:</td>
			<td class="row2"><input id="contact_map" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{CONTACT_MAP}" name="contact_map"></td>
		</tr>
		<tr>
			<td class="row1_form">Datum: *</td>
			<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
		</tr>
		<tr>
			<td class="row1_form">Gegen?: *</td>
			<td class="row2">{S_TEAM}</td>
		</tr>
		<tr>
			<td class="row1_form">XonX?: *</td>
			<td class="row2">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1_form">Warart?: *</td>
			<td class="row2">{S_CATEGORIE}</td>
		</tr>
		<tr>
			<td class="row1_form">Homepage:</td>
			<td class="row2"><input class="post" type="text" value="{CONTACT_HP}" name="contact_hp"></td>
		</tr>
		<!-- END fightus -->
		<tr>
			<td class="row1_form" valign="top">Cpatcha:<br><span class="small">Reload des Captchas per Klick aufs Bild</span></td>
			<td class="row2"><img style="background:#FFF; padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden." /></td>
		</tr>
		<tr>
			<td class="row1_form">Captcha-Code: *</td>
			<td class="row2"><input id="captcha" onBlur="javascript:checkEntry(this)" class="post" type="text" name="captcha" /></td>
		</tr>
		
		<tr>
			<td class="row1_form" valign="top">Nachricht: *</td>
			<td class="row2"><textarea id="contact_message" onBlur="javascript:checkEntry(this)" class="textarea" name="contact_message" cols="40" rows="5">{CONTACT_MESSAGE}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input class="button2" name="submit" type="submit" value="Absenden"> <input class="button" type="reset" value="Zurücksetzen"></td>
		</tr>
		</table>
		{S_HIDDEN_FIELDB}
		</form>
	</td>
</tr>
</table>