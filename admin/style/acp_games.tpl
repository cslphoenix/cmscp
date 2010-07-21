<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
<!-- BEGIN _game_row -->
<tr>
	<td class="row_class1" align="left">{_display._game_row.IMAGE} {_display._game_row.NAME}</td>
	<td class="row_class2" align="center">{_display._game_row.MOVE_UP}{_display._game_row.MOVE_DOWN} <a href="{_display._game_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._game_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _game_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="2">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="game_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{IMAGE_PATH}" + encodeURI(newimage) : "{IMAGE_DEFAULT}";
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
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
				<li id="active"><a href="#" id="current">{L_INFOS}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="150"><label for="game_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="game_name" id="game_name" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_IMAGE}:</td>
	<td class="row2 top">{IMAGE_LIST}<br /><img src="{IMAGE}" id="image" alt="" />
	</td>
</tr>
<tr>
	<td class="row1"><label for="game_size">{L_SIZE}:</label></td>
	<td class="row2"><input type="text" class="post" name="game_size" id="game_size" value="{SIZE}" size="2"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->