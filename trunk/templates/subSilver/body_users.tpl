<form action="{S_ACTION}" method="post" name="post">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td colspan="2" align="right" nowrap="nowrap"><span class="genmed"></span></td>
	</tr>
  </table>
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <th class="thTop" nowrap="nowrap">{L_USERNAME}</th>
	  <th class="thTop" nowrap="nowrap"></th>
	  <th class="thTop" nowrap="nowrap"></th>
	</tr>
	<!-- BEGIN _row -->
	<tr> 
	  <td class="{_row.ROW_CLASS}" align="center"><span class="gen"><a href="{_row.U_VIEWPROFILE}" class="gen">{_row.USERNAME}</a></span></td>
	  <td class="{_row.ROW_CLASS}" align="left" valign="middle">{_row.TEAMS}</td>
	  <td class="{_row.ROW_CLASS}" align="left" valign="middle">{_row.GROUPS}</td>
	</tr>
	<!-- END _row -->
	<!-- BEGIN _entry_empty -->
	<tr> 
	  <td class="row1" align="center" colspan="9"><span class="gen">&nbsp;{NO_USER_ID_SPECIFIED}&nbsp;</span></td>
	</tr>
	<!-- END _entry_empty -->
	<tr>
		<td colspan="3" align="right">{S_LETTER_SELECT} {S_MODE} {S_ORDER} <input type="submit" name="submit" value="{L_SUBMIT}" class="button2" /></td>
	<tr> 
	  <td class="catBottom" colspan="8" height="28">&nbsp;</td>
	</tr>
  </table>
  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td align="right" valign="top"></td>
	</tr>
  </table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr> 
	<td><span class="nav">{PAGE_NUMBER}{PAGINATION}</span></td>
  </tr>
</table>

{S_LETTER_HIDDEN}
</form>
