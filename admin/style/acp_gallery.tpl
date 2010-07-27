<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li>
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
	<td class="rowHead" width="99%">{L_NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _gallery_row -->
<tr>
	<td class="row_class1" align="left"><span style="float:right;">{_display._gallery_row.INFO}&nbsp;</span><b>{_display._gallery_row.NAME}</b><br />{_display._gallery_row.DESC}</td>
	<td class="row_class2" align="left" nowrap="nowrap">{_display._gallery_row.MOVE_UP}{_display._gallery_row.MOVE_DOWN} {_display._gallery_row.OVERVIEW} <a href="{_display._gallery_row.U_UPLOAD}">{I_UPLOAD}</a> <a href="{_display._gallery_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._gallery_row.U_DELETE}">{I_DELETE}</a><span style="padding:8px;"></span>{_display._gallery_row.RESYNC}</td>
</tr>
<!-- END _gallery_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="2">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="gallery_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
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
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END _overview -->
	<!-- BEGIN _upload -->
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<!-- END _upload -->
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
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="gallery_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="gallery_name" id="gallery_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_AUTH}:</label></td>
	<td class="row3">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _auth_gallery -->
		<tr>
			<td nowrap="nowrap">{_input._auth_gallery.S_SELECT}</td>
			<td width="99%">&nbsp;<label for="{_input._auth_gallery.INFO}">{_input._auth_gallery.TITLE}</label></td>
		</tr>
		<!-- END _auth_gallery -->
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><label for="max_height">{L_MAX_HEIGHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_height" id="max_height" value="{MAX_HEIGHT}"></td>
</tr>
<tr>
	<td class="row1"><label for="max_width">{L_MAX_WIDTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_width" id="max_width" value="{MAX_WIDTH}"></td>
</tr>
<tr>
	<td class="row1"><label for="max_filesize">{L_MAX_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_filesize" id="max_filesize" value="{MAX_FILESIZE}"></td>
</tr>
<tr>
	<td class="row1"><label for="per_rows">{L_PER_ROWS}:</label></td>
	<td class="row2"><input type="text" class="post" name="per_rows" id="per_rows" value="{PER_ROWS}"></td>
</tr>
<tr>
	<td class="row1"><label for="per_cols">{L_PER_COLS}:</label></td>
	<td class="row2"><input type="text" class="post" name="per_cols" id="per_cols" value="{PER_COLS}"></td>
</tr>
<tr>
	<td class="row1"><label for="preview_list">{L_PREVIEW_LIST}:</label></td>
	<td class="row2"><label><input type="radio" name="preview_list" id="preview_list" value="1" {S_LIST_YES} />&nbsp;{L_LIST}</label><span style="padding:4px;"></span><label><input type="radio" name="preview_list" value="0" {S_LIST_NO} />&nbsp;{L_PREVIEW}</label></td>
</tr>
<tr>
	<td class="row1"><label for="preview_widht">{L_PREVIEW_WIDHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="preview_widht" id="preview_widht" value="{PREVIEW_WIDHT}"></td>
</tr>
<tr>
	<td class="row1"><label for="preview_height">{L_PREVIEW_HEIGHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="preview_height" id="preview_height" value="{PREVIEW_HEIGHT}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_DESC}: *</label></td>
	<td class="row2"><textarea class="textarea" name="gallery_desc" rows="5" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="gallery_comments">{L_COMMENT}:</label></td>
	<td class="row2"><label><input type="radio" name="gallery_comments" id="gallery_comments" value="1" {S_COMMENT_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="gallery_comments" value="0" {S_COMMENT_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="gallery_rate">{L_RATE}:</label></td>
	<td class="row2"><label><input type="radio" name="gallery_rate" id="gallery_rate" value="1" {S_RATE_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="gallery_rate" value="0" {S_RATE_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->

<!-- BEGIN _upload -->
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
	<li><a href="{S_INPUT}">{L_INPUT}</a></li>
	<!-- BEGIN _overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END _overview -->
	<li id="active"><a href="#" id="current">{L_UPLOAD}</a></li>
</ul>
</div>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
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
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _upload -->

<!-- BEGIN _overview -->
<form action="{S_ACTION}" method="post" id="list" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_EDIT}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_OVERVIEW}</a></li>
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<!-- BEGIN _list -->
<!-- BEGIN _gallery_row -->
<tr>
    <td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current"><span class="middle">{_overview._list._gallery_row.TITLE}</span></a></li>
		</ul>
		</div>
		<table class="update" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="row1"><a href="{_overview._list._gallery_row.IMAGE}" rel="lightbox"><img src="{_overview._list._gallery_row.PREV}" alt="" border="0" /></a></td>
			<td class="row3" nowrap="nowrap">
				<table class="update" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="small" align="right"><label for="pic_title[{_overview._list._gallery_row.PIC_ID}]">{L_TITLE}:&nbsp;</label></td>
					<td class="small" align="left"><input type="text" class="postsmall" name="pic_title[{_overview._list._gallery_row.PIC_ID}]" id="pic_title[{_overview._list._gallery_row.PIC_ID}]" value="{_overview._list._gallery_row.TITLE}"></td>
				</tr>
				<tr>
					<td class="small" align="right">{L_WIDTH}:&nbsp;</td>
					<td class="small" align="left">{_overview._list._gallery_row.WIDTH}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_HEIGHT}:&nbsp;</td>
					<td class="small" align="left">{_overview._list._gallery_row.HEIGHT}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_SIZE}:&nbsp;</td>
					<td class="small" align="left">{_overview._list._gallery_row.SIZE}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_SIZE}:&nbsp;</td>
					<td class="small" align="left">{_overview._list._gallery_row.NAME}</td>
				</tr>
				</table>
			</td>
			<td class="row2" width="100%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><!-- Bewertung --></td>
				</tr>
				<tr>
					<td><!-- Kommentare --></td>
				</tr>
				</table>
			</td>
			<td class="row2 small" nowrap="nowrap"><input type="text" class="postsmall" name="pic_order[{_overview._list._gallery_row.PIC_ID}]" id="pic_title[{_overview._list._gallery_row.PIC_ID}]" value="{_overview._list._gallery_row.ORDER}" size="2"></td>
			<td class="row2 small" nowrap="nowrap">{_overview._list._gallery_row.MOVE_UP}{_overview._list._gallery_row.MOVE_DOWN}</td>
			<td class="row2 small" nowrap="nowrap"><input type="checkbox" name="pics[]" value="{_overview._list._gallery_row.PIC_ID}"></td>
		</tr>
        </table>
    </td>
</tr>
<!-- END _gallery_row -->
<!-- END _list -->
<!-- BEGIN _preview -->
<!-- BEGIN _gallery_row -->
<tr>
	<!-- BEGIN _gallery_col -->
    <td>
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current"><span class="middle">{_overview._preview._gallery_row._gallery_col.TITLE}</span></a></li>
		</ul>
		</div>
		<table class="update" border="0" cellspacing="0" cellpadding="0">
        <tr>
			<td class="row3" nowrap="nowrap">
				<table class="update" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2">
						<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%"><a href="{_overview._preview._gallery_row._gallery_col.IMAGE}" rel="lightbox"><img src="{_overview._preview._gallery_row._gallery_col.PREV}" alt="" border="0" /></a></td>
							<td nowrap="nowrap"><input type="text" class="postsmall" name="pic_order[{_overview._preview._gallery_row._gallery_col.PIC_ID}]" id="pic_title[{_overview._preview._gallery_row._gallery_col.PIC_ID}]" value="{_overview._preview._gallery_row._gallery_col.ORDER}" size="2"></td>
							<td nowrap="nowrap">{_overview._preview._gallery_row._gallery_col.MOVE_UP}{_overview._preview._gallery_row._gallery_col.MOVE_DOWN}</td>
							<td><input type="checkbox" name="pics[]" value="{_overview._preview._gallery_row._gallery_col.PIC_ID}"></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="small" align="right"><label for="pic_title[{_overview._preview._gallery_row._gallery_col.PIC_ID}]">{L_TITLE}:&nbsp;</label></td>
					<td class="small" align="left"><input type="text" class="postsmall" name="pic_title[{_overview._preview._gallery_row._gallery_col.PIC_ID}]" id="pic_title[{_overview._preview._gallery_row._gallery_col.PIC_ID}]" value="{_overview._preview._gallery_row._gallery_col.TITLE}" /></td>
				</tr>
				<tr>
					<td class="small" align="right">{L_WIDTH}:&nbsp;</td>
					<td class="small" align="left">{_overview._preview._gallery_row._gallery_col.WIDTH}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_HEIGHT}:&nbsp;</td>
					<td class="small" align="left">{_overview._preview._gallery_row._gallery_col.HEIGHT}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_SIZE}:&nbsp;</td>
					<td class="small" align="left">{_overview._preview._gallery_row._gallery_col.SIZE}</td>
				</tr>
				<tr>
					<td class="small" align="right">{L_SIZE}:&nbsp;</td>
					<td class="small" align="left">{_overview._preview._gallery_row._gallery_col.NAME}</td>
				</tr>
				</table>
			</td>
		</tr>
        </table>
    </td>
	<!-- END _gallery_col -->
</tr>
<!-- END _gallery_row -->
<!-- END _preview -->
</table>

<table class="update" border="0" cellspacing="0" cellpadding="0">
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
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _overview -->

<!-- BEGIN gallery_resync -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- END gallery_resync -->

<!-- BEGIN _default -->
<form action="{S_ACTION}" method="post" name="form">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li id="active"><a href="#" id="right">{L_DEFAULT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
    <td class="row1" width="155"><label for="max_height">{L_MAX_HEIGHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_height" id="max_height" value="{MAX_HEIGHT}"></td>
</tr>
<tr>
    <td class="row1"><label for="max_width">{L_MAX_WIDTH}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_width" id="max_width" value="{MAX_WIDTH}"></td>
</tr>
<tr>
    <td class="row1"><label for="max_filesize">{L_MAX_FILESIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="max_filesize" id="max_filesize" value="{MAX_FILESIZE}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_AUTH}:</label></td>
	<td class="row3">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _auth_gallery -->
		<tr>
			<td nowrap="nowrap">{_default._auth_gallery.S_SELECT}</td>
			<td width="99%">&nbsp;{_default._auth_gallery.TITLE}</td>
		</tr>
		<!-- END _auth_gallery -->
		</table>
	</td>
</tr>
<tr>
    <td class="row1"><label for="per_rows">{L_PER_ROWS}:</label></td>
	<td class="row2"><input type="text" class="post" name="per_rows" id="per_rows" value="{PER_ROWS}"></td>
</tr>
<tr>
    <td class="row1"><label for="per_cols">{L_PER_COLS}:</label></td>
	<td class="row2"><input type="text" class="post" name="per_cols" id="per_cols" value="{PER_COLS}"></td>
</tr>
<tr>
	<td class="row1"><label for="preview_list">{L_PREVIEW_LIST}:</label></td>
	<td class="row2"><label><input type="radio" name="preview_list" id="preview_list" value="1" {S_LIST_YES} />&nbsp;{L_LIST}</label><span style="padding:4px;"></span><label><input type="radio" name="preview_list" value="0" {S_LIST_NO} />&nbsp;{L_PREVIEW}</label></td>
</tr>
<tr>
    <td class="row1"><label for="preview_widht">{L_PREVIEW_WIDHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="preview_widht" id="preview_widht" value="{PREVIEW_WIDHT}"></td>
</tr>
<tr>
    <td class="row1"><label for="preview_height">{L_PREVIEW_HEIGHT}:</label></td>
	<td class="row2"><input type="text" class="post" name="preview_height" id="preview_height" value="{PREVIEW_HEIGHT}"></td>
</tr>
<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>
<tr>
	<td colspan="6" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _default -->