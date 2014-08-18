	/*	
		require:	acp_training.tpl
					acp_news.tpl
					acp_gallery.tpl
					acp_match.tpl
	*/
	/*
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
	*/
	
	/*	
		require:	acp_logs.tpl
					acp_match.tpl
	*/
	function checked(id)
	{
		if ( document.getElementById('check_'+id).checked == true )
		{
			document.getElementById('check_'+id).checked = false;
		}
		else
		{
			document.getElementById('check_'+id).checked = true;
		}
	}
	
	function clip(id)
	{
		if ( document.getElementById(id).style.display == 'none' )
		{
			document.getElementById("img_" + id).src = "../images/collapse.gif";
			document.getElementById(id).style.display = "";
		}
		else
		{
			document.getElementById("img_" + id).src = "../images/expand.gif";
			document.getElementById(id).style.display = "none";
		}
	}
	
	function toggle(name)
	{
		var e = document.getElementById(name);
		
		if (!e) return true;
		
		if ( e.style.display == "none" )
		{
			e.style.display = "block"
		}
		else
		{
			e.style.display = "none"
		}
		return true;
	}
	
	/*
	 *	Mark/unmark checkboxes
	 *	id = ID of parent container, name = name prefix, aktion = aktion [true/false]
	 *	phpBB 3
	 */
	function marklist(id, name, aktion)
	{
		var parent = document.getElementById(id);
		if (!parent)
		{
			eval('parent = document.' + id);
		}
	
		if (!parent)
		{
			return;
		}
	
		var rb = parent.getElementsByTagName('input');
		
		for (var r = 0; r < rb.length; r++)
		{
			if (rb[r].name.substr(0, name.length) == name)
			{
				rb[r].checked = aktion;
			}
		}
	}
	
	function UnCryptMailto( s )
	{
		var n = 0;
		var r = "";
	  
		for( var i = 0; i < s.length; i++)
		{
			n = s.charCodeAt( i );
			if( n >= 8364 )
			{
				n = 128;
			}
			r += String.fromCharCode( n - 1 );
		}
		return r;
	}
	
	function linkTo_UnCryptMailto( s )
	{
		location.href=UnCryptMailto( s );
	}
	
	function checkbox(checkboxname, checkboxvalue)
	{
		var count = document.getElementsByName(checkboxname).length;
		
		for (var i = 0; i < count; i++)
		{
			document.getElementsByName(checkboxname)[i].checked = checkboxvalue;
		}
	}
	
	/* acp_database, acp_match, acp_settings */
	/* phpBB 3 */
	function selector(bool)
	{
		var table = document.getElementById('table');
	
		for (var i = 0; i < table.options.length; i++)
		{
			table.options[i].selected = bool;
		}
	}
	
	/* phpBB3 // acp_menu, acp_forum, acp_maps */
	/**
	* Set display of page element
	* s[-1,0,1] = hide,toggle display,show
	*/
	function dE(n, s, type)
	{
		if (!type)
		{
			type = 'block';
		}
	
		var e = document.getElementById(n);
		if (!s)
		{
			s = (e.style.display == '') ? -1 : 1;
		}
		e.style.display = (s == 1) ? type : 'none';
	}