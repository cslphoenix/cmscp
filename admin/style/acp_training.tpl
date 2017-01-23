<li class="header">{L_HEADER}<span class="right">{L_OPTION}</span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_listmaps.js"></script>
<form action="{S_ACTION}" method="post">
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
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navopts"><li>{L_SORT}: {S_SORT}</li></ul>
</form>

<br />

<table class="rows">
<tr>
	<th>{L_UPCOMING}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN new_row -->
<tr>
	<td><span class="right">{display.new_row.DATE}</span>{display.new_row.GAME} {display.new_row.NAME}</td>
	<td>{display.new_row.MEMBER}{display.new_row.UPDATE}{display.new_row.DELETE}</td>		
</tr>
<!-- END new_row -->
<!-- BEGIN new_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END new_empty -->
</table>

<form action="{S_ACTION}" method="post">
<table class="lfooter">
<tr>
	<td><input type="text" name="training_vs" /></td>
	<td>{S_TEAM}</td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>

<br />

<table class="rows">
<tr>
	<th>{L_EXPIRED}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN old_row -->
<tr>
	<td><span class="right">{display.old_row.DATE}</span>{display.old_row.GAME} {display.old_row.NAME}</td>
	<td>{display.old_row.MEMBER}{display.old_row.UPDATE}{display.old_row.DELETE}</td>		
</tr>
<!-- END old_row -->
<!-- BEGIN old_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END old_empty -->
</table>

<form action="{S_ACTION}" method="post">
<table class="rfooter">
<tr>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN member -->
{AJAX}
{ERROR_BOX}
<form action="{S_ACTION}" method="post" name="post" id="list">
<fieldset>
	<legend>{L_PLAYER}</legend>
	<table class="users">
	<tr>
		<th colspan="2">{L_PLAYER}</th>
		<th>{L_CREATE}</th>
		<th>{L_UPDATE}</th>
		<th>&nbsp;</th>
	</tr>
	<!-- BEGIN row -->
	<tr onclick="checked({member.row.ID})" class="hover">
		<td>{member.row.NAME}</td>
        <td>{member.row.STATUS}</td>
		<td>{member.row.CREATE}</td>
		<td>{member.row.UPDATE}</td>
		<td><input type="checkbox" name="members[]" value="{member.row.ID}" id="check_{member.row.ID}"></td>
	</tr>
	<!-- END row -->
	<!-- BEGIN no_row -->
	<tr>
		<td class="empty" colspan="4" align="center">{L_NO_PLAYER}</td>
	</tr>
	<!-- END no_row -->
	</table>
	</fieldset>
	
    <table class="footer2">
    <tr>
        <td rowspan="2" width="150%">{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
        <td>{S_OPTIONS}</td>
        <td><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
    </tr>
    <tr>
        <td colspan="2"><a href="#" onclick="marklist('list', 'members', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'members', false); return false;">{L_MARK_DEALL}</a></td>
    </tr>
    </table>
    {S_FIELDS}
</form>

<form action="{S_ACTION}" method="post" name="post" id="list">
<fieldset>
	<legend>{L_PLAYER_ADD}</legend>
	<table class="update" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1"><label for="user_status" title="{L_PLAYER_STATUS}">{L_PLAYER_STATUS}:</label></td>
		<td class="row2"><label><input type="radio" name="user_status" id="user_status" checked="checked" value="1" />&nbsp;{L_SY}</label><span style="padding:4px;"></span><label><input type="radio" name="user_status" value="2" />&nbsp;{L_SN}</label><span style="padding:4px;"></span><label><input type="radio" name="user_status" value="3" />&nbsp;{L_SR}</label></td>
	</tr>
	<tr>
		<td class="row1"><label for="rank_id">{L_USERNAME}:</label></td>
		<td>{S_ADDED}</td>
	</tr>
	</table>
</fieldset>
<table class="rfooter">
<tr>
	<td align="right"><input type="submit" class="button2" value="{L_SUBMIT}" /></td>
</tr>
</table>
{S_FIELDS}
<input type="hidden" name="smode" value="create" />
</form>
<!-- END member -->