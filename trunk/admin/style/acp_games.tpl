<!-- BEGIN display -->
<form action="{S_GAME_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GAME_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_GAME_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_GAME_TEAM}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td class="{display.game_row.CLASS}" align="center" width="1%">{display.game_row.I_IMAGE}</td>
	<td class="{display.game_row.CLASS}" align="left">{display.game_row.NAME}</td>
	<td class="{display.game_row.CLASS}" align="center" width="1%"><a href="{display.game_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.game_row.CLASS}" align="center" width="6%"><a href="{display.game_row.U_MOVE_UP}">{display.game_row.ICON_UP}</a> <a href="{display.game_row.U_MOVE_DOWN}">{display.game_row.ICON_DOWN}</a></td>
	<td class="{display.game_row.CLASS}" align="center" width="1%"><a href="{display.game_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_games -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GAMES}</td>
</tr>
<!-- END no_games -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="game_name" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_GAME_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN games_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{GAMES_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_GAME_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GAME_ACTION}">{L_GAME_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_GAME_NEW_EDIT}</a></li>
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
	<td class="row1" width="20%">{L_GAME_NAME}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="game_name" value="{GAME_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_GAME_IMAGE}:</td>
	<td class="row2">{S_FILENAME_LIST}&nbsp;<img src="{GAME_IMAGE}" id="image" alt="" />
	</td>
</tr>

<tr>
	<td class="row1">{L_GAME_SIZE}:</td>
	<td class="row2"><input class="post" type="text" name="game_size" value="{GAME_SIZE}" ></td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END games_edit -->