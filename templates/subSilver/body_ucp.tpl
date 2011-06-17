<table class="out" width="100%" cellspacing="0">
<tr>
	<td class="info_head" colspan="3">{L_HEAD}</td>
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
			<td>{U_ALL_UNREAD}</td>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<!-- BEGIN _news -->
		<tr>
			<td valign="top">{L_NEWS}</td>
			<td align="left">
				<table cellspacing="0" cellpadding="0" class="lobby">
				<!-- BEGIN _news_row -->
				<tr>
					<td>{lobby._news._news_row.DATE}</td>
					<td>{lobby._news._news_row.TITLE}</td>
					<td>{lobby._news._news_row.COMMENT}</td>
					<td>&nbsp;</td>
				</tr>
				<!-- END _news_row -->
				</table>		
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<!-- END _news -->
		<!-- BEGIN _event_new -->
		<tr>
			<td valign="top">{L_UP_EVENT}:</td>
			<td align="left">
				<table cellspacing="0" cellpadding="0" class="lobby">
				<!-- BEGIN _event_new_row -->
				<tr>
					<td>{lobby._event_new._event_new_row.DATE}</td>
					<td>{lobby._event_new._event_new_row.TITLE}</td>
					<td>{lobby._event_new._event_new_row.COMMENT}</td>
					<td>{lobby._event_new._event_new_row.OPTION}</td>
				</tr>
				<!-- END _event_new_row -->
				</table>
			</td>
		</tr>
		<!-- END _event_new -->
		<!-- BEGIN _event_old -->
		<tr>
			<td valign="top">{L_EX_EVENT}:</td>
			<td align="left">
				<table cellspacing="0" cellpadding="0" class="lobby">
				<!-- BEGIN _event_old_row -->
				<tr>
					<td>{lobby._event_old._event_old_row.DATE}</td>
					<td>{lobby._event_old._event_old_row.TITLE}</td>
					<td>{lobby._event_old._event_old_row.COMMENT}</td>
					<td>&nbsp;</td>
				</tr>
				<!-- END _event_old_row -->
				</table>
			</td>
		</tr>
		<!-- END _event_old -->
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<!-- BEGIN _match_new -->
		<tr>
			<td valign="top">{L_UP_MATCH}:</td>
			<td align="left">
				<table cellspacing="0" cellpadding="0" class="lobby">
				<!-- BEGIN _match_new_row -->
				<tr>
					<td>{lobby._match_new._match_new_row.DATE}</td>
					<td>{lobby._match_new._match_new_row.TITLE}</td>
					<td>{lobby._match_new._match_new_row.COMMENT}</td>
					<td>{lobby._match_new._match_new_row.OPTION}</td>
				</tr>
				<!-- END _match_new_row -->
				</table>			
			</td>
		</tr>
		<!-- END _match_new -->
		<!-- BEGIN _match_old -->
		<tr>
			<td valign="top">{L_EX_MATCH}:</td>
			<td align="left">
				<table cellspacing="0" cellpadding="0" class="lobby">
				<!-- BEGIN _match_old_row -->
				<tr>
					<td>{lobby._match_old._match_old_row.DATE}</td>
					<td>{lobby._match_old._match_old_row.TITLE}</td>
					<td>{lobby._match_old._match_old_row.COMMENT}</td>
					<td>&nbsp;</td>
				</tr>
				<!-- END _match_old_row -->
				</table>			
			</td>
		</tr>
		<!-- END _match_old -->
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<!-- BEGIN training -->
		<tr>
			<td valign="top">Aktuelle Trainings:</td>
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
			<td valign="top">Abelaufene Trainings:</td>
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
	<td class="row2">{profile_edit.catrow.profilerow.FIELD}</td>
</tr>
<!-- END profilerow -->
<!-- END catrow -->

<!-- END profile_edit -->
</table>

