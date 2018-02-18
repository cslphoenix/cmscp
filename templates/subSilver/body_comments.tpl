<!-- BEGIN view -->
<div align="center">
<table class="type3" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td colspan="2" align="center">{ERROR_BOX}</td>
</tr>
<!-- BEGIN comment -->
<tr>
	<th colspan="2">{L_COMMENT_CREATE}Kommentar hinzuf&uuml;gen</th>
</tr>
<!-- BEGIN guest -->
<tr>
	<td><label for="poster_nick">{L_USERNAME} *</label></td>
	<td class="row2"><input type="text" name="poster_nick" id="poster_nick" value="{POSTER_NICK}" /></td>
</tr>
<tr>
	<td><label for="poster_mail">{L_MAIL}: *</label></td>
	<td class="row2"><input type="text"class="post" name="poster_mail" id="poster_mail" value="{POSTER_MAIL}" /></td>
</tr>
<tr>
	<td><label for="poster_hp">{L_HOMEPAGE}:</label></td>
	<td class="row2"><input type="text"class="post" name="poster_hp" id="poster_hp" value="{POSTER_HP}" /></td>
</tr>
<tr>
	<td><label title="{L_CAPTCHA_EXPLAIN}">{L_CAPTCHA}: *</label></td>
	<td class="row2"><img style="background:#FFF; padding:1px;" src="includes/captcha.php" onclick="javascript:this.src='includes/captcha.php?'+Math.random();" border="0"  alt="Das Captcha konnte nicht erstellt werden." /></td>
</tr>
<tr>
	<td><label for="captcha">{L_CAPTCHA_CODE}: *</label></td>
	<td class="row2"><input type="text"class="post" name="captcha" id="captcha" value="" /></td>
</tr>
<!-- END guest -->
<tr>
	<td><label for="poster_msg">{L_COMMENT}:</label></td>
	<td class="row2"><textarea class="textarea" name="poster_msg" id="poster_msg" cols="30" rows="5">{POSTER_MSG}</textarea></td>
</tr>
<tr>
	<td class="center" colspan="2"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}" /><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}" /></td>
</tr>
</table>

<br />

<table class="type3" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>{L_COMMENTS}Kommentare</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{_view._comment.row.CLASS}">
		<div>
			<ul><span class="right">{_view._comment.row.OPTIONS}</span><img src="{_view._comment.row.ICON}" alt="" /><span class="small">{_view._comment.row.POSTER}, {_view._comment.row.DATE}</span></ul>
			<ul>{_view._comment.row.MESSAGE}</ul>
		</div>
	</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td align="center">{L_NONE}</td>
</tr>
<!-- END none -->
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
<!-- END comment -->
</table>
</div>
<!-- END view -->