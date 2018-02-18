<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('menu_show', 1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else if ( value == '1' )
	{
		dE('main', 1);
		dE('menu_show', 1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else if ( value == '2' )
	{
		dE('main', 1);
		dE('menu_show', 1);
		dE('menu_file', 1);
		dE('menu_opts', 1);
	}
	else if ( value == '3' )
	{
		dE('main', -1);
		dE('menu_show', 1);
		dE('menu_file', -1);		
		dE('menu_target', -1);
		dE('menu_intern', -1);
	}
	else if ( value == '4' )
	{
		dE('main', 1);
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
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
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
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
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
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{S_NAME}" /></td>
	<td><input type="submit" name="{S_SUBMIT}" value="{CREATE}" /></td>
</tr>
</table>

<br />

<table class="rows">
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}</td>
	<td>{display.row.UPDATE}&nbsp;{display.row.MOVE_DOWN}{display.row.MOVE_UP}&nbsp;{display.row.DELETE}</td>
</tr>
<!-- BEGIN subrow -->
<tr>
	<td><span style="padding:0px 8px;"></span>{display.row.subrow.NAME}</td>
	<td><span style="padding:0px 8px;"></span>{display.row.subrow.UPDATE}&nbsp;{display.row.subrow.MOVE_DOWN}{display.row.subrow.MOVE_UP}&nbsp;{display.row.subrow.DELETE}</td>
</tr>
<!-- END subrow -->
<!-- BEGIN br_empty -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{display.row.S_NAME}" /></td>
	<td><input type="submit" name="{display.row.S_SUBMIT}" value="{L_CREATE_MODULE}" /></td>
</tr>
</table>
<br />
<table class="rows">
<!-- END br_empty -->
<!-- BEGIN br_empty2 -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{display.row.S_NAME}" /></td>
	<td><input type="submit" name="{display.row.S_SUBMIT}" value="{L_CREATE_MODULE}" /></td>
</tr>
<!-- END br_empty2 -->
<!-- END row -->
</table>

<!-- BEGIN none -->
<table class="rows">
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
</table>
<!-- END none -->
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN navigation -->
<form action="{S_ACTION}" method="post">

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{navigation.row.NAME}</td>
	<td>{navigation.row.UPDATE}&nbsp;{navigation.row.MOVE_DOWN}{navigation.row.MOVE_UP}&nbsp;{navigation.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{S_CREATE}" /></td>
	<td><input type="submit" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END navigation -->