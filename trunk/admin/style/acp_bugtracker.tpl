<!-- BEGIN display -->
<form action="{S_ACTION}" method="post" id="bugtracker_sort" name="bugtracker_sort">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
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
	<td class="rowHead" colspan="3">{L_NAME}</td>
	<td class="rowHead" colspan="2" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_bugtracker -->
<tr>
	<td class="{display.row_bugtracker.CLASS}" nowrap="nowrap">{display.row_bugtracker.STATUS}</td>
	<td class="{display.row_bugtracker.CLASS}" width="97%"><span style="float:right;">{display.row_bugtracker.CREATOR} {display.row_bugtracker.DATE}</span>{display.row_bugtracker.TITLE}</td>
	<td class="{display.row_bugtracker.CLASS}" nowrap="nowrap">{display.row_bugtracker.TYPE}</td>
	<td class="{display.row_bugtracker.CLASS}"><a href="{display.row_bugtracker.U_DETAIL}">{L_DETAIL}</a></td>
	<td class="{display.row_bugtracker.CLASS}"><a href="{display.row_bugtracker.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END row_bugtracker -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry2" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right" colspan="2">{S_SORT}</td>
</tr>
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN detail -->
<script type="text/javascript" src="./../includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
// <![CDATA[
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

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}" method="post">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_PROC}</a></li>
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
	<td class="row1" width="23%">{L_TITLE}:</td>
	<td class="row3">{TITLE}</td>
</tr>
<tr>
	<td class="row1">{L_CREATOR}:</td>
	<td class="row3">{CREATOR} / {DATE} {DATE_CHANGE}</td>
</tr>
<tr>
	<td class="row1">{L_EDITOR}:</td>
	<td class="row3">{EDITOR}</td>
</tr>
<tr>
	<td class="row1">{L_STATUS}:</td>
	<td class="row3"><span style="float:right;">{S_STATUS}</span>{STATUS}</td>
</tr>
<tr>
	<td class="row1">{L_TYPE}:</td>
	<td class="row3"><span style="float:right;">{S_TYPE}</span>{TYPE}</td>
</tr>
<tr>
	<td class="row1">{L_DESC}:</td>
	<td class="row3">{DESC}</td>
</tr>
<tr>
	<td class="row1">{L_MESSAGE}:</td>
	<td class="row3">{MESSAGE}</td>
</tr>
<tr>
	<td class="row1">{L_PHP_SQL}:</td>
	<td class="row3">{PHP_SQL}</td>
</tr>
<tr>
	<td class="row1">{L_VERSION}:</td>
	<td class="row3">{VERSION}</td>
</tr>
<tr>
	<td class="row1 top">{L_REPORT}:</td>
	<td class="row3"><textarea class="textarea" name="report" rows="5" style="width:100%">{REPORT}</textarea></td>
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
<!-- END detail -->