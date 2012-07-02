
<table class="out" width="100%" cellspacing="0">
<tr>
	<td>
		<!-- BEGIN show_sstats -->
		<table class="out" width="100%" cellspacing="0">
		<tr>
			<td class="info_head" colspan="2">{L_SSTATS}AS</td>
		</tr>
		<tr>
			<td>{L_SERVER_NAME}</td>
			<td>{SERVER_NAME}</td>
		</tr>
		<tr>
			<td>{L_SERVER_PLATFORM}</td>
			<td>{SERVER_PLATFORM}</td>
		</tr>
		<tr>
			<td>{L_SERVER_WELCOME_MSG}</td>
			<td>{SERVER_WELCOME_MSG}</td>
		</tr>
		<tr>
			<td>{L_SERVER_TYPE}</td>
			<td>{SERVER_TYPE}</td>
		</tr>
		<tr>
			<td>{L_SERVER_USER_MAX}</td>
			<td>{SERVER_USER_MAX}</td>
		</tr>
		<tr>
			<td>{L_SERVER_USER_CURRENT}</td>
			<td>{SERVER_USER_CURRENT}</td>
		</tr>
		<tr>
			<td>{L_SERVER_UPTIME}</td>
			<td>{SERVER_UPTIME}</td>
		</tr>
		<tr>
			<td>{L_SERVER_NUM_CHANNELS}</td>
			<td>{SERVER_NUM_CHANNELS}</td>
		</tr>
		</table>
		<!-- END show_sstats -->
	
		<!-- BEGIN show_userist -->
		<table class="out" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="info_head" colspan="2">{L_TS_PLIST}</td>
		</tr>
		<!-- BEGIN userlist -->
		<tr>
			<td class="{show_userist.userlist.CLASS}">{show_userist.userlist.USERNAME}</td>
			<td class="{show_userist.userlist.CLASS}">{show_userist.userlist.CHANNEL}</td>
		</tr>
		<!-- END userlist -->
		<tr>
			<td colspan="2"></td>
		</tr>
		</table>
		<!-- END show_userist -->
		
		<table class="out" width="100%" cellspacing="2">
		<!-- BEGIN channel -->
		<tr>
			<td valign="top">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="channel" width="25" valign="top" nowrap="nowrap"><img width="5" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/{channel.CHANNEL_ICON}" width="20" height="13" border="0" alt=""></td>
					<td class="channel" width="100%" valign="top" nowrap="nowrap">{channel.CHANNEL}</td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- BEGIN user -->
		<tr>
			<td>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="40" nowrap="nowrap"><img width="5" height="16" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/{channel.user.USERPIC}" width="20" height="16" border="0" alt=""></td>
					<td width="100%" nowrap="nowrap"><span {channel.user.USERHOVER}>{channel.user.USERNAME}{channel.user.USERSTATUS}</span></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- END user -->
		<!-- BEGIN subchannel -->
		<tr>
			<td valign="top">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="channel" width="40" valign="top" nowrap="nowrap"><img width="5" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img width="15" height="13" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/{channel.subchannel.SUBCHANNEL_ICON}" width="20" height="13" border="0" alt=""></td>
					<td class="channel" width="100%" valign="top" nowrap="nowrap">{channel.subchannel.SUBCHANNEL}</td>
				</tr>
				</table>
			</td>
		</tr>
		
		<!-- BEGIN subuser -->
		<tr>
			<td>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="player" width="55" nowrap="nowrap"><img width="5" height="16" src="images/teamspeak/blank.gif" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/blank.gif" width="15" height="16" border="0" alt=""><img src="images/teamspeak/{channel.subchannel.subuser.USERPIC}" width="20" height="16" border="0" alt=""></td>
					<td width="100%" nowrap="nowrap"><span {channel.subchannel.subuser.USERHOVER}>{channel.subchannel.subuser.USERNAME}{channel.subchannel.subuser.USERSTATUS}</span></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- END subuser -->
		<!-- END subchannel -->
		<!-- END channel -->
		
		<!-- BEGIN offline -->
		<tr>
			<td>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="offline" width="110" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td class="offline" width="110" align="center"><font class="heads"><b>Offline</b></font></td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- END offline -->	
		</table>
	</td>
</tr>
</table>
