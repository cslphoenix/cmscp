<div align="center">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">
		<!-- BEGIN match_edit -->
		<span style="float:right;">{match_edit.EDIT_MATCH} {match_edit.EDIT_MATCH_DETAILS}</span>
		<!-- END match_edit -->
		{L_MATCH_INFO}Matchinfos
	</td>
</tr>
<tr>
	<td class="row4r" align="center">
		<table class="info" cellspacing="2">
		<tr>
			<td width="30%">{L_RIVAL}Gegner:</td>
			<td width="70%">{MATCH_RIVAL} <a href="{U_MATCH_RIVAL_URL}">{MATCH_RIVAL_URL}</a></td>
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
		<table class="info" cellspacing="2">
		<!-- BEGIN map_details_a -->
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td width="30%">{DETAILS_MAPA}</td>
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
		<table class="info" cellspacing="2">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">{L_MATCH_INFO}Teilnahmeinfos</td>
		</tr>
		<tr>
			<td valign="top">
				<form action="{S_TEAM_ACTION}" method="post" id="list" name="post">
				<table class="out" cellspacing="0">
				<tr>
					<td class="rowHead" width="30%">{L_USERNAME}</td>
					<td class="rowHead" width="70%" colspan="2">{L_STATUS}</td>
				</tr>
				<!-- BEGIN match_users_status -->
				<tr>
					<td class="row" width="30%" align="left">{match_users.match_users_status.USERNAME}</td>
					<td class="row" width="69%" align="left" nowrap>{match_users.match_users_status.STATUS}</td>
					<td class="row" width="1%" align="left" nowrap>{match_users.match_users_status.DATE}</td>
				</tr>
				<!-- END match_users_status -->
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
					<td width="33%"><input type="radio" value="1" name="match_users_status" {S_CHECKED_1}> {L_STATUS_YES}</td>
					<td width="34%"><input type="radio" value="2" name="match_users_status" {S_CHECKED_2}> {L_STATUS_NO}</td>
					<td width="33%"><input type="radio" value="3" name="match_users_status" {S_CHECKED_3}> {L_STATUS_REPLACE}</td>
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
			<td class="row1" align="center" colspan="4">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry -->
		</table>
	</td>
</tr>
<!-- END match_users -->
<!-- BEGIN match_comments -->
<tr>
	<td colspan="2" align="center">
		<form action="{S_MATCH_ACTION}" method="post" name="post">
		<table class="info" cellspacing="0">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">Kommentar einfügen</td>
		</tr>
		<!-- BEGIN match_comments_guest -->
		<tr>
			<td class="row1_form" width="25%">Nickname: *</td>
			<td class="row2" width="75%"><input class="post" type="text" value="{POSTER_NICK}" name="poster_nick"></td>
		</tr>
		<tr>
			<td class="row1_form">Mail: *</td>
			<td class="row2"><input class="post" type="text" value="{POSTER_MAIL}" name="poster_mail"></td>
		</tr>
		<tr>
			<td class="row1_form">Homepage:</td>
			<td class="row2"><input class="post" type="text" value="{POSTER_HP}" name="poster_hp"></td>
		</tr>
		<tr>
			<td class="row1_form" valign="top">Cpatcha:<br><span class="small">Reload des Captchas per Klick aufs Bild</span></td>
			<td class="row2"><img style="padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden." /></td>
		</tr>
		<tr>
			<td class="row1_form">Captcha-Code: *</td>
			<td class="row2"><input class="post" type="text" name="captchaa" /></td>
		</tr>
		<!-- END match_comments_guest -->
		<tr>
			<td class="row1_form" width="25%" valign="top">Kommentar:</td>
			<td class="row2" width="75%"><textarea class="textarea" name="comment" cols="40" rows="5"></textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input class="button2" type="submit" value="Absenden"> <input class="button" type="reset" value="Zurücksetzen"></td>
		</tr>
		</table>
		{S_HIDDEN_FIELDB}
		</form>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<form action="{S_MATCH_ACTION}" method="post" name="post">
		<table class="info" cellspacing="0">
		<tr>
			<td colspan="2">&nbsp;</td>
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
					<td align="right">{match_comments.comments.IP}{match_comments.comments.EDIT}{match_comments.comments.DELETE}{match_comments.comments.ID}</td>
				</tr>
				<tr>
					<td valign="top" width="25%" nowrap><a href="{match_comments.comments.U_USERNAME}">{match_comments.comments.L_USERNAME}</a></td>
					<td width="75%">{match_comments.comments.MESSAGE}</td>
				</tr>
				
				</table>
			</td>
		</tr>
		<!-- END comments -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row1" align="center" colspan="4">{NO_ENTRY}</td>
		</tr>
		<!-- END no_entry -->
		</table>
		
		<table class="info" cellspacing="4">
		<tr>
			<td width="50%" align="left">{PAGINATION}</td>
			<td width="50%" align="right">{PAGE_NUMBER}</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<!-- END match_comments -->
</table>
</div>