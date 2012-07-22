<!-- BEGIN input -->
{AJAX}
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="map_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="map_name" id="map_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1"><label>{L_CAT}</label></td>
	<td>
		<!-- BEGIN cat -->
		<label><input type="radio" name="cat_id" value="{_input._cat.CAT_ID}" onclick="setRequest('{_input._cat.CAT_ID}')" {_input._cat.S_MARK} />&nbsp;{_input._cat.CAT_NAME}</label><br />
		<!-- END cat -->
	</td>
</tr>
<tr>
	<td class="row1r"><label for="map_type">{L_TYPE}:</label></td>
	<td class="row2"><input type="text" name="map_type" id="map_type" value="{TYPE}"></td>
</tr>
<tbody id="close">
<tr>
	<td class="row1r"><label for="map_file">{L_FILE}:</label></td>
	<td>{S_FILE}<br /><img src="{IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td class="row1"><label for="map_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
</tbody>
<tbody id="ajax_content">
</tbody>
<tr>
	<td colspan="2"></td>
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
<!-- END input -->

<!-- BEGIN input_cat -->
<script type="text/javascript">
function look_cat(input_cat)
{
	if ( input_cat.length == 0 )
	{
		// Hide the suggestion box.
		$('#cat').hide();
	}
	else
	{
		$.post("./ajax/ajax_maps_cat.php", {string: ""+input_cat+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#cat').show();
					$('#auto_cat').html(data);
				}
			}
		);
	}
}

function set_cat(thisValue)
{
	$('#input_cat').val(thisValue);
	setTimeout("$('#cat').hide();", 200);
}
</script>

<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input_cat.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{input_cat.row.tab.option.CSS}"><label for="{input_cat.row.tab.option.LABEL}" {input_cat.row.tab.option.EXPLAIN}>{input_cat.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input_cat.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="cat_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cat_name" id="cat_name" value="{NAME}"></td>
</tr>
<tr>
	<td class="row1r"><label for="input_cat">{L_TAG}:</label></td>
	<td class="row2"><input type="text" name="cat_tag" id="input_cat" onkeyup="look_cat(this.value);" onblur="set_cat();" value="{TAG}" autocomplete="off"><div class="suggestionsBox" id="cat" style="display:none;"><div class="suggestionList" id="auto_cat"></div></div></td>
</tr>
<tr>
	<td class="row1r"><label for="cat_display">{L_DISPLAY}:</label></td>
	<td class="row2"><label><input type="radio" name="cat_display" id="cat_display" value="1" {S_DISPLAY_YES} />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="cat_display" value="0" {S_DISPLAY_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1"><label for="cat_order">{L_ORDER}:</label></td>
	<td>{S_ORDER}</td>
</tr>
<tr>
	<td colspan="2"></td>
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



<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE_CAT}">{L_CREATE_CAT}</a></li>
</ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<table class="rfooter">
<tr>
	<td><input type="text" name="cat_name" /></td>
	<td><input type="submit" class="button2" name="add_cat" value="{L_CREATE_CAT}"></td>
</tr>
</table>

<br />

<!-- BEGIN cat -->
<table class="rows">
<tr>
	<th><span class="right">{display.cat.TAG}</span>{display.cat.NAME}</th>
	<th>{display.cat.MOVE_DOWN}{display.cat.MOVE_UP}{display.cat.UPDATE}{display.cat.DELETE}</th>
</tr>
<!-- BEGIN maps -->
<tr>
	<td><span class="right">{display.cat.maps.FILE} :: {display.cat.maps.TYPE}</span>{display.cat.maps.NAME}</td>
	<td>{display.cat.maps.MOVE_DOWN}{display.cat.maps.MOVE_UP}{display.cat.maps.UPDATE}{display.cat.maps.DELETE}</td>
</tr>
<!-- END maps -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="{display.cat.S_NAME}" /></td>
	<td><input type="submit" class="button2" name="{display.cat.S_SUBMIT}" value="{L_CREATE_MAP}"></td>
</tr>
</table>

<br />
<!-- END cat -->
</form>
<!-- END display -->