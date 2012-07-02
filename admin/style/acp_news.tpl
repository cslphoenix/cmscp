<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_TITLE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN news_row -->
<tr>
	<td><span class="right">{display.news_row.DATE}</span>{display.news_row.STATUS} {display.news_row.TITLE}</td>
	<td>{display.news_row.PUBLIC}{display.news_row.UPDATE}{display.news_row.DELETE}</td>		
</tr>
<!-- END news_row -->
<!-- BEGIN news_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END news_empty -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_TITLE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN send_row -->
<tr>
	<td><span class="right">{display.send_row.SEND} {display.send_row.DATE}</span>{display.send_row.STATUS} {display.send_row.TITLE}</td>
	<td>{display.send_row.PUBLIC}{display.send_row.UPDATE}{display.send_row.DELETE}</td>		
</tr>
<!-- END send_row -->
<!-- BEGIN send_empty -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END send_empty -->
</table>

<table class="footer">
<tr>
	<td><input type="text" name="news_title"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN input -->
{UIMG}
{TINYMCE}
<form action="{S_ACTION}" method="post" name="form">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!--
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1r"><label for="news_title">{L_TITLE}:</label></td>
	<td class="row2"><input type="text" name="news_title" id="news_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td>{S_LIST_MATCH}</td>
</tr>
<tr>
	<td class="row1r"><label for="image">{L_CAT}:</label></td>
	<td>{S_LIST_CAT}</td>
</tr>
<tr>
	<td class="row1r"><label for="news_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="textarea" name="news_text" id="news_text" rows="20" style="width:100%">{TEXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="news_url">{L_LINK}:</label></td>
	<td>
		<div>
		<!-- BEGIN link_row ->
			<ul><input type="text" name="news_url[]" value="{input.link_row.NEWS_URL}"> <input type="text" name="news_link[]" value="{input.link_row.NEWS_LINK}"> <input type="button" class="more" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></ul>
		<!-- END link_row ->
		</div>
		<div><div><input type="text" name="news_url[]" value=""> <input type="text" name="news_link[]" value=""> <input class="more" type="button" value="{L_MORE}"onclick="clone(this)"></div></div>
	</td>
</tr>
<tr>
	<td class="row1"><label>{L_PUBLIC_TIME}:</label></td>
	<td>{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<!-- BEGIN public ->
<tr>
	<td class="row1"><label for="news_public">{L_PUBLIC}:</label></td>
	<td class="row2"><label><input type="radio" name="news_public" id="news_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="news_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!-- END public ->
<tr>
	<td class="row1"><label for="news_comments">{L_COMMENTS}:</label></td>
	<td class="row2"><label><input type="radio" name="news_comments" id="news_comments" value="1" {S_COMMENTS_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="news_comments" value="0" {S_COMMENTS_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="news_intern">{L_INTERN}:</label></td>
	<td class="row2"><label><input type="radio" name="news_intern" id="news_intern" value="1" {S_INTERN_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="news_intern" value="0" {S_INTERN_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="news_rating">{L_RATING}:</label></td>
	<td class="row2"><label><input type="radio" name="news_rating" id="news_rating" value="1" {S_RATING_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="news_rating" value="0" {S_RATING_NO} />&nbsp;{L_NO}</label></td>
</tr>
</table>
-->

<!-- BEGIN row -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->