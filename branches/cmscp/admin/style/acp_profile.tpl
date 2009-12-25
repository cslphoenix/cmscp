<!-- BEGIN display -->
<form action="{S_PROFILE_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_PROFILE_HEAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_PROFILE_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="info" border="0" cellspacing="1" cellpadding="0">
<!-- BEGIN catrow -->
<tr>
	<td class="rowHead" colspan="2" width="100">{display.catrow.CATEGORY_NAME}</td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap">{display.catrow.CATEGORY_MOVE_UP}{display.catrow.CATEGORY_MOVE_DOWN}</td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap"><a href="{display.catrow.U_CAT_UPDATE}">{L_UPDATE}</a></td>
	<td class="rowHead" align="center" valign="middle" nowrap="nowrap"><a href="{display.catrow.U_CAT_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="{display.catrow.profilerow.CLASS}" width="25%" nowrap="nowrap">{display.catrow.profilerow.PROFILE_NAME}:</td>
	<td class="{display.catrow.profilerow.CLASS}" width="75%" nowrap="nowrap">{display.catrow.profilerow.PROFILE_FIELD}</td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap">{display.catrow.profilerow.PROFILE_MOVE_UP}{display.catrow.profilerow.PROFILE_MOVE_DOWN}</td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{display.catrow.profilerow.U_PROFILE_UPDATE}">{L_UPDATE}</a></span></td>
	<td class="{display.catrow.profilerow.CLASS}" align="center" valign="middle" nowrap="nowrap"><span class="gen"><a href="{display.catrow.profilerow.U_PROFILE_DELETE}">{L_DELETE}</a></span></td>
</tr>
<!-- END profilerow -->
<tr>
	<td colspan="7" class="row_class2"><input type="text" class="post" name="{display.catrow.S_ADD_PROFILE_NAME}" /> <input type="submit" class="button2" name="{display.catrow.S_ADD_PROFILE_SUBMIT}" value="{L_PROFILE_CREATE}"></td>
</tr>
<!-- END catrow -->
<tr>
	<td colspan="7" class="row_class2"><input type="text" class="post" name="categoryname" /> <input type="submit" class="button2"  name="addcategory" value="{L_CREATE_CATEGORY}"></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN category_edit -->
<form action="{S_PROFILE_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_PROFILE_ACTION}" method="post">{L_PROFILE_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_PROFILE_EDIT_CATEGORY}</a></li>
</ul>
</div>
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_PROFILE_ACTION}" method="post">{L_PROFILE_HEAD}</a></li>
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
	<td class="row_class2"><input class="post" type="text" size="25" name="cat_title" value="{CAT_TITLE}"></td>
</tr>
<tr>
	<td class="rowHead" colspan="2" align="center">{S_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="button2"></td>
</tr>
</table>
</form>
<br clear="all" />
<!-- END category_edit -->

<!-- BEGIN profile_edit -->
<form action="{S_PROFILE_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_PROFILE_ACTION}" method="post">{L_PROFILE_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_PROFILE_NEW_EDIT}</a></li>
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
	<td class="row1" width="23%"><label for="profile_name">{L_PROFILE_NAME}: *</label></td>
	<td class="row3"><input type="text" size="25" name="profile_name" id="profile_name" value="{PROFILE_NAME}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_field">{L_PROFILE_FIELD}: *</label></td>
	<td class="row3"><input type="text" size="25" name="profile_field" id="profile_field" value="{PROFILE_FIELD}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_category">{L_PROFILE_CATEGORY}:</label></td>
	<td class="row3">{S_PROFILE_CATEGORY}</td>
</tr>
<tr> 
	<td class="row1"><label for="profile_type">{L_PROFILE_TYPE}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_type" value="1" id="profile_type" {S_TYPE_AREA} />&nbsp;{L_TYPE_TEXT}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_type" value="0" {S_TYPE_TEXT} />&nbsp;{L_TYPE_AREA}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_language">{L_PROFILE_LANGUAGE}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_language" value="1" id="profile_language" {S_LANG_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_language" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_required">{L_PROFILE_REQUIRED}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_required" value="1" id="profile_required" {S_REQ_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_required" value="0" {S_REQ_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row2" colspan="2"><strong>{L_PROFILE_SHOW}</strong></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sguest">{L_PROFILE_SGUEST}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_sguest" value="1" id="profile_sguest" {S_SGUEST_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_sguest" value="0" {S_SGUEST_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_smember">{L_PROFILE_SMEMBER}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_smember" value="1" id="profile_smember" {S_SMEMBER_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_smember" value="0" {S_SMEMBER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sregister">{L_PROFILE_SREG}:</label></td>
	<td class="row3"><label><input type="radio" name="profile_sregister" value="1" id="profile_sregister" {S_SREG_YES} />&nbsp;{L_YES}</label>&nbsp;&nbsp;<label><input type="radio" name="profile_sregister" value="0" {S_SREG_NO} />&nbsp;{L_NO}</label></td>
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
		
<br clear="all" />
<!-- END profile_edit -->