<!-- BEGIN list -->
<table class="match">
<tr>
	<td class="header" colspan="2">{L_GAMESERVER}</td>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td class="{list.game_row.CLASS}" width="100%">{list.game_row.NAME}</td>
	<td class="{list.game_row.CLASS}" nowrap="nowrap">{list.game_row.USERS} {list.game_row.STATUS}</td>
</tr>
<!-- END game_row -->
<!-- BEGIN entry_empty_game -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END entry_empty_game -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_VOICESERVER}</td>
</tr>
<!-- BEGIN voice_row -->
<tr>
	<td class="{list.voice_row.CLASS}">{list.voice_row.NAME}</td>
	<td class="{list.voice_row.CLASS}">{list.voice_row.STATUS}</td>
</tr>
<!-- END voice_row -->
<!-- BEGIN entry_empty_voice -->
<tr>
	<td class="none" colspan="2">{L_NONE}</td>
</tr>
<!-- END entry_empty_voice -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END list -->

<!-- BEGIN view -->

<!-- BEGIN ts3 -->

	<div style="border:0px;">
	<div>{SERV}</div>
	<!-- BEGIN channel -->
	<div>{view.ts3.channel.CHANNEL}
		<!-- BEGIN user -->
		<div>{view.ts3.channel.user.USER}</div>
		<!-- END user -->
	</div>
	<!-- BEGIN subchannel -->
	<div>{view.ts3.channel.subchannel.CHANNEL}
		<!-- BEGIN user -->
		<div>{view.ts3.channel.subchannel.user.USER}</div>
		<!-- END user -->
	</div>
	<!-- END subchannel -->
	<!-- END channel -->
	</div>

	<!--
	<table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
	<tr>
		<td>{SERV}</td>
	</tr>
	<!-- BEGIN channel ->
	<tr>
		<td>{view.ts3.channel.CHANNEL}</td>
	</tr>
	<!-- BEGIN user ->
	<tr>
		<td>{view.ts3.channel.user.USER}</td>
	</tr>
	<!-- END user ->
	<!-- BEGIN subchannel ->
	<tr>
		<td>{view.ts3.channel.subchannel.CHANNEL}</td>
	</tr>
	<!-- BEGIN user ->
	<tr>
		<td>{view.ts3.channel.subchannel.user.USER}</td>
	</tr>
	<!-- END user ->
	<!-- END subchannel ->
	<!-- END channel ->
	</table>
	-->

<!-- END ts3 -->

<!-- BEGIN cstrike -->

<style>

div {
	width:80%;
	padding-left:5px;
}

span.lang {
	font-weight:bold;
	padding-left: 10px;
	padding-right: 2px;
	color: #B22222;
}

hr.hr_server {border: 0px; border-bottom: 1px solid #333;}

</style>

<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>{L_DETAILS}</th>
</tr>
</table>

<br />
<div>
	<span class="lang">{L_HOSTNAME}:</span> {HOSTNAME}
	<hr class="hr_server" />
</div>

<br />
<div>
	<span class="lang">{L_ADDRESS}:</span> {ADDRESS}<span class="lang">{L_JOIN}:</span> {JOIN}
	<hr class="hr_server" />
</div>

<br />
<div>
	<span class="lang">{L_MAP}:</span> {MAP}<span class="lang">{L_NEXTMAP}:</span> {NEXTMAP}
	<hr class="hr_server" />
</div>

<br />
<div>
	<span class="lang">{L_PLAYERS}:</span> {PLAYERS}
	<hr class="hr_server" />
</div>

<!-- END cstrike -->


<!-- END view -->
