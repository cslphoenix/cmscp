<!-- BEGIN display -->
<form action="{S_GALLERY_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GALLERY_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_GALLERY_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2" width="100%">{L_GALLERY_NAME}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN gallery_row -->
<tr>
	<td class="{display.gallery_row.CLASS}" align="left" width="99%"><b>{display.gallery_row.GALLERY_NAME}</b><br />{display.gallery_row.GALLERY_DESC}</td>
	<td class="{display.gallery_row.CLASS}" nowrap="nowrap">{display.gallery_row.GALLERY_INFO}</td>
	<td class="{display.gallery_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.gallery_row.U_UPLOAD}">{L_UPLOAD}</a></td>
	<td class="{display.gallery_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.gallery_row.U_EDIT}">{L_EDIT}</a></td>
	<td class="{display.gallery_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.gallery_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END gallery_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="gallery_name" type="text" value=""></td>
	<td><input class="button" type="submit" value="{L_GALLERY_ADD}" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
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
<form action="{S_GALLERY_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GALLERY_ACTION}">{L_GALLERY_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_GALLERY_NEW_EDIT}</a></li>
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
	<td class="row1" width="160">{L_GALLERY_NAME}: *</td>
	<td class="row2"><input class="post" type="text" name="gallery_name" value="{GALLERY_NAME}" ></td>
</tr>
<tr>
	<td class="row1" colspan="2">{L_GALLERY_AUTH}</td>
</tr>
<!-- BEGIN gallery_auth_data -->
<tr>
	<td class="row1" align="right">{gallery_edit.gallery_auth_data.CELL_TITLE}</td>
	<td class="row3">{gallery_edit.gallery_auth_data.S_AUTH_LEVELS_SELECT}</td>
</tr>
<!-- END gallery_auth_data -->
<tr>
	<td class="row1">{L_GALLERY_DESC}: *</td>
	<td class="row2"><textarea class="textarea" name="gallery_desc" rows="20" style="width:100%">{GALLERY_DESC}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_GALLERY_COMMENT}:</td>
	<td class="row3"><input type="radio" name="gallery_comments" value="1" {S_CHECKED_COMMENT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="gallery_comments" value="0" {S_CHECKED_COMMENT_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_GALLERY_RATE}:</td>
	<td class="row3"><input type="radio" name="gallery_rate" value="1" {S_CHECKED_RATE_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="gallery_rate" value="0" {S_CHECKED_RATE_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
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
<form action="{S_GALLERY_ACTION}" method="post" name="form" id="form" enctype="multipart/form-data">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GALLERY_ACTION}">{L_GALLERY_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_GALLERY_NEW_EDIT}</a></li>
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
	<td class="row1">{L_GALLERY_COMMENT}:</td>
	<td class="row3">
		<div><div>
			<input class="post" name="ufile[]" type="file" id="ufile[]" size="25" />
			<input class="post" name="title[]" type="text" id="title[]" value="">
			<input class="button2" type="button" value="mehr"onclick="clone(this)">
		</div></div>
	</td>
</tr>
<!--
<tr>
	<td class="row1">{L_GALLERY_COMMENT}:</td>
	<td class="row3"><input class="post" type="file" name="test"></td>
</tr>
-->
<tr>
	<td colspan="2" align="center"><input type="submit" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END gallery_upload -->