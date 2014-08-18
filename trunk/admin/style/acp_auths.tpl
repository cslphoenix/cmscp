<form action="{S_ACTION}" method="post">
<h1>{L_HEAD}</h1>
<p>{L_EXPLAIN}L_EXPLAIN</p>

<script type="text/javascript">

function set_right(id,text)
{
	var obj = document.getElementById(id).value = text;
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

<!-- BEGIN display -->
{S_SELECT}
{S_HIDDEN}
<table class="lfooter">
<tr>
	<td><input type="submit" name="submit" value="{L_SUBMIT}" /></td>
	<td></td>
</table>

{S_FIELDS}
<!-- END display -->

<!-- BEGIN usergroups -->
</form>

<div style="float: left; width: 49%;">
	
	{L_USERS}
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_USERS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_USER_UPDATE}</dd>
				<dd class="full" style="text-align: right;"><label><input type="checkbox" class="radio" name="all_users" value="1" /> {L_ALL_USERS}</label></dd>
			</dl>
		</fieldset>
	
		<fieldset class="fast">
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="user">
			<input type="submit" name="submit_delete" value="{L_AUTH_DELETE}" class="delete"> &nbsp; <input type="submit" name="submit_update" value="{L_AUTH_UPDATE}">
		</fieldset>
	</form>

	<form action="{S_ACTION}" method="post" id="users_add">
		<fieldset>
			<legend>{L_USERS_ADDED}</legend>
			<dl>
				<dd class="full"><textarea id="username" name="user_names" rows="5" cols="5" style="width: 100%; height: 60px;"></textarea></dd>
			</dl>
		</fieldset>

		<fieldset class="fast">
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="user">
			<input type="submit" name="submit_create" value="{L_AUTH_CREATE}">
		</fieldset>
	</form>
</div>

<div style="float: right; width: 49%;">
	
	{L_GROUPS}
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_GROUPS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_GROUP_UPDATE}</dd>
				<dd class="full" style="text-align: right;"><label><input type="checkbox" class="radio" name="all_groups" value="1" /> {L_ALL_GROUPS}</label></dd>
			</dl>
		</fieldset>
	
		<fieldset class="fast">
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="group">
			<input type="submit" name="submit_delete" value="{L_AUTH_DELETE}" class="delete"> &nbsp; <input type="submit" name="submit_update" value="{L_AUTH_UPDATE}">
		</fieldset>
	</form>

	<form action="{S_ACTION}" method="post" id="groups_add">
		<fieldset>
			<legend>{L_GROUPS_ADDED}</legend>
			<dl>
				<dd class="full">{S_GROUP_CREATE}</dd>
			</dl>
		</fieldset>

		<fieldset class="fast">
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="group">
			<input type="submit" name="submit_create" value="{L_AUTH_CREATE}">
		</fieldset>
	</form>
</div>
<form action="{S_ACTION}" method="post">
<!-- END usergroups -->

<!-- BEGIN permission -->
permission
<script type="text/javascript">

function set_permission(type, forum, group)
{
	switch ( type )
	{
		<!-- BEGIN settings -->
		case '{permission.settings.ID}':
			<!-- BEGIN row -->
			document.getElementById('{permission.settings.row.FIELD}' + '[' + forum + '][' + group + ']' + '{permission.settings.row.VALUE}').checked = true;
			<!-- END row -->
		break;		
		<!-- END settings -->
	}
}

</script>

