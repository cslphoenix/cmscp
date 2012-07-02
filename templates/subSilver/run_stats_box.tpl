<a name="debug"></a> 
<table cellpadding="2" cellspacing="1" border="0" width="100%" class="row4">
<tr>
	<td colspan="2" align="center"><span class="gensmall">{L_STAT_PAGE_DUR}{L_STAT_QUERIES}{L_STAT_SETUP} [ <a href="#debug" onclick="toggle('debug')">Explain</a> ]</span></td>
</tr>
<tbody id="debug" style="display:none">
<!-- BEGIN stat_run -->
<tr>
	<!-- BEGIN light -->
	<td>
	<!-- END light -->
	<!-- BEGIN light_ELSE -->
	<td>
	<!-- END light_ELSE -->
			{stat_run.STAT_FILE}<br />
			{stat_run.STAT_LINE}<br />
			{stat_run.STAT_TIME}
	</td>
	<!-- BEGIN light -->
	<td class="row2">
	<!-- END light -->
	<!-- BEGIN light_ELSE -->
	<td class="row2">
	<!-- END light_ELSE -->
		<table cellpadding="2" cellspacing="1" width="100%">
		<tr>
			<td><span class="gensmall">{L_STAT_REQUEST}</span></td>
		</tr>
		<tr>
			<td><span class="gensmall">{stat_run.STAT_REQUEST}</span></td>
		</tr>
		</table>
		<!-- BEGIN explain -->
		<table cellpadding="2" cellspacing="1" width="100%">
		<tr>
			<!-- BEGIN cell -->
			<td align="center"><span class="gensmall">{stat_run.explain.cell.STAT_LEGEND}</span></td>
			<!-- END cell -->
		</tr>
		<!-- BEGIN table -->
		<tr>
			<!-- BEGIN cell -->
			<!-- BEGIN light -->
			<td>
			<!-- END light -->
			<!-- BEGIN light_ELSE -->
			<td>
			<!-- END light_ELSE -->
			<span class="gensmall">&nbsp;{stat_run.explain.table.cell.STAT_VALUE}&nbsp;</span></td>
			<!-- END cell -->
		</tr>
		<!-- END table -->
		</table>
		<!-- END explain -->
	</td>
</tr>
<!-- END stat_run -->
</tbody>
</table>