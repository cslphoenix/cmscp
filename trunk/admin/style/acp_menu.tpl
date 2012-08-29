<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

var request = false;

function setRequest(value, meta, name)
{
	if ( window.XMLHttpRequest ) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/ajax_main.php";
		
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('type='+value+'&meta='+meta+'&name='+name);
		request.onreadystatechange = interpretRequest;
	}
}

function interpretRequest()
{
	switch (request.readyState)
	{
		case 4:
		
			if (request.status != 200)
			{
				alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status);
			}
			else
			{
				var content = request.responseText;
				document.getElementById('ajax_content').innerHTML = content;
			}
			break;
		
		default: document.getElementById('close').style.display = "none"; break;
	}
}

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else if ( value == '1' )
	{
		dE('main', 1);
		dE('menu_file', -1);
		dE('menu_opts', -1);
	}
	else
	{
		dE('main', 1);
		dE('menu_file', 1);
		dE('menu_opts', 1);
	}
}

function display_modes(value)
{
//	alert(value);
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
// ]]>
</script>
<form action="{S_ACTION}" method="post">
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

<!-- BEGIN list -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th>{UPDATE}{DELETE}</th>
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
	<th>{list.row.NAME}</th>
	<th>{list.row.MOVE_DOWN}{list.row.MOVE_UP}{list.row.UPDATE}{list.row.DELETE}</th>
</tr>
<!-- BEGIN mod -->
<tr>
	<td>{list.row.mod.NAME}</td>
	<td>{list.row.mod.MOVE_DOWN}{list.row.mod.MOVE_UP}{list.row.mod.UPDATE}{list.row.mod.DELETE}</td>
</tr>
<!-- END mod -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{list.row.S_NAME}" /></td>
	<td><input type="submit" name="{list.row.S_SUBMIT}" value="{L_CREATE_MODULE}" /></td>
</tr>
</table>
<br />
<!-- END row -->
{S_FIELDS}
</form>
<!-- END list -->


<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">

<table class="rows">
<!-- BEGIN cat -->
<tr>
	<th>{display.cat.NAME}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- END cat -->
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