<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_TITLE}</td>
	<td class="rowHead">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _news_row -->
<tr>
	<td class="row_class1" align="center" width="1%"><img src="{_display._news_row.STATUS}" alt=""></td>
	<td class="row_class1"><span style="float:right;">{_display._news_row.DATE}</span>{_display._news_row.TITLE}</td>
	<td class="row_class2" align="center">{_display._news_row.PUBLIC} {_display._news_row.UPDATE} {_display._news_row.DELETE}</td>		
</tr>
<!-- END _news_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="3">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<br />

<table class="rows">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_TITLE}</td>
	<td class="rowHead">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _send_row -->
<tr>
	<td class="row_class1" align="center" width="1%"><img src="{_display._send_row.STATUS}" alt=""></td>
	<td class="row_class1"><span style="float:right;">{_display._send_row.SEND} {_display._send_row.DATE}</span>{_display._send_row.TITLE}</td>
	<td class="row_class2" align="center">{_display._send_row.PUBLIC} {_display._send_row.UPDATE} {_display._send_row.DELETE}</td>		
</tr>
<!-- END _send_row -->
</table>

<table class="footer">
<tr>
	<td><input type="text" class="post" name="news_title"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
{UIMG}
{TINYMCE}
<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155"><label for="news_title">{L_TITLE}: *</label></td>
	<td class="row2" width="77%"><input type="text" class="post" name="news_title" id="news_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row2">{S_LIST_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label for="image">{L_CAT}: *</label></td>
	<td class="row2">{S_LIST_CAT}<br /><img src="{IMAGE}" id="image" alt=""></td>
</tr>
<tr>
	<td class="row1"><label for="news_text">{L_TEXT}: *</label></td>
	<td class="row2"><textarea class="textarea" name="news_text" id="news_text" rows="20" style="width:100%">{TEXT}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="news_url">{L_LINK}:</label></td>
	<td class="row2">
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _link_row -->
		<tr>
			<td><input type="text" class="post" name="news_url[]" value="{_input._link_row.NEWS_URL}"> <input type="text" class="post" name="news_link[]" value="{_input._link_row.NEWS_LINK}"> <input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END _link_row -->
		</table>
		<div><div><input type="text" class="post" name="news_url[]" value=""> <input type="text" class="post" name="news_link[]" value=""> <input class="button2" type="button" value="{L_MORE}"onclick="clone(this)"></div></div>
	</td>
</tr>
<tr>
	<td class="row1"><label>{L_PUBLIC_TIME}:</label></td>
	<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<!-- BEGIN _public -->
<tr>
	<td class="row1"><label for="news_public">{L_PUBLIC}:</label></td>
	<td class="row2"><label><input type="radio" name="news_public" id="news_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="news_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO}</label></td>
</tr>
<!-- END _public -->
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
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->