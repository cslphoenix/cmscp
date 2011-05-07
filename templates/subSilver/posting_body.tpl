<form action="{S_POST_ACTION}" method="post" name="post" onsubmit="return checkForm(this)">

{POST_PREVIEW_BOX}
{ERROR_BOX}

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="info_head" colspan="5">
		<a href="{U_FORUM}">{L_FORUM}</a>
		<!-- BEGIN sub -->
		 <a href="{sub.U_VIEW_FORUM}">{sub.FORUM_NAME}</a> ::
		<!-- END sub -->
		<!-- BEGIN switch_not_privmsg --> 
		<a href="{U_VIEW_FORUM}">{FORUM_NAME}</a> :: {L_POST_A}</span></td>
		<!-- END switch_not_privmsg -->
	</td>
</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="100%">
<tr> 
	<td class="row1" width="22%">{L_SUBJECT}</td>
	<td class="row2" width="78%"><input type="text" name="subject" size="45" maxlength="60" style="width:450px" tabindex="2" class="post" value="{SUBJECT}" /></td>
</tr>
<!-- BEGIN switch_type_toggle -->
<tr> 
	<td class="row1" width="22%">{L_SUBJECT}</td>
	<td class="row2" width="78%">{S_TYPE_TOGGLE}</td>
</tr>
<!-- END switch_type_toggle -->
<tr> 
	<td class="row1" valign="top">{L_MESSAGE_BODY}</td>
	<td class="row2"><textarea name="message" rows="15" cols="35" wrap="virtual" style="width:450px" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{MESSAGE}</textarea></td>
</tr>
</table>
<!--{POLLBOX}-->
		 
{S_HIDDEN_FORM_FIELDS}	
<input type="submit" tabindex="5" name="preview" class="mainoption" value="{L_PREVIEW}">
<input type="submit" accesskey="s" tabindex="6" name="post" class="mainoption" value="{L_SUBMIT}">
	

  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
	</tr>
  </table>
</form>