<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>



<table class="lfooter">
<tr>
	<td>{S_SELECT}</td>
	<td></td>
</table>

<table class="lfooter">
<tr>
	<td><input type="submit" value="{L_SUBMIT}" /></td>
	<td></td>
</table>

{S_FIELDS}
</form>
<!-- END display -->

<!-- BEGIN auth_show -->
<script type="text/javascript">

function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}

function set_permission(type, right)
{
	
//	alert(forms);
//	.checked = true;
	switch ( type )
	{
		<!-- BEGIN set_perm -->
		case '{auth_show.set_perm.ID}':
			<!-- BEGIN row -->
			document.getElementById('{auth_show.set_perm.row.FIELD}' + '[' + right + ']' + '{auth_show.set_perm.row.VALUE}').checked = true;
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

/**
* Mark all radio buttons in one panel
* id = table ID container, s = status ['y'/'u'/'n']
*/
function mark_options(id, s)
{
	var t = document.getElementById(id);

	if (!t)
	{
		return;
	}

	var rb = t.getElementsByTagName('input');

	for (var r = 0; r < rb.length; r++)
	{
		if (rb[r].id.substr(rb[r].id.length-1) == s)
		{
			rb[r].checked = true;
		}
	}
}

</script>

<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{NAME}</a></li></ul>
<table class="update">
<tr>
	<td class="row1">{L_AUTH}select_auth:</td>
	<td class="row2"><span class="right"><a href="#" onClick="toggle('{auth_show.TOGGLE}'); return false;">Details</a></span>{SIMPLE}</td>
</tr>
</table>

<!--<span class="right"><a href="#" onclick="mark_options('{auth_show.NAME}0', 'y');">Alle <strong>Ja</strong></a> &middot; <a href="#" onclick="mark_options('{auth_show.NAME}0', 'n');">Alle <strong>Nein</strong></a> &middot; <a href="#" onclick="mark_options('{auth_show.NAME}0', 'u');">Alle <strong>Nie</strong></a></span>-->

<div style="display:;" align="center"  id="{auth_show.NAME}0">
	<div class="tabs">
	<ul>
		<!-- BEGIN cats -->
		<li><a href="#{auth_show.cats.CAT}">{auth_show.cats.NAME}</a></li>
		<!-- END cats -->		
	</ul>
	<!-- BEGIN cats -->
	<div name="#{auth_show.cats.CAT}">
		<table class="index">
		<!-- BEGIN auths -->
		<tr>
			<td>{auth_show.cats.auths.LANG}</td>
			<td>{auth_show.cats.auths.AUTH}</td>
		</tr>
		<!-- END auths -->
		</table>
	</div>
	<!-- END cats -->
	</div>
</div>

<table class="out" cellspacing="0">
<tr>
	<td align="center"><input type="submit" name="submit" value="{L_SUBMIT}" /> <input type="reset" value="{L_RESET}" name="reset"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END auth_show -->