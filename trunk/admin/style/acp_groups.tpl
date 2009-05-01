<!-- BEGIN display -->
<form action="{S_GROUP_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GROUP_TITLE}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead">{L_GROUP_NAME}</td>
	<td class="rowHead" nowrap="nowrap">{L_GROUP_MEMBERCOUNT}</td>
	<td class="rowHead" colspan="4">{L_SETTINGS}</td>
</tr>
<!-- BEGIN group_row -->
<tr>
	<td class="row_class1" align="left" width="100%">{display.group_row.NAME}</td>
	<td class="row_class1" align="center" width="1%">{display.group_row.MEMBER_COUNT}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_MEMBER}">{L_MEMBER}</a></td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="row_class2" align="center" nowrap="nowrap">{display.group_row.MOVE_UP} {display.group_row.MOVE_DOWN}</td>
	<td class="row_class2" align="center" nowrap="nowrap"><a href="{display.group_row.U_DELETE}">{display.group_row.L_DELETE}</a></td>
</tr>
<!-- END group_row -->
<!-- BEGIN no_groups -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_groups -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td width="100%" align="right"><input class="post" name="group_name" type="text" value=""></td>
	<td><input type="hidden" name="mode" value="add" /><input class="button" type="submit" name="add" value="{L_GROUP_ADD}" /></td>
</tr>
</table>
</form>
<!-- END display -->

<!-- BEGIN groups_edit -->
<script type="text/javascript">
// <![CDATA[

     var perline = 9;
     var divSet = false;
     var curId;
     var colorLevels = Array('0', '3', '6', '9', 'C', 'F');
     var colorArray = Array();
     var ie = false;
     var nocolor = 'none';
	 if (document.all) { ie = true; nocolor = ''; }
	 function getObj(id) {
		if (ie) { return document.all[id]; } 
		else {	return document.getElementById(id);	}
	 }

     function addColor(r, g, b) {
     	var red = colorLevels[r];
     	var green = colorLevels[g];
     	var blue = colorLevels[b];
     	addColorValue(red, green, blue);
     }

     function addColorValue(r, g, b) {
     	colorArray[colorArray.length] = '#' + r + r + g + g + b + b;
     }
     
     function setColor(color) {
     	var link = getObj(curId);
     	var field = getObj(curId + 'field');
     	var picker = getObj('colorpicker');
     	field.value = color;
     	if (color == '') {
	     	link.style.background = nocolor;
	     	link.style.color = nocolor;
	     	color = nocolor;
     	} else {
	     	link.style.background = color;
	     	link.style.color = color;
	    }
     	picker.style.display = 'none';
	    eval(getObj(curId + 'field').title);
     }
        
     function setDiv() {     
     	if (!document.createElement) { return; }
        var elemDiv = document.createElement('div');
        if (typeof(elemDiv.innerHTML) != 'string') { return; }
        genColors();
        elemDiv.id = 'colorpicker';
	    elemDiv.style.position = 'absolute';
        elemDiv.style.display = 'none';
        elemDiv.style.border = '#000000 1px solid';
        elemDiv.style.background = '#FFFFFF';
        elemDiv.innerHTML = '<span style="font-family:Verdana; font-size:11px;">' 
          	+ '(<a href="javascript:setColor(\'\');">No color</a>)<br>' 
        	+ getColorTable() 
        	+ '</span>';

        document.body.appendChild(elemDiv);
        divSet = true;
     }
     
     function pickColor(id) {
     	if (!divSet) { setDiv(); }
     	var picker = getObj('colorpicker');     	
		if (id == curId && picker.style.display == 'block') {
			picker.style.display = 'none';
			return;
		}
     	curId = id;
     	var thelink = getObj(id);
     	picker.style.top = getAbsoluteOffsetTop(thelink) + 20;
     	picker.style.left = getAbsoluteOffsetLeft(thelink);     
	picker.style.display = 'block';
     }
     
     function genColors() {
        addColorValue('0','0','0');
        addColorValue('3','3','3');
        addColorValue('6','6','6');
        addColorValue('8','8','8');
        addColorValue('9','9','9');                
        addColorValue('A','A','A');
        addColorValue('C','C','C');
        addColorValue('E','E','E');
        addColorValue('F','F','F');                                
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(0,0,a);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,a,5);

        for (a = 1; a < colorLevels.length; a++)
			addColor(0,a,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,5,a);
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(a,0,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,a,a);
			
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(a,a,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,5,a);
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(0,a,a);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,5,5);

        for (a = 1; a < colorLevels.length; a++)
			addColor(a,0,a);			
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,a,5);
			
       	return colorArray;
     }
     function getColorTable() {
         var colors = colorArray;
      	 var tableCode = '';
         tableCode += '<table border="0" cellspacing="1" cellpadding="1">';
         for (i = 0; i < colors.length; i++) {
              if (i % perline == 0) { tableCode += '<tr>'; }
              tableCode += '<td bgcolor="#000000"><a style="outline: 1px solid #000000; color: ' 
              	  + colors[i] + '; background: ' + colors[i] + ';font-size: 10px;" title="' 
              	  + colors[i] + '" href="javascript:setColor(\'' + colors[i] + '\');">   </a></td>';
              if (i % perline == perline - 1) { tableCode += '</tr>'; }
         }
         if (i % perline != 0) { tableCode += '</tr>'; }
         tableCode += '</table>';
      	 return tableCode;
     }
     function relateColor(id, color) {
     	var link = getObj(id);
     	if (color == '') {
	     	link.style.background = nocolor;
	     	link.style.color = nocolor;
	     	color = nocolor;
     	} else {
	     	link.style.background = color;
	     	link.style.color = color;
	    }
	    eval(getObj(id + 'field').title);
     }
     function getAbsoluteOffsetTop(obj) {
     	var top = obj.offsetTop;
     	var parent = obj.offsetParent;
     	while (parent != document.body) {
     		top += parent.offsetTop;
     		parent = parent.offsetParent;
     	}
     	return top;
     }
     
     function getAbsoluteOffsetLeft(obj) {
     	var left = obj.offsetLeft;
     	var parent = obj.offsetParent;
     	while (parent != document.body) {
     		left += parent.offsetLeft;
     		parent = parent.offsetParent;
     	}
     	return left;
     }


