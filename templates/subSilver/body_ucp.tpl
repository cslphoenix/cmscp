<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th colspan="3">{L_HEAD}</th>
</tr>
<!-- BEGIN lobby -->
<tr>
	<td colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>&nbsp;</td>
			<td><a href="ucp.php?mode=profile_edit">Profil bearbeiten</a></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>{U_ALL_UNREAD}</td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN news -->
<tr>
	<th colspan="2"><span class="right">{UNREAD_NEWS}</span>{L_NEWS}</th>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN news_row -->
		<tr>
			<td><span title="{lobby.news.news_row.DATEI}">{lobby.news.news_row.DATE}</span></td>
			<td>{lobby.news.news_row.TITLE}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END news_row -->
		</table>		
	</td>
</tr>
<tr>
	<td colspan="2"></td>
</tr>
<!-- END news -->

<!-- BEGIN event -->
<tr>
	<th colspan="2"><span class="right">{UNREAD_EVENT}</span>{L_EVENT}</th>
</tr>
<!-- BEGIN new -->
<tr>
	<td class="top right">{L_UPCOMING}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN new_row -->
		<tr>
			<td>{lobby.event.new.new_row.DATE}</td>
			<td>{lobby.event.new.new_row.TITLE}</td>
			<td>{lobby.event.new.new_row.OPTION}</td>
		</tr>
		<!-- END new_row -->
		</table>		
	</td>
</tr>
<!-- END new -->
<!-- BEGIN old -->
<tr>
	<td class="top right">{L_EXPIRED}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN old_row -->
		<tr>
			<td>{lobby.event.old.old_row.DATE}</td>
			<td>{lobby.event.old.old_row.TITLE}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END old_row -->
		</table>
	</td>
</tr>
<!-- END old -->
<tr>
	<td colspan="2"></td>
</tr>
<!-- END event -->

<!-- BEGIN match -->
<tr>
	<th colspan="4"><span class="right">{UNREAD_MATCH}</span>{L_MATCH}</th>
</tr>
<!-- BEGIN new -->
<tr>
	<td class="top right">{L_UPCOMING}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN new_row -->
		<tr>
			<td>{lobby.match.new.new_row.DATE}</td>
			<td>{lobby.match.new.new_row.TITLE}</td>
			<td>{lobby.match.new.new_row.OPTION}</td>
		</tr>
		<!-- END new_row -->
		</table>
	</td>
</tr>
<!-- END new -->

<!-- BEGIN old -->
<tr>
	<td class="top right">{L_EXPIRED}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN old_row -->
		<tr>
			<td>{lobby.match.old.old_row.DATE}</td>
			<td>{lobby.match.old.old_row.TITLE}</td>
			<td>&nbsp;</td>
		</tr>
		<!-- END old_row -->
		</table>			
	</td>
</tr>
<!-- END old -->
<tr>
	<td colspan="2"></td>
</tr>
<!-- END match -->

<!-- BEGIN train -->
<tr>
	<th colspan="4"><span class="right">{UNREAD_TRAIN}</span>{L_TRAIN}</th>
</tr>
<!-- BEGIN new -->
<tr>
	<td class="top right">{L_UPCOMING}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN new_row -->
		<tr>
			<td>{lobby.train.new.new_row.DATE}</td>
			<td>{lobby.train.new.new_row.TITLE}</td>
			<td>{lobby.train.new.new_row.OPTION}</td>
		</tr>
		<!-- END new_row -->
		</table>
	</td>
</tr>
<!-- END new -->
<!-- BEGIN old -->
<tr>
	<td class="top right">{L_EXPIRED}</td>
	<td>
		<table class="type6" width="100%" cellpadding="0" cellspacing="0" border="0">
		<!-- BEGIN old_row -->
		<tr>
			<td>{lobby.train.old.old_row.DATE}</td>
			<td>{lobby.train.old.old_row.TITLE}</td>
			<td>{lobby.train.old.old_row.OPTION}</td>
		</tr>
		<!-- END old_row -->
		</table>
	</td>
</tr>
<!-- END old -->
<!-- END train -->
<!-- END lobby -->

<!-- BEGIN update -->
<tr>
	<td colspan="2"></td>
</tr>
<!-- BEGIN cat -->
<tr>
	<th colspan="2">{update.cat.CATEGORY_NAME}</th>
</tr>
<!-- BEGIN field -->
<tr> 
	<td class="row1" width="160">{update.cat.field.NAME}</td>
	<td class="row2">{update.cat.field.INPUT}</td>
</tr>
<!-- END field -->
<!-- END cat -->

<!-- END update -->
</table>

