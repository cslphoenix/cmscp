<div align="center">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">
		<!-- BEGIN match_edit -->
		<span class="small" style="float:right;">{match_edit.EDIT_MATCH} {match_edit.EDIT_MATCH_DETAILS}</span>
		<!-- END match_edit -->
		<span class="small" style="float:right;">{MATCH_MAIN}</span>
		{L_MATCH_INFO}Matchinfos
	</td>
</tr>
<tr>
	<td class="row4r" align="center">
		<table class="info" width="55%" cellspacing="2">
		<tr>
			<td width="35%">{L_RIVAL}Gegner:</td>
			<td width="65%">{MATCH_RIVAL} <a href="{U_MATCH_RIVAL_URL}">{MATCH_RIVAL_URL}</a></td>
		</tr>
		<tr>
			<td>{L_RIVAL_TAG}Clantag Gegner:</td>
			<td>{MATCH_RIVAL_TAG}</td>
		</tr>
		<tr>
			<td>{L_MATCH_DETAILS}Matchdetails:</td>
			<td>{MATCH_CATEGORIE}: {MATCH_TYPE} - Liga: {MATCH_LEAGUE_INFO}</td>
		</tr>
		<tr>
			<td>{L_SERVER}Server</td>
			<td>{SERVER} {SERVER_PW}</td>
		</tr>
		<!-- BEGIN hltv -->
		<tr>
			<td>{L_HLTV}HLTV-Server</td>
			<td>{HLTV} {HLTV_PW}</td>
		</tr>
		<!-- END hltv -->
		<!-- BEGIN clan -->
		<tr>
			<td>{L_HLTV}Clanlineup</td>
			<td>
				<!-- BEGIN clan_lineup -->
					{clan.clan_lineup.PLAYERS}
				<!-- END clan_lineup -->
			</td>
		</tr>
		<!-- END clan -->
		<!-- BEGIN rival -->
		<tr>
			<td>{L_HLTV}Gegnerlineup</td>
			<td>
				<!-- BEGIN rival_lineup -->
					{rival.rival_lineup.PLAYERS}
				<!-- END rival_lineup -->
			</td>
		</tr>
		<!-- END rival -->
		</table>
	</td>
</tr>
<tr>
	<td class="row4r" align="center">
		<table class="info" width="55%" cellspacing="2">
		<!-- BEGIN map_details_a -->
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td width="35%">{DETAILS_MAPA}</td>
			<td width="1%" align="right"><span class="{CLASSA}">{DETAILS_SOCRE_A}</span></td>
			<td width="1%" align="center">:</td>
			<td width="1%"><span class="{CLASSA}">{DETAILS_SOCRE_B}</span></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_A}</td>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_B}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END map_details_a -->
		<!-- BEGIN map_details_b -->
		<tr>
			<td>{DETAILS_MAPB}</td>
			<td align="right"><span class="{CLASSB}">{DETAILS_SOCRE_C}</span></td>
			<td align="center">:</td>
			<td><span class="{CLASSB}">{DETAILS_SOCRE_D}</span></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_C}</td>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_D}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END map_details_b -->
		<!-- BEGIN map_details_c -->
		<tr>
			<td>{DETAILS_MAPC}</td>
			<td align="right"><span class="{CLASSC}">{DETAILS_SOCRE_E}</span></td>
			<td align="center">:</td>
			<td><span class="{CLASSC}">{DETAILS_SOCRE_F}</span></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_E}</td>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_F}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END map_details_c -->
		<!-- BEGIN map_details_d -->
		<tr>
			<td>{DETAILS_MAPD}</td>
			<td align="right"><span class="{CLASSD}">{DETAILS_SOCRE_G}</span></td>
			<td align="center">:</td>
			<td><span class="{CLASSD}">{DETAILS_SOCRE_H}</span></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_G}</td>
			<td>&nbsp;</td>
			<td>{DETAILS_PIC_H}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END map_details_d -->
		</table>
	
	</td>
