<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript">
// <![CDATA[

function update_image(newimage)
{
	document.getElementById('image').src = (newimage) ? "{IPATH}" + encodeURI(newimage) : "./../images/spacer.gif";
}

// ]]>
</script>
<form action="{S_ACTION}" method="post">
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

<!-- BEGIN ndisplay -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_TITLE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{ndisplay.row.DATE}</span>{ndisplay.row.STATUS} {ndisplay.row.TITLE}</td>
	<td>{ndisplay.row.PUBLIC}{ndisplay.row.UPDATE}{ndisplay.row.DELETE}</td>		
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="footer">
<tr>
	<td><input type="text" name="news_title"></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
	<td></td>
	<td>{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END ndisplay -->

<!-- BEGIN cdisplay -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{cdisplay.row.NAME}</td>
	<td>{cdisplay.row.MOVE_DOWN}{cdisplay.row.MOVE_UP}{cdisplay.row.UPDATE}{cdisplay.row.DELETE}</td>		
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END cdisplay -->

<!-- BEGIN sdisplay -->
<table class="rows">
<tr>
	<th>{L_TITLE}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN send_row -->
<tr>
	<td><span class="right">{ndisplay.send_row.SEND} {ndisplay.send_row.DATE}</span>{ndisplay.send_row.STATUS} {ndisplay.send_row.TITLE}</td>
	<td>{ndisplay.send_row.PUBLIC}{ndisplay.send_row.UPDATE}{ndisplay.send_row.DELETE}</td>		
</tr>
<!-- END send_row -->
<!-- BEGIN send_empty -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END send_empty -->
</table>
<!-- END sdisplay -->

