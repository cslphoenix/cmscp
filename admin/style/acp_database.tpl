<!-- BEGIN _database_backup -->
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	</ul>
</div>
<table class="head" cellspacing="0">
<tr>
	<td class="row2">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<form method="post" action="{S_ACTION}">
<table class="update" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th colspan="2">
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_BACKUP}</a></li>
				<li><a href="{S_OPTIMIZE}">{L_OPTIMIZE}</a></li>
				<li><a href="{S_RESTORE}">{L_RESTORE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row1 top"><label for="full_backup">{L_TYPE}:</label></td>
	<td class="row2">
		<label><input type="radio" name="backup_type" value="data" /> {L_TYPE_DATA}</label><br />
		<label><input type="radio" name="backup_type" value="structure" /> {L_TYPE_STRU}</label><br />
		<label><input type="radio" name="backup_type" id="full_backup" value="full" checked="checked" /> {L_TYPE_FULL}</label>
	</td>
</tr>
<tr>
	<td class="row1 top"><label for="table">{L_TABLE}:</label></td>
	<td class="row2">
		<label><input type="radio" name="table_type" value="dev" /> {L_TABLE_DEV}</label><br />
		<label><input type="radio" name="table_type" value="full" checked="checked" id="table" /> {L_TABLE_FULL}</label><br />
		<label><input type="radio" name="table_type" value="min" /> {L_TABLE_MIN}</label>
	</td>
</tr>
<tr>
	<td class="row1"><label for="tables_add">{L_TABLES_ADD}:</label></td>
	<td class="row2"><input type="text" class="post" name="add_tables" id="tables_add" /></td>
</tr>
<tr>
	<td class="row1" style="width:200px;"><label for="gzip">{L_TABLES_ZIP}:</label></td>
	<td class="row2"><label><input type="radio" name="gzip_type" value="1" id="gzip" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="gzip_type" value="0" checked />&nbsp;{L_NO}</label>
</tr>
<tr>
	<td class="row1 top"><label for="file">{L_DOWNLOAD}:</label></td>
	<td class="row2">
		<label><input type="radio" name="download_type" value="file" checked="checked" id="file" /> {L_DL_FILE}</label><br />
		<label><input type="radio" name="download_type" value="serv" /> {L_DL_SERV}</label><br />
		<label><input type="radio" name="download_type" value="both" /> {L_DL_BOTH}</label>
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="submit" value="{L_SUBMIT}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _database_backup -->

<!-- BEGIN _database_optimize -->
<script type="text/javascript">  

/*
	Einfacher Klapptext, wird mit jquery noch erweitert!
*/

function clip(id)
{
	if ( document.getElementById("tbody_" + id).style.display == 'none' )
	{
		document.getElementById("img_" + id).src = "style/collapse.gif";
		document.getElementById("tbody_" + id).style.display = "";
	}
	else
	{
		document.getElementById("img_" + id).src = "style/expand.gif";
		document.getElementById("tbody_" + id).style.display = "none";
	}
}

</script>

{SELECT_SCRIPT}
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_HEAD}</a></li>
	</ul>
</div>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<form action="{S_ACTION}" method="post" name="tablesForm">
<div id="navcontainer">
	<ul id="navlist">
		<li><a href="{S_BACKUP}">{L_BACKUP}</a></li>
		<li id="active"><a href="#" id="current">{L_OPTIMIZE}</a></li>
		<li><a href="{S_RESTORE}">{L_RESTORE}</a></li>
		<li id="active"><a href="#" onclick="clip('settings')" id="right"><img src="style/expand.gif" id="img_settings" width="9" height="9" border="0" /> {L_SETTINGS}</a></li>
	</ul>