// ]]>
</script>

<form action="{S_GROUP_ACTION}" method="post">
	
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_HEAD}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
				<li id="active"><a href="#" id="current">{L_GROUP_NEW_EDIT}</a></li>
				<li><a href="{S_MEMBER_ACTION}">{L_GROUP_MEMBER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2"><span class="small">{L_REQUIRED}</span></td>
</tr>
</table>

<br>
<table class="edit" cellspacing="0">
<tr>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_DATA}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<tr>
			<td class="row1" width="25%">{L_GROUP_NAME}: *</td>
			<td class="row2" width="75%"><input class="post" type="text" name="group_name" value="{GROUP_NAME}" ></td>
		</tr>
		<!-- BEGIN add_group -->
		<tr>
			<td class="row1" width="25%">{L_GROUP_MOD}: *</td>
			<td class="row2" width="75%">{S_GROUP_MOD}</td>
		</tr>
		<!-- END add_group -->
		<tr>
			<td class="row1">{L_GROUP_ACCESS}:</td>
			<td class="row2">{S_GROUP_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_TYPE}:</td>
			<td class="row2">{S_GROUP_TYPE}</td>
		</tr>
		<tr>
			<td class="row1" valign="top">{L_GROUP_DESCRIPTION}:</td>
			<td class="row3"><textarea class="textarea" name="group_description" rows="5" cols="40">{GROUP_DESCRIPTION}</textarea></td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_COLOR}:</td>
			<td class="row2">
				<input size="7" id="pickfield" class="post" type="text" name="group_color" value="{GROUP_COLOR}" onChange="relateColor('pick', this.value);">
				<a href="javascript:pickColor('pick');" id="pick" style="border: 1px solid #000000; font-size:10px; text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
				<script language="javascript">relateColor('pick', getObj('pickfield').value);</script>
			</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_LEGEND}:</td>
			<td class="row3"><input type="radio" name="group_legend" value="1" {S_CHECKED_LEGEND_YES} /> {L_SHOW} <input type="radio" name="group_legend" value="0" {S_CHECKED_LEGEND_NO} /> {L_NOSHOW} </td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_RANK}:</td>
			<td class="row3">{S_GROUP_RANK}</td>
		</tr>
		<tr>
			<td class="row3" colspan="2">&nbsp;</td>
		</tr>
		</table>
		
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_LOGO}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<!--
		<tr>
			<td class="row1" width="25%">{L_GROUP_NAME}: *</td>
			<td class="row2" width="75%"><input class="post" type="text" name="group_name" value="{GROUP_NAME}" ></td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_ACCESS}:</td>
			<td class="row2">{S_GROUP_ACCESS}</td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_TYPE}:</td>
			<td class="row2">{S_GROUP_TYPE}</td>
		</tr>
		<tr>
			<td class="row1" valign="top">{L_GROUP_DESCRIPTION}:</td>
			<td class="row3"><textarea class="textarea" name="group_description" rows="5" cols="40">{GROUP_DESCRIPTION}</textarea></td>
		</tr>
		<tr>
			<td class="row1">{L_GROUP_TYPE}:</td>
			<td class="row2">
				<input size="7" id="pickfield" class="post" type="text" name="group_color" value="{GROUP_COLOR}" onChange="relateColor('pick', this.value);">
				<a href="javascript:pickColor('pick');" id="pick" style="border: 1px solid #000000; font-size:10px; text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
				<script language="javascript">relateColor('pick', getObj('pickfield').value);</script>
		</td>
		
		</tr>
		-->
		</table>
	</td>
	<td valign="top">
		<table class="edit" cellspacing="1">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{L_GROUP_AUTH}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<!-- BEGIN group_auth_data -->
		<tr>
			<td class="row1">{groups_edit.group_auth_data.CELL_TITLE}</td>
			<td class="row3">{groups_edit.group_auth_data.S_AUTH_LEVELS_SELECT}</td>
		</tr>
		<!-- END group_auth_data -->
		</table>
	</td>
