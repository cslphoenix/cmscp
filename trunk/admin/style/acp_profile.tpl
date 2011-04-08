<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="99%" colspan="2">{_display._cat_row.NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} <a href="{_display._cat_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- BEGIN _profile_row -->
<tr> 
	<td class="row_class1 row1" nowrap="nowrap"><b>{_display._cat_row._profile_row.NAME}</b></td>
	<td class="row_class1 row2" width="99%">{_display._cat_row._profile_row.FIELD}</td>
	<td class="row_class2" align="center">{_display._cat_row._profile_row.MOVE_UP}{_display._cat_row._profile_row.MOVE_DOWN} <a href="{_display._cat_row._profile_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row._profile_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _profile_row -->
<!-- BEGIN _no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END _no_entry -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="{_display._cat_row.S_NAME}" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="{_display._cat_row.S_SUBMIT}" value="{L_CREATE_FIELD}"></td>
</tr>
</table>

<br />

<table border="0" cellspacing="1" cellpadding="2">
<!-- END _cat_row -->
<tr>
	<td align="right"><input type="text" class="post" name="cat_name" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<script type="text/javascript">
	
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NETWORKS_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}

	var request = false;

	// Request senden
	function setRequest(value)
	{
		// Request erzeugen
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			request=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			request=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		// überprüfen, ob Request erzeugt wurde
		if (!request)
		{
			alert("Kann keine XMLHTTP-Instanz erzeugen");
			return false;
		}
		else
		{
			var url = "./../includes/ajax/ajax_profile.php";
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
	function interpretRequest() {
		switch (request.readyState) {
			// wenn der readyState 4 und der request.status 200 ist, dann ist alles korrekt gelaufen
			case 4:
				if (request.status != 200) {
					alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status);
				} else {
					var content = request.responseText;
					// den Inhalt des Requests in das <div> schreiben
					document.getElementById('content').innerHTML = content;
				}
				break;
			
			default:
					document.getElementById('close').style.display = "none";
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
<tr>
	<td class="row1" width="155"><label for="profile_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" size="25" name="profile_name" id="profile_name" value="{PROFILE_NAME}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_field">{L_FIELD}: *</label></td>
	<td class="row2"><input type="text" size="25" name="profile_field" id="profile_field" value="{PROFILE_FIELD}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label>{L_CAT}: *</label></td>
	<td class="row2">
		<!-- BEGIN _cat -->
		<label><input type="radio" name="cat_id" value="{_input._cat.CAT_ID}" onclick="setRequest('{_input._cat.CAT_ID}')" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END _cat -->
	</td>
</tr>
<tr> 
	<td class="row1"><label for="profile_type">{L_TYPE}: *</label></td>
	<td class="row2"><label><input type="radio" name="profile_type" value="1" id="profile_type" {S_TYPE_AREA} />&nbsp;{L_TYPE_TEXT}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_type" value="0" {S_TYPE_TEXT} />&nbsp;{L_TYPE_AREA}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_language">{L_LANGUAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_language" value="1" id="profile_language" {S_LANG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_language" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_necessary">{L_REQUIRED}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_necessary" value="1" id="profile_necessary" {S_REQ_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_necessary" value="0" {S_REQ_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row2" colspan="2"><strong>{L_SHOW}</strong></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sguest">{L_SGUEST}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_sguest" value="1" id="profile_sguest" {S_SGUEST_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_sguest" value="0" {S_SGUEST_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_smember">{L_SMEMBER}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_smember" value="1" id="profile_smember" {S_SMEMBER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_smember" value="0" {S_SMEMBER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sregister">{L_SREG}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_sregister" value="1" id="profile_sregister" {S_SREG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_sregister" value="0" {S_SREG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_order">{L_ORDER}:</label></td>
	<td class="row2"><div id="close">{S_ORDER}</div><div id="content"></div></td>
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
	<td class="row1 top"><label for="game_order">{L_ORDER}:</label></td>
	<td class="row2 top">{S_ORDER}</td>
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