	
			</td>
			<td width="15%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN minical -->
				<tr>
					<td class="info_head"><span style="float:right">{CAL_CACHE}</span><span style="float:right;">{MONTH}&nbsp;</span>Kalender</td>
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
				<!-- END minical -->
				<!-- BEGIN match -->
				<tr>
					<td class="info_head"><span style="float:right;">{MONTH}</span>Wars</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN match_row -->
						<tr>
							<td width="99%"><a href="{match.match_row.U_NAME}">{match.match_row.L_NAME}</a></td>
							<td width="1%" nowrap>{match.match_row.DATE}</td>
						</tr>
						<!-- END match_row -->
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				<!-- END match -->
				<!-- BEGIN training -->
				<tr>
					<td class="info_head"><span style="float:right;">{MONTH}</span>Trainings</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN training_row -->
						<tr>
							<td width="99%"><a href="{training.training_row.U_NAME}">{training.training_row.L_NAME}</a></td>
							<td width="1%" nowrap>{training.training_row.DATE}</td>
						</tr>
						<!-- END training_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END training -->
				
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
	<td style="background-image:url(templates/subSilver/images/page_/democms1.2_28.png); padding: 2px 3px;"><span class="small">{LOGGED_IN_USER_LIST}</span></td>
</tr>
<tr>
	<td>
		<table width="986" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_28-14.png); height:125px; width:267px;" valign="top"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_29.png); height:125px; width:233px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_32.png); height:125px; width:486px;"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="background-image:url(templates/subSilver/images/page_/democms1.2_34.png); background-repeat:repeat; padding: 2px 3px;"><span class="small">{GROUPS_LEGENED}</span></td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_36.png); height:100px; width:27px;"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_37.png); height:100px; background-repeat:repeat-x;">
				<table width="100%" height="100" border="0" cellspacing="0" cellpadding="0">
				<tr style="height:46px;">
					<td style="padding:15px 10px 5px 10px;" width="100%">&copy; by CMS-CP.de</td>
					<td style="padding:15px 10px 5px 10px;" nowrap>Impressum</td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:15px 10px 5px 10px;" nowrap><a href="contact.php">Kontatkt</a></td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:15px 10px 5px 10px;" nowrap>FAQ</td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:15px 10px 5px 10px;" nowrap>RSS-Feed</td>
				</tr>
				<tr>
					<td colspan="8">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%" valign="top" align="center" style="padding:5px 0px;" nowrap>&nbsp;</td>
							<td colspan="6" width="80%" align="center">
							
								<div class="copyright">
									<br />
									{ADMIN_LINK}
									<br />
									powered by<a class="copyright" href="http://www.coding.phoenix-area51.de/" target="_blank">CMSCP</a>&copy; 2009 CMSCP Team
									<br />
									{DEBUG} {CACHE}
								</div>
								
							
							</td>
							<td width="10%" valign="top" align="center" style="padding:5px 0px;" nowrap>Nach Oben <img src="templates/subSilver/images/page_/democms1.3-schnitt_44.png" alt=""></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_41.png); height:100px; width:27px;"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>

<div align="center">{RUN_STATS_BOX}</div>
</body>
</html>