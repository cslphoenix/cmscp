
<script language="javascript">

function hdr_ref(object)
{
	if (document.getElementById)
	{
		return document.getElementById(object);
	}
	else if (document.all)
	{
		return eval('document.all.' + object);
	}
	else
	{
		return false;
	}
}

function hdr_toggle(object, open_close, open_icon, close_icon)
{
	var object = hdr_ref(object);
	var icone = hdr_ref(open_close);

	if( !object.style )
	{
		return false;
	}

	if( object.style.display == 'none' )
	{
		object.style.display = '';
		icone.src = close_icon;
	}
	else
	{
		object.style.display = 'none';
		icone.src = open_icon;
	}
}
</script>

<!-- BEGIN display -->
<form action="{S_BT_ACTION}" method="post" id="bt_sort" name="bt_sort">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_BT_HEAD}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_BT_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_BT_NAME}</td>
</tr>
<!-- BEGIN bugtracker_row -->
<tr>
	<td class="{display.bugtracker_row.CLASS}" align="left" width="100%" onClick="hdr_toggle('bt_{display.bugtracker_row.BT_ID}','bt_{display.bugtracker_row.BT_ID}_open_close', './../admin/style/images/expand.gif', './../admin/style/images/collapse.gif'); return false;">
		<span style="float:right;">{display.bugtracker_row.BT_CREATOR} {display.bugtracker_row.BT_DATE}</span><img src="./../admin/style/images/expand.gif" id="bt_{display.bugtracker_row.BT_ID}_open_close" border="0" />{display.bugtracker_row.BT_TITLE}
	</td>
<!--<td class="{display.bugtracker_row.CLASS}" align="center" width="1%"><a href="{display.bugtracker_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.bugtracker_row.CLASS}" align="center" width="1%"><a href="{display.bugtracker_row.U_DELETE}">{L_DELETE}</a></td>-->
</tr>
<tr id="bt_{display.bugtracker_row.BT_ID}" style="display:none;">
	<td class="{display.bugtracker_row.CLASS}">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="15%">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>{L_DESCRIPTION}:</td>
			<td>{display.bugtracker_row.BT_DESC}</td>
		</tr>
		<tr>
			<td>{L_TYPE}:</td>
			<td>{display.bugtracker_row.BT_TYPE}</td>
		</tr>
		<tr>
			<td valign="top">{L_MESSAGE}:</td>
			<td>{display.bugtracker_row.BT_MESSAGE}</td>
		</tr>
		<tr onClick="hdr_toggle('bt_s_{display.bugtracker_row.BT_ID}','bt_s_{display.bugtracker_row.BT_ID}_open_close', './../admin/style/images/expand.gif', './../admin/style/images/collapse.gif'); return false;">
			<td><img src="./../admin/style/images/expand.gif" id="bt_s_{display.bugtracker_row.BT_ID}_open_close" border="0" /></td>
			<td></td>
		</tr>
		
		<tr id="bt_s_{display.bugtracker_row.BT_ID}" style="display:none;">
			<td colspan="2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="15%">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>{L_TYPE}:</td>
					<td>{display.bugtracker_row.S_TYPE}</td>
				</tr>
				<tr>
					<td>{L_STATUS}:</td>
					<td>{display.bugtracker_row.S_STATUS}</td>
				</tr>
				<tr>
					<td valign="top">{L_MESSAGE}:</td>
					<td></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				</table>
			</td></td>
			<td>
				
			
			</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END bugtracker_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="noentry" align="center" colspan="4">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{S_SORT} {PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
</form>

<!-- END display -->

<!-- BEGIN authlist_edit -->
<form action="{S_AUTHLIST_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_AUTHLIST_ACTION}">{L_AUTHLIST_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_AUTHLIST_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_AUTHLIST_NAME}: *</td>
	<td class="row3" width="80%"><input class="post" type="text" name="auth_name" value="{AUTH_NAME}" ></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END authlist_edit -->