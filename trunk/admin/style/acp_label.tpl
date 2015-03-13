<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN input -->
<script type="text/javascript" src="style/ajax_main.js"></script>
<script type="text/javascript">

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
{ERROR_BOX}

<!-- BEGIN row -->
<!-- BEGIN hidden -->
{input.row.hidden.HIDDEN}
<!-- END hidden -->
<!-- BEGIN tab -->
<fieldset>
	<legend>{input.row.tab.L_LANG}</legend>
	<!-- BEGIN option -->
	{input.row.tab.option.DIV_START}
	<dl>			
		<dt class="{input.row.tab.option.CSS}"><label for="{input.row.tab.option.LABEL}"{input.row.tab.option.EXPLAIN}>{input.row.tab.option.L_NAME}:</label></dt>
		<dd>{input.row.tab.option.OPTION}</dd>
	</dl>
	{input.row.tab.option.DIV_END}
	<!-- END option -->
</fieldset>
<!-- END tab -->
<!-- END row -->

<!-- BEGIN acl_users -->
<br />
<fieldset>
	<legend>{L_ACL_USERS}</legend>
	{input.acl_users.USERS}
</fieldset>
<!-- END acl_users -->
<!-- BEGIN acl_groups -->
<fieldset>
	<legend>{L_ACL_GROUPS}</legend>
	{input.acl_groups.GROUPS}
</fieldset>
<!-- END acl_groups -->

<br />

<fieldset>
	<legend>{L_PERMISSION}</legend>
	<div align="center">
		<div class="tabs">
			<ul>
				<!-- BEGIN cats -->
				<li><a {input.cats.AUTH} href="#{input.cats.CAT}">{input.cats.NAME}</a></li>
				<!-- END cats -->
			</ul>
			<!-- BEGIN cats -->
			<div name="#{input.cats.CAT}" id="{input.cats.OPTIONS}">
				<table class="ttabs" cellpadding="1" cellspacing="1">
				<tr>
					<th>{L_SETTINGS}</th>
					<th><a href="#" onclick="mark_options('{input.cats.OPTIONS}', 'y'); return false;">{OPT_YES}Ja</a></th>
					<th><a href="#" onclick="mark_options('{input.cats.OPTIONS}', 'u'); return false;">{OPT_UNSET}Nein</a></th>
					<th><a href="#" onclick="mark_options('{input.cats.OPTIONS}', 'n'); return false;">{OPT_NEVER}Nie</a></th>
				</tr>
				<!-- BEGIN auths -->
				<tr>
					<td>{input.cats.auths.LANG}</td>
					<td>{input.cats.auths.OPT_YES}</td>
					<td>{input.cats.auths.OPT_UNSET}</td>
					<td>{input.cats.auths.OPT_NEVER}</td>
				</tr>
				<!-- END auths -->
				</table>
			</div>
			<!-- END cats -->
		</div>
	</div>
</fieldset>

<div class="submit">
<dl>
	<dt><input type="submit" name="submit" value="{L_SUBMIT}"></dt>
	<dd><input type="reset" value="{L_RESET}"></dd>
</dl>
</div>
{S_FIELDS}
</form>
<!-- END input -->

<!-- BEGIN display -->
<form action="{S_ACTION}" method="post">

<table class="rows">
<tr>
	<th>{L_NAME}</th>
	<th>{L_SETTINGS}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td>{display.row.NAME}<br />{display.row.DESC}</td>
	<td>{display.row.MOVE_DOWN}{display.row.MOVE_UP}{display.row.UPDATE}{display.row.DELETE}</td>
</tr>
<!-- END row -->
<!-- BEGIN empty -->
<tr>
	<td class="empty" colspan="2">{L_EMPTY}</td>
</tr>
<!-- END empty -->
</table>

<table class="lfooter">
<tr>
	<td><input type="text" name="label_name" /></td>
	<td><input type="submit" value="{L_CREATE}" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END display -->