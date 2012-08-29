<!-- BEGIN input -->
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
<form action="{S_ACTION}" method="post">	
<ul id="navlist"><li><a href="{S_ACTION}">{L_HEAD}</a></li><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li></ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

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
<tr {input.row.tab.option.ID}>
	<td class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
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
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="#" id="right" onclick="return false;">{L_BANKDATA}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input_cat.row.hidden.HIDDEN}
<!-- END hidden -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{bankdata.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="{bankdata.row.tab.option.CSS}"><label for="{bankdata.row.tab.option.LABEL}" {bankdata.row.tab.option.EXPLAIN}>{bankdata.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{bankdata.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
</table>
<!-- END row -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<!-- BEGIN row -->
<table class="update">
<!-- BEGIN option -->
<tr>
<td class="row1r"><label for="{_bankdata.row.KEY}_{_bankdata.row._option.KEYS}">{_bankdata.row._option.LNGS}:</label></td>
	<td>
		<!-- BEGIN input -->
		<input type="text" name="{_bankdata.row._option.KEYS}" id="{_bankdata.row.KEY}_{_bankdata.row._option.KEYS}" value="{_bankdata.row._option._input.VALUE}" />
		<!-- END input -->
	</td>
</tr>
<!-- END option -->
</table>
<!-- END row -->

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END bankdata -->

<!-- BEGIN display -->
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

<!-- BEGIN bank -->
<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th colspan="2">{L_BANK}</th>
</tr>
<tr>
	<td class="row_class1">{L_HOLDER}</td>
	<td class="row_class2">{display.bank.HOLDER}</td>
</tr>
<tr>
	<td class="row_class1">{L_NAME} / {L_BLZ}</td>
	<td class="row_class2">{display.bank.NAME} / {display.bank.BLZ}</td>
</tr>
<tr>
	<td class="row_class1">{L_NUMBER}</td>
	<td class="row_class2">{display.bank.NUMBER}</td>
</tr>
<tr>
	<td class="row_class1">{L_REASON}</td>
	<td class="row_class2">{display.bank.REASON}</td>
</tr>
</table>

<table class="rfooter">
<tr>
	<td><input type="hidden" name="mode" value="bankdata_delete" /><input type="submit" class="button" value="{L_DELETE}" /></td>
</tr>
</table>
</form>

<br />
<!-- END bank -->

<form action="{S_ACTION}" method="post">
<table class="rows">
<tr>
	<th>{L_REASON}</th>
	<th>{L_INTERVAL}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN cash_row -->
<tr>
	<td><span class="right">{display.cash_row.AMOUNT}&nbsp;</span>{display.cash_row.TYPE} {display.cash_row.NAME}</td>
	<td>{display.cash_row.DATE}</td>
	<td>{display.cash_row.UPDATE}{display.cash_row.DELETE}</td>
</tr>
<!-- END cash_row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="3">{L_EMPTY}</td>
</tr>
<!-- END empty -->
<tr>
	<th><span class="right">{POSTAGE_CASH}&nbsp;</span></th>
	<th colspan="2">{L_POSTAGE}</th>
</tr>
</table>

<table class="lfooter">
<tr>
	<td><input type="hidden" name="mode" value="create_cat" /><input type="text" name="cash_name" /></td>
	<td><input type="submit" class="button2" value="{L_CREATE}"></td>
</tr>
</table>
</form>

<br />

<form action="{S_ACTION}" method="post">
<table class="rows" cellspacing="1">
<tr>
	<th colspan="2"><span class="right">{POSTAGE_CASHUSER}</span>{L_USERNAME}</th>
	<th>{L_INTERVAL}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN user_row -->
<tr>
	<td><span class="right">{display.user_row.TIME}&nbsp;&bull;&nbsp;({display.user_row.MONTH})</span>{display.user_row.USER}</td>
	<td>{display.user_row.AMOUNT}</td>
	<td>{display.user_row.INTERVAL}</td>
	<td>{display.user_row.UPDATE}{display.user_row.DELETE}</td>		
</tr>
<!-- END user_row -->
<!-- BEGIN empty_user -->
<tr>
	<td class="empty" colspan="4">{L_EMPTY}</td>
</tr>
<!-- END empty_user -->
<tr>
	<th colspan="2"><span class="{POSTAGE_CLASS} right">{POSTAGE}</span>&nbsp;</th>
	<th colspan="2">{L_POSTAGE}</th>
</tr>
</table>

<table class="footer">
<tr>
	<td><input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value, 0, 2);" onblur="fill();" autocomplete="off"></td>
	<td><input type="submit" value="{L_CREATE_USER}"></td>
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
<input type="hidden" name="mode" value="create" />
</form>
<!-- END display -->