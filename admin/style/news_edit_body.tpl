	
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>


<form action="{S_NEWS_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>{L_NEWS_HEAD} - {L_NEWS_NEW_EDIT}</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NEWS_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="news_title" value="{NEWS_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_NEWSCAT}:</td>
	<td class="row2">{S_NEWSCAT_LIST}<br /><img src="{NEWSCAT_IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_MATCH}:</td>
	<td class="row2">{S_NEWS_MATCH_LIST}</td>
</tr>
<tr>
	<td class="row1">{L_NEWS_TEXT}:</td>
	<td class="row2"><textarea class="post" name="news_text" rows="10" cols="80">{NEWS_TEXT}</textarea></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_NEWS_LINK}: </td>
	<td class="row2" width="80%"><input class="post" type="text" name="news_url1" value="{NEWS_URL1}" > <input class="post" type="text" name="news_link1" value="{NEWS_LINK1}" ></td>
</tr>
<tr>
	<td class="row1" width="20%">{L_NEWS_LINK}: </td>
	<td class="row2" width="80%"><input class="post" type="text" name="news_url2" value="{NEWS_URL2}" > <input class="post" type="text" name="news_link2" value="{NEWS_LINK2}" ></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_PUBLIC_TIME}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<tr>
	<td class="row1">{L_NEWS_PUBLIC}:</td>
	<td class="row3"><input type="radio" name="news_public" value="1" {S_CHECKED_PUBLIC_YES} /> {L_YES} <input type="radio" name="news_public" value="0" {S_CHECKED_PUBLIC_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_NEWS_COMMENTS}:</td>
	<td class="row3"><input type="radio" name="news_comments" value="1" {S_CHECKED_COMMENTS_YES} /> {L_YES} <input type="radio" name="news_comments" value="0" {S_CHECKED_COMMENTS_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_NEWS_INTERN}:</td>
	<td class="row3"><input type="radio" name="news_intern" value="1" {S_CHECKED_INTERN_YES} /> {L_YES} <input type="radio" name="news_intern" value="0" {S_CHECKED_INTERN_NO} /> {L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_NEWS_RATING}:</td>
	<td class="row3"><input type="radio" name="news_rating" value="1" {S_CHECKED_RATING_YES} /> {L_YES} <input type="radio" name="news_rating" value="0" {S_CHECKED_RATING_NO} /> {L_NO} </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>