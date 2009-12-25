<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead">{L_TITLE}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_event -->
<tr>
	<td class="{display.row_event.CLASS}" align="left" width="99%"><span style="float:right;">{display.row_event.DATE}</span>{display.row_event.TITLE}</td>
	<td class="{display.row_event.CLASS}"><a href="{display.row_event.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.row_event.CLASS}"><a href="{display.row_event.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END row_event -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" colspan="3" align="center">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="event_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN event_edit -->
<script type="text/javascript" src="./../includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
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
</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}" method="post">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
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
	<td class="row1" width="23%"><label for="event_title">{L_TITLE}: *</label></td>
	<td class="row3"><input type="text" class="post" name="event_title" id="event_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1">{L_LEVEL}:</td>
	<td class="row3">{S_LEVEL}</td>
</tr>
<tr>
	<td class="row1 top"><label for="event_desc">{L_DESC}: *</label></td>
	<td class="row3"><textarea class="textarea" name="event_desc" id="event_desc" rows="20" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1">{L_DURATION}:</td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label for="event_comments">{L_COMMENTS}:</label></td>
	<td class="row3"><input type="radio" name="event_comments" id="event_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="event_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END event_edit -->