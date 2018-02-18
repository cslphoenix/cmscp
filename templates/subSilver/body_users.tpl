<!-- BEGIN list -->
<form action="{S_ACTION}" method="post" name="post">
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr> 
	<th>{L_USERNAME}</th>
	<!-- BEGIN groups -->
	<th>{L_GROUPS}1</th>
	<!-- END groups -->
	<!-- BEGIN teams -->
	<th>{L_TEAMS}2</th>
	<!-- END teams -->
</tr>
<!-- BEGIN row -->
<tr> 
	<td class="{_list.row.CLASS}">{_list.row.USERNAME}</td>
	<!-- BEGIN groups -->
	<td class="{_list.row.CLASS}">{_list.row.GROUPS}</td>
	<!-- END groups -->
	<!-- BEGIN teams -->
	<td class="{_list.row.CLASS}">{_list.row.TEAMS}</td>
	<!-- END teams -->
</tr>
<!-- END row -->
<!-- BEGIN entry_empty -->
<tr> 
	<td colspan="3"><span class="gen">&nbsp;{NO_USER_ID_SPECIFIED}&nbsp;</span></td>
</tr>
<!-- END entry_empty -->
<tr> 
	<td colspan="3">&nbsp;</td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr> 
<td><span class="nav">{PAGE_NUMBER}{PAGINATION}</span></td>
</tr>
</table>

{S_LETTER_HIDDEN}
</form>
<!-- END list -->

<!-- BEGIN block -->
<script type="text/JavaScript">
// <![CDATA[

<!-- BEGIN add_member -->
function suggest(inputString){
        if(inputString.length == 0) {
            $('#suggestions').fadeOut();
        } else {
        $('#country').addClass('load');
            $.post("autosuggest.php", {queryString: ""+inputString+""}, function(data){
                if(data.length >0) {
                    $('#suggestions').fadeIn();
                    $('#suggestionsList').html(data);
                    $('#country').removeClass('load');
                }
            });
        }
    }
 
    function fill(thisValue) {
        $('#country').val(thisValue);
        setTimeout("$('#suggestions').fadeOut();", 600);
    }

