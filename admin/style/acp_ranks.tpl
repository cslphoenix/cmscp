<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

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
	if ( value == '1' || value == '3' )
	{
		dE('rank_special', -1);
		dE('rank_min', -1);
		dE('rank_standard', 1);
	}
	else if ( value == '2' )
	{
		dE('rank_special', 1);
		dE('rank_min', 1);
		dE('rank_standard', -1);
	}
	else if ( value == '4' )
	{
		dE('rank_min', -1);
	}
	else if ( value == '5' )
	{
		dE('rank_min', 1);
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

<!-- BEGIN typ -->
<table class="rows">
<tr>
	<th>{display.typ.RANKS}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.typ.row.SPECIAL}</span>{display.typ.row.NAME}</td>
	<td>{display.typ.row.IMAGE}{display.typ.row.MOVE_DOWN}{display.typ.row.MOVE_UP}{display.typ.row.UPDATE}{display.typ.row.DELETE}</td>
</tr>
<!-- END row -->
</table>
<!-- BEGIN br_empty -->
<br />
<!-- END br_empty -->
<!-- END typ -->

<!-- BEGIN no_entry -->
<table class="rows">
<tr>
	<th>{display.no_entry.RANKS}</th>
	<th>{L_SETTINGS}</th>
</tr>
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
</table>
<!-- END no_entry -->

<table class="lfooter">
<tr>
	<td><input type="text" name="rank_name"></td>
	<td><input type="submit" class="button2" name="rank_type" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->