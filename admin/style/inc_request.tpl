<script type="text/javascript">
// <![CDATA[

var request = false;

// Request senden
function setRequest(value)
{
	if ( window.XMLHttpRequest ) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	// überprüfen, ob Request erzeugt wurde
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/{FILE}.php";
		
		// Request öffnen
		request.open('post', url, true);
		
		// Requestheader senden
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		
		// Request senden
		request.send('mode='+value);
		//request.send("mode="+value+"&option="+option);
		
		// Request auswerten
		request.onreadystatechange = interpretRequest;
	}
}

function setRequest(value)
{
	if ( window.XMLHttpRequest ) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	// überprüfen, ob Request erzeugt wurde
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/{FILE}.php";
		
		// Request öffnen
		request.open('post', url, true);
		
		// Requestheader senden
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		
		// Request senden
		request.send('smode='+value);
		//request.send("mode="+value+"&option="+option);
		
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
				document.getElementById('ajax_content').innerHTML = content;
			}
			break;
		
		default: document.getElementById('close').style.display = "none"; break;
	}
}

// ]]>
</script>