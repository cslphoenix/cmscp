<!-- BEGIN _view -->
<table class="comment">
<!-- BEGIN _comment -->
<tr>
	<td class="error" colspan="2">{ERROR_BOX}</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table class="comment_create">
		<tr>
			<td class="header" colspan="2">{L_COMMENT_CREATE}</td>
		</tr>
		<!-- BEGIN _guest -->
		<tr>
			<td><label for="poster_nick">{L_USERNAME} *</label></td>
			<td><input type="text" class="post" name="poster_nick" id="poster_nick" value="{POSTER_NICK}" /></td>
		</tr>
		<tr>
			<td class="row1"><label for="poster_mail">{L_MAIL}: *</label></td>
			<td class="row2"><input type="text"class="post" name="poster_mail" id="poster_mail" value="{POSTER_MAIL}" /></td>
		</tr>
		<tr>
			<td class="row1"><label for="poster_hp">{L_HOMEPAGE}:</label></td>
			<td class="row2"><input type="text"class="post" name="poster_hp" id="poster_hp" value="{POSTER_HP}" /></td>
		</tr>
		<tr>
			<td class="row1" valign="top"><label title="{L_CAPTCHA_EXPLAIN}">{L_CAPTCHA}: *</label></td>
			<td class="row2"><img style="background:#FFF; padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden." /></td>
		</tr>
		<tr>
			<td class="row1"><label for="captcha">{L_CAPTCHA_CODE}: *</label></td>
			<td class="row2"><input type="text"class="post" name="captcha" id="captcha" value="" /></td>
		</tr>
		<!-- END _guest -->
		<tr>
			<td class="row1" valign="top"><label for="poster_msg">{L_COMMENT}:</label></td>
			<td class="row2"><textarea class="textarea" name="poster_msg" id="poster_msg" cols="30" rows="5">{POSTER_MSG}</textarea></td>
		</tr>
		<tr>
			<td class="footer" colspan="2"><input type="submit" class="button2" name="submit" value="as{L_SUBMIT}" /><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}" /></td>
		</tr>
		</table>
	</td>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class="header">{L_COMMENTS}</td>
</tr>
<!-- BEGIN _row -->
<tr>
	<td class="{_view._comment._row.CSS}">
		<table class="comment_row">
		<tr>
			<td><span class="right">{_view._comment._row.OPTIONS}</span><img src="{_view._comment._row.ICON}" alt="" /><span class="small">{_view._comment._row.DATE}</span></td>
		</tr>
		<tr>
			<td>{_view._comment._row.MESSAGE}</td>
		</tr>
		<tr>
			<td>by {_view._comment._row.POSTER}</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END _row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGINATION}</td>
</tr>
<!-- END _comment -->
</table>
<!-- END _view -->