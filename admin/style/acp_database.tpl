<li class="header">{L_HEAD}</li>
<p>{L_EXPLAIN}</p>

<!-- BEGIN backup -->
<form action="{S_ACTION}" method="post">
<table class="update">
<tr>
	<td class="row1"><label for="full">{L_TYPE}:</label></td>
	<td class="row2"><label><input type="radio" name="type" value="full" id="full" checked="checked" />&nbsp;{L_TYPE_FULL}</label><span style="padding:4px;"></span><label><input type="radio" name="type" value="data" />&nbsp;{L_TYPE_DATA}</label><span style="padding:4px;"></span><label><input type="radio" name="type" value="structure" />&nbsp;{L_TYPE_STRU}</label></td>
</tr>
<tr>
	<td class="row1"><label for="download">{L_DOWNLOAD}:</label></td>
	<td class="row2"><label><input type="radio" name="download" value="server" checked="checked" id="download" />&nbsp;{L_DOWNLOAD_SERVER}</label><span style="padding:4px;"></span><label><input type="radio" name="download" value="file" />&nbsp;{L_DOWNLOAD_FILE}</label></td>
</tr>
<tr>
	<td class="row1"><label for="compress">{L_COMPRESS}:</label></td>
	<td class="row2"><label><input type="radio" name="compress" value="1" id="compress" checked="checked" />&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="compress" value="0" />&nbsp;{L_NO}</label>
</tr>
<tr>
	<td class="row1"><label for="table">{L_TABLE}:</label></td>
	<td>{S_TABLE}<br /><a href="#" class="small" onclick="selector(true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" class="small" onclick="selector(false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="backupstart" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}"></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END backup -->

<!-- BEGIN optimize -->
<form action="{S_ACTION}" method="post">
{S_SCRIPT}
<table class="update">
<tr>
	<td class="row1">{L_SHOW_NOT_OPTIMIZED}:</td>
	<td class="row2"><label><input type="radio" name="db_showoptimized" value="1" {S_OPTIMIZED_YES}/>&nbsp;{L_YES}</label><span style="padding:4px;"></span><label><input type="radio" name="db_showoptimized" value="0" {S_OPTIMIZED_NO} />&nbsp;{L_NO}</label></td>
</tr>
<tr>
	<td class="row1">{L_SHOW_BEGIN_FOR}:</td>
	<td class="row2"><input class="post" type="text" name="db_show_begin_for" value="{S_SHOW_BEGIN_FOR}" /></td>
</tr>
</table>

<br/>

<table class="submit">
<tr>
	<td><input type="submit" name="configure" value="{L_SUBMIT}"></td>
	<td><input type="reset" value="{L_RESET}" onClick="document.tablesForm.show_begin_for.value=''"></td>
</tr>
</table>
{S_FIELDS}
</form>

<br />

<form action="{S_ACTION}" method="post" name="tablesForm">
<table class="rows db">
<tr>
	<th>{L_TABLE}</th>
	<th>{L_ROWS}</th>
	<th>{L_SIZE}</th>
	<th>{L_STATUS}</th>
	<th><input type="checkbox" name="selected_tbl[]" onclick="checkbox(this.name,this.checked)" /></th>
</tr>	
<!-- BEGIN optimize_row -->
<tr>
	<td><label for="check_{optimize.optimize_row.NUM}" title="{optimize.optimize_row.TITLE}">{optimize.optimize_row.NAME}</label></td>
	<td><label for="check_{optimize.optimize_row.NUM}">{optimize.optimize_row.ROWS}</label></td>
	<td><label for="check_{optimize.optimize_row.NUM}">{optimize.optimize_row.SIZE}</label></td>
	<td><label for="check_{optimize.optimize_row.NUM}">{optimize.optimize_row.FREE}</label></td>
	<td>{optimize.optimize_row.S_SELECT}</td>
</tr>
<!-- END optimize_row -->
<tr>
	<th>{TOTAL_TBLS}</th>
	<th>{TOTAL_ROWS}</th>
	<th>{TOTAL_SIZE}</th>
	<th>{TOTAL_FREE}</th>
	<th><input type="checkbox" name="selected_tbl[]" onclick="checkbox(this.name,this.checked)" /></th>
</tr>
</table>

<table class="footer">
<tr>
	<td><input type="submit" name="optimize" value="{L_OPTIMIZE}"></td>
	<td></td>
	<td></td>
	<td><a href="#" onclick="setCheckboxes('tablesForm', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', false); return false;">{L_MARK_DEALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', 'invert'); return false;">{L_MARK_INVERT}</a></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END optimize -->

<!-- BEGIN restore -->
<form action="{S_ACTION}" method="post" name="tablesForm" enctype="multipart/form-data">
<table class="update">
<tr>
	<td class="row1">{L_FILE}:</td>
	<td class="row2">{S_SELECT}</td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="restore_start" value="{L_FILE_RESTORE}" class="button2" /></td>
</tr>
</table>
{S_FIELDS}
</form>
<!-- END restore -->