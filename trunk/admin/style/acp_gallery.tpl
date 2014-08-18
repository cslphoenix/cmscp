<li class="header">{L_HEAD}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('copy', 1);
		dE('gallery_acpview', 1);
		dE('gallery_picture', -1);
		dE('gallery_filesize', 1);
		dE('gallery_dimension', 1);
		dE('gallery_format', 1);
		dE('gallery_thumbnail', 1);
	}
	else
	{
		dE('main', 1);
		dE('copy', -1);
		dE('gallery_acpview', -1);
		dE('gallery_picture', 1);
		dE('gallery_filesize', -1);
		dE('gallery_dimension', -1);
		dE('gallery_format', -1);
		dE('gallery_thumbnail', -1);
	}
}

</script>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
<!-- BEGIN option -->
{input.row.tab.option.DIV_START}
<dl>			
	<dt class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
{input.row.tab.option.DIV_END}
<!-- END option -->
</fieldset>
<!-- END tab -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
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

{ERROR_BOX}

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
<script type="text/javascript" src="./../includes/js/jquery/jRating.jquery.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
	<!-- BEGIN info -->
	$(".rate_{overview.info.ID}").jRating({
		bigStarsPath : './../images/jquery/stars.png',
		smallStarsPath : './../images/jquery/small.png',
		length:{RATE_LENGTH},
		step:{RATE_STEP},
		rateMax:{RATE_MAX},
		type:'{RATE_TYPE}',
		showRateInfo:false,
		isDisabled:true,
	});
	<!-- END info -->
});

</script>
<form action="{S_ACTION}" method="post" name="form" id="form" enctype="multipart/form-data">
{ERROR_BOX}

<li class="header">{L_UPLOAD_DATA}</li>
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

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<br />
<form action="{S_ACTION}" method="post" id="list" name="form">
<table class="update">
<!-- BEGIN list -->
<!-- BEGIN gallery_row -->
<tr>
    <td>
		<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;"><span class="middle">{overview.list.gallery_row.TITLE}</span></a></li></ul>
		<table class="none">
        <tr>
        	<td width="1%"><a href="{overview.list.gallery_row.IMAGE}" rel="lightbox"><img src="{overview.list.gallery_row.PREV}" alt="" /></a></td>
			<td width="5%">
				<table class="none">
				<tr>
					<td><label for="pic_title[{overview.list.gallery_row.PIC_ID}]">{L_TITLE}:&nbsp;</label></td>
					<td><input type="text" class="postsmall" name="pic_title[{overview.list.gallery_row.PIC_ID}]" id="pic_title[{overview.list.gallery_row.PIC_ID}]" value="{overview.list.gallery_row.TITLE}"></td>
				</tr>
				<tr>
					<td>{L_WIDTH}:&nbsp;</td>
					<td>{overview.list.gallery_row.WIDTH}</td>
				</tr>
				<tr>
					<td>{L_HEIGHT}:&nbsp;</td>
					<td>{overview.list.gallery_row.HEIGHT}</td>
				</tr>
				<tr>
					<td>{L_SIZE}:&nbsp;</td>
					<td>{overview.list.gallery_row.SIZE}</td>
				</tr>
				<tr>
					<td>{L_NAME}:&nbsp;</td>
					<td>{overview.list.gallery_row.NAME}</td>
				</tr>
				</table>
			</td>
			<td width="94%" valign="top">
				<table class="none">
					<tr>
						<td width="10%" align="right">{L_RATING}:</td>
						<td>{overview.list.gallery_row.RATING}</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><div class="rate_{overview.list.gallery_row.PIC_ID}" data-average="{overview.list.gallery_row.R_SUM}" data-id="{overview.list.gallery_row.PIC_ID}" data-type="3"></div></td>
						
					</tr>
					<tr>
						<td>{L_COMMENT}:</td>
						<td>{overview.list.gallery_row.COMMENT}</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					</table>
			</td>
			<td class="row2" nowrap="nowrap">{overview.list.gallery_row.MOVE_DOWN}{overview.list.gallery_row.MOVE_UP}</td>
			<td class="row2" nowrap="nowrap"><input type="checkbox" name="pics[]" value="{overview.list.gallery_row.PIC_ID}"></td>
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
		<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;"><span class="middle">{overview.preview.gallery_row.gallery_col.TITLE}</span></a></li></ul>
		<table class="none">
        <tr>
			<td class="row3" nowrap="nowrap">
				<table class="none">
				<tr>
					<td colspan="2">
						<table class="none">
						<tr>
							<td width="100%"><a href="{overview.preview.gallery_row.gallery_col.IMAGE}" rel="lightbox"><img src="{overview.preview.gallery_row.gallery_col.PREV}" alt="" border="0" /></a></td>
							<td nowrap="nowrap"><input type="text" class="postsmall" name="pic_order[{overview.preview.gallery_row.gallery_col.PIC_ID}]" id="pic_title[{overview.preview.gallery_row.gallery_col.PIC_ID}]" value="{overview.preview.gallery_row.gallery_col.ORDER}" size="2"></td>
							<td nowrap="nowrap">{overview.preview.gallery_row.gallery_col.MOVE_DOWN}{overview.preview.gallery_row.gallery_col.MOVE_UP}</td>
							<td><input type="checkbox" name="pics[]" value="{overview.preview.gallery_row.gallery_col.PIC_ID}"></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>{L_TITLE}:&nbsp;<label for="pic_title[{overview.preview.gallery_row.gallery_col.PIC_ID}]"></label></td>
					<td><input type="text" class="postsmall" name="pic_title[{overview.preview.gallery_row.gallery_col.PIC_ID}]" id="pic_title[{overview.preview.gallery_row.gallery_col.PIC_ID}]" value="{overview.preview.gallery_row.gallery_col.TITLE}" /></td>
				</tr>
				<tr>
					<td>{L_WIDTH}:&nbsp;</td>
					<td>{overview.preview.gallery_row.gallery_col.WIDTH}</td>
				</tr>
				<tr>
					<td>{L_HEIGHT}:&nbsp;</td>
					<td>{overview.preview.gallery_row.gallery_col.HEIGHT}</td>
				</tr>
				<tr>
					<td>{L_SIZE}:&nbsp;</td>
					<td>{overview.preview.gallery_row.gallery_col.SIZE}</td>
				</tr>
				<tr>
					<td>{L_NAME}:&nbsp;</td>
					<td>{overview.preview.gallery_row.gallery_col.NAME}</td>
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
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
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

<div class="submit">
<dl>
	<dt><input type="submit" name="update" value="{L_UPDATE}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
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