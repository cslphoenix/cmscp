<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE_FORUM}">{L_CREATE_FORUM}</a></li>
	<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li></ul>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" name="cat_name" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

<br />

<!-- BEGIN cat_row -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="99%">{display.catrow.NAME}</td>
	<td class="rowHead" align="left" width="99%">{L_AUTH}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{display.catrow.MOVE_UP}{display.catrow.MOVE_DOWN} <a href="{display.catrow.U_UPDATE}">{I_UPDATE}</a> <a href="{display.catrow.U_RESYNC}">{I_RESYNC}</a> <a href="{display.catrow.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- BEGIN forum_row -->
<tr> 
	<td class="row_class1" align="left" width="99%">
		<span class="right">{display.catrow._forumrow.TOPICS} / {display.catrow._forumrow.POSTS}</span>
		<span class="gen"><a href="{display.catrow._forumrow.U_VIEWFORUM}">{display.catrow._forumrow.NAME}</a></span><br />
		<span class="small">{display.catrow._forumrow.DESC}</span>
	</td>
	<td class="row_class1" align="center" nowrap="nowrap">{display.catrow._forumrow.AUTH}</td>
	<td class="row_class2" align="center">{display.catrow._forumrow.MOVE_UP}{display.catrow._forumrow.MOVE_DOWN} <a href="{display.catrow._forumrow.U_UPDATE}">{I_UPDATE}</a> <a href="{display.catrow._forumrow.U_RESYNC}">{I_RESYNC}</a> <a href="{display.catrow._forumrow.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- BEGIN sub_row -->
<tr> 
	<td class="row_class1" align="left" width="99%">
		<span class="right">{display.catrow._forumrow._subrow.TOPICS} / {display.catrow._forumrow._subrow.POSTS}</span>
		<span class="gen">&nbsp;&not;&nbsp;{display.catrow._forumrow._subrow.NAME}</span>
	</td>
	<td class="row_class1" align="center">{display.catrow._forumrow._subrow.AUTH}</td>
	<td class="row_class2" align="center">{display.catrow._forumrow._subrow.MOVE_UP}{display.catrow._forumrow._subrow.MOVE_DOWN} <a href="{display.catrow._forumrow._subrow.U_UPDATE}">{I_UPDATE}</a> <a href="{display.catrow._forumrow._subrow.U_RESYNC}">{I_RESYNC}</a> <a href="{display.catrow._forumrow._subrow.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END sub_row -->
<!-- END forum_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="3">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->

</table>

<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" name="{display.catrow.S_NAME}" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="{display.catrow.S_SUBMIT}" value="{L_CREATE_FORUM}"></td>
</tr>
</table>

<br />
<!-- END cat_row -->
</form>
<!-- END display -->

<!-- BEGIN input -->
{TINYMCE}
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
		var url = "./ajax/ajax_forum.php";
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

function clip(id)
{
	if ( document.getElementById("tbody_" + id).style.display == 'none' )
	{
		document.getElementById("tbody_" + id).style.display = "";
	}
	else
	{
		document.getElementById("tbody_" + id).style.display = "none";
	}
}

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{PATH}" + encodeURI(newimage) : "./images/spacer.gif";
}

function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}

