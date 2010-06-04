<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li>
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
	<td class="rowHead" width="99%">{L_NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_gallery -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{display.row_gallery.INFO}&nbsp;</span><b>{display.row_gallery.NAME}</b><br />{display.row_gallery.DESC}</td>
	<td class="row_class2" align="left" nowrap="nowrap">{display.row_gallery.MOVE_UP}{display.row_gallery.MOVE_DOWN} {display.row_gallery.OVERVIEW} <a href="{display.row_gallery.U_UPLOAD}">{I_UPLOAD}</a> <a href="{display.row_gallery.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_gallery.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END row_gallery -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="2">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="gallery_name"></td>
	<td class="top" align="right" width="1%"><input class="button" type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN gallery_edit -->
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
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
	<!-- BEGIN overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END overview -->
	<!-- BEGIN upload -->
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<!-- END upload -->
	<li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="23%"><label for="gallery_name">{L_NAME}: *</label></td>
	<td class="row3"><input type="text" class="post" name="gallery_name" id="gallery_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_AUTH}:</label></td>
	<td class="row3">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN auth_gallery_data -->
		<tr>
			<td nowrap="nowrap">{gallery_edit.auth_gallery_data.S_AUTH_LEVELS_SELECT}</td>
			<td width="99%">&nbsp;<label for="{gallery_edit.auth_gallery_data.CELL_INFO}">{gallery_edit.auth_gallery_data.CELL_TITLE}</label></td>
		</tr>
		<!-- END auth_gallery_data -->
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><label for="max_height">{L_MAX_HEIGHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_height" id="max_height" value="{MAX_HEIGHT}"></td>
</tr>
<tr>
	<td class="row1"><label for="max_width">{L_MAX_WIDTH}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_width" id="max_width" value="{MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="max_filesize">{L_MAX_FILESIZE}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_filesize" id="max_filesize" value="{MAX_FILESIZE}"></td>
</tr>
<tr>
	<td class="row1"><label for="per_rows">{L_PER_ROWS}:</label></td>
	<td class="row3"><input type="text" class="post" name="per_rows" id="per_rows" value="{PER_ROWS}"></td>
</tr>
<tr>
	<td class="row1"><label for="per_cols">{L_PER_COLS}:</label></td>
	<td class="row3"><input type="text" class="post" name="per_cols" id="per_cols" value="{PER_COLS}"></td>
</tr>
<tr>
	<td class="row1"><label for="preview_widht">{L_PREVIEW_WIDHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="preview_widht" id="preview_widht" value="{PREVIEW_WIDHT}"></td>
</tr>
<tr>
	<td class="row1"><label for="preview_height">{L_PREVIEW_HEIGHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="preview_height" id="preview_height" value="{PREVIEW_HEIGHT}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_DESC}: *</label></td>
	<td class="row3"><textarea class="textarea" name="gallery_desc" rows="5" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="gallery_comments">{L_COMMENT}:</label></td>
	<td class="row3"><label><input type="radio" name="gallery_comments" id="gallery_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="gallery_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="gallery_rate">{L_RATE}:</label></td>
	<td class="row3"><label><input type="radio" name="gallery_rate" id="gallery_rate" value="1" {S_RATE_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="gallery_rate" value="0" {S_RATE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END gallery_edit -->

<!-- BEGIN gallery_upload -->
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
			
			if ( arrInput[i].type=='file' )
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
<form action="{S_ACTION}" method="post" name="form" id="form" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_EDIT}</a></li>
	<!-- BEGIN overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END overview -->
	<li id="active"><a href="#" id="current">{L_UPLOAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 top">{L_UPLOAD}:</td>
	<td class="row3">
		<div><div>
			<input class="post" name="ufile[]" type="file" id="ufile[]" size="25" /><br />
			<input class="post" name="title[]" type="text" id="title[]">&nbsp;<input class="button2" type="button" value="mehr"onclick="clone(this)">
		</div></div>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END gallery_upload -->

<!-- BEGIN gallery_overview -->
<form action="{S_ACTION}" method="post" id="list" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_EDIT}</a></li>
	<li id="active"><a href="#" id="current">{L_OVERVIEW}</a></li>
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN row_gallery -->
<tr>
	<!-- BEGIN gallery_col -->
    <td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current"><input type="checkbox" name="pics[]" value="{gallery_overview.row_gallery.gallery_col.PIC_ID}">&nbsp;{gallery_overview.row_gallery.gallery_col.TITLE}</a></li>
		</ul>
		</div>

		<table class="edit" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="row2"><a href="{gallery_overview.row_gallery.gallery_col.IMAGE}" rel="lightbox"><img src="{gallery_overview.row_gallery.gallery_col.PREV}" alt="" border="" /></a></td>
			<td class="row3">
				<table class="edit" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">{L_WIDTH}:</td>
					<td align="center">{gallery_overview.row_gallery.gallery_col.WIDTH}</td>
				</tr>
				<tr>
					<td align="right">{L_HEIGHT}:</td>
					<td align="center">{gallery_overview.row_gallery.gallery_col.HEIGHT}</td>
				</tr>
				<tr>
					<td align="right">{L_SIZE}:</td>
					<td align="center">{gallery_overview.row_gallery.gallery_col.SIZE}</td>
				</tr>
				</table>
			</td>
		</tr>
        </table>
    </td>
    <!-- END gallery_col -->
</tr>
<!-- END row_gallery -->
<tr>
	<td colspan="{PER_ROWS}" align="center">&nbsp;</td>
</tr>
</table>

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'pic', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'pic', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_DELETE}" class="button2"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END gallery_overview -->

<!-- BEGIN gallery_default -->
<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li id="active"><a href="#" id="right">{L_DEFAULT}</a></li>
</ul>
</div>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td class="row1" width="23%"><label for="max_height">{L_MAX_HEIGHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_height" id="max_height" value="{MAX_HEIGHT}"></td>
</tr>
<tr>
    <td class="row1"><label for="max_width">{L_MAX_WIDTH}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_width" id="max_width" value="{MAX_WIDTH}"></td>
</tr>
<tr>
    <td class="row1"><label for="max_filesize">{L_MAX_FILESIZE}:</label></td>
	<td class="row3"><input type="text" class="post" name="max_filesize" id="max_filesize" value="{MAX_FILESIZE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_AUTH}:</td>
	<td class="row3">
		<table class="edit" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN auth_gallery_data -->
		<tr>
			<td nowrap="nowrap">{gallery_default.auth_gallery_data.S_SELECT}</td>
			<td width="99%">&nbsp;{gallery_default.auth_gallery_data.TITLE}</td>
		</tr>
		<!-- END auth_gallery_data -->
		</table>
	</td>
</tr>
<tr>
    <td class="row1"><label for="per_rows">{L_PER_ROWS}:</label></td>
	<td class="row3"><input type="text" class="post" name="per_rows" id="per_rows" value="{PER_ROWS}"></td>
</tr>
<tr>
    <td class="row1"><label for="per_cols">{L_PER_COLS}:</label></td>
	<td class="row3"><input type="text" class="post" name="per_cols" id="per_cols" value="{PER_COLS}"></td>
</tr>
<tr>
    <td class="row1"><label for="preview_widht">{L_PREVIEW_WIDHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="preview_widht" id="preview_widht" value="{PREVIEW_WIDHT}"></td>
</tr>
<tr>
    <td class="row1"><label for="preview_height">{L_PREVIEW_HEIGHT}:</label></td>
	<td class="row3"><input type="text" class="post" name="preview_height" id="preview_height" value="{PREVIEW_HEIGHT}"></td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="6" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END gallery_default -->