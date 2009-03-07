	
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{GAMES_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_TEAM_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_GAME_HEAD} - {L_GAME_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_GAME_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="game_name" value="{GAME_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_GAME_IMAGE}:</td>
	<td class="row2">{S_FILENAME_LIST}&nbsp;<img src="{GAME_IMAGE}" id="image" alt="" />
	</td>
</tr>

<tr>
	<td class="row1">{L_GAME_SIZE}:</td>
	<td class="row2"><input class="post" type="text" name="game_size" value="{GAME_SIZE}" ></td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>