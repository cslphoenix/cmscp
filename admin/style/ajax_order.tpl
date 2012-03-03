<script type="text/javascript">
// <![CDATA[

var request = false;

// Request senden
function setRequest(new_mode, new_opt, cur_mode, cur_opt)
{
	// Request erzeugen
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		request = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// überprüfen, ob Request erzeugt wurde
	if (!request)
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/ajax_order.php";
		
		// Request öffnen
		request.open('post', url, true);
		
		// Requestheader senden
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		
		// Request senden
		//request.send('name='+value);
		request.send("new_mode="+new_mode+"&new_opt="+new_opt+"&cur_mode="+cur_mode+"&cur_opt="+cur_opt);
		
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

// ]]>
</script>