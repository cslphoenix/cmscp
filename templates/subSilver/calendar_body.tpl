<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="info_head"><span style="float:right">{MONTH}</span>Kalender</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			{DAYS}
		</tr>
		<tr>
			
		</tr>
		</table>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
</tr>
</table>
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="info_head" colspan="2"><span style="float:right">{MONTH}</span>Kalender</td>
</tr>
<!-- BEGIN days -->
<tr>
	<td class="{days.CLASS} row4" valign="top" align="center" width="5%"><a name="{days.CAL_ID}"></a>{days.CAL_DAY}</td>
	<td class="{days.CLASS} row4" valign="top" width="95%">{days.CAL_EVENT}</td>
</tr>
<!-- END days -->
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td class="info_foot" colspan="2">Legende: <span style="font-weight:normal;"><span class="today">Heute</span>, <span class="birthday">Geburtstage</span>, <span class="events">Ereignisse</span>, <span class="wars">Wars</span>, <span class="trains">Trainings</span>, <span class="more">mehrere Ereignisse an einem Tag</span></span></td>
</tr>
</table>
