<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_TITLE}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _news_row -->
<tr>
	<td class="row_class1" align="center" width="1%"><img src="{_display._news_row.STATUS}" alt=""></td>
	<td class="row_class1" align="left">{_display._news_row.TITLE}</td>
	<td class="row_class2" align="center">{_display._news_row.LINK} {_display._news_row.UPDATE} {_display._news_row.DELETE}</td>		
</tr>
<!-- END _news_row -->


<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" colspan="5" align="center">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="news_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
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

<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="155"><label for="news_title">{L_TITLE}: *</label></td>
	<td class="row2" width="77%"><input type="text" class="post" name="news_title" id="news_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="image">{L_CAT}: *</label></td>
	<td class="row2">{S_LIST_CAT}<br><img src="{IMAGE}" id="image" alt=""></td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row2">{S_LIST_MATCH}</td>
</tr>
<tr>
	<td class="row1 top"><label for="news_text">{L_TEXT}: *</label></td>
	<td class="row2"><textarea class="textarea" name="news_text" id="news_text" rows="20" style="width:100%">{TEXT}</textarea></td>
</tr>

<tr>
	<td class="row1 top"><label for="news_url">{L_LINK}:</label></td>
	<td class="row2">
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN link_row -->
		<tr>
			<td><input type="text" class="post" name="news_url[]" value="{news_edit.link_row.NEWS_URL}"> <input type="text" class="post" name="news_link[]" value="{news_edit.link_row.NEWS_LINK}"> <input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END link_row -->
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