<!-- BEGIN _list -->
<table class="match">
<tr>
	<td class="header" colspan="2">{L_GAMESERVER}</td>
</tr>
<!-- BEGIN _game_row -->
<tr>
	<td class="{_list._game_row.CLASS}">{_list._game_row.NAME}</td>
	<td class="{_list._game_row.CLASS}">{_list._game_row.STATUS}</td>
</tr>
<!-- END _game_row -->
<!-- BEGIN _entry_empty_game -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_game -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td class="header" colspan="3">{L_VOICESERVER}</td>
</tr>
<!-- BEGIN _voice_row -->
<tr>
	<td class="{_list._voice_row.CLASS}">{_list._voice_row.NAME}</td>
	<td class="{_list._voice_row.CLASS}">{_list._voice_row.STATUS}</td>
</tr>
<!-- END _voice_row -->
<!-- BEGIN _entry_empty_voice -->
<tr>
	<td class="entry_empty" colspan="2">{L_ENTRY_NO}</td>
</tr>
<!-- END _entry_empty_voice -->
</table>

<br />

<table class="news" width="100%" cellspacing="0">
<tr>
	<td class="footer"><span class="right">{PAGE_NUMBER}</span>{PAGE_PAGING}</td>
</tr>
</table>
<!-- END _list -->

<!-- BEGIN _view -->
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

<!-- END _view -->
