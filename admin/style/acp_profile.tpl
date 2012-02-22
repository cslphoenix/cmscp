<!-- BEGIN _display -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="footer">
<tr>
	<td><input type="text" class="post" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}" /></td>
	<td></td>
    <td></td>
</tr>
</table>
<br />
<br />


<!-- BEGIN _cat_row -->
<table class="rows">
<tr>
	<td class="rowHead" align="left" width="99%">{_display._cat_row.NAME}</td>
	<td class="rowHead" align="center" nowrap="nowrap">{_display._cat_row.MOVE_UP}{_display._cat_row.MOVE_DOWN} <a href="{_display._cat_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- BEGIN _profile_row -->
<tr> 
	<td class="row_class1" nowrap="nowrap">{_display._cat_row._profile_row.NAME}<span style="float:right;"><span style="font-size:10px;">{_display._cat_row._profile_row.FIELD}</span></span></td>
	<td class="row_class2" align="center">{_display._cat_row._profile_row.MOVE_UP}{_display._cat_row._profile_row.MOVE_DOWN} <a href="{_display._cat_row._profile_row.U_UPDATE}">{I_UPDATE}</a> <a href="{_display._cat_row._profile_row.U_DELETE}">{I_DELETE}</a></td>
</tr>
<!-- END _profile_row -->
<!-- BEGIN _entry_empty -->
<tr>
	<td class="entry_empty" align="center" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty -->
</table>

<table class="footer">
<tr>
	<td><input type="text" class="post" name="{_display._cat_row.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{_display._cat_row.S_SUBMIT}" value="{L_CREATE_FIELD}" /></td>
    <td></td>
    <td></td>
</tr>
</table>
<br />
<!-- END _cat_row -->
</form>
<!-- END _display -->

<!-- BEGIN _input -->
<script type="text/javascript">  
// <![CDATA[
	function set_right(id)
	{
		var obj = document.getElementById(id).checked = true;
	}
// ]]>
</script>
{AJAX}
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_ACTION}">{L_HEAD}</a></li>
		<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
	</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1" width="155"><label for="profile_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" size="25" name="profile_name" id="profile_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="profile_field">{L_FIELD}: *</label></td>
	<td class="row2"><input type="text" class="post" size="25" name="profile_field" id="profile_field" value="{FIELD}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_CAT}: *</label></td>
	<td class="row2">
		<!-- BEGIN _cat -->
		<label><input type="radio" name="profile_cat" value="{_input._cat.CAT_ID}" onclick="setRequest('profile', {_input._cat.CAT_ID}, {CUR_CAT}, {CUR_ORDER})" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END _cat -->
	</td>
</tr>
<tr> 
	<td class="row1"><label for="profile_type">{L_TYPE}: *</label></td>
	<td class="row2"><label><input type="radio" name="profile_type" value="1" id="profile_type" {S_TYPE_AREA} />&nbsp;{L_TYPE_TEXT}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_type" value="0" {S_TYPE_TEXT} />&nbsp;{L_TYPE_AREA}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_language">{L_LANGUAGE}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_language" value="1" id="profile_language" {S_LANG_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_language" value="0" {S_LANG_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_necessary">{L_NECESSARY}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_necessary" value="1" id="profile_necessary" {S_NECESSARY_YES} onclick="set_right('profile_show_register');" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_necessary" value="0" {S_NECESSARY_NO} />&nbsp;{L_NO}</label></td>
</tr>
</tbody>
<tr>
	<td class="row1" colspan="2">{L_SHOW}</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="profile_show_guest">{L_GUEST}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_show_guest" value="1" id="profile_show_guest" {S_GUEST_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_show_guest" value="0" {S_GUEST_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_show_member">{L_MEMBER}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_show_member" value="1" id="profile_show_member" {S_MEMBER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_show_member" value="0" {S_MEMBER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_show_register">{L_REGISTER}:</label></td>
	<td class="row2"><label><input type="radio" name="profile_show_register" value="1" id="profile_show_register" {S_REGISTER_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="profile_show_register" value="0" {S_REGISTER_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="profile_order">{L_ORDER}:</label></td>
	<td class="row2"><div id="close">{S_ORDER}</div><div id="content"></div></td>
</tr>
</tbody>
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

<!-- BEGIN _input_cat -->
<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current">{L_INPUT}</a></li>
</ul>
</div>

<table class="header">
<tr>
	<td>{L_REQUIRED}</td>
</tr>
</table>

<br /><div align="center">{ERROR_BOX}</div>

<table class="update">
<tr>
	<td colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_INPUT_DATA}</a></li>
			</ul>
		</div>
	</td>
</tr>
<tbody class="trhover">
<tr>
	<td class="row1"><label for="cat_name">{L_NAME}: *</label></td>
	<td class="row2"><input type="text" class="post" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td class="row2">{S_ORDER}</td>
</tr>
</tbody>
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