</div>

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="row4 small">{L_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="update" border="0" cellspacing="0" cellpadding="0">
<tbody id="tbody_settings" style="display:none">
<tr>
	<td class="row1">{L_ENABLE_CRON}:</td>
	<td class="row2"><input type="radio" name="enable_optimize_cron" value="1" {S_ENABLE_CRON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_optimize_cron" value="0" {S_ENABLE_CRON_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_CRON_EVERY}:</td>
	<td class="row2">
		<!-- BEGIN _cron_every -->
		<select name="cron_every">
			<option value="2592000" {_database_optimize._cron_every.MONTH}>{_database_optimize._cron_every.L_MONTH}</option>
			<option value="1296000" {_database_optimize._cron_every.2WEEKS}>{_database_optimize._cron_every.L_2WEEKS}</option>
			<option value="604800" {_database_optimize._cron_every.WEEK}>{_database_optimize._cron_every.L_WEEK}</option>
			<option value="259200" {_database_optimize._cron_every.3DAYS}>{_database_optimize._cron_every.L_3DAYS}</option>
			<option value="86400" {_database_optimize._cron_every.DAY}>{_database_optimize._cron_every.L_DAY}</option>
			<option value="21600" {_database_optimize._cron_every.6HOURS}>{_database_optimize._cron_every.L_6HOURS}</option>
			<option value="3600" {_database_optimize._cron_every.HOUR}>{_database_optimize._cron_every.L_HOUR}</option>
			<option value="1800" {_database_optimize._cron_every.30MINUTES}>{_database_optimize._cron_every.L_30MINUTES}</option>
			<option value="20" {_database_optimize._cron_every.20SECONDS}>{_database_optimize._cron_every.L_20SECONDS}</option>
		</select>
		<!-- END _cron_every -->
	</td>
</tr>
<tr>
	<td class="row1" valign="top">
		{L_CURRENT_TIME}:<br />
		{L_NEXT_CRON_ACTION}:<br />
		{L_PERFORMED_CRON}:
	</td>
	<td class="row2" valign="top">
		{CURRENT_TIME}<br />
		{NEXT_CRON}<br />
		{PERFORMED_CRON}
	</td>
</tr>

	<td class="row1">{L_SHOW_NOT_OPTIMIZED}:</td>
	<td class="row2"><input type="radio" name="show_not_optimized" value="1" {S_ENABLE_NOT_OPTIMIZED_YES}/> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_not_optimized" value="0" {S_ENABLE_NOT_OPTIMIZED_NO} /> {L_NO}</td>
</tr>

<tr>
	<td class="row1">{L_SHOW_BEGIN_FOR}:</td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="show_begin_for" value="{S_SHOW_BEGIN_FOR}" /></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="configure" value="{L_CONFIGURE}"><span style="padding:4px;"></span><input type="reset" class="button" value="{L_RESET}" onClick="document.tablesForm.show_begin_for.value=''"></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</tbody>
<tr>
	<td colspan="2">
		<table class="info" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="rowHead" align="center">{L_TABLE}</td>
			<td class="rowHead" align="center">{L_RECORD}</td>
			<td class="rowHead" align="center">{L_TYPE}</td>
			<td class="rowHead" align="center">{L_SIZE}</td>
			<td class="rowHead" align="center">{L_STATUS}</td>
			<td class="rowHead" align="center">#</td>
		</tr>	
		<!-- BEGIN _optimize -->
		<tr>
			<td class="row_class1">{_database_optimize._optimize.TABLE}</td>
			<td class="row_class1" align="right">{_database_optimize._optimize.RECORD}</td>
			<td class="row_class1" align="center">{_database_optimize._optimize.TYPE}</td>
			<td class="row_class1" align="right">{_database_optimize._optimize.SIZE}</td>
			<td class="row_class1" align="center">{_database_optimize._optimize.STATUS}</td>
			<td class="row_class2" align="center">{_database_optimize._optimize.S_SELECT_TABLE}</td>
		</tr>
		<!-- END _optimize -->
		<tr>
			<td class="rowHead">{TOT_TABLE}</td>
			<td class="rowHead" align="right">{TOT_RECORD}</td>
			<td class="rowHead" align="center">- -</td>
			<td class="rowHead" align="right">{TOT_SIZE}</td>
			<td class="rowHead" align="center">{TOT_STATUS}</td>
			<td class="rowHead">&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center"><a href="#" onclick="setCheckboxes('tablesForm', true); return false;">{L_CHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', false); return false;">{L_UNCHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', 'invert'); return false;">{L_INVERTCHECKED}</a></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" class="button2" name="optimize" value="{L_START_OPTIMIZE}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END _database_optimize -->


<!-- BEGIN _display -->
<form action="{S_AUTH_ACTION}" method="get">
<div id="navcontainer">
	<ul id="navlist">
		<li id="active"><a href="#" id="current">{L_AUTH_TITLE}</a></li>
	</ul>
</div>
<table class="head" cellspacing="0">
<tr>
	<td class="row2">{L_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_AUTH_SELECT}</td>
</tr>
<tr>
	<td class="row2">{S_FIELDS}{S_AUTH_SELECT}&nbsp;&nbsp;<input type="submit" value="{L_LOOK_UP}" class="button2"></td>
</tr>
</table>
</form>
<!-- END _display -->

<!-- BEGIN auth_forum -->
<form action="{S_FORUMAUTH_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_ACTION}">{L_AUTH_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_FORUM}: {FORUM_NAME}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_AUTH_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="out" cellspacing="0">
<!-- BEGIN forum_auth_data -->
<tr>
	<th>{auth_forum.forum_auth_data.CELL_TITLE}</th>
	<td>{auth_forum.forum_auth_data.S_AUTH_LEVELS_SELECT}</td>
</tr>
<!-- END forum_auth_data -->
<tr>
	<td colspan="2" align="center" class="row3"><span class="gensmall">{U_SWITCH_MODE}</span></td>
</tr>
<tr>
	<td colspan="2" align="center">{S_FIELDS} <input type="submit" name="submit" value="{L_SUBMIT}" class="button2" /> <input type="reset" value="{L_RESET}" name="reset" class="button"></td>
</tr>
</table>
</form>
<!-- END auth_forum -->