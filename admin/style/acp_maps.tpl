<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>

<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">

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
		dE('main', -1);
		dE('map_tag', 1);
		dE('map_info', -1);
		dE('map_file', -1);
	}
	else
	{
		dE('main', 1);
		dE('map_tag', -1);
		dE('map_info', 1);
		dE('map_file', 1);
	}
}

<!-- BEGIN update_image -->
function update_image_{input.update_image.NAME}(newimage)
{
	document.getElementById('image').src = (newimage) ? "{input.update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}

function update_ajax_{input.update_image.NAME}(newimage)
{
	document.getElementById('image2').src = (newimage) ? "{input.update_image.PATH}/" + encodeURI(newimage) : "./../admin/style/images/spacer.gif";
}
<!-- END update_image -->

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

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN upload -->
{ERROR_BOX}
<br /><div align="center">{ERROR_BOX_PLAYER}</div>
<form action="{S_ACTION}" method="post" enctype="multipart/form-data">
<fieldset>
	<legend>{L_MAPS_PIC}</legend>

    <dl>
        <dt>{L_MAP}:</dt>
        <dd><div><div><input class="post" name="map_name[]" type="text" id="map_name[]">&nbsp;<input type="file" name="ufile[]" id="ufile[]">&nbsp;<input type="button" class="more" value="{L_MORE}" onclick="clone(this)"></div></div></dd>
    </dl>

</fieldset>
<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_UPLOAD}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}

</form>
<!-- END upload -->

<!-- BEGIN list -->
<form action="{S_ACTION}" method="post">

{ERROR_BOX}

<table class="rows2">
<tr>
	<!-- BEGIN name_option -->
	<th>{list.name_option.NAME}</th>
	<!-- END name_option -->
</tr>
<!-- BEGIN row -->
<tr>
	<!-- BEGIN type_option -->
	<td>{list.row.type_option.TYPE}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>


<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END list -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{display.row.INFO}</span>{display.row.NAME}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}&nbsp;{display.row.UPDATE}&nbsp;{display.row.DELETE}</td>
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
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->