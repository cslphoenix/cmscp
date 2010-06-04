<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" width="99%">{L_TITLE}</td>
	<td class="rowHead"align="center">{L_SETTINGS}</td>
</tr>
<!-- BEGIN row_newscat -->
<tr>
	<td class="row_class1" align="left">{display.row_newscat.TITLE}</td>
	<td class="row_class2" align="center">{display.row_newscat.MOVE_UP} {display.row_newscat.MOVE_DOWN} <a href="{display.row_newscat.U_UPDATE}">{I_UPDATE}</a> <a href="{display.row_newscat.U_DELETE}">{I_DELETE}</a></td>		
</tr>
<!-- END row_newscat -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_noentry" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="footer" border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="newscat_title"></td>
	<td class="top" align="right" width="1%"><input type="submit" class="button" value="{L_CREATE}"></td>
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
		document.getElementById('image').src = (newimage) ? "{IMAGE_PATH}" + encodeURI(newimage) : "{IMAGE_DEFAULT}";
	}
// ]]>
</script>

<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_NEW_EDIT}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="edit" border="0" cellspacing="0" cellpadding="0">

<tr>
	<td class="row1" width="23%"><label for="newscat_title">{L_TITLE}: *</label></td>
	<td class="row2"><input type="text" class="post" name="newscat_title" id="newscat_title" value="{TITLE}"></td>
</tr>
<tr>
	<td class="row1 top">{L_IMAGE}:</td>
	<td class="row2">{IMAGE_LIST}<br><img src="{IMAGE}" id="image" alt=""></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}">&nbsp;&nbsp;<input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END newscat_edit -->
