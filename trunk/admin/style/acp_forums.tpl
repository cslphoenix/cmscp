<!-- BEGIN display -->
<form method="post" action="{S_FORUM_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_FORUM_TITLE}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_FORUM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
		<td class="rowHead" colspan="7">{L_FORUM_TITLE}</td>
	</tr>
	<!-- BEGIN catrow -->
	<tr>
		<td class="rowHead" width="97%"><a href="{display.catrow.U_VIEWCAT}">{display.catrow.CAT_DESC}</a></td>
		<td class="rowHead" width="97%"><a href="{display.catrow.U_PERMISSIONS}">{display.catrow.PERMISSIONS}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%" nowrap><a class="small" href="{display.catrow.U_CAT_MOVE_UP}">{display.catrow.L_MOVE_UP}</a> <a class="small" href="{display.catrow.U_CAT_MOVE_DOWN}">{display.catrow.L_MOVE_DOWN}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%"><a class="small" href="{display.catrow.U_CAT_EDIT}">{L_EDIT}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%"><a class="small" href="{display.catrow.U_CAT_DELETE}">{L_DELETE}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%">&nbsp;</td>
	</tr>
	<!-- BEGIN forumrow -->
	<tr> 
		<td class="{display.catrow.forumrow.CLASS}" width="100%"><span class="gen"><a href="{display.catrow.forumrow.U_VIEWFORUM}">{display.catrow.forumrow.FORUM_NAME}</a></span><br /><span class="small">{display.catrow.forumrow.FORUM_DESC}<br />{display.catrow.forumrow.NUM_TOPICS} / {display.catrow.forumrow.NUM_POSTS}</span></td>
		<td class="{display.catrow.forumrow.CLASS}" align="center" valign="middle" nowrap><a href="{display.catrow.forumrow.U_FORUM_PERMISSIONS}">{display.catrow.forumrow.PERMISSIONS}</a></td>
		<td class="{display.catrow.forumrow.CLASS}" align="center" valign="middle" nowrap><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_MOVE_UP}">{display.catrow.forumrow.L_MOVE_UP}</a> <a href="{display.catrow.forumrow.U_FORUM_MOVE_DOWN}">{display.catrow.forumrow.L_MOVE_DOWN}</a></span></td>
		<td class="{display.catrow.forumrow.CLASS}" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_EDIT}">{L_EDIT}</a></span></td>
		<td class="{display.catrow.forumrow.CLASS}" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_DELETE}">{L_DELETE}</a></span></td>
		<td class="{display.catrow.forumrow.CLASS}" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_RESYNC}">{L_RESYNC}</a></span></td>
	</tr>
	<!-- END forumrow -->
	<tr>
		<td colspan="7" class="row_class2"><input class="post" type="text" name="{display.catrow.S_ADD_FORUM_NAME}" /> <input type="submit" class="button2" name="{display.catrow.S_ADD_FORUM_SUBMIT}" value="{L_CREATE_FORUM}" /></td>
	</tr>
	<!-- END catrow -->
	<tr>
		<td colspan="7" class="row_class2"><input class="post" type="text" name="categoryname" /> <input type="submit" class="button2"  name="addcategory" value="{L_CREATE_CATEGORY}" /></td>
	</tr>
</table>
</form>
<!-- END display -->

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

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_EDIT_CATEGORY}</td>
</tr>
<tr>
	<td class="row_class1">{L_CATEGORY}</td>
	<td class="row_class2"><input class="post" type="text" size="25" name="cat_title" value="{CAT_TITLE}" /></td>
</tr>
<tr>
	<td class="rowHead" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2" /></td>
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

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1">{L_FORUM_NAME}</td>
	<td class="row2"><input type="text" size="25" name="forum_name" value="{FORUM_NAME}" class="post" /></td>
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
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2" /></td>
</tr>
</table>
</form>
		
<br clear="all" />
<!-- END forum_edit -->