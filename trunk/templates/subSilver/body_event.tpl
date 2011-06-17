<!-- BEGIN _list -->
<table class="event" width="100%" cellspacing="0">
<tr>
	<td class="header" colspan="3">{L_UPCOMING}</td>
</tr>
<!-- BEGIN _new_row -->
<tr>
	<td class="{_list._new_row.CLASS}">{_list._new_row.TITLE}</td>
	<td class="{_list._new_row.CLASS}">{_list._new_row.DATE}</td>
	<td class="{_list._new_row.CLASS}"><span class="{_list._new_row.CSS}">{_list._new_row.STATUS}</span></td>
</tr>
<!-- END _new_row -->
<!-- BEGIN _entry_empty_new -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_new -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_EXPIRED}</td>
</tr>
<!-- BEGIN _old_row -->
<tr>
	<td class="{_list._old_row.CLASS}">{_list._old_row.TITLE}</td>
	<td class="{_list._old_row.CLASS}">{_list._old_row.DATE}</td>
	<td class="{_list._old_row.CLASS}"><span class="{_list._old_row.CSS}">{_list._old_row.STATUS}</span></td>
</tr>
<!-- END _old_row -->
<!-- BEGIN _entry_empty_old -->
<tr>
	<td class="entry_empty" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_old -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END _list -->

<!-- BEGIN _detail -->
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">
		<!-- BEGIN _update -->
		<span class="small" style="float:right;">&nbsp;&bull;&nbsp;{_detail._update.UPDATE}&nbsp;&bull;&nbsp;{_detail._update.UPDATE_DETAIL}</span>
		<!-- END _update -->
		<span class="small" style="float:right;">{EVENT_MAIN}</span>
		{L_EVENT_INFO}
	</td>
</tr>
<!-- BEGIN _comment -->
<tr>
	<td colspan="2" align="center">
		<form action="{S_ACTION}" method="post" name="post">
		<table class="info" width="55%" cellspacing="0">
		<tr>
			<td colspan="2"><br />{ERROR_BOX}</td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">Kommentar einfügen</td>
		</tr>
		<!-- BEGIN _guest -->
		<tr>
			<td class="row1" width="30%">{L_USERNAME} *</td>
			<td class="row2" width="70%"><input type="text" class="post" name="poster_nick" id="poster_nick" value="{POSTER_NICK}" /></td>
		</tr>
		<tr>
			<td class="row1">{L_MAIL}: *</td>
			<td class="row2"><input type="text"class="post" name="poster_mail" id="poster_mail" value="{POSTER_MAIL}" /></td>
		</tr>
		<tr>
			<td class="row1">{L_HOMEPAGE}:</td>
			<td class="row2"><input type="text"class="post" name="poster_hp" id="poster_hp" value="{POSTER_HP}" /></td>
		</tr>
		<tr>
			<td class="row1" valign="top">Cpatcha:<br /><span class="small">Reload des Captchas per Klick aufs Bild</span></td>
			<td class="row2"><img style="background:#FFF; padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden." /></td>
		</tr>
		<tr>
			<td class="row1">Captcha-Code: *</td>
			<td class="row2"><input type="text"class="post" name="captcha" id="captcha" value="" /></td>
		</tr>
		<!-- END _guest -->
		<tr>
			<td class="row1" width="30%" valign="top">Kommentar:</td>
			<td class="row2" width="70%"><textarea class="textarea" name="poster_msg" cols="30" rows="5">{POSTER_MSG}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center"><input type="submit" class="button2" name="submit" value="Absenden" /><input type="reset" class="button" value="Zurücksetzen" /></td>
		</tr>
		
		</table>
		{S_FIELDS}
		</form>
	</td>
</tr>
<tr>
	<td colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="comments">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="info_head">Kommentare</td>
		</tr>
		<!-- BEGIN _comment_row -->
		<tr>
			<td class="{_detail._comment._comment_row.CSS}">
				<span class="right">{_detail._comment._comment_row.OPTIONS}</span><img src="{_detail._comment._comment_row.ICON}" alt="" />
				{_detail._comment._comment_row.DATE}<br />
				{_detail._comment._comment_row.MESSAGE}
			</td>
			<td class="{_detail._comment._comment_row.CSS}">{_detail._comment._comment_row.POSTER}</td>
		</tr>
		<!-- END _comment_row -->
		<!-- BEGIN _entry_empty -->
		<tr>
			<td colspan="2" align="center">{L_ENTRY_NO}</td>
		</tr>
		<!-- END _entry_empty -->
		<tr>
			<td colspan="2"><span class="right">{PAGE_NUMBER}</span>{PAGINATION}</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END _comment -->
</table>
<!-- END _detail -->