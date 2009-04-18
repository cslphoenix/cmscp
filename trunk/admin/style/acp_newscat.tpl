<!-- BEGIN display -->
<form action="{S_NEWSCAT_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_NEWSCAT_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_NEWSCAT_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_NEWSCAT_NAME}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN newscat_row -->
<tr>
	<td class="{display.newscat_row.CLASS}" align="left" nowrap="nowrap">{display.newscat_row.NEWSCAT_NAME}</td>
	<td class="{display.newscat_row.CLASS}" align="center"><img width="50%" src="{NEWSCAT_PATH}/{display.newscat_row.NEWSCAT_IMAGE}" alt=""></td>
	<td class="{display.newscat_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.newscat_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.newscat_row.CLASS}" align="center" nowrap="nowrap">{display.newscat_row.MOVE_UP} {display.newscat_row.MOVE_DOWN}</td>
	<td class="{display.newscat_row.CLASS}" align="center" nowrap="nowrap"><a href="{display.newscat_row.U_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END newscat_row -->
<!-- BEGIN no_entry -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_ENTRY}</td>
</tr>
<!-- END no_entry -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="news_category_title" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_NEWSCAT_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN newscat_edit -->
<script type="text/javascript">
// <![CDATA[
	function update_image(newimage)
	{
		document.getElementById('image').src = (newimage) ? "{NEWSCAT_PATH}/" + encodeURI(newimage) : "./../images/spacer.gif";
	}
// ]]>
</script>

<form action="{S_NEWSCAT_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_NEWSCAT_ACTION}">{L_NEWSCAT_HEAD}</a></li>
				<li id="active"><a href="#" id="current">{L_NEWSCAT_NEW_EDIT}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br />

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="20%">{L_NEWSCAT_TITLE}: *</td>
	<td class="row2" width="80%"><input class="post" type="text" name="news_category_title" value="{NEWSCAT_TITLE}" ></td>
</tr>
<tr>
	<td class="row1">{L_NEWSCAT_IMAGE}:</td>
	<td class="row2">{S_NEWSCAT_LIST}<br /><img src="{NEWSCAT_IMAGE}" id="image" alt="" /></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END newscat_edit -->
