<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[
function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}
// ]]>
</script>
<form action="{S_ACTION}" method="post">
{TINYMCE}
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
	<!-- BEGIN upload -->
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<!-- END upload -->
	<!-- BEGIN overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END overview -->
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label><span>{input.row.tab.option.AUTH}</span></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN upload -->
<form action="{S_ACTION}" method="post" name="form" id="form" enctype="multipart/form-data">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li><a href="{S_UPDATE}">{L_INPUT}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_UPLOAD}</a></li>
	<!-- BEGIN overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END overview -->
</ul>

<br /><div align="center">{ERROR_BOX}</div>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1">{L_UPLOAD}:</td>
	<td class="row2">
		<div><div>
			<input class="post" name="title[]" type="text" id="title[]">&nbsp;<input class="post" name="ufile[]" type="file" id="ufile[]" size="25" />&nbsp;<input class="more" type="button" value="{L_MORE}" onclick="clone(this)">
		</div></div>
	</td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END upload -->

<!-- BEGIN overview -->
<form action="{S_ACTION}" method="post" id="list" name="form">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_UPDATE}">{L_INPUT}</a></li>
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_OVERVIEW}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br />

<table>
<!-- BEGIN list -->
<!-- BEGIN gallery_row -->
<tr>
    <td>
		<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;"><span class="middle">{_overview._list._galleryrow.TITLE}</span></a></li></ul>
		<table>
        <tr>
        	<td><a href="{_overview._list._galleryrow.IMAGE}" rel="lightbox"><img src="{_overview._list._galleryrow.PREV}" alt="" /></a></td>
			<td>
				<table class="update2">
				<tr>
					<td class="row1"><label for="pic_title[{_overview._list._galleryrow.PIC_ID}]">{L_TITLE}:&nbsp;</label></td>
					<td class="row2"><input type="text" class="postsmall" name="pic_title[{_overview._list._galleryrow.PIC_ID}]" id="pic_title[{_overview._list._galleryrow.PIC_ID}]" value="{_overview._list._galleryrow.TITLE}"></td>
				</tr>
				<tr>
					<td class="row1">{L_WIDTH}:&nbsp;</td>
					<td class="row2">{_overview._list._galleryrow.WIDTH}</td>
				</tr>
				<tr>
					<td class="row1">{L_HEIGHT}:&nbsp;</td>
					<td class="row2">{_overview._list._galleryrow.HEIGHT}</td>
				</tr>
				<tr>
					<td class="row1">{L_SIZE}:&nbsp;</td>
					<td class="row2">{_overview._list._galleryrow.SIZE}</td>
				</tr>
				<tr>
					<td class="row1">{L_NAME}:&nbsp;</td>
					<td class="row2">{_overview._list._galleryrow.NAME}</td>
				</tr>
				</table>
			</td>
			<td width="100%">
				<table class="update2">
				<tr>
					<td>Bewertung</td>
				</tr>
				<tr>
					<td>Kommentare</td>
				</tr>
				</table>
			</td>
			<td class="row2" nowrap="nowrap"><input type="text" class="postsmall" name="pic_order[{_overview._list._galleryrow.PIC_ID}]" id="pic_title[{_overview._list._galleryrow.PIC_ID}]" value="{_overview._list._galleryrow.ORDER}" size="2"></td>
			<td class="row2" nowrap="nowrap">{_overview._list._galleryrow.MOVE_UP}{_overview._list._galleryrow.MOVE_DOWN}</td>
			<td class="row2" nowrap="nowrap"><input type="checkbox" name="pics[]" value="{_overview._list._galleryrow.PIC_ID}"></td>
		</tr>
        </table>
    </td>
</tr>
<!-- END gallery_row -->
<!-- END list -->
<!-- BEGIN preview -->
<!-- BEGIN gallery_row -->
<tr>
	<!-- BEGIN gallery_col -->
    <td>
		<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;"><span class="middle">{_overview._preview._galleryrow._gallery_col.TITLE}</span></a></li></ul>
		<table class="update">
        <tr>
			<td class="row3" nowrap="nowrap">
				<table class="update" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2">
						<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%"><a href="{_overview._preview._galleryrow._gallery_col.IMAGE}" rel="lightbox"><img src="{_overview._preview._galleryrow._gallery_col.PREV}" alt="" border="0" /></a></td>
							<td nowrap="nowrap"><input type="text" class="postsmall" name="pic_order[{_overview._preview._galleryrow._gallery_col.PIC_ID}]" id="pic_title[{_overview._preview._galleryrow._gallery_col.PIC_ID}]" value="{_overview._preview._galleryrow._gallery_col.ORDER}" size="2"></td>
							<td nowrap="nowrap">{_overview._preview._galleryrow._gallery_col.MOVE_UP}{_overview._preview._galleryrow._gallery_col.MOVE_DOWN}</td>
							<td><input type="checkbox" name="pics[]" value="{_overview._preview._galleryrow._gallery_col.PIC_ID}"></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="row1">{L_TITLE}:&nbsp;<label for="pic_title[{_overview._preview._galleryrow._gallery_col.PIC_ID}]"></label></td>
					<td class="row2"><input type="text" class="postsmall" name="pic_title[{_overview._preview._galleryrow._gallery_col.PIC_ID}]" id="pic_title[{_overview._preview._galleryrow._gallery_col.PIC_ID}]" value="{_overview._preview._galleryrow._gallery_col.TITLE}" /></td>
				</tr>
				<tr>
					<td class="row1">{L_WIDTH}:&nbsp;</td>
					<td class="row2">{_overview._preview._galleryrow._gallery_col.WIDTH}</td>
				</tr>
				<tr>
					<td class="row1">{L_HEIGHT}:&nbsp;</td>
					<td class="row2">{_overview._preview._galleryrow._gallery_col.HEIGHT}</td>
				</tr>
				<tr>
					<td class="row1">{L_SIZE}:&nbsp;</td>
					<td class="row2">{_overview._preview._galleryrow._gallery_col.SIZE}</td>
				</tr>
				<tr>
					<td class="row1">{L_NAME}:&nbsp;</td>
					<td class="row2">{_overview._preview._galleryrow._gallery_col.NAME}</td>
				</tr>
				</table>
			</td>
		</tr>
        </table>
    </td>
	<!-- END gallery_col -->
</tr>
<!-- END gallery_row -->
<!-- END preview -->
</table>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="49%" align="left">{PAGINATION}</td>
	<td width="49%" align="right">{PAGE_NUMBER}</td>
</tr>
<tr>
	<td colspan="2" align="right"><a class="small" onclick="marklist('list', 'pic', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a class="small" onclick="marklist('list', 'pic', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END overview -->

<!-- BEGIN gallery_resync -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li><li><a href="{S_CREATE}">{L_CREATE}</a></li><li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li></ul>
<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- END gallery_resync -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li><li><a href="{S_CREATE}">{L_CREATE}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.INFO}&nbsp;</span><b>{display.row.NAME}</b><br />{display.row.DESC}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.RESYNC}{display.row.OVERVIEW}{display.row.UPLOAD}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="footer">
<tr>
	<td><input type="text" name="gallery_name"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->