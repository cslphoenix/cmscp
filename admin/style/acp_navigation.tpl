<!-- BEGIN input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
<!-- END tab -->
</div>
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
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<th>{L_MAIN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN main_row -->
<tr>
	<td><span class="right">{display.main_row.URL}</span>{display.main_row.NAME}</td>
	<td>{display.main_row.LANG}{display.main_row.SHOW}{display.main_row.INTERN}{display.main_row.MOVE_DOWN}{display.main_row.MOVE_UP}{display.main_row.UPDATE}{display.main_row.DELETE}</td>
</tr>
<!-- END main_row -->
<!-- BEGIN no_entry_main -->
<tr>
	<td class="row_class1" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_main -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[1]"></td>
	<td><input type="submit" class="button2" name="navi_type[1]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_CLAN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN clan_row -->
<tr>
	<td><span class="right">{display.clan_row.URL}</span>{display.clan_row.NAME}</td>
	<td>{display.clan_row.LANG}{display.clan_row.SHOW}{display.clan_row.INTERN}{display.clan_row.MOVE_DOWN}{display.clan_row.MOVE_UP}{display.clan_row.UPDATE}{display.clan_row.DELETE}</td>
</tr>
<!-- END clan_row -->
<!-- BEGIN no_entry_clan -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_clan -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[2]"></td>
	<td><input type="submit" class="button2" name="navi_type[2]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
	
<table class="rows">
<tr>
	<th>{L_COM}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN com_row -->
<tr>
	<td><span class="right">{display.com_row.URL}</span>{display.com_row.NAME}</td>
	<td>{display.com_row.LANG}{display.com_row.SHOW}{display.com_row.INTERN}{display.com_row.MOVE_DOWN}{display.com_row.MOVE_UP}{display.com_row.UPDATE}{display.com_row.DELETE}</td>
</tr>
<!-- END com_row -->
<!-- BEGIN no_entry_com -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_com -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[3]"></td>
	<td><input type="submit" class="button2" name="navi_type[3]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_MISC}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN misc_row -->
<tr>
	<td><span class="right">{display.misc_row.URL}</span>{display.misc_row.NAME}</td>
	<td>{display.misc_row.LANG}{display.misc_row.SHOW}{display.misc_row.INTERN}{display.misc_row.MOVE_DOWN}{display.misc_row.MOVE_UP}{display.misc_row.UPDATE}{display.misc_row.DELETE}</td>
</tr>
<!-- END misc_row -->
<!-- BEGIN no_entry_misc -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_misc -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[4]"></td>
	<td><input type="submit" class="button2" name="navi_type[4]" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_USER}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN user_row -->
<tr>
	<td><span class="right">{display.user_row.URL}</span>{display.user_row.NAME}</td>
	<td>{display.user_row.LANG}{display.user_row.SHOW}{display.user_row.INTERN}{display.user_row.MOVE_DOWN}{display.user_row.MOVE_UP}{display.user_row.UPDATE}{display.user_row.DELETE}</td>
</tr>
<!-- END user_row -->
<!-- BEGIN no_entry_user -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END no_entry_user -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="navi_name[5]"></td>
	<td><input type="submit" class="button2" name="navi_type[5]" value="{L_CREATE}"></td>
</tr>
</table>

<br />
{S_FIELDS}
</form>
<!-- END display -->