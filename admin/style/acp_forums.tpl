<!-- BEGIN display -->
<form method="post" action="{S_FORUM_ACTION}">
<table class="head" cellspacing="0">
<tr>
	<th>{L_FORUM_TITLE}</th>
</tr>
<tr>
	<td>{L_FORUM_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
		<td class="rowHead" colspan="7">{L_FORUM_TITLE}</td>
	</tr>
	<!-- BEGIN catrow -->
	<tr>
		<td class="rowHead" colspan="4" width="97%"><a href="{display.catrow.U_VIEWCAT}">{display.catrow.CAT_DESC}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%" nowrap><a class="small" href="{display.catrow.U_CAT_MOVE_UP}">{display.catrow.L_MOVE_UP}</a> <a class="small" href="{display.catrow.U_CAT_MOVE_DOWN}">{display.catrow.L_MOVE_DOWN}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%"><a class="small" href="{display.catrow.U_CAT_EDIT}">{L_EDIT}</a></td>
		<td class="rowHead" align="center" valign="middle" width="1%"><a class="small" href="{display.catrow.U_CAT_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- BEGIN forumrow -->
	<tr> 
		<td class="row_class2"><span class="gen"><a href="{display.catrow.forumrow.U_VIEWFORUM}" target="_new">{display.catrow.forumrow.FORUM_NAME}</a></span><br /><span class="gensmall">{display.catrow.forumrow.FORUM_DESC}</span></td>
		<td class="row_class1" align="center" valign="middle"><span class="gen">{display.catrow.forumrow.NUM_TOPICS}</span></td>
		<td class="row_class2" align="center" valign="middle"><span class="gen">{display.catrow.forumrow.NUM_POSTS}</span></td>
		<td class="row_class1" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_EDIT}">{L_EDIT}</a></span></td>
		<td class="row_class2" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_DELETE}">{L_DELETE}</a></span></td>
		<td class="row_class1" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_MOVE_UP}">{L_MOVE_UP}</a> <br /> <a href="{display.catrow.forumrow.U_FORUM_MOVE_DOWN}">{L_MOVE_DOWN}</a></span></td>
		<td class="row_class2" align="center" valign="middle"><span class="gen"><a href="{display.catrow.forumrow.U_FORUM_RESYNC}">{L_RESYNC}</a></span></td>
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
	<th>{L_EDIT_CATEGORY}</th>
</tr>
<tr>
	<td>{L_EDIT_CATEGORY_EXPLAIN}</td>
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
	<th>{L_FORUM_TITLE}</th>
</tr>
<tr>
	<td>{L_FORUM_EXPLAIN}</td>
</tr>
</table>

<br />


<table class="edit" cellspacing="1">
	<tr> 
	  <th class="thHead" colspan="2">{L_FORUM_SETTINGS}</th>
	</tr>
	<tr> 
	  <td class="row1">{L_FORUM_NAME}</td>
	  <td class="row2"><input type="text" size="25" name="forumname" value="{FORUM_NAME}" class="post" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FORUM_DESCRIPTION}</td>
	  <td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="forumdesc" class="post">{DESCRIPTION}</textarea></td>
	</tr>
	<tr> 
	  <td class="row1">{L_CATEGORY}</td>
	  <td class="row2"><select name="c">{S_CAT_LIST}</select></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FORUM_STATUS}</td>
	  <td class="row2"><select name="forumstatus">{S_STATUS_LIST}</select></td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td>
	</tr>
  </table>
</form>
		
<br clear="all" />
<!-- END forum_edit -->