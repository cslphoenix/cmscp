<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
}

function rank_posts(row)
{
	if ( row == '1' || row == '3' )
	{
		document.getElementById('rank_min').style.display = "none";
	}
	else if ( row == '2' )
	{
		document.getElementById('rank_min').style.display = "";
	}
}

function display_options(value)
{
	if ( value == '1' )
	{
		dE('rank_special', -1);
		dE('rank_min', -1);
		de('rank_standard', 1);
	}
	else if ( value == '2' )
	{
		dE('rank_special', 1);
		dE('rank_min', 1);
		de('rank_standard', -1);
	}
	else if ( value == '3' )
	{
		dE('rank_special', -1);
		dE('rank_min', -1);
		de('rank_standard', 1);
	}
}

// ]]>
</script>
{AJAX}
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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="rows">
<tr>
	<th>{L_PAGE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN page_row -->
<tr>
	<td><span class="right">{display.page_row.STANDARD}</span>{display.page_row.NAME}</td>
	<td>{display.page_row.IMAGE}{display.page_row.MOVE_DOWN}{display.page_row.MOVE_UP}{display.page_row.UPDATE}{display.page_row.DELETE}</td>
</tr>
<!-- END page_row -->
<!-- BEGIN page_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END page_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[1]"></td>
	<td><input type="submit" class="button2" name="rank_type[1]" value="{L_CREATE_PAGE}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_FORUM}</th>
	<th>{L_SPECIAL}</th>
	<th>{L_MIN}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN forum_row -->
<tr>
	<td>{display.forum_row.NAME}</td>
	<td>{display.forum_row.SPECIAL}</td>
	<td>{display.forum_row.MIN}</td>
	<td>{display.forum_row.IMAGE}{display.forum_row.MOVE_DOWN}{display.forum_row.MOVE_UP}{display.forum_row.UPDATE}{display.forum_row.DELETE}</td>
</tr>
<!-- END forum_row -->
<!-- BEGIN forum_empty -->
<tr>
	<td class="empty" colspan="4">{L_EMPTY}</td>
</tr>
<!-- END forum_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[2]"></td>
	<td><input type="submit" class="button2" name="rank_type[2]" value="{L_CREATE_FORUM}"></td>
</tr>
</table>

<br />

<table class="rows">
<tr>
	<th>{L_TEAM}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td><span class="right">{display.team_row.STANDARD}</span>{display.team_row.NAME}</td>
	<td>{display.team_row.IMAGE}{display.team_row.MOVE_DOWN}{display.team_row.MOVE_UP}{display.team_row.UPDATE}{display.team_row.DELETE}</td>
</tr>
<!-- END team_row -->
<!-- BEGIN team_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END team_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name[3]"></td>
	<td><input type="submit" class="button2" name="rank_type[3]" value="{L_CREATE_TEAM}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->