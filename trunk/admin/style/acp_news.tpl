<!-- BEGIN display -->
<form method="post" action="{S_NEWS_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_NEWS_TITLE}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_NEWS_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_NEWS_TEAM}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN news_row -->
<tr>
	<td class="{display.news_row.CLASS}" align="center">{display.news_row.I_IMAGE}</td>
	<td class="{display.news_row.CLASS}" align="left">{display.news_row.NAME}</td>
	<td class="{display.news_row.CLASS}" align="center" width="1%"><a href="{display.news_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.news_row.CLASS}" align="center" width="1%"><a href="{display.news_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END news_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="news_title" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_NEWS_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN news_edit -->
	
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<script type="text/JavaScript">
// <![CDATA[

	function clone(objButton)
	{
		if ( objButton.parentNode )
		{
			tmpNode = objButton.parentNode.cloneNode(true);
			target = objButton.parentNode.parentNode;
			arrInput = tmpNode.getElementsByTagName("input");
			
			for ( var i=0; i<arrInput.length; i++ )
			{
				if ( arrInput[i].type=='text' )
				{
					arrInput[i].value='';
				}
			}
			
			target.appendChild(tmpNode);
			objButton.value="entfernen";
			objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
		}
	}
	
// ]]>
</script>

<form action="{S_NEWS_ACTION}" method="post" name="form">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NEWS_ACTION}">{L_NEWS_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NEWS_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
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
	<td class="row2"><textarea class="textarea" name="news_text" rows="10" cols="80">{NEWS_TEXT}</textarea></td>
</tr>

<tr>
	<td class="row1">{L_NEWS_LINK}:</td>
	<td class="row2">
		<table cellspacing="0">
		<!-- BEGIN link_row -->
		<tr>
			<td class=""><input class="post" type="text" name="news_url[]" value="{link_row.NEWS_URL}">	<input class="post" type="text" name="news_name[]" value="{link_row.NEWS_NAME}"> <input  class="button2" type="button" value="eigentlich löschen ;)" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END link_row -->
		</table>
		<div><div>
			<input class="post" type="text" name="news_url[]" value="">
			<input class="post" type="text" name="news_name[]" value="">
			<input class="button2" type="button" value="noch eins"onclick="clone(this)">			
		</div></div>
		
		</td>
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
<!-- END news_edit -->