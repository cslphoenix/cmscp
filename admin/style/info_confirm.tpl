<form action="{S_CONFIRM_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
	<div id="navcontainer">
		<ul id="navlist">
			<li id="active"><a href="#" id="current">{MESSAGE_TITLE}</a></li>
		</ul>
	</div>
	</th>
</tr>
</table>

<br>

<table class="edit" cellspacing="1">
<tr>
	<td align="center">
		<br>
		{MESSAGE_TEXT}
		<br><br>
		{S_HIDDEN_FIELDS}
		<input type="submit" name="confirm" value="{L_YES}" class="button2" />&nbsp;&nbsp;
		<input type="submit" name="cancel" value="{L_NO}" class="button" />
	</td>
</tr>
</table>
</form>

<br clear="all" />