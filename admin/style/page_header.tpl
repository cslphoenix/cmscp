<!DOCTYPE HTML><head>
{META}
	
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />

	<title>{L_HEADER}</title>
	<link rel="icon" href="./../favicon.ico" type="image/x-icon">
	
	
	
	<link rel="stylesheet" type="text/css" href="style/style.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.atooltip.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.minicolors.css" media="screen">
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.lightbox.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.maxlength.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.custom.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.ui.datepicker.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./../includes/css/jRating.jquery.css" media="screen" />
    
	<link rel="stylesheet" type="text/css" href="./../includes/css/jquery.minicolors.css" media="screen" />
	
	
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.js"></script>
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.sparkline.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.atooltip.js"></script>
<!--<script type="text/javascript" src="./../includes/js/jquery/jquery.exptextarea.js"></script>-->
	<script type="text/javascript" src="./../includes/js/jquery/jquery.autosize.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.lightbox.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.maxlength.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.litetabs.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.timepicker-addon.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.sliderAccess.js"></script>
	<script type="text/javascript" src="./../includes/js/jquery/jquery.ui.datetimepicker-de.js"></script>
	
	<script type="text/javascript" src="./../includes/js/jquery/jquery.minicolors.js"></script>
	
	<script type="text/javascript" src="style/vars.js"></script>
	<script type="text/javascript" src="style/jquery.vars.js"></script>
	
	<script type="text/javascript">
	<!-- notwendig für $lang['common_remove'] / {L_REMOVE} -->
	function clone(objButton)
	{
		if ( objButton.parentNode )
		{
			tmpNode		= objButton.parentNode.cloneNode(true);
			target		= objButton.parentNode.parentNode;
			arrInput	= tmpNode.getElementsByTagName("input");
			
			for ( var i = 0; i < arrInput.length; i++ )
			{
				if ( arrInput[i].type == 'text' )
				{
					arrInput[i].value = '';
				}
				
				if ( arrInput[i].type=='file' )
				{
					arrInput[i].value='';
				}
			}
			
			target.appendChild(tmpNode);
			objButton.value="{L_REMOVE}";
			objButton.onclick=new Function('f1','this.parentNode.parentNode.removeChild(this.parentNode)');
		}
	}
	</script>
</head>
<body>
<a name="#top"></a>
<div id="head">
	<table style="padding:5px;" id="hover">
	<tr>
		<td width="35%" align="left"><span class="small">{L_TIME}</span></td>
		<td width="30%" align="center"><a href="{U_OVERVIEW}">{L_OVERVIEW}</a><span class="small">&nbsp;&bull;&nbsp;</span><a href="{U_SITE}">{L_SITE}</a></td>
		<td width="35%" align="right"><span class="small"><b>{L_USER}</b> [ <a href="{U_LOGOUT}" target="_parent">{L_LOGOUT}</a> ] [ <a href="{U_SESSION}" target="_parent">{L_SESSION}</a> ]</span></td>
	</tr>
	</table>
</div>
<div id="cont">
<div id="wrap">
	<div class="border-left">
	<div class="border-right">
	<div class="border-top">
	<div class="border-top-left">
	<div class="border-top-right">
	<div class="inside">
		<div id="page-header"></div>
		<div id="page-body">
			
			<ul id="navlist">
				<!-- BEGIN icat -->
				<li{icat.ACTIVE}><a href="{icat.URL}"{icat.CURRENT}>{icat.NAME}</a></li>
				<!-- END icat -->
			</ul>
		
			<div id="cmenu">
			<!-- BEGIN ilab -->
			<ul>
				<li class="header">{ilab.NAME}</li>
				<!-- BEGIN isub -->
				<li><a href="{ilab.isub.U_MODULE}"{ilab.isub.CLASS}>{ilab.isub.L_MODULE}</a></li><br>
				<!-- END isub -->
			</ul>
			<!-- END ilab -->
			</div>
			
			<div id="cmain">
					
					
					