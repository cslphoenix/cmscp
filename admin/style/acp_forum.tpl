<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

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
		var url = "ajax/ajax_main.php";
		
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('type='+value+'&meta='+meta+'&name='+name);
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
/**
* Set display of page element
* s[-1,0,1] = hide,toggle display,show
*/

function display_options(value)
{
	if ( value == '0' )
	{
		dE('main', -1);
		dE('copy', -1);
		dE('forum_desc', -1);
		dE('forum_icons', -1);
		dE('forum_legend', -1);
		dE('forum_status', -1);
	}
	else if ( value == '1' )
	{
		dE('main', 1);
		dE('copy', 1);
		dE('forum_desc', 1);
		dE('forum_icons', 1);
		dE('forum_legend', 1);
		dE('forum_status', 1);
	}
	else
	{
		dE('main', 1);
		dE('copy', 1);
		dE('forum_desc', 1);
		dE('forum_icons', 1);
		dE('forum_legend', 1);
		dE('forum_status', 1);
	}
}

// ]]>
</script>

<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<div class="update">
<!-- BEGIN tab -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul>
<!-- BEGIN option -->
<div{input.row.tab.option.ID}>
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
</div>
<!-- END option -->
<!-- END tab -->
</div>
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

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input_cat.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input_cat.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input_cat.row.tab.option.CSS}"><label for="{input_cat.row.tab.option.LABEL}" {input_cat.row.tab.option.EXPLAIN}>{input_cat.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input_cat.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input_cat -->

<!-- BEGIN list -->
<br />
<h1>{L_HEAD}</h1>
{L_EXPLAIN}
<br />
<table class="rows">
<tr>
	<th>{CAT} :: {NAME}</th>
	<th>{UPDATE}{DELETE}</th>
</tr>
</table>
<br />
<form action="{S_ACTION}" method="post">
<table class="lfooter">
<tr>
	<td><input type="text" name="menu_label" /></td>
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>
<br />
<!-- BEGIN row -->
<table class="rows">
<tr>
	<td class="label">{list.row.NAME}</td>
	<td>{list.row.MOVE_DOWN}{list.row.MOVE_UP}{list.row.UPDATE}{list.row.DELETE}</td>
</tr>
<!-- BEGIN mod -->
<tr>
	<td>{list.row.mod.NAME}</td>
	<td>{list.row.mod.MOVE_DOWN}{list.row.mod.MOVE_UP}{list.row.mod.UPDATE}{list.row.mod.DELETE}</td>
</tr>
<!-- END mod -->
</table>
<table class="lfooter">
<tr>
	<td><input type="text" name="{list.row.S_NAME}" /></td>
	<td><input type="submit" name="{list.row.S_SUBMIT}" value="{L_CREATE}" /></td>
</tr>
</table>
<br />
<!-- END row -->
{S_FIELDS}
</form>
<!-- END list -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE_FORUM}">{L_CREATE_FORUM}</a></li>
	<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li></ul>

<table class="header">
<tr>
	<td class="info">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="rows">
<!-- BEGIN cat -->
<tr>
	<th>{display.cat.NAME}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.RESYNC}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- END cat -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="forum_name" /></td>
	<td><input type="submit" value="{L_CREATE_CAT}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN form -->
<tr> 
	<td>
		<span class="right">{display.cat.form.TOPICS} / {display.cat.form.POSTS}</span>
		<span class="gen"><a href="{display.cat.form.U_VIEWFORUM}">{display.cat.form.NAME}</a></span><br />
		<span class="small">{display.cat.form.DESC}</span>
	</td>
	<td>{display.cat.form.AUTH}</td>
	<td>{display.cat.form.MOVE_DOWN}{display.cat.form.MOVE_UP}{display.cat.form.RESYNC}{display.cat.form.UPDATE}{display.cat.form.DELETE}</td>
</tr>
<!-- BEGIN sub -->
<tr> 
	<td>
		<span class="right">{display.cat.form.sub.TOPICS} / {display.cat.form.sub.POSTS}</span>
		<span class="gen">&nbsp;&not;&nbsp;{display.cat.form.sub.NAME}</span>
	</td>
	<td>{display.cat.form.sub.AUTH}</td>
	<td>{display.cat.form.sub.MOVE_DOWN}{display.cat.form.sub.MOVE_UP}{display.cat.form.sub.RESYNC}{display.cat.form.sub.UPDATE}{display.cat.form.sub.DELETE}</a></td>
</tr>
<!-- END sub -->
<!-- END form -->
