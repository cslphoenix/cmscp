<!-- BEGIN display -->
<form action="{S_EVENT_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_EVENT_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_EVENT_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_EVENT_TITLE}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<!-- BEGIN event_row -->
<tr>
	<td class="{display.event_row.CLASS}" align="left" width="99%">{display.event_row.EVENT_TITLE}</td>
	<td class="{display.event_row.CLASS}" nowrap="nowrap">{display.event_row.EVENT_DATE}</td>
	<td class="{display.event_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.event_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.event_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.event_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END event_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="event_title" type="text" value=""></td>
	<td><input class="button" type="submit" name="event_add" value="{L_EVENT_ADD}" /></td>
</tr>
</table>
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
<form action="{S_EVENT_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_EVENT_ACTION}">{L_EVENT_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_EVENT_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_EVENT_TITLE}: *</td>
	<td class="row2"><input class="post" type="text" name="event_title" value="{EVENT_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_EVENT_LEVEL}:</td>
	<td class="row2">{S_EVENT_LEVEL}</td>
</tr>
<tr>
	<td class="row1">{L_EVENT_DESCRIPTION}: *</td>
	<td class="row2"><textarea class="textarea" name="event_description" rows="20" style="width:100%">{EVENT_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_EVENT_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1">{L_EVENT_DURATION}:</td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1">{L_EVENT_COMMENTS}:</td>
	<td class="row3"><input type="radio" name="event_comments" value="1" {S_CHECKED_COMMENT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="event_comments" value="0" {S_CHECKED_COMMENT_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END event_edit -->