<tr>
	<td align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END groups_edit -->

<!-- BEGIN group_member -->
<form action="{S_GROUP_ACTION}" method="post" id="list" name="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_TITLE}</a></li>
				<li><a href="{S_OVERVIEW}">{L_OVERVIEW}</a></li>
				<li><a href="{S_GROUP_EDIT}">{L_GROUP_NEW_EDIT}</a></li>
				<li id="active"><a href="#" id="current">{L_GROUP_MEMBER}</a></li>
			</ul>
		</div>
	</th>

</tr>
<tr>
	<td>{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" align="left">{L_USERNAME}</td>
	<td class="rowHead" align="center">{L_REGISTER}</td>
	<td class="rowHead" align="center">#</td>
</tr>
<tr>
	<td class="rowHead" colspan="7">{L_MODERATOR}</td>
</tr>
<!-- BEGIN mods_row -->
<tr>
	<td class="{group_member.mods_row.CLASS}" align="left" width="100%">{group_member.mods_row.USERNAME}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap">{group_member.mods_row.REGISTER}</td>
	<td class="{group_member.mods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.mods_row.USER_ID}" /></td>
</tr>
<!-- END mods_row -->
<!-- BEGIN switch_no_moderators -->
<tr>
	<td colspan="7" class="row_class1" align="center"><span class="gen">{L_NO_MODERATORS}</span></td>
</tr>
<!-- END switch_no_moderators -->
<tr>
	<td class="rowHead" colspan="7">{L_MEMBER}</td>
</tr>
<!-- BEGIN nomods_row -->
<tr>
	<td class="{group_member.nomods_row.CLASS}" align="left" width="100%">{group_member.nomods_row.USERNAME}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap">{group_member.nomods_row.REGISTER}</td>
	<td class="{group_member.nomods_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="members[]" value="{group_member.nomods_row.USER_ID}" /></td>
</tr>
<!-- END nomods_row -->
<!-- BEGIN switch_no_members -->
<tr>
	<td colspan="7" class="row_class1" align="center"><span class="gen">{L_NO_MEMBERS}</span></td>
</tr>
<!-- END switch_no_members -->
<!-- BEGIN pending -->
<tr>
	<td class="rowHead" colspan="7">{L_PENDING_MEMBER}</td>
</tr>
<!-- BEGIN pending_row -->
<tr>
	<td class="{group_member.pending.pending_row.CLASS}" align="left" width="100%">{group_member.pending.pending_row.USERNAME}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap">{group_member.pending.pending_row.REGISTER}</td>
	<td class="{group_member.pending.pending_row.CLASS}" align="center" nowrap="nowrap"><input type="checkbox" name="pending_members[]" value="{group_member.pending.pending_row.USER_ID}" checked="checked" /></td>
</tr>
<!-- END pending_row -->
<!-- END pending -->

<!-- BEGIN no_members_row -->
<tr>
	<td class="row_class1" align="center" colspan="7">{NO_GROUPS}</td>
</tr>
<!-- END no_members_row -->
</table>

<table class="foot" cellspacing="2">
<tr>
	<td colspan="2" align="right">
		{S_ACTION_OPTIONS}
		<input type="submit" name="send" value="{L_SUBMIT}" class="button" />
	</td>
</tr>
<tr>
	<td colspan="2" align="right"><a href="#" onclick="marklist('list', 'member', true); return false;">&raquo; {L_MARK_ALL}</a>&nbsp;<a href="#" onclick="marklist('list', 'member', false); return false;">&raquo; {L_MARK_DEALL}</a></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<form action="{S_GROUP_ACTION}" method="post" name="post" id="list">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GROUP_ADD_MEMBER}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>

