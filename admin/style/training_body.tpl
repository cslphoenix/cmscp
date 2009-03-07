<form method="post" action="{S_TRAINING_ACTION}">
	<table class="head" cellspacing="0">
	<tr>
		<th>{L_TRAINING_TITLE}</th>
	</tr>
	<tr>
		<td class="row2">{L_TRAINING_EXPLAIN}</td>
	</tr>
	</table>
	
	<br />
	
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
		<td class="{training_row_n.CLASS}" align="center" width="1%">{training_row_n.I_IMAGE}</td>
		<td class="{training_row_n.CLASS}" align="left" width="100%">{training_row_n.NAME}</td>
		<td class="{training_row_n.CLASS}" align="center" nowrap>{training_row_n.TRAINING_DATE}</td>
		<td class="{training_row_n.CLASS}" align="center" width="1%"><a href="{training_row_n.U_EDIT}">{L_SETTING}</a></td>		
		<td class="{training_row_n.CLASS}" align="center" width="1%"><a href="{training_row_n.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END training_row_n -->
	
	<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
	
	<tr>
		<td class="rowHead" colspan="5">{L_EXPIRED}</td>
	</tr>
	<!-- BEGIN training_row_o -->
	<tr>
		<td class="{training_row_o.CLASS}" align="center" width="1%">{training_row_o.I_IMAGE}</td>
		<td class="{training_row_o.CLASS}" align="left" width="100%">{training_row_o.NAME}</td>
		<td class="{training_row_o.CLASS}" align="center" nowrap>{training_row_o.TRAINING_DATE}</td>
		<td class="{training_row_o.CLASS}" align="center" width="1%"><a href="{training_row_o.U_EDIT}">{L_SETTING}</a></td>		
		<td class="{training_row_o.CLASS}" align="center" width="1%"><a href="{training_row_o.U_DELETE}">{L_DELETE}</a></td>
	</tr>
	<!-- END training_row_o -->
	<!-- BEGIN no_entry -->
	<tr>
		<td class="row_class1" align="center" colspan="5">{NO_ENTRY}</td>
	</tr>
	<!-- END no_entry -->
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
		<td><input class="button" type="submit" name="add" value="{L_TRAINING_ADD}" /></td>
	</tr>
	</table>
</form>