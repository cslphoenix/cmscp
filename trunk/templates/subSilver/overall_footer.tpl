			</td>
			<td width="15%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN _sn_minical -->
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
				<!-- END _sn_minical -->
				
				<!-- BEGIN _sn_next_match -->
				<tr>
					<td class="info_head"><span style="float:right;">{MONTH}</span>Wars</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _match_row -->
						<tr>
							<td width="99%">{_sn_next_match._match_row.GAME} {_sn_next_match._match_row.NAME}</td>
							<td width="1%" nowrap="nowrap">{_sn_next_match._match_row.DATE}</td>
						</tr>
						<!-- END _match_row -->
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				<!-- END _sn_next_match -->
				<!-- BEGIN _sn_next_training -->
				<tr>
					<td class="info_head"><span style="float:right;">{MONTH}</span>Trainings</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<!-- BEGIN _training_row -->
						<tr>
							<td width="1%">{_sn_next_training._training_row.GAME}</td>
							<td width="99%">&nbsp;{_sn_next_training._training_row.NAME}</td>
							<td width="1%" nowrap="nowrap">{_sn_next_training._training_row.DATE}</td>
						</tr>
						<!-- END _training_row -->
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<!-- END _sn_next_training -->
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
		<form action="{S_NEWSLETTER_ACTION}" method="post">
		<tr>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_28-14.png); height:125px; width:267px;" valign="top"></td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_29.png); height:125px; width:233px; padding: 5px;">
				
				<table class="out" width="100%" cellspacing="0" >
				<tr>
					<th>Newsletter</th>
				</tr>
				<tr>
					<td><input type="text" class="post" name="mail"  maxlength="100" size="25" value="email@adresse" onblur="this.className='post';if(this.value=='')this.value='email@adresse'" onfocus="this.className='post';if(this.value=='email@adresse') this.value='';" />  <input type="submit" name="submit" class="button3" value=""></td>
				</tr>
				<tr>
					<td> <input type="checkbox" name="unsubscribe" /> Austragen</td>
				</tr>
				<tr>
					<td>text newsletter eintragen austragen bla bla blubb</td>
				</tr>
				</table>
				
			</td>
			<td style="background-image:url(templates/subSilver/images/page_/democms1.2_32.png); height:125px; width:486px;"></td>
		</tr>
		</form>
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
				<tr style="height:23px;">
					<td style="padding:10px 5px 0px 5px;" width="100%">&copy; by CMS-Phoenix.de</td>
					<td style="padding:10px 5px 0px 5px;" nowrap="nowrap">{ADMIN_LINK}</td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:10px 5px 0px 5px;" nowrap="nowrap"><a href="imprint.php">Impressum</a></td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:10px 5px 0px 5px;" nowrap="nowrap"><a href="contact.php">Kontatkt</a></td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:10px 5px 0px 5px;" nowrap="nowrap">FAQ</td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
					<td style="padding:10px 5px 0px 5px;" nowrap="nowrap"><a href="rss/news.php">RSS-Feed</a></td>
					<td width="4" height="46"><img src="templates/subSilver/images/page_/democms1.2_39.png" alt=""></td>
				</tr>
				<tr>
					<td colspan="10">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="10%" valign="top" align="center" style="padding:5px 0px;" nowrap="nowrap">&nbsp;</td>
							<td colspan="6" width="80%" align="center">
								<div class="copyright">
									<br>
									powered by<a class="copyright" href="http://www.cms-phoenix.de/" target="_blank">CMS-Phoenix.de</a>&copy; 2009 Phoenix - Version: {VERSION}
									<br>
									{DEBUG} {CACHE}
								</div>
							</td>
							<td width="10%" valign="top" align="center" style="padding:5px 0px;" nowrap="nowrap">Nach Oben <img src="templates/subSilver/images/page_/democms1.3-schnitt_44.png" alt=""></td>
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