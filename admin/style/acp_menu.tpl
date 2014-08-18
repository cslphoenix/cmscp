<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('menu_lang', 1);
		dE('menu_show', 1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else if ( value == '1' )
	{
		dE('main', 1);
		dE('menu_lang', 1);
		dE('menu_show', 1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else if ( value == '2' )
	{
		dE('main', 1);
		dE('menu_lang', 1);
		dE('menu_show', 1);
		dE('menu_file', 1);
		dE('menu_opts', 1);
	}
	else if ( value == '3' )
	{
		dE('main', -1);
		dE('menu_lang', 1);
		dE('menu_show', 1);
		dE('menu_file', -1);		
		dE('menu_target', -1);
		dE('menu_intern', -1);
	}
	else if ( value == '4' )
	{
		dE('main', 1);
		dE('menu_lang', 1);
		dE('menu_show', 1);
		dE('menu_file', 1);		
		dE('menu_target', 1);
		dE('menu_intern', 1);
	}
}

function display_modes(value)
{
	// Find the old select tag
	var item = document.getElementById('menu_menu_opts');

	// Create the new select tag
	var new_node = document.createElement('select');
	new_node.setAttribute('id', 'menu_menu_opts');
	new_node.setAttribute('name', 'menu[menu_opts]');

	// Substitute it for the old one
	item.parentNode.replaceChild(new_node, item);

	// Reset the variable
	item = document.getElementById('menu_menu_opts');

	var j = 0;

	<!-- BEGIN m_names -->
	if (value == '{input.m_names.A_NAME}')
	{
		<!-- BEGIN modes -->
		item.options[j] = new Option('{input.m_names.modes.A_OPTION}');
		item.options[j].value = '{input.m_names.modes.A_VALUE}';
		j++;
		<!-- END modes -->
	}
	
	<!-- END m_names -->
	// select first item
	item.options[0].selected = true;
}

</script>
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

<dl class="submit">
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN menu -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th><span class="right">{UPDATE}{DELETE}</span></th>
</tr>
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="menu_label" /></td>
	<td><input type="submit" value="{L_CREATE_LABEL}"></td>
</tr>
</table>
<br />
<!-- BEGIN row -->
<table class="rows">
<tr>
	<th>{menu.row.NAME}</th>
	<th>{menu.row.MOVE_DOWN}{menu.row.MOVE_UP}{menu.row.UPDATE}{menu.row.DELETE}</th>
</tr>
<!-- BEGIN mod -->
<tr>
	<td>{menu.row.mod.NAME}</td>
	<td>{menu.row.mod.MOVE_DOWN}{menu.row.mod.MOVE_UP}{menu.row.mod.UPDATE}{menu.row.mod.DELETE}</td>
</tr>
<!-- END mod -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{menu.row.S_NAME}" /></td>
	<td><input type="submit" name="{menu.row.S_SUBMIT}" value="{L_CREATE_MODULE}" /></td>
</tr>
</table>
<br />
<!-- END row -->
{S_FIELDS}
</form>
<!-- END menu -->

<!-- BEGIN navi -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th><span class="right">{UPDATE}{DELETE}</span></th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{navi.row.NAME}</td>
	<td>{navi.row.MOVE_DOWN}{navi.row.MOVE_UP}{navi.row.UPDATE}{navi.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="profile_field" /></td>
	<td><input type="submit" value="{L_CREATE_FIELD}" /></td>
</tr>
</table>
<br />

{S_FIELDS}
</form>
<!-- END navi -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="menu_name" /></td>
	<td><input type="submit" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->