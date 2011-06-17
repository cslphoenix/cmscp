<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
		<li><a href="{S_CREATE}">{L_CREATE}</a></li>
		<li><a id="setting" href="{S_DEFAULT}">{L_DEFAULT}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN _gallery_row -->
<tr>
	<td><span style="float:right;">{_display._gallery_row.INFO}&nbsp;</span><b>{_display._gallery_row.NAME}</b><br />{_display._gallery_row.DESC}</td>
	<td>{_display._gallery_row.MOVE_UP}{_display._gallery_row.MOVE_DOWN}{_display._gallery_row.RESYNC} {_display._gallery_row.VIEW}{_display._gallery_row.UPLOAD} {_display._gallery_row.UPDATE} {_display._gallery_row.DELETE}</td>
</tr>
<!-- END _gallery_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="footer">
<tr>
	<td></td>
	<td><input type="text" class="post" name="gallery_name"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	<!-- BEGIN _upload -->
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<!-- END _upload -->
	<!-- BEGIN _overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END _overview -->
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tr>
	<td class="row1" width="155"><label for="gallery_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="gallery_name" id="gallery_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_AUTH}:</label></td>
	<td class="row3">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _auth -->
		<tr>
			<td nowrap="nowrap">{_input._auth.SELECT}</td>
			<td width="99%">&nbsp;<label for="{_input._auth.INFO}">{_input._auth.TITLE}</label></td>
		</tr>
		<!-- END _auth -->
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
	<td class="row1"><label>{L_DESC}: *</label></td>
	<td class="row2"><textarea class="textarea" name="gallery_desc" rows="5" style="width:50%">{DESC}</textarea></td>
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
<form action="{S_ACTION}" method="post" name="form" id="form" enctype="multipart/form-data">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="{S_UPDATE}">{L_INPUT}</a></li>
	<li id="active"><a href="#" id="current">{L_UPLOAD}</a></li>
	<!-- BEGIN _overview -->
	<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
	<!-- END _overview -->
</ul>
</div>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1">{L_UPLOAD}:</td>
	<td class="row3">
		<div><div>
			
			<input class="post" name="title[]" type="text" id="title[]">&nbsp;<input class="post" name="ufile[]" type="file" id="ufile[]" size="25" />&nbsp;<input class="button2" type="button" value="mehr"onclick="clone(this)">
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
	<li><a href="{S_UPDATE}">{L_INPUT}</a></li>
	<li><a href="{S_UPLOAD}">{L_UPLOAD}</a></li>
	<li id="active"><a href="#" id="current">{L_OVERVIEW}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
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
        <tr class="hover">
        	<td class="row1"><a href="{_overview._list._gallery_row.IMAGE}" class="lightbox"><img src="{_overview._list._gallery_row.PREV}" alt="" border="0" /></a></td>
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
					<td class="small" align="right">{L_NAME}:&nbsp;</td>
					<td class="small" align="left">{_overview._list._gallery_row.NAME}</td>
				</tr>
				</table>
			</td>
			<td class="row2" width="100%">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>Bewertung</td>
				</tr>
				<tr>
					<td>Kommentare</td>
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
					<td class="small" align="right">{L_TITLE}:&nbsp;<label for="pic_title[{_overview._preview._gallery_row._gallery_col.PIC_ID}]"></label></td>
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
					<td class="small" align="right">{L_NAME}:&nbsp;</td>
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
	<td colspan="2" align="right"><a class="small" onclick="marklist('list', 'pic', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a class="small" onclick="marklist('list', 'pic', false); return false;">{L_MARK_DEALL}</a></td>
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

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
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

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
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
	<td class="row1"><label>{L_AUTH}:</label></td>
	<td class="row3">
		<table class="update" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _auth_gallery -->
		<tr>
			<td nowrap="nowrap">{_default._auth_gallery.S_DROP}</td>
			<td width="99%">&nbsp;<label for="{_default._auth_gallery.FIELD}">{_default._auth_gallery.L_NAME}</label></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _default -->