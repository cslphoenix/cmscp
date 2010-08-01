<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
</ul>
</div>

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row2 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<!-- BEGIN _cat_row -->
<table border="0" cellspacing="1" cellpadding="0">
<tr>
	<td colspan="2">
		<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{_display._cat_row.CAT_NAME}</a></li>
			<li><a id="right" href="{_display._cat_row.U_CAT_DELETE}">{I_DELETE_SMALL}</a></li>
			<li><a id="right" href="{_display._cat_row.U_CAT_UPDATE}">{I_UPDATE_SMALL}</a></li>
			<li>{_display._cat_row.CAT_MOVE_DOWN}</li>
			<li>{_display._cat_row.CAT_MOVE_UP}</li>
		</ul>
		</div>
	</td>
</tr>
<!-- BEGIN _profile_row -->
<tr> 
	<td class="row1" nowrap="nowrap">{_display._cat_row._profile_row.NAME}:</td>
	<td class="row2" nowrap="nowrap"><span style="float:right;">{_display._cat_row._profile_row.MOVE_UP}{_display._cat_row._profile_row.MOVE_DOWN} <a href="{_display._cat_row._profile_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row._profile_row.U_DELETE}">{I_DELETE}</a></span>{_display._cat_row._profile_row.FIELD}</td>
</tr>
<!-- END _profile_row -->
</table>

<table border="0" cellspacing="1" cellpadding="2">
<tr>
	<td align="right"><input type="text" class="post" name="{_display._cat_row.S_ADD_PROFILE_NAME}" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="{_display._cat_row.S_ADD_PROFILE_SUBMIT}" value="{L_CREATE}"></td>
</tr>
</table>

<br />

<table border="0" cellspacing="1" cellpadding="2">
<!-- END _cat_row -->
<tr>
	<td align="right"><input type="text" class="post" name="categoryname" /></td>
	<td align="right" class="top" width="1%"><input type="submit" class="button2" name="addcategory" value="{L_CAT_CREATE}"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN _input_cat -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1"><label for="category_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="category_name" id="category_name" value="{NAME}"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input_cat -->

<!-- BEGIN _input -->
<form action="{S_PROFILE_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_PROFILE_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_DATA_INPUT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1" width="155"><label for="profile_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" size="25" name="profile_name" id="profile_name" value="{PROFILE_NAME}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_field">{L_FIELD}: *</label></td>
	<td class="row2"><input type="text" size="25" name="profile_field" id="profile_field" value="{PROFILE_FIELD}" class="post"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_category">{L_CATEGORY}:</label></td>
	<td class="row2">{S_PROFILE_CATEGORY}</td>
</tr>
<tr> 
	<td class="row1"><label for="profile_type">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_type" value="1" id="profile_type" {S_TYPE_AREA} />&nbsp;{L_TYPE_TEXT}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_type" value="0" {S_TYPE_TEXT} />&nbsp;{L_TYPE_AREA}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_language">{L_LANGUAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_language" value="1" id="profile_language" {S_LANG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_language" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_required">{L_REQUIRED}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_required" value="1" id="profile_required" {S_REQ_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_required" value="0" {S_REQ_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row2" colspan="2"><strong>{L_SHOW}</strong></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sguest">{L_SGUEST}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_sguest" value="1" id="profile_sguest" {S_SGUEST_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_sguest" value="0" {S_SGUEST_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_smember">{L_SMEMBER}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_smember" value="1" id="profile_smember" {S_SMEMBER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_smember" value="0" {S_SMEMBER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1" align="right"><label for="profile_sregister">{L_SREG}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_sregister" value="1" id="profile_sregister" {S_SREG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_sregister" value="0" {S_SREG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _input -->