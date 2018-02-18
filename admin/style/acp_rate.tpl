<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
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
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN news -->
<tr>
	<td><span class="right">{display.news.RATE}</span>{display.news.NAME}</td>
	<td>{display.news.MOVE_DOWN}{display.news.MOVE_UP}{display.news.UPDATE}{display.news.DELETE}</td>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.news.row.NAME}</td>
	<td>{display.news.row.MOVE_DOWN}{display.news.row.MOVE_UP}{display.news.row.UPDATE}{display.news.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- END news -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN download -->
<tr>
	<td><span class="right">{display.download.RATE}</span>{display.download.NAME}</td>
	<td>{display.download.MOVE_DOWN}{display.download.MOVE_UP}{display.download.UPDATE}{display.download.DELETE}</td>
</tr>
<!-- BEGIN rate -->
<tr>
	<td>{display.download.rate.NAME}</td>
	<td>{display.download.rate.MOVE_DOWN}{display.download.rate.MOVE_UP}{display.download.rate.UPDATE}{display.download.rate.DELETE}</td>
</tr>
<!-- END rate -->
<!-- END download -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<br />

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN gallery -->
<tr>
	<td><span class="right">{display.gallery.RATE}</span>{display.gallery.NAME}</td>
	<td>{display.gallery.MOVE_DOWN}{display.gallery.MOVE_UP}{display.gallery.UPDATE}{display.gallery.DELETE}</td>
</tr>
<!-- BEGIN rate -->
<tr>
	<td>{display.gallery.rate.NAME}</td>
	<td>{display.gallery.rate.MOVE_DOWN}{display.gallery.rate.MOVE_UP}{display.gallery.rate.UPDATE}{display.gallery.rate.DELETE}</td>
</tr>
<!-- END rate -->
<!-- END gallery -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END none -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="game_name" /></td>
	<td><input type="submit" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->