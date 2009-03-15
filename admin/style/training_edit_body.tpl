<form action="{S_TRAINING_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_TRAINING_HEAD} - {L_TRAINING_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_TRAINING_VS}: *</td>
	<td class="row2"><input id="training_vs" onBlur="javascript:checkEntry(this)" class="post" type="text" name="training_vs" value="{TRAINING_VS}" ></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_TEAM}: *</td>
	<td class="row2">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MATCH}:</td>
	<td class="row2">{S_MATCH}</td>
</tr>

<tr>
	<td class="row1">{L_TRAINING_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_DURATION}:</td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MAPS}: *</td>
	<td class="row3"><input id="training_maps" onBlur="javascript:checkEntry(this)" class="post" type="text" name="training_maps" value="{TRAINING_MAPS}" ></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_COMMENT}:</td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_comment">{TRAINING_COMMENT}</textarea></td>
</tr>


<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>