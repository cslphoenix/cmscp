<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE_FORUM}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE_CATEGORY}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _cat_row -->
<table class="info" border="0" cellspacing="1" cellpadding="0">
<tr>
	<td class="rowHead" align="left" width="99%" colspan="5"><a href="{_display._cat_row.U_VIEWCAT}">{_display._cat_row.CAT_DESC}</a></td>
	<td class="rowHead" align="center" nowrap="nowrap">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} <a href="{_display._cat_row.U_CAT_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row.U_CAT_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- BEGIN _forum_row -->
<tr> 
	<td class="{_display._cat_row._forum_row.CLASS}" width="100%"><span class="gen"><a href="{_display._cat_row._forum_row.U_VIEWFORUM}">{_display._cat_row._forum_row.FORUM_NAME}</a></span><br><span class="small">{_display._cat_row._forum_row.FORUM_DESC}<br>{_display._cat_row._forum_row.NUM_TOPICS} / {_display._cat_row._forum_row.NUM_POSTS}</span></td>
	<td class="{_display._cat_row._forum_row.CLASS}" align="center" valign="middle" nowrap="nowrap"><a href="{_display._cat_row._forum_row.U_FORUM_PERMISSIONS}">{_display._cat_row._forum_row.PERMISSIONS}</a></td>
	<td class="{_display._cat_row._forum_row.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{_display._cat_row._forum_row.U_FORUM_MOVE_UP}">{_display._cat_row._forum_row.L_MOVE_UP}</a> <a href="{_display._cat_row._forum_row.U_FORUM_MOVE_DOWN}">{_display._cat_row._forum_row.L_MOVE_DOWN}</a></span></td>
	<td class="{_display._cat_row._forum_row.CLASS}" align="center" valign="middle"><span class="gen"><a href="{_display._cat_row._forum_row.U_UPDATE}">{L_EDIT}</a></span></td>
	<td class="{_display._cat_row._forum_row.CLASS}" align="center" valign="middle"><span class="gen"><a href="{_display._cat_row._forum_row.U_DELETE}">{L_DELETE}</a></span></td>
	<td class="{_display._cat_row._forum_row.CLASS}" align="center" valign="middle"><span class="gen"><a href="{_display._cat_row._forum_row.U_RESYNC}">{L_RESYNC}</a></span></td>
</tr>
<!-- END _forum_row -->
</table>

<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td align="right"><input type="text" class="post" name="{_display._cat_row.S_ADD_FORUM_NAME}" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="{_display._cat_row.S_ADD_FORUM_SUBMIT}" value="{L_CREATE_FORUM}"></td>
</tr>
</table>

<br />

<table border="0" cellspacing="1" cellpadding="0">
<!-- END _cat_row -->
<tr>
	<td align="right"><input type="text" class="post" name="categoryname" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="addcategory" value="{L_CREATE_CATEGORY}"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN category_edit -->
<form action="{S_FORUM_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_FORUM_ACTION}">{L_FORUM_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_EDIT_CATEGORY}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_EDIT_CATEGORY_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_EDIT_CATEGORY}</td>
</tr>
<tr>
	<td class="row_class1">{L_CATEGORY}</td>
	<td class="row_class2"><input class="post" type="text" size="25" name="cat_title" value="{CAT_TITLE}"></td>
</tr>
<tr>
	<td class="rowHead" colspan="2" align="center">{S_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2"></td>
</tr>
</table>
</form>
		
<br clear="all" />
<!-- END category_edit -->

<!-- BEGIN forum_edit -->
<form action="{S_FORUM_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_FORUM_ACTION}">{L_FORUM_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_FORUM_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_FORUM_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1">{L_FORUM_NAME}</td>
	<td class="row2"><input type="text" size="25" name="forum_name" value="{FORUM_NAME}" class="post"></td>
</tr>
<tr>
	<td class="row1">{L_FORUM_DESCRIPTION}</td>
	<td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="forum_desc" class="post">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_CATEGORY}</td>
	<td class="row2">{S_CAT_LIST}</td>
</tr>
<tr> 
	<td class="row1">{L_PERMISSIONS}</td>
	<td class="row2">{S_AUTH_LEVELS_SELECT}</td>
</tr>
<tr>
	<td class="row1">{L_FORUM_STATUS}</td>
	<td class="row2">{S_STATUS_LIST}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2"></td>
</tr>
</table>
</form>
		
<br clear="all" />
<!-- END forum_edit -->