<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<br />

<table class="lfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}" /></td>
</tr>
</table>

<br />

<!-- BEGIN cat_row -->
<table class="rows">
<tr>
	<th>{display.catrow.NAME}</th>
	<th>{display.catrow.MOVE_UP}{display.catrow.MOVE_DOWN}{display.catrow.UPDATE}{display.catrow.DELETE}</th>
</tr>
<!-- BEGIN profile_row -->
<tr>
	<td><span class="right"><span style="font-size:10px;">{display.catrow._profilerow.FIELD}</span></span>{display.catrow._profilerow.NAME}</td>
	<td>{display.catrow._profilerow.MOVE_UP}{display.catrow._profilerow.MOVE_DOWN}{display.catrow._profilerow.UPDATE}{display.catrow._profilerow.DELETE}</td>
</tr>
<!-- END profile_row -->
<!-- BEGIN entry_empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END entry_empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.catrow.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.catrow.S_SUBMIT}" value="{L_CREATE_FIELD}" /></td>
</tr>
</table>
<br />
<!-- END cat_row -->
</form>
<!-- END display -->

<!-- BEGIN input -->
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
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<table class="update">
<tr>
	<td colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul></td>
</tr>
<tr>
	<td class="row1r"><label for="profile_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" size="25" name="profile_name" id="profile_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="profile_field">{L_FIELD}:</label></td>
	<td class="row2"><input type="text" size="25" name="profile_field" id="profile_field" value="{FIELD}"></td>
</tr>
<tr>
	<td class="row1r"><label>{L_CAT}:</label></td>
	<td>
		<!-- BEGIN cat -->
		<label><input type="radio" name="profile_cat" value="{_input._cat.CAT_ID}" onclick="setRequest('profile', {_input._cat.CAT_ID}, {CUR_CAT}, {CUR_ORDER})" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END cat -->
	</td>
</tr>
<tr>
	<td class="row1r"><label for="profile_type">{L_TYPE}:</label></td>
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
<tr>
	<td class="normal">{L_SHOW}</td>
	<td>&nbsp;</td>
</tr>
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
	<td><div id="close">{S_ORDER}</div><div id="ajax_content"></div></td>
</tr>
</tbody>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo">
	<li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input_cat -->