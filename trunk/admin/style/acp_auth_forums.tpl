<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

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

<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
{S_GROUP_IN}
{S_FIELDS}
<input type="submit" name="grp_delete" value="{L_SUBMIT} delete" />
<input type="submit" name="grp_update" value="{L_SUBMIT}" />
</form>
<br />
<form action="{S_ACTION}" method="post">
{S_GROUP_OUT}
{S_FIELDS}
<input type="submit" name="grp_create" value="{L_SUBMIT}" />


</form>
<!-- END select -->

<!-- BEGIN auth_forum -->
<script type="text/javascript">

function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
}

function set_permission(type, forum, group)
{
//	alert(type);
//	alert(forum);
//	alert(group);
	
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
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}</p>

<!-- BEGIN row -->
<ul id="navlist"><li id="active"><a href="#" id="current" onclick="return false;">{auth_forum.row.NAME}</a></li></ul>
<!-- BEGIN group -->

<div class="update">
<dl>
	<dt>{auth_forum.row.group.NAME}</dt>
	<dd>{auth_forum.row.group.SIMPLE}<a href="#" class="closed right" onClick="toggle('{auth_forum.row.group.AUTHS}'); return false;">Details</a></dd>
</dl>
</div>

<div style="display:;" id="{auth_forum.row.group.AUTHS}">
	<div class="right" style="padding:5px;">
		<a href="#" onclick="mark_options('{auth_forum.row.group.AUTHS}', 'y'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">Alle Ja</a>
		<a href="#" onclick="mark_options('{auth_forum.row.group.AUTHS}', 'u'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">Alle Nein</a>
		<a href="#" onclick="mark_options('{auth_forum.row.group.AUTHS}', 'n'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">Alle Nie</a>
	</div>
	<div align="center">
	<div class="tabs" align="right">
	<ul>
		<!-- BEGIN cats -->
		<li><a {auth_forum.row.group.cats.AUTH} href="#{auth_forum.row.group.cats.CAT}">{auth_forum.row.group.cats.NAME}</a></li>
		<!-- END cats -->
	</ul>
	<!-- BEGIN cats -->
	<div name="#{auth_forum.row.group.cats.CAT}" id="{auth_forum.row.group.cats.OPTIONS}">
		<table class="ttabs">
		<tr>
			<th>{L_SETTINGS}</th>
			<th><a href="#" onclick="mark_options('{auth_forum.row.group.cats.OPTIONS}', 'y'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">{OPT_YES}Ja</a></th>
			<th><a href="#" onclick="mark_options('{auth_forum.row.group.cats.OPTIONS}', 'u'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">{OPT_UNSET}Nein</a></th>
			<th><a href="#" onclick="mark_options('{auth_forum.row.group.cats.OPTIONS}', 'n'); reset_simpleauth('{auth_forum.row.group.TOGGLE}'); return false;">{OPT_NEVER}Nie</a></th>
		</tr>
		<!-- BEGIN auths -->
		<tr>
			<td>{auth_forum.row.group.cats.auths.LANG}</td>
			<td>{auth_forum.row.group.cats.auths.OPT_YES}</td>
			<td>{auth_forum.row.group.cats.auths.OPT_UNSET}</td>
			<td>{auth_forum.row.group.cats.auths.OPT_NEVER}</td>
		</tr>
		<!-- END auths -->
		</table>
	</div>
	<!-- END cats -->
	</div>
	</div>
</div>
<!-- END group -->
<br />
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END auth_forum -->