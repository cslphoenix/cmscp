<form action="{S_ACTION}" method="post">
<div id="navcontainer">
<ul id="navlist">
	<li id="active"><a href="#" id="current">{M_TITLE}</a></li>
</ul>
</div>

<br />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
	<td align="center">
		<br />
		{M_TEXT}
		<br />
		<br />
		<input type="submit" class="button2" name="confirm" value="{L_YES}"><span style="padding:4px;"></span><input type="submit" class="button" name="cancel" value="{L_NO}" />
	</td>
</tr>
</table>
{S_FIELDS}
</form>
<br clear="all" />