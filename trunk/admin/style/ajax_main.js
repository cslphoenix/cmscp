var request = false;

function setRequest(type, meta, name, curt, mode, data)
{
	if (window.XMLHttpRequest) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if (!request)
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/ajax_main.php";
		
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('type='+type+'&meta='+meta+'&name='+name+'&curt='+curt+'&mode='+mode+'&data='+data);
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