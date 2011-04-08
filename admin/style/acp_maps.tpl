<!-- BEGIN _display -->
<script type="text/javascript">  

/*
	Einfacher Klapptext, wird mit jquery noch erweitert!
*/

function clip(id)
{
	if ( document.getElementById("tbody_" + id).style.display == 'none' )
	{
		document.getElementById("img_" + id).src = "style/collapse.gif";
		document.getElementById("tbody_" + id).style.display = "";
	}
	else
	{
		document.getElementById("img_" + id).src = "style/expand.gif";
		document.getElementById("tbody_" + id).style.display = "none";
	}
}

</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>
<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" class="post" name="cat_name" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%" align="left" onclick="clip('{_display._cat_row.CAT_ID}')"><!--<img src="style/expand.gif" id="img_{_display._cat_row.CAT_ID}" width="9" height="9" border="0" />-->{_display._cat_row.CAT_NAME}<span style="float:right;">{_display._cat_row.CAT_TAG}</span></td>
	<td class="rowHead" align="center" nowrap="nowrap">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} <a href="{_display._cat_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- <tbody id="tbody_{_display._cat_row.CAT_ID}" style="display:none"> -->
<!-- BEGIN _map_row -->
<tr>
	<td class="row_class1"><span class="gen">{_display._cat_row._map_row.MAP_NAME}</span><span style="float:right;">{_display._cat_row._map_row.MAP_FILE} :: {_display._cat_row._map_row.MAP_TYPE}</span></td>
	<td class="row_class2" align="center" valign="middle" nowrap="nowrap">{_display._cat_row._map_row.MOVE_UP}{_display._cat_row._map_row.MOVE_DOWN} <a href="{_display._cat_row._map_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row._map_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _map_row -->
<!-- </tbody> -->
</table>

<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" class="post" name="{_display._cat_row.S_NAME}" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="{_display._cat_row.S_SUBMIT}" value="{L_CREATE_MAP}"></td>
</tr>
</table>

<br />
<!-- END _cat_row -->
</form>
<!-- END _display -->

<!-- BEGIN _input_cat -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="cat_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_tag">{L_TAG}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_tag" id="cat_tag" value="{TAG}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input_cat -->

<!-- BEGIN _input -->
<script type="text/javascript">

var request = false;

// Request senden
function setRequest(value)
{
	// Request erzeugen
	if ( window.XMLHttpRequest )
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		request = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// überprüfen, ob Request erzeugt wurde
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "./ajax/ajax_maps.php";
		// Request öffnen
		request.open('post', url, true);
		// Requestheader senden
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		// Request senden
		request.send('name='+value);
	//	request.send("name="+value+"&option="+option);
		// Request auswerten
		request.onreadystatechange = interpretRequest;
	}
}

// Request auswerten
function interpretRequest()
{
	switch (request.readyState)
	{
		// wenn der readyState 4 und der request.status 200 ist, dann ist alles korrekt gelaufen
		case 4:
			
			if (request.status != 200)
			{
				alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status);
			}
			else
				{
				var content = request.responseText;
				// den Inhalt des Requests in das <div> schreiben
				document.getElementById('content').innerHTML = content;
			}
			break;
			
		default: document.getElementById('close').style.display = "none"; break;
	}
}

// Request senden
function setRequest2(value)
{
	// Request erzeugen
	if ( window.XMLHttpRequest )
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		request = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// überprüfen, ob Request erzeugt wurde
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "./ajax/ajax_subforum.php";
		// Request öffnen
		request.open('post', url, true);
		// Requestheader senden
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		// Request senden
		request.send('name='+value);
	//	request.send("name="+value+"&option="+option);
		// Request auswerten
		request.onreadystatechange = interpretRequest2;
	}
}

// Request auswerten
function interpretRequest2()
{
	switch (request.readyState)
	{
		// wenn der readyState 4 und der request.status 200 ist, dann ist alles korrekt gelaufen
		case 4:
			
			if (request.status != 200)
			{
				alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status);
			}
			else
				{
				var content = request.responseText;
				// den Inhalt des Requests in das <div> schreiben
				document.getElementById('content2').innerHTML = content;
			}
			break;
			
		default: document.getElementById('close2').style.display = "none"; break;
	}
}

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{PATH}" + encodeURI(newimage) : "./images/spacer.gif";
}

</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="map_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="map_name" id="map_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1 top"><label>{L_CAT}</label></td>
	<td class="row2">
		<!-- BEGIN _cat -->
		<label><input type="radio" name="cat_id" value="{_input._cat.CAT_ID}" disabled="disabled" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END _cat -->
	</td>
</tr>
<tr>
	<td class="row1"><label for="map_type">{L_TYPE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="map_type" id="map_type" value="{TYPE}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="map_file">{L_FILE}: *</label></td>
	<td class="row2">{S_FILE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="map_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->