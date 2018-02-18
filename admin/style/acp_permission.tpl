<li class="header">{L_HEADER}<span class="right"><span class="rightd">{L_OPTION}</span></span></li>

<p>{L_EXPLAIN}<br /><br />{L_SWITCH}</p>

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

function lookup(user_name, user_level)
{
	if ( user_name.length == 0 ) { $('#suggestions').hide(); }
	else
	{
		$.post("./ajax/ajax_user.php", {user_name: ""+user_name+"", user_level: ""+user_level+""}, function(data)
		{
			if ( data.length > 0 )
			{
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			}
		});
	}
}

function fill(thisValue)
{
	$('#user_name').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
}

</script>

<!-- BEGIN display -->
<fieldset>
	<legend>{NAME}</legend>
	<dl>
		<dt style="padding-top:3px;">{L_SELECT}:</dt>
		<dd>{S_SELECT}</dd>
	</dl>
</fieldset>
{S_HIDDEN}
<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd></dd>
</dl>
</div>
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
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_users" value="1" />&nbsp;{L_USERS_ALL}</label><br />{S_USER_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><input type="submit" name="submit_delete" value="{L_AUTH_DELETE}" class="delete">&nbsp;<input type="submit" name="submit_update" value="{L_AUTH_UPDATE}"></dd>
			</dl>
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="user">
		</fieldset>
	</form>

	<form action="{S_ACTION}" method="post" id="users_add">
		<fieldset>
			<legend>{L_USERS_ADDED}</legend>
			<dl>
				<dd class="full"><textarea id="username" name="user_names" style="width: 100%; height: 70px;"></textarea></dd>
				<dd class="full" style="text-align: left;"><input type="submit" name="submit_create" value="{L_AUTH_CREATE}"></dd>
			</dl>
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="user">
		</fieldset>
	</form>
</div>

<div style="float: right; width: 49%;">
	
	<strong>{L_GROUPS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_GROUPS_MANAGE}</legend>
			<dl>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_groups" value="1" />&nbsp;{L_GROUPS_ALL}</label><br />{S_GROUP_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><input type="submit" name="submit_delete" value="{L_AUTH_DELETE}" class="delete">&nbsp;<input type="submit" name="submit_update" value="{L_AUTH_UPDATE}"></dd>
			</dl>
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="group">
		</fieldset>
	</form>

	<form action="{S_ACTION}" method="post" id="groups_add">
		<fieldset>
			<legend>{L_GROUPS_ADDED}</legend>
			<dl>
				<dd class="full">{S_GROUP_CREATE}</dd>
				<dd class="full" style="text-align: left;">{S_GROUP_COPY}<input type="submit" name="submit_create" value="{L_AUTH_CREATE}"></dd>
			</dl>
			{S_HIDDEN}
			<input type="hidden" name="ug_type" value="group">
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
			<legend>{L_USERS_MANAGE} </legend>
			<dl>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_users" value="1" />&nbsp;{L_USERS_ALL}</label><br />{S_USER_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><input type="submit" name="submit_update" value="{L_AUTH_SHOW}"></dd>
			</dl>
		</fieldset>
		{S_HIDDEN}
		<input type="hidden" name="ug_type" value="user">
	</form>

	
</div>

<div style="float: right; width: 49%;">
	
	<strong>{L_GROUPS}</strong>
	
	<form action="{S_ACTION}" method="post" id="groups">
		<fieldset>
			<legend>{L_GROUPS_MANAGE}</legend>
			<dl>
				<dd class="full" style="text-align: left;"><label><input type="checkbox" class="radio" name="all_groups" value="1" />&nbsp;{L_GROUPS_ALL}</label><br />{S_GROUP_UPDATE}</dd>
				<dd class="full" style="text-align: left;"><input type="submit" name="submit_update" value="{L_AUTH_SHOW}"></dd>
			</dl>
		</fieldset>
		{S_HIDDEN}
		<input type="hidden" name="ug_type" value="group">
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
			<a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'y'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_ALL_YES}</a>
			<a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'u'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_ALL_NO}</a>
			<a href="#" onclick="mark_options('{permission.row.parent.AUTHS}', 'n'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_ALL_NEVER}</a>
		</div>
		<a href="#" onClick="toggle('{permission.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a>
	</div>
	<dl class="permissions-simple" style="padding-bottom:15px;">
		<dt style="width: 20%; padding-top:3px;">{permission.row.parent.LABEL}:</dt>
		<dd style="margin-left: 20%;">{permission.row.parent.SIMPLE}</dd>
	</dl>
	<div style="display:;" id="{permission.row.parent.AUTHS}">
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a href="#{permission.row.parent.cats.CAT}">{permission.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{permission.row.parent.cats.CAT}" id="{permission.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<thead>
				<tr>
					<th>{L_VIEW_AUTH}</th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'y'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_YES}</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'u'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_NO}</a></th>
					<th><a href="#" onclick="mark_options('{permission.row.parent.cats.OPTIONS}', 'n'); reset_simpleauth('{permission.row.parent.TOGGLE}'); return false;">{L_NEVER}</a></th>
				</tr>
				</thead>
				<tbody>
				<!-- BEGIN auths -->
				<tr>
					<td>{permission.row.parent.cats.auths.OPT_NAME}</td>
					<td>{permission.row.parent.cats.auths.OPT_YES}</td>
					<td>{permission.row.parent.cats.auths.OPT_UNSET}</td>
					<td>{permission.row.parent.cats.auths.OPT_NEVER}</td>
				</tr>				
				<!-- END auths -->
				</tbody>
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
<div style="text-align:right"><a href="#" onClick="toggle('{BLUBB}'); return false;">{L_PERMISSION_ALL}</a></div>
<!-- BEGIN row -->
<fieldset class="permissions" id="permission">
	<legend id="legend">{view.row.NAME}</legend>
<!-- BEGIN parent -->
<fieldset class="permissions" id="permission">
	<legend id="legend">{view.row.parent.NAME}</legend>
	<div class="permissions-switch">
		<div class="permissions-reset"><a href="#" onClick="toggle('{view.row.parent.AUTHS}'); return false;">{L_PERMISSION}</a></div>
		
	</div>
	<div class="permissions-simple" style="padding: 3px 3px 15px 3px; text-align:left;">
		{view.row.parent.INFO}
	</div>
	<div style="display:;" id="{view.row.parent.AUTHS}">
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {view.row.parent.cats.AUTH} href="#{view.row.parent.cats.CAT}">{view.row.parent.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{view.row.parent.cats.CAT}" id="{view.row.parent.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<thead>
				<tr>
					<th>{L_VIEW_AUTH}</th>
					<th>{L_YES}</th>
					<th>{L_NO}</th>
				</tr>
				</thead>
				<!-- BEGIN auths -->
				<tbody>
				<tr>
					<td><span class="right">{view.row.parent.cats.auths.OPT_INFO}</span>{view.row.parent.cats.auths.OPT_NAME}</td>
					<td class="{view.row.parent.cats.auths.CSS_YES}">&nbsp;</td>
					<td class="{view.row.parent.cats.auths.CSS_NO}">&nbsp;</td>
				</tr>
				</tbody>
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