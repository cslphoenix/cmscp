<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="info_head"><span style="float:right;">{L_STATS_FROM}</span>Counter</td>
</tr>
<tr>
	<td class="row4">
		<form method="post" action="{S_COUNTER_ACTION}">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="50%" valign="top">{SELECT_DAY}{SELECT_MONTH}{SELECT_YEAR} <input class="button" type="submit" name="submit" value="Daten anzeigen"></td>
			<td width="50%" valign="top" align="right">{L_DAY}: {COUNT_DAY} - {L_MONTH}: {COUNT_MONTH} - {L_YEAR}: {COUNT_YEAR}
			</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<!-- BEGIN table_row -->
		<table cellpadding="1" style="width:100%; height:400px; text-align:center;  font-size:10px; margin:0px;">
		<tr>
			<td colspan="{table_row.COL}" style="height:5px;">{table_row.VALUE}</td>
		</tr>
		
		<tr>
			<!-- BEGIN balken_row -->
			<td style="vertical-align:bottom; height:5px; width:{table_row.balken_row.WERT1}px;"><div style="margin:auto; background-color:red; height:{table_row.balken_row.WERT2}px; width:{table_row.balken_row.WERT3}" title="{table_row.balken_row.WERT4}"></div></td>
			<!-- END balken_row -->
		</tr>
		
		<tr>
			<!-- BEGIN stellen_row -->
			<td style=" border:solid 1px #fff; height:5px;">{table_row.stellen_row.STELLE}</td>
			<!-- END stellen_row -->
		</tr>
		
		
		
		</table>
		<!-- END table_row -->
	</td>
</tr>
<tr>
			<td>&nbsp;</td>
		</tr>
</table>
