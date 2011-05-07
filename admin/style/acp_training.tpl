<!-- BEGIN _display -->
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_UPCOMING}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _training_new_row -->
<tr>
	<td class="row_class1" align="center">{_display._training_new_row.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_new_row.DATE}</span>{_display._training_new_row.NAME}</td>
	<td class="row_class2" align="center"><a href="{_display._training_new_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._training_new_row.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END _training_new_row -->
<!-- BEGIN _no_entry_new -->
<tr>
	<td class="entry_empty" colspan="3" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_new -->
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%" colspan="2">{L_EXPIRED}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{L_SETTINGS}</td>
</tr>
<!-- BEGIN _training_old_row -->
<tr>
	<td class="row_class1" align="center">{_display._training_old_row.IMAGE}</td>
	<td class="row_class1" align="left" width="100%"><span style="float:right;">{_display._training_old_row.DATE}</span>{_display._training_old_row.NAME}</td>
	<td class="row_class2" align="center"><a href="{_display._training_old_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._training_old_row.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END _training_old_row -->
<!-- BEGIN _no_entry_old -->
<tr>
	<td class="entry_empty" colspan="3" align="center">{L_ENTRY_NO}</td>
</tr>
<!-- END _no_entry_old -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<form action="{S_ACTION}" method="post">
	<td align="left">{S_LIST}</td>
	</form>
	<form action="{S_ACTION}" method="post">
	<td align="right"><input type="text" class="post" name="training_vs"></td>
	<td class="top" align="right" width="1%">{S_TEAMS}</td>
	<td class="top" align="right" width="1%"><input type="submit" class="button2" value="{L_CREATE}"></td>
	{S_FIELDS}
	</form>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END _display -->

<!-- BEGIN _input -->
{TINYMCE}
<script type="text/JavaScript">

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
		var url = "./ajax/ajax_listmaps.php";
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
			
		default:
			
			document.getElementById('close').style.display = "none";
		/*	
			for ( var i = 0; i < training_maps.length; i++ )
			{
				training_maps[i].value = '';
			}
		*/
			break;
	}
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
<tbody class="trhover">
<tr>
	<td class="row1"><label for="training_vs">{L_VS}: *</label></td>
	<td class="row2"><input class="post" type="text" name="training_vs" id="training_vs" value="{VS}"></td>
</tr>
<tr>
	<td class="row1"><label for="team_id">{L_TEAM}: *</label></td>
	<td class="row2">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1"><label for="match_id">{L_MATCH}:</label></td>
	<td class="row2">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DATE}:</label></td>
	<td class="row2">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1"><label>{L_DURATION}:</label></td>
	<td class="row2">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1"><label>{L_MAPS}: *</label></td>
	<td class="row2">
		<div id="close">
		<table border="0" cellspacing="0" cellpadding="0">
		
		<!-- BEGIN _map_row -->
		<!--
		<tr>
			<td><input type="text" class="post" name="training_maps[]" id="training_maps" value="{_input._map_row.MAP}"> <input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		-->
		<!-- END _map_row -->
		<!-- BEGIN _maps_row -->
		<tr>
			<td>{_input._maps_row.MAPS}<input  class="button2" type="button" value="{L_REMOVE}" onClick="this.parentNode.parentNode.removeChild(this.parentNode)"></td>
		</tr>
		<!-- END _maps_row -->
		</table>
		{S_MAPS}</div><div id="content"></div>
	</td>
</tr>
<tr>
	<td class="row1"><label for="training_text">{L_TEXT}:</label></td>
	<td class="row2"><textarea class="post" rows="5" cols="50" name="training_text" id="training_text">{TEXT}</textarea></td>
</tr>
</tbody>
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