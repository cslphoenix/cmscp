	
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_NEWSCAT_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_NEWSCAT_HEAD} - {L_NEWSCAT_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NEWSCAT_TITLE}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="news_categorie_title" value="{NEWSCAT_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_NEWSCAT_IMAGE}:</td>
	<td class="row2">{S_NEWSCAT_LIST}<br /><img src="{NEWSCAT_IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>