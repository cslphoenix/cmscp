<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
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
	<td class="rowHead" colspan="2">{L_NAME}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td class="{display.game_row.CLASS}" align="center" width="1%">{display.game_row.IMAGE}</td>
	<td class="{display.game_row.CLASS}" align="left" width="99%">{display.game_row.NAME}</td>
	<td class="{display.game_row.CLASS}" align="center" nowrap="nowrap">{display.game_row.MOVE_UP}{display.game_row.MOVE_DOWN}</td>
	<td class="{display.game_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.game_row.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.game_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.game_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_games -->
<tr>
	<td class="row_noentry" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_games -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="game_name"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN games_edit -->
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
	<li><a href="{S_ACTION}" method="post">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
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
	<td class="row1" width="23%"><label for="game_name">{L_NAME}: *</label></td>
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
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END games_edit -->