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
		var url = "ajax/ajax_path.php";
		
		request.open('post', url, true);
		
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		
		request.send('cat='+value);
		
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
		dE('map_tag', 1);
		dE('main', -1);
		dE('map_info', -1);
		dE('map_file', -1);
	}
	else
	{
		dE('map_tag', -1);
		dE('main', 1);
		dE('map_info', 1);
		dE('map_file', 1);
	}
}

<!-- BEGIN update_image -->
function update_image_{input.update_image.NAME}(newimage)
{
	document.getElementById('image').src = (newimage) ? "{input.update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}
<!-- END update_image -->

<!-- BEGIN update_image -->
function update_ajax_{input.update_image.NAME}(newimage)
{
	document.getElementById('image2').src = (newimage) ? "{input.update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}
<!-- END update_image -->


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
	<th>{NAME} :: {TAG}</th>
	<th>{UPDATE}{DELETE}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{list.row.FILE} :: {list.row.INFO}</span>{list.row.NAME}</td>
	<td>{list.row.MOVE_DOWN}{list.row.MOVE_UP}{list.row.UPDATE}{list.row.DELETE}</td>
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
	<td><input type="text" name="map_name" /></td>
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END list -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<!-- BEGIN cat -->
<tr>
	<th><span class="right">{display.cat.TAG}</span>{display.cat.NAME}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- END cat -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="map_name" /></td>
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->