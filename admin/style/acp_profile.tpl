<!-- BEGIN display -->
<form action="{S_PROFILE_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_PROFILE_HEAD}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_PROFILE_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<!-- BEGIN catrow -->
<tr>
	<td class="rowHead" colspan="2" width="100"><a href="{display.catrow.U_VIEWCAT}">{display.catrow.CATEGORY_NAME}</a></td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap"><a class="small" href="{display.catrow.U_CAT_MOVE_UP}">{display.catrow.L_MOVE_UP}</a> <a class="small" href="{display.catrow.U_CAT_MOVE_DOWN}">{display.catrow.L_MOVE_DOWN}</a></td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap"><a class="small" href="{display.catrow.U_CAT_EDIT}">{L_EDIT}</a></td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap"><a class="small" href="{display.catrow.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="{display.catrow.profilerow.CLASS}" width="50%" nowrap="nowrap">{display.catrow.profilerow.PROFILE_NAME}:</td>
	<td class="{display.catrow.profilerow.CLASS}" width="50%" nowrap="nowrap">{display.catrow.profilerow.PROFILE_FIELD}</td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{display.catrow.profilerow.U_PROFILE_MOVE_UP}">{display.catrow.profilerow.L_MOVE_UP}</a> <a href="{display.catrow.profilerow.U_PROFILE_MOVE_DOWN}">{display.catrow.profilerow.L_MOVE_DOWN}</a></span></td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{display.catrow.profilerow.U_PROFILE_EDIT}">{L_EDIT}</a></span></td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{display.catrow.profilerow.U_PROFILE_DELETE}">{L_DELETE}</a></span></td>
</tr>
<!-- END profilerow -->
<tr>
	<td colspan="7" class="row_class2"><input class="post" type="text" name="{display.catrow.S_ADD_PROFILE_NAME}" /> <input type="submit" class="button2" name="{display.catrow.S_ADD_PROFILE_SUBMIT}" value="{L_CREATE_PROFILE}" /></td>
</tr>
<!-- END catrow -->
<tr>
	<td colspan="7" class="row_class2"><input class="post" type="text" name="categoryname" /> <input type="submit" class="button2"  name="addcategory" value="{L_CREATE_CATEGORY}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN category_edit -->
<form action="{S_PROFILE_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_PROFILE_ACTION}">{L_PROFILE_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_PROFILE_EDIT_CATEGORY}</a></li>
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
	<td class="row_class2"><input class="post" type="text" size="25" name="cat_title" value="{CAT_TITLE}" /></td>
</tr>
<tr>
	<td class="rowHead" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2" /></td>
</tr>
</table>
</form>
<br clear="all" />
<!-- END category_edit -->

<!-- BEGIN profile_edit -->
<form action="{S_PROFILE_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_PROFILE_ACTION}">{L_PROFILE_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_PROFILE_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_PROFILE_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_PROFILE_NAME}</td>
	<td class="row3"><input type="text" size="25" name="profile_name" value="{PROFILE_NAME}" class="post" /></td>
</tr>
<tr>
	<td class="row1" width="160">{L_PROFILE_FIELD}</td>
	<td class="row3"><input type="text" size="25" name="profile_field" value="{PROFILE_FIELD}" class="post" /></td>
</tr>
<tr>
	<td class="row1">{L_PROFILE_CATEGORY}</td>
	<td class="row3">{S_PROFILE_CATEGORY}</td>
</tr>
<tr> 
	<td class="row1">{L_PROFILE_TYPE}</td>
	<td class="row3"><input type="radio" name="profile_type" value="1" {S_CHECKED_TYPE_AREA} />&nbsp;{L_TYPE_AREA}&nbsp;&nbsp;<input type="radio" name="profile_type" value="0" {S_CHECKED_TYPE_TEXT} /> {L_TYPE_TEXT} </td>
</tr>
<tr>
	<td class="row1">{L_PROFILE_SGUEST}</td>
	<td class="row3"><input type="radio" name="profile_sguest" value="1" {S_CHECKED_SGUEST_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="profile_sguest" value="0" {S_CHECKED_SGUEST_NO} />&nbsp;{L_NO} </td>
</tr>
<tr>
	<td class="row1">{L_PROFILE_SMEMBER}</td>
	<td class="row3"><input type="radio" name="profile_smember" value="1" {S_CHECKED_SMEMBER_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="profile_smember" value="0" {S_CHECKED_SMEMBER_NO} />&nbsp;{L_NO} </td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
		
<br clear="all" />
<!-- END profile_edit -->