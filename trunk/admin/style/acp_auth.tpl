<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

{S_FORMS}

<table class="lfooter">
<tr>
	<td><input type="submit" name="sel_f" value="{L_SUBMIT}" /></td>
	<td></td>
</table>

{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN select -->

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<form action="{S_ACTION}" method="post">
{S_GROUPS_IN}
{S_FIELDS}
<input type="submit" name="grp_delete" value="{L_SUBMIT} delete" />
<input type="submit" name="grp_update" value="{L_SUBMIT}" />
</form>
<br />
<form action="{S_ACTION}" method="post">
{S_GROUPS_OUT}
{S_FIELDS}
<input type="submit" name="grp_create" value="{L_SUBMIT}" />


</form>
<!-- END select -->

<!-- BEGIN auth_forum -->
<script type="text/javascript">

$(function() { $('.tabs').liteTabs({ borders: true, height: 'auto', selectedTab: 1, width: '750' }); });

function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}

function set_permission(type, forum, group)
{
	
//	alert(forms);
//	.checked = true;
	switch ( type )
	{
		<!-- BEGIN set_perm -->
		case '{auth_forum.set_perm.ID}':
			<!-- BEGIN row -->
			document.getElementById('{auth_forum.set_perm.row.FIELD}' + '[' + forum + '][' + group + ']' + '{auth_forum.set_perm.row.VALUE}').checked = true;
			<!-- END row -->
		break;		
		<!-- END set_perm -->
	}
}

function reset_simpleauth(id)
{
	if (!document.getElementById(id))
	{
		return;
	}

	document.getElementById(id).options[0].selected = true;
}
</script>

<form action="{S_ACTION}" method="post">
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{L_HEAD}</a></li></ul>
<ul id="navinfo"><li>{L_EXPLAIN}</li></ul>

<!-- BEGIN row -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{auth_forum.row.NAME}</a></li></ul>
<table class="update" border="1">
<!-- BEGIN group -->
<tr>
	<th align="right" style="padding:5px;">{auth_forum.row.group.NAME}</th>
	<th></th>
</tr>
<tr>
	<td class="row1">{L_AUTH}auth:</td>
	<td class="row2">{auth_forum.row.group.SIMPLE}<a href="#" class="right closed" onClick="toggle('{auth_forum.row.group.TOGGLE}'); return false;">Details</a></td>
</tr>
<tr>
	<td colspan="2" class="row3">
		<div id="{auth_forum.row.group.TOGGLE}" style="display:none;" align="center">
			<div class="tabs">
			<ul>
				<li><a href="#1">{L_AUTH_FORUM}</a></li>
				<li><a href="#2">{L_AUTH_OPTION}</a></li>
				<li><a href="#3">{L_AUTH_POLL}</a></li>
			</ul>
			<div name="#1">
				<table class="index">
				<!-- BEGIN fauth -->
				<tr>
					<td>{auth_forum.row.group.fauth.LANG}</td>
					<td>{auth_forum.row.group.fauth.AUTH}</td>
				</tr>
				<!-- END fauth -->
				</table>
			</div>
			<div name="#2">
				<table class="index">
				<!-- BEGIN mauth -->
				<tr>
					<td>{auth_forum.row.group.mauth.LANG}</td>
					<td>{auth_forum.row.group.mauth.AUTH}</td>
				</tr>
				<!-- END mauth -->
				</table>
			</div>
			<div name="#3">
				<table class="index">
				<!-- BEGIN pauth -->
				<tr>
					<td>{auth_forum.row.group.pauth.LANG}</td>
					<td>{auth_forum.row.group.pauth.AUTH}</td>
				</tr>
				<!-- END pauth -->
				</table>
			</div>
			</div>
		</div>
	</td>
</tr>
<!-- END group -->
</table>
<!-- END row -->

<table class="out" cellspacing="0">
<tr>
	<td align="center"><input type="submit" name="submit" value="{L_SUBMIT}" /> <input type="reset" value="{L_RESET}" name="reset"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END auth_forum -->