</script>
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>

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
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1r"><label for="forum_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" size="25" name="forum_name" id="forum_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_SUB}:</label></td>
	<td class="row2"><label><input type="radio" name="sub" value="1" onChange="document.getElementById('form_sub').style.display = ''; document.getElementById('form_main').style.display = 'none';" {S_SUB_YES} />&nbsp;{L_YES}</label>
		<span style="padding:4px;"></span>
		<label><input type="radio" name="sub" value="0" onChange="document.getElementById('form_sub').style.display = 'none'; document.getElementById('form_main').style.display = '';" {S_SUB_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tbody id="form_sub" style="display:{S_SUB}">
<tr>
	<td class="row1"><label>{L_MAIN}:</label></td>
	<td>{S_FORMS}</td>
</tr>
<tr>
	<td class="row1"><label for="forum_order">{L_ORDER}:</label></td>
	<td><div id="close2">{S_SORDER}</div><div id="content2"></div></td>
</tr>
</tbody>
<tbody id="form_main" style="display:{S_MAIN}">
<!-- BEGIN cats -->
<tr>
	<td class="row1r"><label>{L_CAT}:</label></td>
	<td>
		<!-- BEGIN cat -->
		<label><input type="radio" name="cat_id" value="{_input._cats._cat.CAT_ID}" onclick="setRequest('{_input._cats._cat.CAT_ID}')" {_input._cats._cat.S_MARK} />&nbsp;{_input._cats._cat.CAT_NAME}</label><br />
		<!-- END cat -->
	</td>
</tr>
<!-- END cats -->
<tr>
	<td class="row1"><label for="forum_order">{L_ORDER}:</label></td>
	<td><div id="close">{S_ORDER}</div><div id="ajax_content"></div></td>
</tr>
<tr>
	<td class="row1"><label for="forum_legend" title="{L_LEGEND_EX}">{L_LEGEND}:</label></td>
	<td class="row2"><label><input type="radio" name="forum_legend" value="1" id="forum_legend" {S_LEGEND_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="forum_legend" value="0" {S_LEGEND_NO} />&nbsp;{L_NO}</label></td>
</tr>
</tbody>
<tr> 
	<td class="row1 top"><span class="right"><img src="{IMAGE}" id="image" alt="" /></span><label for="forum_icon">{L_ICON}:</label></td>
	<td>{S_IMAGE}</td>
</tr>
<tr>
	<td class="row1 top"><label for="forum_desc">{L_DESC}:</label></td>
	<td class="row2"><textarea class="textarea" name="forum_desc" id="forum_desc" rows="20" style="width:100%">{DESC}</textarea></td>
</tr>
<tr>
	<td class="row1"><label for="forum_status">{L_STATUS}:</label></td>
	<td class="row2"><label><input type="radio" name="forum_status" value="0" id="forum_status" {S_UNLOCKED} />&nbsp;{L_UNLOCKED}</label><span style="padding:4px;"></span><label><input type="radio" name="forum_status" value="1" {S_LOCKED} />&nbsp;{L_LOCKED}</label></td>
</tr>
<tr>
	<td class="row1"><label>{L_AUTH}:</label></td>
	<td class="row2"><label><input type="radio" name="forum_auth" value="0" onChange="document.getElementById('auth_extended').style.display = 'none'; document.getElementById('auth_simple').style.display = '';" {S_AUTH_SIMPLE} />&nbsp;{L_SIMPLE}</label>
		<span style="padding:4px;"></span>
		<label><input type="radio" name="forum_auth" value="1" onChange="document.getElementById('auth_extended').style.display = ''; document.getElementById('auth_simple').style.display = 'none';" {S_AUTH_EXPAND} />&nbsp;{L_EXPAND}</label>
	</td>
</tr>
<tbody id="auth_extended" style="display:{S_EXPAND}">
<tr>
	<td>
		<!-- BEGIN auth_simple -->
		{_input._auth_simple.SELECT}
		<!-- END auth_simple -->
	</td>
	<td>
		<table border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN auth -->
		<tr>
			<td nowrap="nowrap">{_input._auth.SELECT}</td>
			<td width="99%">&nbsp;<label for="{_input._auth.INFO}">{_input._auth.TITLE}</label></td>
		</tr>
		<!-- END auth -->
		</table>
	</td>
</tr>
</tbody>
<tbody id="auth_simple" style="display:{S_SIMPLE}">
<tr>
	<td></td>
	<td>
		<!-- BEGIN auth_simple -->
		{_input._auth_simple.S_SELECT}
		<!-- END auth_simple -->
	</td>
</tr>
</tbody>
<tr>
	<td>{L_COPY}:</td>
	<td>{S_COPY}</td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>

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
			<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1r"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1 top"><label for="game_order">{L_ORDER}:</label></td>
	<td class="row2 top">{S_ORDER}</td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input_cat -->