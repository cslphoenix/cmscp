<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head">{L_TEAMSPEAK}</td>
</tr>
<tr>
	<td>
		<table class="out" width="100%" cellspacing="0">
		<tr>
			<td class="row1r" width="50%">{t_name}:</td>
			<td class="row2r" align="center">{name}</td>
		</tr>
		<tr>
			<td class="row1r">{t_os}:</td>
			<td class="row2r" align="center">{os}</td>
		</tr>
		<tr>
			<td class="row1r">{t_uptime}:</td>
			<td class="row2r" align="center">{uptime}</td>
		</tr>
		<tr>
			<td class="row1r">{t_channels}:</td>
			<td class="row2r" align="center">{channels}</td>
		</tr>
		<tr>
			<td class="row1r">{t_user}:</td>
			<td class="row2r" align="center">{user}</td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		</table>
		
		<table class="out" width="100%" cellspacing="0">
		<tr>
			<td class="info_head" colspan="4">{users_head}</td>
		</tr>
		<tr>
			<td>{player}</td>
			<td>{channel}</td>
			<td align="right">{logintime}</td>
			<td align="right">{idletime}</td>
		</tr>
		<!-- BEGIN userstats -->
		<tr>
			<td class="{class}"><img src="images/teamspeak/channel.gif" alt="" /> {userstats.player}</td>
			<td class="{class}" >{userstats.channel}</td>
			<td class="{class}" align="right">{userstats.misc3}</td>
			<td class="{class}" align="right">{userstats.misc4}</td>
		</tr>
		<!-- END userstats -->
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		</table>
		
		<table class="out" width="100%" cellspacing="0">
		<tr>
			<td class="info_head" colspan="2">{channel_head}</td>
		</tr>
		<tr>
			<td class="row2r" width="50%" valign="top">
				<!-- BEGIN channel -->
				{channel.channel}
				<!-- BEGIN subchannel -->
				{channel.subchannel.subchannels}
				<!-- END subchannel -->
				<!-- END channel -->
			</td>
			<td class="row2r" width="50%" valign="top">
				<table cellpadding="0" cellspacing="0" width="98%" align="center">
				<form>
				{info}
				</form>
				</table>
			</td>
		</tr>
		</table>
	
	
	</td>
</tr>
</table>
