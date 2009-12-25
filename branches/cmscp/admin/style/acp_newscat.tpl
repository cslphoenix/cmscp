<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_NEWSCAT_HEAD}</a></li>
	<li><a href="{S_NEWSCAT_CREATE}">{L_NEWSCAT_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_NEWSCAT_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" colspan="2">{L_NEWSCAT_TITLE}</td>
	<td class="rowHead" colspan="3" align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN newscat_row -->
<tr>
	<td class="{display.newscat_row.CLASS}" align="left" nowrap="nowrap">{display.newscat_row.NEWSCAT_TITLE}</td>
	<td class="{display.newscat_row.CLASS}" align="center"><img src="{NEWSCAT_PATH}/{display.newscat_row.NEWSCAT_IMAGE}" width="25%" alt=""></td>
	<td class="{display.newscat_row.CLASS}" align="center">{display.newscat_row.MOVE_UP} {display.newscat_row.MOVE_DOWN}</td>
	<td class="{display.newscat_row.CLASS}" align="center"><a href="{display.newscat_row.U_UPDATE}">{L_UPDATE}</a></td>		
	<td class="{display.newscat_row.CLASS}" align="center"><a href="{display.newscat_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END newscat_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="news_category_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_NEWSCAT_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN newscat_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}" method="post">{L_NEWSCAT_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEWSCAT_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br />

<table class="edit" border="0" cellspacing="0" cellpadding="0">

<tr>
	<td class="row1" width="23%"><label for="news_category_title">{L_NEWSCAT_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="news_category_title" id="news_category_title" value="{NEWSCAT_TITLE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_NEWSCAT_IMAGE}:</td>
	<td class="row2">{S_NEWSCAT_LIST}<br><img src="{NEWSCAT_IMAGE}" id="image" alt=""></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END newscat_edit -->
