<!-- BEGIN display -->
<form action="{S_GROUP_ACTION}" method="post">
<table class="head" cellspacing="0">
<tr>
	<th>
		<div id="navcontainer">
			<ul id="navlist">
				<li id="active"><a href="#" id="current">{L_GROUP_TITLE}</a></li>
			</ul>
		</div>
	</th>
</tr>
<tr>
	<td class="row2">{L_GROUP_EXPLAIN}</td>
</tr>
</table>

<br />

<table class="row" cellspacing="1">
<tr>
	<td class="rowHead" colspan="2">{L_GROUP_TEAM}</td>
	<td class="rowHead" colspan="3">{L_SETTINGS}</td>
</tr>
<!-- BEGIN group_row -->
<tr>
	<td class="{display.group_row.CLASS}" align="left">{display.group_row.NAME}</td>
	<td class="{display.group_row.CLASS}" align="center" width="1%"><a href="{display.group_row.U_EDIT}">{L_EDIT}</a></td>		
	<td class="{display.group_row.CLASS}" align="center" width="6%"><a href="{display.group_row.U_MOVE_UP}">{display.group_row.ICON_UP}</a> <a href="{display.group_row.U_MOVE_DOWN}">{display.group_row.ICON_DOWN}</a></td>
	<td class="{display.group_row.CLASS}" align="center" width="1%"><a href="{display.group_row.U_DELETE}">{L_DELETE}</a></td>
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
				<li id="active"><a href="#" id="current">{L_GROUP_NEW_EDIT}</a></li>
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
	<td class="row1" width="20%">{L_GROUP_NAME}: *</td>
	<td class="row2" colspan="2" width="80%"><input class="post" type="text" name="group_name" value="{GROUP_NAME}" ></td>
</tr>
<tr>
	<td class="row1">{L_GROUP_ACCESS}:</td>
	<td class="row2" colspan="2">{S_GROUP_ACCESS}</td>
</tr>
<tr>
	<td class="row1">{L_GROUP_TYPE}:</td>
	<td class="row2" colspan="2">{S_GROUP_TYPE}</td>
</tr>
<tr>
	<td class="row1" valign="top">{L_GROUP_DESCRIPTION}:</td>
	<td class="row3" colspan="2"><textarea class="textarea" name="group_description" rows="5" cols="40">{GROUP_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1">{L_GROUP_TYPE}:</td>
	<td class="row2" colspan="2">
		<input size="7" id="pickfield" class="post" type="text" name="group_color" value="{GROUP_COLOR}" onChange="relateColor('pick', this.value);">
		<a href="javascript:pickColor('pick');" id="pick" style="border: 1px solid #000000; font-size:10px; text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
		<script language="javascript">relateColor('pick', getObj('pickfield').value);</script>
</td>
</tr>
<!-- BEGIN group_auth_data -->
<tr>
	<td class="row1">{groups_edit.group_auth_data.CELL_TITLE}</td>
	<td class="row3">{groups_edit.group_auth_data.S_AUTH_LEVELS_SELECT2}</td>
	<td class="row2"><!--{groups_edit.group_auth_data.S_AUTH_LEVELS_SELECT}--></td>
</tr>
<!-- END group_auth_data -->
<tr>
	<td colspan="3" align="center"><input type="submit" name="send" value="{L_SUBMIT}" class="button2" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" name="reset" class="button" /></td>
</tr>
</table>
{S_HIDDEN_FIELDS}
</form>
<!-- END groups_edit -->