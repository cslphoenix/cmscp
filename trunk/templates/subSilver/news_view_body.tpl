<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="3">{NEWS_TITLE}</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="2%">&nbsp;</td>
	<td width="96%">{NEWS_TEXT}</td>
	<td width="2%">&nbsp;</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- BEGIN news_comments -->
<tr>
	<td colspan="3" align="center">
		<form action="{S_NEWS_ACTION}" method="post" name="form">
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
		<!-- BEGIN news_comments_guest -->
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
		<!-- END news_comments_guest -->
		<!-- BEGIN news_comments_member -->
		<tr>
			<td class="row1_form" width="30%" valign="top">Kommentar:</td>
			<td class="row2" width="70%"><textarea class="textarea" name="comment" cols="30" rows="5">{COMMENT}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input class="button2" name="submit" type="submit" value="Absenden"> <input class="button" type="reset" value="Zurücksetzen"></td>
		</tr>
		<!-- END news_comments_member -->
		</table>
		{S_HIDDEN_FIELDB}
		</form>
	</td>
</tr>
<tr>
	<td colspan="3" align="center">
		<form action="{S_MATCH_ACTION}" method="post" name="post">
		<table class="info" width="75%" cellspacing="1">
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="info_head">Kommentare</td>
		</tr>
		<!-- BEGIN comments -->
		<tr>
			<td class="{news_comments.comments.CLASS}">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				
				<tr>
					<td width="25%">{news_comments.comments.DATE}</td>
					<td width="75%" align="right">{news_comments.comments.IP}{news_comments.comments.EDIT}{news_comments.comments.DELETE}{news_comments.comments.ID} <img src="{news_comments.comments.ICON}" alt=""></td>
				</tr>
				<tr>
					<td valign="top"><a href="{news_comments.comments.U_USERNAME}">{news_comments.comments.L_USERNAME}</a></td>
					<td valign="top">{news_comments.comments.MESSAGE}</td>
				</tr>
				
				</table>
			</td>
		</tr>
		<!-- END comments -->
		<!-- BEGIN no_entry -->
		<tr>
			<td class="row1" align="center" colspan="4">{L_EMPTY}</td>
		</tr>
		<!-- END no_entry -->
		</table>
		
		<table class="info" width="75%" cellspacing="4">
		<tr>
			<td width="100%" align="left"><span style="float:right;">{PAGE_NUMBER}</span>{PAGINATION}</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<!-- END news_comments -->
</table>