</tr>
<!-- BEGIN match_users -->
<tr>
	<td colspan="2" align="center">
		<table class="info" width="55%" cellspacing="2">
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">{L_MATCH_INFO}Teilnahmeinfos</td>
		</tr>
		<tr>
			<td valign="top">
				<form action="{S_ACTION}" method="post" id="list" name="post">
				<table class="out" width="100%" cellspacing="0">
				<tr>
					<td class="rowHead" width="30%">{L_USERNAME}</td>
					<td class="rowHead" width="70%" colspan="2">{L_STATUS}</td>
				</tr>
				<!-- BEGIN match_users_status -->
				<tr>
					<td width="30%" align="left" nowrap="nowrap">{match_users.match_users_status.USERNAME}</td>
					<td width="20%" align="left" nowrap="nowrap"><span class="{match_users.match_users_status.CLASS}">{match_users.match_users_status.STATUS}</span></td>
					<td width="50%" align="left" nowrap="nowrap">{match_users.match_users_status.DATE}</td>
				</tr>
				<!-- END match_users_status -->
				<!-- BEGIN no_entry_status -->
				<tr>
 					<td align="center" colspan="3">{L_NONE}</td>
				</tr>
				<!-- END no_entry -->
				</table>
				</form>
			</td>
		</tr>
		<!-- BEGIN users_status -->
		<tr>
			<td valign="top" align="center">
				<form action="{S_MATCH_ACTION}" method="post" name="post">
				<table class="out" cellspacing="0">
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr align="center">
					<td width="33%" nowrap="nowrap"><input type="radio" value="1" name="match_users_status" {S_CHECKED_1}> {L_STATUS_YES}</td>
					<td width="34%" nowrap="nowrap"><input type="radio" value="2" name="match_users_status" {S_CHECKED_2}> {L_STATUS_NO}</td>
					<td width="33%" nowrap="nowrap"><input type="radio" value="3" name="match_users_status" {S_CHECKED_3}> {L_STATUS_REPLACE}</td>
				</tr>
				<tr>
					<td class="row4" colspan="3" align="center"><input class="button" type="submit" value="{L_SET_STATUS}"></td>
				</tr>
				</table>
				{S_HIDDEN_FIELDA}
				</form>
			</td>
		</tr>
		<!-- END users_status -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row1" align="center" colspan="4">{L_NONE}</td>
		</tr>
		<!-- END no_entry -->
		</table>
	</td>
</tr>
<!-- END match_users -->
<!-- BEGIN match_comments -->
<tr>
	<td colspan="2" align="center">
		<form action="{S_MATCH_ACTION}" method="post" name="form">
		<table class="info" width="55%" cellspacing="0">
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">Kommentar einfügen</td>
		</tr>
		</table>

		{ERROR_BOX}<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

		<table class="info" width="55%" cellspacing="0">
		<!-- BEGIN match_comments_guest -->
		<tr>
			<td class="row1_form" width="30%">Nickname: *</td>
			<td class="row2" width="70%"><input id="poster_nick" class="post" type="text" value="{POSTER_NICK}" name="poster_nick"></td>
		</tr>
		<tr>
			<td class="row1_form">Mail: *</td>
			<td class="row2"><input id="poster_mail" class="post" type="text" value="{POSTER_MAIL}" name="poster_mail"></td>
		</tr>
		<tr>
			<td class="row1_form">Homepage:</td>
			<td class="row2"><input class="post" type="text" value="{POSTER_HP}" name="poster_hp"></td>
		</tr>
		<tr>
			<td class="row1_form" valign="top">Cpatcha:<br><span class="small">Reload des Captchas per Klick aufs Bild</span></td>
			<td class="row2"><img style="background:#FFF; padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden."></td>
		</tr>
		<tr>
			<td class="row1_form">Captcha-Code: *</td>
			<td class="row2"><input id="captcha" class="post" type="text" name="captcha"></td>
		</tr>
		<tr>
			<td class="row1_form" width="25%" valign="top">Kommentar:</td>
			<td class="row2" width="75%"><textarea class="textarea" name="comment" cols="30" rows="5">{COMMENT}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input class="button2" name="submit" type="submit" value="Absenden"> <input class="button" type="reset" value="Zurücksetzen"></td>
		</tr>
		<!-- END match_comments_guest -->
		<!-- BEGIN match_comments_member -->
		<tr>
			<td class="row1_form" width="30%" valign="top">Kommentar:</td>
			<td class="row2" width="70%"><textarea class="textarea" name="comment" cols="30" rows="5">{COMMENT}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input class="button2" name="submit" type="submit" value="Absenden"> <input class="button" type="reset" value="Zurücksetzen"></td>
		</tr>
		<!-- END match_comments_member -->
		</table>
		{S_HIDDEN_FIELDB}
		</form>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<form action="{S_MATCH_ACTION}" method="post" name="post">
		<table class="info" width="55%" cellspacing="0">
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="info_head">Kommentare</td>
		</tr>
		<!-- BEGIN comments -->
		<tr>
			<td class="{match_comments.comments.CLASS}">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				
				<tr>
					<td>{match_comments.comments.DATE}</td>
					<td align="right">{match_comments.comments.IP}{match_comments.comments.EDIT}{match_comments.comments.DELETE}{match_comments.comments.ID} <img src="{match_comments.comments.ICON}" alt=""></td>
				</tr>
				<tr>
					<td valign="top" width="25%" nowrap="nowrap"><a href="{match_comments.comments.U_USERNAME}">{match_comments.comments.L_USERNAME}</a></td>
					<td width="75%">{match_comments.comments.MESSAGE}</td>
				</tr>
				
				</table>
			</td>
		</tr>
		<!-- END comments -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row1" align="center" colspan="4">{L_NONE}</td>
		</tr>
		<!-- END no_entry -->
		</table>
		
		<table class="info" width="55%" cellspacing="4">
		<tr>
			<td width="100%" align="left"><span style="float:right;">{PAGE_NUMBER}</span>{PAGINATION}</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<!-- END match_comments -->
</table>
</div>