<table class="edit" cellspacing="1">
<tr>
	<td class="row1" width="25%" valign="top"><b>{L_GROUP_ADD}:</b><br><span class="small">{L_GROUP_ADD_MEMBER_EX}</span></td>
	<td class="row3" width="75%" valign="top">
		<textarea class="textarea" name="members" cols="40" rows="5"></textarea>
		<br>
		<br>
		{S_ACTION_ADDUSERS}
		<br>
		<br>
		<input type="checkbox" name="mod" /> Moderatorstatus
	</td>
</tr>
<tr>
	<td class="row3">&nbsp;</td>
	<td class="row3"><input type="submit" name="send" value="{L_SUBMIT}" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS2}
</form>
<!-- END team_member -->
<!--
<tr>
	<td class="row1">{groups_list.group_auth.CELL_TITLE}</td>
	<td class="row3">{groups_list.group_auth.S_AUTH_LEVELS_SELECT}</td>
</tr>
-->


<!-- BEGIN groups_list -->
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li><a href="{S_GROUP_ACTION}">{L_GROUP_TITLE}</a></li>
				<li id="active"><a href="#" id="current">{L_OVERVIEW}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_OVERVIEW_EXPLAIN}</td>
</tr>
</table>

<br>

<table class="edit" cellspacing="0">
<tr>
	<!-- BEGIN groups_data -->
	<td>
		<table class="edit" cellspacing="0">
		<tr>
			<th colspan="2">
				<div id="navcontainer">
					<ul id="navlist">
						<li id="active"><a href="#" id="current">{groups_list.groups_data.GROUP_NAME}</a></li>
					</ul>
				</div>
			</th>
		</tr>
		<!-- BEGIN groups_auth -->
		<tr>
			{groups_list.groups_data.groups_auth.CELL_TITLE}
			<td class="row3">{groups_list.groups_data.groups_auth.S_AUTH_LEVELS_SELECT}</td>
		</tr>
		<!-- END groups_auth -->
		</table>
	</td>
	<!-- END groups_data -->
</tr>
</table>

<table class="foot" cellspacing="4">
<tr>
	<td width="50%" align="left">{PAGE_NUMBER}</td>
	<td width="50%" align="right">{PAGINATION}</td>
</tr>
</table>
<!-- END groups_list -->