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
<!-- BEGIN type_icon -->
<tr> 
	<td>{L_ICON}icon</td>
	<td>{S_ICON}</td>
</tr>
<!-- END type_icon -->
<tr> 
	<td>{L_SUBJECT}subject</td>
	<td><input type="text" name="post_subject" value="{POST_SUBJECT}" /></td>
</tr>
<tr> 
	<td>{L_MESSAGE}message</td>
	<td><textarea name="post_message" rows="15" cols="35">{POST_MESSAGE}</textarea></td>
</tr>
<!-- BEGIN type_toggle -->
<tr> 
	<td>{L_TYPE}type</td>
	<td>{S_TYPE}</td>
</tr>
<!-- END type_toggle -->
<tr> 
	<td>{L_OPTION}option</td>
	<td><input name="post_notify" type="checkbox" value="on" {S_NOTIFY} /> {L_NOTIFY}Benachrichten, wenn geantwortet wird auf diesen Beitrag<br /></td>
</tr>
<tr> 
	<td colspan="2" align="center"><input type="submit" name="submit" class="button" value="Absenden"></td>
</tr>
</table>
{S_FIELDS}	
</form>