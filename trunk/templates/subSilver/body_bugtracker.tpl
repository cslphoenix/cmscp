<link rel="stylesheet" href="templates/subSilver/theme/bugtracker.css" type="text/css" />
<!-- BEGIN list -->

<form action="{S_BUGTRACKER_ACTION}" method="post" id="bt_sort" name="bt_sort">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="2">{L_BUGTRACKER}</td>
	<td class="info_head" style="text-align:center;">{L_ASSIGNED}</td>
	<td class="info_head" style="text-align:center;">{L_STATUS_TYPE}</td>
</tr>
<!-- BEGIN bt_row -->
<tr>
	<td class="{list.bt_row.CLASS} {list.bt_row.CLASS_STATUS}" align="center" nowrap="nowrap" width="5%">{list.bt_row.BT_ID}</td>
	<td class="{list.bt_row.CLASS}" align="left" width="100%">
		&nbsp;<a href="{list.bt_row.U_DETAILS}">{list.bt_row.BT_TITLE}</a>
		<br>
		&nbsp;{list.bt_row.BT_CREATE}
		<br>
		&nbsp;{list.bt_row.BT_DESC}
	</td>
	<td class="{list.bt_row.CLASS}" align="center" nowrap="nowrap">&nbsp;{list.bt_row.BT_WORKER}&nbsp;</td>
	<td class="{list.bt_row.CLASS}" align="center" nowrap="nowrap">{list.bt_row.BT_STATUS}<br>{list.bt_row.BT_TYPE}</td>
</tr>
<!-- END list.bt_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="info" width="100%" cellspacing="4">
<tr>
	<td class="noentry" colspan="3">{S_BUGTRACKER_SORT}</td>
	<td class="noentry" align="right"><input class="button2" type="submit" name="add" value="Eintragen"></td>
</tr>
<tr>
	<td class="noentry" colspan="3">{PAGINATION}</td>
	<td class="noentry" align="right">{PAGE_NUMBER}</td>
</tr>
</table>
</form>
<!-- END list -->

<!-- BEGIN show -->

<!-- END show -->

<!-- BEGIN entry -->

<script type="text/javascript" src="./includes/tiny_mce/tiny_mce.js"></script>
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

<form action="{S_BUGTRACKER_ACTION}" method="post" name="form">
<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head">{BT_HEAD}BugTracker :: Neuen Eintrag hinzufügen</td>
</tr>
<tr>
	<td>
		<table class="info" width="100%" cellspacing="0">
		<tr>
			<td class="row1 row4" colspan="2"><span class="small">{L_REQUIRED}</span></td>
		</tr>
		<tr>
			<td class="row1 row4" colspan="2">{ERROR_BOX}<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#FFF;"></div></td>
		</tr>
		<tr>
			<td class="row1_form" width="25%">{L_TITLE}:</td>
			<td class="row2" width="75%"><input id="bt_title" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{BT_TITLE}" name="bt_title"></td>
		</tr>
		<tr>
			<td class="row1_form">{L_DESC}:</td>
			<td class="row2"><input id="bt_desc" onBlur="javascript:checkEntry(this)" class="post" type="text" value="{BT_DESC}" name="bt_desc"></td>
		</tr>
		<tr>
			<td class="row1_form">{L_TYPE}:</td>
			<td class="row2">{S_TYPE}</td>
		</tr>
		<tr>
			<td class="row1_form">{L_PHP}:</td>
			<td class="row2"><input class="post" type="text" value="{BT_PHP}" name="bt_php"></td>
		</tr>
		<tr>
			<td class="row1_form">{L_SQL}:</td>
			<td class="row2"><input class="post" type="text" value="{BT_SQL}" name="bt_sql"></td>
		</tr>
		<tr>
			<td class="row1_form" valign="top">{L_MESSAGE}:</td>
			<td class="row2"><textarea id="bt_message" onBlur="javascript:checkEntry(this)" class="textarea" name="bt_message" style="width:100%;">{BT_MESSAGE}</textarea></td>
		</tr>
		<tr>
			<td class="row4" colspan="2" align="center">
				<input class="button2" name="submit" type="submit" value="Absenden">
				<input class="button" type="reset" value="Zurücksetzen">
			</td>
		</tr>
		</table>
		{S_HIDDEN_FIELD}
	</td>
</tr>
</table>
</form>
<!-- END entry -->

<!-- BEGIN comment -->

<!-- END comment -->