<!-- BEGIN display -->
<form action="{S_TRAINING_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{L_TRAINING_TITLE}</a></li>
		</ul>
	</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_TRAINING_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="3">{L_TRAINING}</td>
	<td class="rowHead" colspan="2">{L_SETTINGS}</td>
</tr>
<tr>
	<td class="rowHead" colspan="5">{L_UPCOMING}</td>
</tr>
<!-- BEGIN training_row_n -->
<tr>
	<td class="{display.training_row_n.CLASS}" align="center" width="1%">{display.training_row_n.I_IMAGE}</td>
	<td class="{display.training_row_n.CLASS}" align="left" width="100%">{display.training_row_n.NAME}</td>
	<td class="{display.training_row_n.CLASS}" align="center" nowrap="nowrap">{display.training_row_n.TRAINING_DATE}</td>
	<td class="{display.training_row_n.CLASS}" align="center" width="1%"><a href="{display.training_row_n.U_EDIT}">{L_SETTING}</a></td>		
	<td class="{display.training_row_n.CLASS}" align="center" width="1%"><a href="{display.training_row_n.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END training_row_n -->

<!-- BEGIN no_entry_new -->
<tr>
	<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_new -->

<tr>
	<td class="rowHead" colspan="5">{L_EXPIRED}</td>
</tr>
<!-- BEGIN training_row_o -->
<tr>
	<td class="{display.training_row_o.CLASS}" align="center" width="1%">{display.training_row_o.I_IMAGE}</td>
	<td class="{display.training_row_o.CLASS}" align="left" width="100%">{display.training_row_o.NAME}</td>
	<td class="{display.training_row_o.CLASS}" align="center" nowrap="nowrap">{display.training_row_o.TRAINING_DATE}</td>
	<td class="{display.training_row_o.CLASS}" align="center" width="1%"><a href="{display.training_row_o.U_EDIT}">{L_SETTING}</a></td>		
	<td class="{display.training_row_o.CLASS}" align="center" width="1%"><a href="{display.training_row_o.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END training_row_o -->
<!-- BEGIN no_entry_old -->
<tr>
	<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
</tr>
<!-- END no_entry_old -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="99%" align="right"><input class="post" name="training_vs" type="text" value="" /></td>
	<td width="1%" align="right">{S_TEAMS}</td>
	<td><input type="hidden" name="mode" value="training_add"><input class="button" type="submit" value="{L_TRAINING_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN training_edit -->
<form action="{S_TRAINING_ACTION}" method="post" name="form" onSubmit="javascript:return checkForm()">
	
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_TRAINING_ACTION}">{L_TRAINING_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_TRAINING_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>
<div align="center" id="msg" style="font-weight:bold; font-size:12px; color:#F00;"></div>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="160">{L_TRAINING_VS}: *</td>
	<td class="row2"><input id="training_vs" onBlur="javascript:checkEntry(this)" class="post" type="text" name="training_vs" value="{TRAINING_VS}" ></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_TEAM}: *</td>
	<td class="row2">{S_TEAMS}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MATCH}:</td>
	<td class="row2">{S_MATCH}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_DATE}:</td>
	<td class="row3">{S_DAY} . {S_MONTH} . {S_YEAR} - {S_HOUR} : {S_MIN}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_DURATION}:</td>
	<td class="row3">{S_DURATION}</td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_MAPS}: *</td>
	<td class="row3"><input id="training_maps" onBlur="javascript:checkEntry(this)" class="post" type="text" name="training_maps" value="{TRAINING_MAPS}" ></td>
</tr>
<tr>
	<td class="row1">{L_TRAINING_TEXT}:</td>
	<td class="row3"><textarea class="post" rows="5" cols="50" name="training_text">{TRAINING_TEXT}</textarea></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END training_edit -->