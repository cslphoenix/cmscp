	
			</td>
			<td width="15%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN minical -->
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
							{DAY}
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td class="info_head"><span style="float:right">{MONTH}</span>Wars</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN match -->
						<tr>
							<td width="99%">{minical.match.NAME}</td>
							<td width="1%" nowrap>{minical.match.DATE}</td>
						</tr>
						<!-- END match -->
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="info_head"><span style="float:right">{MONTH}</span>Trainings</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN training -->
						<tr>
							<td width="99%">{minical.training.NAME}</td>
							<td width="1%" nowrap>{minical.training.DATE}</td>
						</tr>
						<!-- END training -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END minical -->
				<!-- BEGIN teams -->
				<tr>
					<td class="info_head">{L_TEAMS}</td>
				</tr>
				<tr>
					<td class="row4">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN teams_row -->
						<tr>
							
							<td width="90%" align="left" style="vertical-align:top;"><a href="{teams.teams_row.TO_TEAM}">&raquo; {teams.teams_row.TEAM_NAME}</a></td>
							<td width="10%">{teams.teams_row.TEAM_GAME}</td>
						</tr>
						<!-- END teams_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END teams -->
				<tr>
					<td>&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_22-16.png); height:20px;"></td>
</tr>
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page/phx_23.png); height:125px; width:267px;" valign="top"><span class="small">{LOGGED_IN_USER_LIST}</span></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_25.png); height:125px; width:233px;"></td>
			<td style="background-image:url(templates/subSilver/images/page/phx_32.png); height:125px; width:486px;"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page/phx_29.png); height:81px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10%" align="center">C clanname ....</td>
					<td width="50%">&nbsp;</td>
					<td width="9%" align="center">Impressum</td>
					<td width="1%" align="center">|</td>
					<td width="9%" align="center">Kontakt</td>
					<td width="1%" align="center">|</td>
					<td width="9%" align="center">FAQ</td>
					<td width="1%" align="center">|</td>
					<td width="9%" align="center">RSS-Feed</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding:3px;" align="center"><hr width="95%" color="#999"></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="80%"><div class="copyright"><br />{ADMIN_LINK}<br />
	powered by<a class="copyright" href="http://www.coding.phoenix-area51.de/" target="_blank">CMSCP</a>&copy; 2009 CMSCP Team
</div></td>
					<td width="10%" align="center">Nach oben</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>

<div align="center">{RUN_STATS_BOX}</div>
</body>
</html>