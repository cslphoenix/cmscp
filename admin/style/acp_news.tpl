<!-- BEGIN display -->
<form action="{S_NEWS_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_NEWS_HEAD}</a></li>
	<li><a href="{S_NEWS_CREATE}">{L_NEWS_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_NEWS_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_NEWS_NAME}</td>
	<td class="rowHead" colspan="3" align="center" width="1%">{L_SETTINGS}</td>
</tr>
<!-- BEGIN news_row -->
<tr>
	<td class="{display.news_row.CLASS}" align="center" width="1%"><img src="{display.news_row.NEWS_STATUS}" alt=""></td>
	<td class="{display.news_row.CLASS}" align="left">{display.news_row.NEWS_TITLE}</td>
	<td class="{display.news_row.CLASS}" align="center" width="1%">{display.news_row.NEWS_LINK}</td>
	<td class="{display.news_row.CLASS}" align="center" width="1%">{display.news_row.NEWS_UPDATE}</td>		
	<td class="{display.news_row.CLASS}" align="center" width="1%">{display.news_row.NEWS_DELETE}</td>
</tr>
<!-- END news_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" colspan="5" align="center">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="news_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_NEWS_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN news_edit -->
<script type="text/javascript" src="./../includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
// <![CDATA[
	
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}

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
			objButton.value="{L_REMOVE}";
			objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
		}
	}
	
	tinyMCE.init({
		language : "de",
		theme : "advanced",
		mode : "textareas",
		plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
		theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,image,removeformat,cleanup,code,preview",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		plugin_preview_width : "500",
		plugin_preview_height : "600",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		entity_encoding : "raw",
		add_unload_trigger : false,
		remove_linebreaks : false,
		inline_styles : false,
		convert_fonts_to_spans : false,
	});

// ]]>
</script>

<form action="{S_NEWS_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_NEWS_ACTION}" method="post">{L_NEWS_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEWS_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="news_title">{L_NEWS_TITLE}: *</label></td>
	<td class="row2" width="77%"><input type="text" class="post" name="news_title" id="news_title" value="{NEWS_TITLE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_NEWS_CAT}: *</td>
	<td class="row2">{S_NEWS_CAT_LIST}<br><img src="{NEWSCAT_IMAGE}" id="image" alt=""></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_MATCH}:</td>
	<td class="row2">{S_NEWS_MATCH_LIST}</td>
</tr>
<tr>
	<td class="row1 top"><label for="news_text">{L_NEWS_TEXT}: *</label></td>
	<td class="row2"><textarea class="textarea" name="news_text" id="news_text" rows="20" style="width:100%">{NEWS_TEXT}</textarea></td>
</tr>

<tr>
	<td class="row1 top">{L_NEWS_LINK}:</td>
	<td class="row2">
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN link_row -->
		<tr>
			<td><input type="text" class="post" name="news_url[]" value="{news_edit.link_row.NEWS_URL}">	<input type="text" class="post" name="news_name[]" value="{news_edit.link_row.NEWS_NAME}"> <input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END link_row -->
		</table>
		<div><div><input type="text" class="post" name="news_url[]" value=""> <input type="text" class="post" name="news_name[]" value=""> <input class="button2" type="button" value="{L_MORE}"onclick="clone(this)"></div></div>
	</td>
</tr>
<tr>
	<td class="row1">{L_NEWS_PUBLIC_TIME}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}  </td>
</tr>
<!-- BEGIN public -->
<tr>
	<td class="row1"><label for="news_public">{L_NEWS_PUBLIC}:</label></td>
	<td class="row3"><input type="radio" name="news_public" id="news_public" value="1" {S_PUBLIC_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="news_public" value="0" {S_PUBLIC_NO} />&nbsp;{L_NO} </td>
</tr>
<!-- END public -->
<tr>
	<td class="row1"><label for="news_comments">{L_NEWS_COMMENTS}:</label></td>
	<td class="row3"><input type="radio" name="news_comments" id="news_comments" value="1" {S_COMMENTS_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="news_comments" value="0" {S_COMMENTS_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td class="row1"><label for="news_intern">{L_NEWS_INTERN}:</label></td>
	<td class="row3"><input type="radio" name="news_intern" id="news_intern" value="1" {S_INTERN_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="news_intern" value="0" {S_INTERN_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td class="row1"><label for="news_rating">{L_NEWS_RATING}:</label></td>
	<td class="row3"><input type="radio" name="news_rating" id="news_rating" value="1" {S_RATING_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="news_rating" value="0" {S_RATING_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END news_edit -->