<script language="javascript" type="text/javascript">

var requestn = false;

function setRequestn(menu)
{
	if (window.XMLHttpRequest)
	{ requestn = new XMLHttpRequest(); }
	else
	{ requestn = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if (!requestn)
	{ alert("Kann keine XMLHTTP-Instanz erzeugen"); return false; }
	else
	{
		var url = "ajax/ajax_menu.php";
		requestn.open('post', url, true);
		requestn.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		requestn.send('mode='+menu);
		requestn.onreadystatechange = interpretRequestn;
	}
}

function interpretRequestn()
{
	switch (requestn.readyState)
	{
		case 4:
		
			if (requestn.status != 200)
			{ alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+requestn.status); }
			else
			{
				var content = requestn.responseText;
				document.getElementById('ajax_navi').innerHTML = content;
			}
			break;
	}
}

</script>
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_NAVIGATION}</a></li></ul>
<!-- BEGIN cat_row -->
<table class="navi">
<tr>
	<th onclick="toggle('{catrow.NAME}'); setRequestn('{catrow.NAME}');">{catrow.L_NAME}</th>
</tr>
<tbody id="{catrow.NAME}" style="display:{catrow.SHOW};">
<!-- BEGIN mod_row -->
<tr>
	<td><a href="{catrow.modrow.U_MODULE}" {catrow.modrow.CLASS}>{catrow.modrow.L_MODULE}</a></td>
</tr>
<!-- END mod_row -->
</tbody>
</table>
<!-- END cat_row -->

<!-- BEGIN row -->
<table class="navi">
<tr>
	<th onclick="toggle('{row.NAME}'); setRequestn('{row.NAME}');">{row.L_NAME}</th>
</tr>
<tbody id="{row.NAME}" style="display:{row.SHOW};">
<!-- BEGIN sub -->
<tr>
	<td><a href="{row.sub.U_MODULE}"{row.sub.CLASS}>{row.sub.L_MODULE}</a></td>
</tr>
<!-- END sub -->
</tbody>
</table>
<!-- END row -->



</td>
<td width="830" valign="top">