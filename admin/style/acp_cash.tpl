<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<form action="{S_ACTION}" method="post">
<script type="text/JavaScript">
<!-- BEGIN ajax -->
function look_{input.ajax.NAME}({input.ajax.NAME}, user_new, user_level)
{
	if ( {input.ajax.NAME}.length == 0 )
	{
		$('#{input.ajax.NAME}').hide();
	}
	else
	{
		$.post("./ajax/{input.ajax.FILE}", {{input.ajax.NAME}: ""+{input.ajax.NAME}+"", user_new: ""+user_new+"", user_level: ""+user_level+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#{input.ajax.NAME}').show();
					$('#auto_{input.ajax.NAME}').html(data);
				}
			}
		);
	}
}
function set_{input.ajax.NAME}(thisValue)
{
	$('#user_{input.ajax.NAME}').val(thisValue);
	setTimeout("$('#{input.ajax.NAME}').hide();", 200);
}
<!-- END ajax -->
function set_infos(id,text)
{
	var obj = document.getElementById(id).value = text;
}
</script>
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
<!-- BEGIN option -->
{input.row.tab.option.DIV_START}
<dl>			
	<dt{input.row.tab.option.CSS}><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
	<dd>{input.row.tab.option.OPTION}</dd>
</dl>
{input.row.tab.option.DIV_END}
<!-- END option -->
</fieldset>
<!-- END tab -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input_cat.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input_cat.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{input_cat.row.tab.option.CSS}"><label for="{input_cat.row.tab.option.LABEL}" {input_cat.row.tab.option.EXPLAIN}>{input_cat.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input_cat.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END input_cat -->

<!-- BEGIN bankdata -->
<form action="{S_ACTION}" method="post">
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<fieldset>
	<legend>{bankdata.row.L_LANG}</legend>
<!-- BEGIN option -->
{bankdata.row.option.DIV_START}
<dl>			
	<dt class="red"><label for="{bankdata.row.KEY}_{bankdata.row.option.KEYS}">{bankdata.row.option.LNGS}:</label></dt>
	<!-- BEGIN input -->
	<dd><input type="text" name="{bankdata.row.KEY}[{bankdata.row.option.KEYS}]" id="{bankdata.row.KEY}_{bankdata.row.option.KEYS}" value="{bankdata.row.option.input.VALUE}" /></dd>
	<!-- END input -->
</dl>
{bankdata.row.option.DIV_END}
<!-- END option -->
</fieldset>
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END bankdata -->

<!-- BEGIN display -->
test
<script type="text/JavaScript">

function lookup(user_name, user_new, user_level)
{
	if ( user_name.length == 0 )
	{
		// Hide the suggestion box.
		$('#suggestions').hide();
	}
	else
	{
		$.post("./ajax/ajax_user.php", {user_name: ""+user_name+"", user_new: ""+user_new+"", user_level: ""+user_level+""}, function(data) {
				if ( data.length > 0 )
				{
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			}
		);
	}
}

function fill(thisValue)
{
	$('#user_name').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

</script>
<ul id="navlist">
	<li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li>
	<li><a href="{S_CREATE}">{L_CREATE}</a></li>
	<li><a href="{S_CREATE_USER}">{L_CREATE_USER}</a></li>
	<li><a id="setting" href="{S_BANKDATA}">{L_CREATE_BANK}</a></li>
</ul>
<p>{L_EXPLAIN}</p>

<br />
<!-- END display -->

<!-- BEGIN bank -->
<!-- work -->
<table class="rows">
<tr>
	<th colspan="2">{L_BANK}</th>
</tr>
<tr>
	<td class="row_class1">{L_HOLDER}</td>
	<td class="row_class2">{HOLDER}</td>
</tr>
<tr>
	<td class="row_class1">{L_NAME} / {L_BLZ}</td>
	<td class="row_class2">{NAME} / {BLZ}</td>
</tr>
<tr>
	<td class="row_class1">{L_NUMBER}</td>
	<td class="row_class2">{NUMBER}</td>
</tr>
<tr>
	<td class="row_class1">{L_REASON}</td>
	<td class="row_class2">{REASON}</td>
</tr>
</table>

<table class="footer">
<tr>
	<td><form action="{S_ACTION}" method="post"><input type="hidden" name="mode" value="bankdata" /><input type="submit" class="button" value="{L_CREATE}" /></form></td>
	<td><form action="{S_ACTION}" method="post"><input type="hidden" name="mode" value="bankdata_delete" /><input type="submit" class="button" value="{L_DELETE}" /></form></td>
</tr>
</table>
<!-- END bank -->

<!-- BEGIN user -->
<form action="{S_ACTION}" method="post">
<table class="rows" cellspacing="1">
<tr>
	<th colspan="2"><span class="right">{POSTAGE_CASHUSER}</span>{L_USERNAME}</th>
	<th>{L_INTERVAL}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{user.row.MONTH}</span><span title="{user.row.TIME}">{user.row.USER}</span></td>
	<td>{user.row.AMOUNT}</td>
	<td>{user.row.INTERVAL}</td>
	<td>{user.row.UPDATE}{user.row.DELETE}</td>		
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="4">{L_NONE}</td>
</tr>
<!-- END none -->
<tr>
	<th colspan="2"><span class="{POSTAGE_CLASS} right">{POSTAGE}</span>&nbsp;</th>
	<th colspan="2">{L_POSTAGE}</th>
</tr>
</table>

<table class="footer">
<tr>
	<td><input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value, 0, 5);" onblur="fill();" autocomplete="off"></td>
	<td><input type="submit" value="{L_CREATE}"></td>
	<td></td>
	<td rowspan="2">{PAGE_NUMBER}<br />{PAGE_PAGING}</td>
</tr>
<tr>
	<td colspan="2">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div>
	</td>
	<td></td>
	</tr>
</table>
{S_FIELDS}
</form>
<!-- END user -->

<!-- BEGIN type -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_REASON}</th>
	<th>{L_INTERVAL}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td><span class="right">{type.row.AMOUNT}&nbsp;</span>{type.row.TYPE} {type.row.NAME}</td>
	<td>{type.row.DATE}</td>
	<td>{type.row.UPDATE}{type.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="none" colspan="3">{L_NONE}</td>
</tr>
<!-- END none -->
<tr>
	<th><span class="right">{POSTAGE_CASH}&nbsp;</span></th>
	<th colspan="2">{L_POSTAGE}</th>
</tr>
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="cash_type" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END type -->