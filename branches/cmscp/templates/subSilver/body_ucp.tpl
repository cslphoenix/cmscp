<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="3">{L_HEAD_UCP}</td>
</tr>
<!-- BEGIN lobby -->
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="ucp.php?mode=profile_edit">Profil bearbeiten</a></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="20%" valign="top" nowrap="nowrap">News:</td>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN news_new_row -->
				<tr>
					<td width="30%" nowrap="nowrap">{lobby.news_new_row.NEWS_NAME}</td>
					<td align="left">{lobby.news_new_row.NEWS_COMMENTS}</td>
					
				</tr>
				<!-- END news_new_row -->
				</table>			
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="20%" valign="top" nowrap="nowrap">Aktuelle Clanwars:</td>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN match_new_row -->
				<tr>
					<td width="30%" nowrap="nowrap">{match_new_row.MATCH_NAME}</td>
					<td align="left">{match_new_row.MATCH_COMMENTS}</td>
					
				</tr>
				<!-- END match_new_row -->
				</table>			
			</td>
		</tr>
		<!-- BEGIN match_old -->
		<tr>
			<td width="20%" valign="top" nowrap="nowrap">Abelaufene Clanwars:</td>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN match_old_row -->
				<tr>
					<td width="30%" nowrap="nowrap">{match_old.match_old_row.MATCH_NAME}</td>
					<td align="left">{match_old.match_old_row.MATCH_COMMENTS}</td>
				</tr>
				<!-- END match_old_row -->
				</table>			
			</td>
		</tr>
		<!-- END match_old -->
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<!-- BEGIN training -->
		<tr>
			<td width="20%" valign="top" nowrap="nowrap">Aktuelle Trainings:</td>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN training_new_row -->
				<tr>
					<td nowrap="nowrap">{training.training_new_row.TRAINING_NAME}</td>
					<td align="left">{training.training_new_row.TRAINING_COMMENTS}</td>
				</tr>
				<!-- END training_new_row -->
				</table>			
			</td>
		</tr>
		<!-- BEGIN training_old -->
		<tr>
			<td valign="top" nowrap="nowrap">Abelaufene Trainings:</td>
			<td align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<!-- BEGIN training_old_row -->
				<tr>
					<td width="20%" nowrap="nowrap">{training.training_old.training_old_row.TRAINING_NAME}</td>
					<td align="left">{training.training_old.training_old_row.TRAINING_COMMENTS}</td>
					
				</tr>
				<!-- END training_old_row -->
				</table>			
			</td>
		</tr>
		<!-- END training_old -->
		<!-- END training -->
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<!-- END lobby -->
<!-- BEGIN profile_edit -->


<!-- BEGIN catrow -->
<tr>
	<th colspan="2">{profile_edit.catrow.CATEGORY_NAME}</th>
</tr>
<!-- BEGIN profilerow -->
<tr> 
	<td class="row1" width="160">{profile_edit.catrow.profilerow.NAME}</td>
	<td class="row3">{profile_edit.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->

<!-- END profile_edit -->
</table>

