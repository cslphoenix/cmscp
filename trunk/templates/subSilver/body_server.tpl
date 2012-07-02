<!-- BEGIN list -->
<table class="match">
<tr>
	<td class="header" colspan="2">{L_GAMESERVER}</td>
</tr>
<!-- BEGIN game_row -->
<tr>
	<td class="{_list._gamerow.CLASS}">{_list._gamerow.NAME}</td>
	<td class="{_list._gamerow.CLASS}">{_list._gamerow.STATUS}</td>
</tr>
<!-- END game_row -->
<!-- BEGIN entry_empty_game -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
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
	<td class="{_list._voicerow.CLASS}">{_list._voicerow.NAME}</td>
	<td class="{_list._voicerow.CLASS}">{_list._voicerow.STATUS}</td>
</tr>
<!-- END voice_row -->
<!-- BEGIN entry_empty_voice -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
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
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td>{SERV}</td>
</tr>
<!-- BEGIN channel -->
<tr>
	<td>{_view.channel.CHANNEL}</td>
</tr>
<!-- BEGIN user -->
<tr>
	<td>{_view.channel.user.USER}</td>
</tr>
<!-- END user -->
<!-- BEGIN subchannel -->
<tr>
	<td>{_view.channel.subchannel.CHANNEL}</td>
</tr>
<!-- BEGIN user -->
<tr>
	<td>{_view.channel.subchannel.user.USER}</td>
</tr>
<!-- END user -->
<!-- END subchannel -->
<!-- END channel -->
</table>

<!-- END view -->