function lookup(user_name,type,type_id)
{
	if ( user_name.length == 0 )
	{
		$('#suggestions').hide();
	}
	else
	{
		$.post("./includes/ajax/users.php", {user_name: ""+user_name+"", type: ""+type+"", type_id: ""+type_id+""}, function(data)
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
<!-- END add_member -->
var request = false;

function setRequest(value)
{
	if (window.XMLHttpRequest)
	{	request = new XMLHttpRequest(); }
	else
	{	request = new ActiveXObject("Microsoft.XMLHTTP"); }
	
	if ( !request )
	{	alert("Kann keine XMLHTTP-Instanz erzeugen"); return false; }
	else
	{
		var url = "./admin/ajax/ajax_team_ranks.php";
		request.open('post', url, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send('mode='+value);
		request.onreadystatechange = interpretRequest;
	}
}

function interpretRequest()
{
	switch (request.readyState)
	{
		case 4:
		
			if (request.status != 200)
			{	alert("Der Request wurde abgeschlossen, ist aber nicht OK\nFehler:"+request.status); }
			else
			{	var content = request.responseText; document.getElementById('ajax_content').innerHTML = content; }
			break;
		
		default: document.getElementById('close').style.display = "none"; break;
	}
}

// ]]>
</script>
<form action="{S_ACTION}" method="post" name="list">
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr> 
	<th><span class="right">{OVERVIEW}</span>{TYPE}</th>
</tr>
<tr> 
	<td>
		{DETAILS}&nbsp;
		<!-- BEGIN switch_subscribe_group_input -->
		<input class="button2" type="submit" name="joingroup" value="{L_JOIN_GROUP}" />
		<!-- END switch_subscribe_group_input -->
		<!-- BEGIN switch_unsubscribe_group_input -->
		<input class="button2" type="submit" name="unsub" value="{L_UNSUBSCRIBE_GROUP}" />
		<!-- END switch_unsubscribe_group_input -->
	</td>
</tr>
<!-- BEGIN hidden_group -->
<tr>
	<td colspan="6" align="center">{L_HIDDEN_MEMBERS}</td>
</tr>
<!-- END hidden_group -->
</table>

<!-- BEGIN mod -->
<table class="type1 rows" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>{L_MOD}</th>
	<th>{L_POSTS}</th>
	<th>{L_COMMENTS}</th>
	<th>{L_JOINED}</th>
	<!-- BEGIN switch_admin -->
	<th>&nbsp;</th>
	<!-- END switch_admin -->
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{block.mod.row.CLASS}">{block.mod.row.NAME}</td>
	<td class="{block.mod.row.CLASS}">{block.mod.row.POSTS}</td>
	<td class="{block.mod.row.CLASS}">{block.mod.row.COMMENTS}</td>
	<td class="{block.mod.row.CLASS}">{block.mod.row.JOIN}</td>
	<!-- BEGIN switch_admin -->
	<td class="{block.mod.row.CLASS}"><input type="checkbox" name="members[]" value="{block.mod.row.USER}"></td>
	<!-- END switch_admin -->
</tr>
<!-- END row -->
<!-- BEGIN no_mod -->
<tr>
	<td class="none" colspan="3" align="center">{L_NO_MODERATORS}</td>
</tr>
<!-- END no_mod -->
</table>
<br />
<!-- END mod -->

<!-- BEGIN mem -->
<table class="type1 rows" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>{L_MEM}</th>
	<th>{L_POSTS}</th>
	<th>{L_COMMENTS}</th>
	<th>{L_JOINED}</th>
	<!-- BEGIN switch_moderator -->
	<th>&nbsp;</th>
	<!-- END switch_moderator -->
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{block.mem.row.CLASS}">{block.mem.row.NAME}</td>
	<td class="{block.mem.row.CLASS}">{block.mem.row.POSTS}</td>
	<td class="{block.mem.row.CLASS}">{block.mem.row.COMMENTS}</td>
	<td class="{block.mem.row.CLASS}">{block.mem.row.JOIN}</td>
	<!-- BEGIN switch_moderator -->
	<td class="{block.mem.row.CLASS}"><input type="checkbox" name="members[]" value="{block.mem.row.USER}"></td>
	<!-- END switch_moderator -->
</tr>
<!-- END row -->
<!-- BEGIN no_mem -->
<tr>
	<td class="none" colspan="3" align="center">{L_NO_MEMBERS}</td>
</tr>
<!-- END no_mem -->
</table>
<!-- END mem -->

<!-- BEGIN add_member -->
<table class="type1" style="border:none; width:100%; border-spacing:0px; padding:0px;">
<tr>
	<td align="left" class="top" width="1%"><input type="text" name="user_name" id="user_name" onkeyup="lookup(this.value, '{TYPE_MODE}', '{TYPE_ID}');" onblur="fill();" autocomplete="off"></td>
	<td align="left" class="top"><input type="submit" name="add" value="{L_ADD_MEMBER}" class="button2"></td>
	<td align="right" class="top">{S_OPTION}<div id="ajax_content"></div></td>
	<td align="right" class="top" width="1%"><input type="submit" value="{L_SUBMIT}" class="button2"></td>
</tr>
<tr>
	<td colspan="2">
		<div class="suggestionsBox" id="suggestions" style="display:none;">
			<div class="suggestionList" id="autoSuggestionsList"></div>
		</div>
	</td>
	<td align="right" colspan="2"><a href="#" onclick="marklist('list', 'member', true); return false;">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">{L_MARK_DEALL}</a></td>
</tr>
</table>
<!-- END add_member -->

<!-- BEGIN pen -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN row -->
<tr>
	<td class="{block.pen.row.CLASS}">{block.pen.NAME}</td>
	<td class="{block.pen.row.CLASS}">{block.pen.JOIN}</td>
	<td class="{block.pen.row.CLASS}"><input type="checkbox" name="members[]" value="{block.pen.USER}"></td>
</tr>
<!-- END row -->
</table>
<!-- END pen -->


{S_FIELDS}
</form>
<!-- END block -->

<!-- BEGIN listg -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN in_groups -->
<!-- BEGIN is_member -->
<tr>
	<th colspan="2">{L_CUR}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{listg.in_groups.is_member.row.CLASS}">{listg.in_groups.is_member.row.NAME}{listg.in_groups.is_member.row.DESC}</td>
	<td class="{listg.in_groups.is_member.row.CLASS}">{listg.in_groups.is_member.row.TYPE}</td>
</tr>
<!-- END row -->
<tr>
	<td colspan="2"></td>
</tr>
<!-- END is_member -->
<!-- BEGIN is_pending -->
<tr>
	<th colspan="2">{L_PEN}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{listg.in_groups.is_pending.row.CLASS}">{listg.in_groups.is_pending.row.NAME}{listg.in_groups.is_pending.row.DESC}</td>
	<td class="{listg.in_groups.is_pending.row.CLASS}">{listg.in_groups.is_pending.row.TYPE}</td>
</tr>
<!-- END row -->
<!-- END pending -->
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<!-- END in_groups -->
<!-- BEGIN no_group -->
<tr>
	<th colspan="2">{L_NON}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{listg.no_group.row.CLASS}">{listg.no_group.row.NAME}{listg.no_group.row.DESC}</td>
	<td class="{listg.no_group.row.CLASS}">{listg.no_group.row.TYPE}</td>
</tr>
<!-- END row -->
<!-- END no_group -->
</table>
<!-- END listg -->

<!-- BEGIN listt -->
<table class="type1" width="100%" cellpadding="0" cellspacing="0" border="0">
<!-- BEGIN game_row -->
<tr>
	<th colspan="4">{listt.game_row.L_GAME}</th>
</td>
</tr>
<!-- BEGIN team_row -->
<tr>
	<td class="{listt.game_row.team_row.CLASS}">{listt.game_row.team_row.GAME} {listt.game_row.team_row.NAME}</td>
	<td class="{listt.game_row.team_row.CLASS}">{listt.game_row.team_row.FIGHTUS}</td>
	<td class="{listt.game_row.team_row.CLASS}">{listt.game_row.team_row.JOINUS}</td>
	<td class="{listt.game_row.team_row.CLASS}">{listt.game_row.team_row.MATCH}</td>
</tr>
<!-- END team_row -->
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<!-- END game_row -->
<!-- BEGIN no_entry_team -->
<tr>
	<td class="row1" align="center" colspan="5">{L_NONE}</td>
</tr>
<!-- END no_entry_team -->
</table>
<!-- END listt -->