<!-- BEGIN row -->
<h2>{permission.row.NAME}</h2>
<!-- BEGIN group -->
<fieldset class="permissions" id="permission">
	<legend id="legend">{permission.row.group.NAME}</legend>
	<div class="permissions-switch">
		<div class="permissions-reset">
			<a href="#" onclick="mark_options('{permission.row.group.AUTHS}', 'y'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">Alle Ja</a> <a href="#" onclick="mark_options('{permission.row.group.AUTHS}', 'u'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">Alle Nein</a> <a href="#" onclick="mark_options('{permission.row.group.AUTHS}', 'n'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">Alle Nie</a>
		</div>
		<a href="#" onClick="toggle('{permission.row.group.AUTHS}'); return false;">Details</a>
	</div>
	<dl class="permissions-simple">
		<dt style="width: 20%;">{L_LABEL}:</dt>
		<dd style="margin-left: 20%;">{permission.row.group.SIMPLE}</dd>
	</dl>

	<div style="display:none;" id="{permission.row.group.AUTHS}" align="center">
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {permission.row.group.cats.AUTH} href="#{permission.row.group.cats.CAT}">{permission.row.group.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{permission.row.group.cats.CAT}" id="{permission.row.group.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_SETTINGS}</th>
					<th><a href="#" onclick="mark_options('{permission.row.group.cats.OPTIONS}', 'y'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">{OPT_YES}Ja</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.group.cats.OPTIONS}', 'u'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">{OPT_UNSET}Nein</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.group.cats.OPTIONS}', 'n'); reset_simpleauth('{permission.row.group.TOGGLE}'); return false;">{OPT_NEVER}Nie</a></th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td class="row_class1">{permission.row.group.cats.auths.OPT_NAME}</td>
					<td class="row_class2">{permission.row.group.cats.auths.OPT_YES}</td>
					<td class="row_class2">{permission.row.group.cats.auths.OPT_UNSET}</td>
					<td class="row_class2">{permission.row.group.cats.auths.OPT_NEVER}</td>
				</tr>
				<!-- END auths -->
				</table>
			</div>
			<!-- END cats -->
		</div>
	</div>
</fieldset>
<!-- END group -->
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="update" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_HIDDEN}
<!-- END permission -->

<!-- BEGIN auth_show -->
auth_show
<script type="text/javascript">

function set_permission(type, right)
{
	switch ( type )
	{
		<!-- BEGIN settings -->
		case '{auth_show.settings.ID}':
			<!-- BEGIN row -->
			document.getElementById('{auth_show.settings.row.FIELD}' + '[' + right + ']' + '{auth_show.settings.row.VALUE}').checked = true;
			<!-- END row -->
		break;		
		<!-- END settings -->
	}
}

</script>

<fieldset>
	<legend>{L_NAME}</legend>
	<dl>
		<dt>{L_LABEL}:</dt>
		<dd>{L_SIMPLE}<span class="right"><a href="#" class="closed right" onClick="toggle('{AUTHS}'); return false;">Details</a></span>{SIMPLE}</dd>
	</dl>

	<div style="display:;" id="{AUTHS}">
	<div class="right" style="padding:5px;">
		<a href="#" onclick="mark_options('{AUTHS}', 'y'); reset_simpleauth('{TOGGLE}'); return false;">Alle Ja</a>
		<a href="#" onclick="mark_options('{AUTHS}', 'u'); reset_simpleauth('{TOGGLE}'); return false;">Alle Nein</a>
		<a href="#" onclick="mark_options('{AUTHS}', 'n'); reset_simpleauth('{TOGGLE}'); return false;">Alle Nie</a>
	</div>
	<div align="center">
	<div class="tabs" align="right">
	<ul>
		<!-- BEGIN cats -->
		<li><a href="#{auth_show.cats.CAT}">{auth_show.cats.NAME}</a></li>
		<!-- END cats -->		
	</ul>
	<!-- BEGIN cats -->
	<div name="#{auth_show.cats.CAT}" id="{auth_show.cats.OPTIONS}">
		<table class="ttabs" cellpadding="1" cellspacing="1">
		<tr>
			<th>{L_SETTINGS}</th>
			<th><a href="#" onclick="mark_options('{auth_show.cats.OPTIONS}', 'y'); reset_simpleauth('{TOGGLE}'); return false;">{OPT_YES}Ja</a></th>
			<th><a href="#" onclick="mark_options('{auth_show.cats.OPTIONS}', 'u'); reset_simpleauth('{TOGGLE}'); return false;">{OPT_UNSET}Nein</a></th>
			<th><a href="#" onclick="mark_options('{auth_show.cats.OPTIONS}', 'n'); reset_simpleauth('{TOGGLE}'); return false;">{OPT_NEVER}Nie</a></th>
		</tr>
		<!-- BEGIN auths -->
		<tr>
			<td class="row_class1">{auth_show.cats.auths.LANG}</td>
			<td class="row_class2">{auth_show.cats.auths.OPT_YES}</td>
			<td class="row_class2">{auth_show.cats.auths.OPT_UNSET}</td>
			<td class="row_class2">{auth_show.cats.auths.OPT_NEVER}</td>
		</tr>
		<!-- END auths -->
		</table>
	</div>
	<!-- END cats -->
	</div>
	</div>
</div>
</fieldset>

<div class="submit">
<dl>
	<dt>update<input type="submit" name="update" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_HIDDEN}

<!-- END auth_show -->
</form>