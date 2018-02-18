<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">

var request = false;

function setRequest(type, game)
{
	if ( window.XMLHttpRequest ) { request = new XMLHttpRequest(); } else { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if ( !request )
	{
		alert("Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	else
	{
		var url = "ajax/ajax_gameq.php";
		
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('type='+type+'&game='+game);
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

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th><span class="right">{LIST_VOICE}</span>{L_VOICE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN voice_row -->
<tr>
	<td><span style="float: right;">{display.voice_row.USERS} {display.voice_row.STATUS}</span>{display.voice_row.TYPE} {display.voice_row.NAME}</td>
	<td>{display.voice_row.MOVE_DOWN}{display.voice_row.MOVE_UP}{display.voice_row.UPDATE} {display.voice_row.DELETE}</td>
</tr>
<!-- END voice_row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="voice_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>

<br />

<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th><span class="right">{LIST_GAME}</span>{L_GAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td><span style="float: right;">{display.game_row.USERS} {display.game_row.STATUS}</span>{display.game_row.TYPE} {display.game_row.NAME}</td>
	<td>{display.game_row.MOVE_DOWN}{display.game_row.MOVE_UP}{display.game_row.UPDATE} {display.game_row.DELETE}</td>
</tr>
<!-- END game_row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="game_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN server_list -->
<form action="{S_ACTION}" method="post">
{ERROR_BOX}
<fieldset>
	<legend>{L_LANG}</legend>
<table class="rows2">
<tr>
	<!-- BEGIN name_option -->
	<th{server_list.name_option.CSS}>{server_list.name_option.NAME}</th>
	<!-- END name_option -->
</tr>
<!-- BEGIN row -->
<tr>
	<!-- BEGIN type_option -->
	<td>{server_list.row.type_option.TYPE}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>
</fieldset>
<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END server_list -->

<!-- BEGIN gameq -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th><span class="right">{LIST_VOICE}</span>{L_VOICE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN voice_row -->
<tr>
	<td><span class="righti">{gameq.voice_row.GAME} &bull; {gameq.voice_row.DPORT}</span>{gameq.voice_row.NAME}</td>
	<td>{gameq.voice_row.UPDATE} {gameq.voice_row.DELETE}</td>
</tr>
<!-- END voice_row -->
<!-- BEGIN voice_empty -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END game_empty -->
</table>

<br />

<table class="rows">
<tr>
	<th><span class="right">{LIST_GAME}</span>{L_GAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td><span class="righti">{gameq.game_row.GAME} &bull; {gameq.game_row.DPORT}</span>{gameq.game_row.NAME}</td>
	<td>{gameq.game_row.UPDATE} {gameq.game_row.DELETE}</td>
</tr>
<!-- END game_row -->
<!-- BEGIN game_empty -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END game_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="gameq_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END gameq -->

<!-- BEGIN gameq_list -->
<form action="{S_ACTION}" method="post">
{ERROR_BOX}
<fieldset>
	<legend>{L_LANG}</legend>
<table class="rows2">
<tr>
	<!-- BEGIN name_option -->
	<th{gameq_list.name_option.CSS}>{gameq_list.name_option.NAME}</th>
	<!-- END name_option -->
</tr>
<!-- BEGIN row -->
<tr>
	<!-- BEGIN type_option -->
	<td align="center">{gameq_list.row.type_option.TYPE}</td>
	<!-- END type_option -->
</tr>
<!-- END row -->
</table>
</fieldset>
<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END gameq_list -->