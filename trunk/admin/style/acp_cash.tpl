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
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

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

<!-- BEGIN input_cat -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br />

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r"><label for="cash_name">{L_NAME}:</label></td>
	<td class="row2"><input type="text" name="cash_name" id="cash_name" value="{NAME}" /></td>
</tr>
<tr>
	<td class="row1r"><label for="cash_amount">{L_AMOUNT}:</label></td>
	<td class="row2"><input type="text" name="cash_amount" id="cash_amount" value="{AMOUNT}" /></td>
</tr>
<tr>
	<td class="row1"><label for="cash_type">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="cash_type" id="cash_type" value="0" {S_TYPE_GAME} />&nbsp;{L_TYPE_GAME}</label><br />
		<label><input type="radio" name="cash_type" value="1" {S_TYPE_VOICE} />&nbsp;{L_TYPE_VOICE}</label><br />
		<label><input type="radio" name="cash_type" value="2" {S_TYPE_OTHER} />&nbsp;{L_TYPE_OTHER}</label>
	</td>
</tr>
<tr>
	<td class="row1"><label for="cash_interval">{L_INTERVAL}:</label></td>
	<td class="row2"><label><input type="radio" name="cash_interval" id="cash_interval" value="0" {S_INT_MONTH} />&nbsp;{L_INTAVAL_MONTH}</label><br />
		<label><input type="radio" name="cash_interval" value="1" {S_INT_WEEKS} />&nbsp;{L_INTAVAL_WEEKS}</label><br />
		<label><input type="radio" name="cash_interval" value="2" {S_INT_WEEKLY} />&nbsp;{L_INTAVAL_WEEKLY}</label>
	</td>
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

<!-- BEGIN input -->

<script language="javascript" type="text/javascript">
function disable_checkbox(name)
{
//	checkbox_name = name;
//	document.getElementById(checkbox_name).disabled = 'disabled';
	document.getElementById(name).disabled = '';
}

function activate_checkbox(name)
{
//	checkbox_name = name;
//	document.getElementById(checkbox_name).disabled = '';
	document.getElementById(name).disabled = 'disabled';
}
</script>

<script language="javascript" type="text/javascript">
<!-- // JavaScript-Bereich für ältere Browser auskommentieren
// Folgende Funktion aktiviert oder dektiviert eine Gruppe von Radio-Buttons,
// die als Detaileingabe zu einem Checkbox-Element gehören.
function toggle_activation (element)
{
	// Ist das angegebene Element eines vom Typ checkbox?
	if (element.type == 'checkbox')
	{
		// Name der Radio-Button-Gruppe, die aktiviert bzw.
		// deaktiviert werden soll ist in der Value-Eigenschaft
		// der angegebenen Checkbox gespeichert.
		var groupname = element.value;

		// Existieren HTML-Elementobjekte, die den in der Checkbox
		// gespeicherten Namen in der Name-Eigenschaft tragen.
		if (document.getElementsByName(groupname))
		{
			// Array mit den Elementobjekten zwischenspeichern
			var group = document.getElementsByName(groupname);
	
			// Jedes der ermittelten Element einzeln durchgehen
			for (var i = 0; i < group.length; i++)
			// Handelt es sich bei dem Element um einen
			// Radio-button?
			if (group[i].type == 'radio')
			// Radio-Button aktivieren, wenn das
			// Checkbox-Element angekreuzt ist bzw,
			// deaktivieren, wenn dem nicht so ist.
			group[i].disabled = !element.checked;
		}
	}
}
// -->
</script>


<form action="{S_ACTION}" method="post">	
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

<!-- BEGIN row -->
<table class="update">
<!-- BEGIN tab -->
<tr>
	<th colspan="2"><ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{input.row.tab.L_LANG}</a></li></ul></th>
</tr>
<!-- BEGIN option -->
<tr>
	<td class="row1{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}" {input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></td>
	<td class="row2">{input.row.tab.option.OPTION}</td>
</tr>
<!-- END option -->
<!-- END tab -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<!-- END row -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_INPUT_DATA}</a></li></ul>
<table class="update">
<tr>
	<td class="row1r">{L_USER}:</td>
	<td class="row2">{S_USER}</td>
</tr>
<tr>
	<td class="row1r">{L_USER_AMOUNT}:</td>
	<td class="row2"><input type="text" name="user_amount" value="{AMOUNT}" /></td>
</tr>
<tr>
	<td class="row1">{L_USER_MONTH}:</td>
	<td class="row2">
		
		<table class="none">
		<!-- BEGIN month -->
		<tr class="hover">
			<td class="row3"><label><input type="checkbox" id="checkbox_group_" name="user_month[{input._month.NUM}]" value="{input._month.NUM}" onchange="toggle_activation(this)" {input._month.CHECK} /> {input._month.MONTH}</label></td>
			<td>{input._month.SWITCH}</td>
		</tr>
		<!-- END month -->
		</table>
		
		{S_MONTH}
	</td>
</tr>
<tr>
	<td class="row1"><label for="user_interval">{L_USER_INTERVAL}:</label></td>
	<td class="row2"><label><input type="radio" name="user_interval" value="1" id="user_interval" {S_INTAVAL_ONLY} />&nbsp;{L_INTAVAL_ONLY}</label><span style="padding:4px;"></span><label><input type="radio" name="user_interval" value="0" {S_INTAVAL_MONTH} />&nbsp;{L_INTAVAL_MONTH}</label></td>
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

<!-- BEGIN bankdata -->
<form action="{S_ACTION}" method="post">
<ul id="navlist">
	<li><a href="{S_ACTION}">{L_HEAD}</a></li>
	<li><a href="#" id="right" onclick="return false;">{L_BANKDATA}</a></li>
</ul>
<ul id="navinfo"><li>{L_REQUIRED}</li></ul>

<br /><div align="center">{ERROR_BOX}</div>

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