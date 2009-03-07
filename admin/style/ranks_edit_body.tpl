
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{RANKS_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_TEAM_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_RANK_HEAD} - {L_RANK_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_RANK_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="rank_title" value="{RANK_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_RANK_IMAGE}:</td>
	<td class="row2">{S_FILENAME_LIST}&nbsp;<img src="{RANK_IMAGE}" id="image" alt="" />
	</td>
</tr>
<tr>
	<td class="row1">{L_RANK_TYPE}:</td>
	<td class="row2">
		<input type="radio" name="rank_type" value="0" {CHECKED_TYPE_PAGE} /> {L_TYPE_PAGE}
		<input type="radio" name="rank_type" value="1" {CHECKED_TYPE_FORUM} /> {L_TYPE_FORUM}
		<input type="radio" name="rank_type" value="2" {CHECKED_TYPE_TEAM} /> {L_TYPE_TEAM}
	</td> 
</tr>
<tr>
	<td class="row1">{L_RANK_SPECIAL}:</td>
	<td class="row2">
		<input type="radio" name="rank_special" value="0" {CHECKED_SPECIAL_NO} /> {L_NO}
		<input type="radio" name="rank_special" onClick="this.form.rank_min.value=''" value="1" {CHECKED_SPECIAL_YES} /> {L_YES}
	</td>
</tr>

<tr>
	<td class="row1">{L_RANK_MIN}:</td>
	<td class="row2"><input class="post" type="text" name="rank_min" value="{RANK_MIN}" ></td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>