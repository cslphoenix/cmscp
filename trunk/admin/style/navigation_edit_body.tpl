<form action="{S_NAVI_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_NAVI_HEAD} - {L_NAVI_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NAVI_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="navi_name" value="{NAVI_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_NAVI_URL}:</td>
	<td class="row2">{S_FILENAME_LIST} <input class="post" type="text" name="navi_url" value="{NAVI_URL}" id="select" ></td>
</tr>
<tr>
	<td class="row1">{L_NAVI_TYPE}:</td>
	<td class="row2">
		<input type="radio" name="navi_type" value="1" {CHECKED_TYPE_MAIN} /> {L_TYPE_MAIN}<br />
		<input type="radio" name="navi_type" value="2" {CHECKED_TYPE_CLAN} /> {L_TYPE_CLAN}<br />
		<input type="radio" name="navi_type" value="3" {CHECKED_TYPE_COM} /> {L_TYPE_COM}<br />
		<input type="radio" name="navi_type" value="4" {CHECKED_TYPE_MISC} /> {L_TYPE_MISC}<br />
		<input type="radio" name="navi_type" value="5" {CHECKED_TYPE_USER} /> {L_TYPE_USER}<br />
	</td> 
</tr>
<tr>
	<td class="row1">{L_NAVI_LANGUAGE}:</td>
	<td class="row2">
		<input type="radio" name="navi_lang" value="0" {CHECKED_LANG_NO} /> {L_NO}
		<input type="radio" name="navi_lang" value="1" {CHECKED_LANG_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_SHOW}:</td>
	<td class="row2">
		<input type="radio" name="navi_show" value="0" {CHECKED_SHOW_NO} /> {L_NO}
		<input type="radio" name="navi_show" value="1" {CHECKED_SHOW_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_INTERN}:</td>
	<td class="row2">
		<input type="radio" name="navi_intern" value="0" {CHECKED_INTERN_NO} /> {L_NO}
		<input type="radio" name="navi_intern" value="1" {CHECKED_INTERN_YES} /> {L_YES}
	</td>
</tr>
<tr>
	<td class="row1">{L_NAVI_TARGET}:</td>
	<td class="row2">
		<input type="radio" name="navi_target" value="1" {CHECKED_TARGET_NEW} /> {L_NAVI_NEW}
		<input type="radio" name="navi_target" value="0" {CHECKED_TARGET_SELF} /> {L_NAVI_SELF}
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>