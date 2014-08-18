<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
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
	
	<strong>{L_USERS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_USERS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_USER_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_users" value="1" />{L_USERS_ALL}</label></dd>
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
				<dd class="full"><textarea id="username" name="user_names" style="width: 100%; height: 70px;"></textarea></dd>
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
	
	<strong>{L_GROUPS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_GROUPS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_GROUP_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_groups" value="1" />{L_GROUPS_ALL}</label></dd>
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

<!-- BEGIN show -->
</form>

<div style="float: left; width: 49%;">
	
	<strong>{L_USERS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_USERS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_USER_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_users" value="1" />{L_USERS_ALL}</label></dd>
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
				<dd class="full"><textarea id="username" name="user_names" style="width: 100%; height: 70px;"></textarea></dd>
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
	
	<strong>{L_GROUPS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_GROUPS_MANAGE}</legend>
			<dl>
				<dd class="full">{S_GROUP_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_groups" value="1" />{L_GROUPS_ALL}</label></dd>
			</dl>
		</fieldset>
	
		<fieldset class="fast">
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="group">
			<input type="submit" name="submit_delete" value="{L_AUTH_DELETE}" class="delete"> &nbsp;<input type="submit" name="submit_show" value="{L_AUTH_UPDATE}">
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
<!-- END show -->

<!-- BEGIN permission -->
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

{S_OPTIONS}

<!-- BEGIN row -->
<fieldset>
	<legend id="legend">{permission.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="permissions" id="permission">
	<legend id="legend">{permission.row.parent.NAME}</legend>
	<div class="permissions-switch">
		<div class="permissions-reset">
			<a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'y'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">Alle Ja</a> <a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'u'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">Alle Nein</a> <a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'n'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">Alle Nie</a>
		</div>
		<a href="#" onClick="toggle('{permission.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a>
	</div>
	<dl class="permissions-simple">
		<dt style="width: 20%; padding-top:3px;">{permission.row.parent.LABEL}:</dt>
		<dd style="margin-left: 20%;">{permission.row.parent.SIMPLE}</dd>
	</dl>

	<div style="display:none;" id="{permission.row.parent.AUTHS}" align="center">
	    <br />
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {permission.row.parent.cats.AUTH} href="#{permission.row.parent.cats.CAT}">{permission.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{permission.row.parent.cats.CAT}" id="{permission.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_SETTINGS}</th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'y'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{OPT_YES}Ja</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'u'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{OPT_UNSET}Nein</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'n'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{OPT_NEVER}Nie</a></th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td>{permission.row.parent.cats.auths.OPT_NAME}</td>
					<td>{permission.row.parent.cats.auths.OPT_YES}</td>
					<td>{permission.row.parent.cats.auths.OPT_UNSET}</td>
					<td>{permission.row.parent.cats.auths.OPT_NEVER}</td>
				</tr>
				<!-- END auths -->
				</table>
			</div>
			<!-- END cats -->
		</div>
	</div>
</fieldset>
<!-- END parent -->
</fieldset>
<!-- END row -->

<div class="submit">
<dl>
	<dt><input type="submit" name="update" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_HIDDEN}
<!-- END permission -->

<!-- BEGIN view -->
{S_OPTIONS}

<!-- BEGIN row -->
<fieldset>
	<legend id="legend">{view.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="views" id="view">
	<legend id="legend">{view.row.parent.NAME}</legend>
	<div class="views-switch">
		
		<a href="#" onClick="toggle('{view.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a>
	</div>
	<div id="{view.row.parent.AUTHS}" align="center">
	    <br />
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {view.row.parent.cats.AUTH} href="#{view.row.parent.cats.CAT}">{view.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{view.row.parent.cats.CAT}" id="{view.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_VIEW_AUTH}</th>
					<th>{L_YES}</th>
					<th>{L_NO}</th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td>{view.row.parent.cats.auths.OPT_NAME}</td>
					<td class="{view.row.parent.cats.auths.CSS_YES}">&nbsp;</td>
					<td class="{view.row.parent.cats.auths.CSS_NO}">&nbsp;</td>
				</tr>
				<!-- END auths -->
				</table>
			</div>
			<!-- END cats -->
		</div>
	</div>
</fieldset>
<!-- END parent -->
</fieldset>
<!-- END row -->
{S_HIDDEN}
<!-- END view -->

</form>