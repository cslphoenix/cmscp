<?php

//function check_image_type(&$type)
function image_check_type(&$type)
{
	global $lang;

	switch ( $type )
	{
		case 'jpeg':
		case 'pjpeg':
		case 'jpg':
			return '.jpg';
		break;
		
		case 'gif':
			return '.gif';
		break;
		
		case 'png':
			return '.png';
		break;
		
		default:
			message(GENERAL_ERROR, $lang['image_filetype_wrong'], '', __LINE__, __FILE__);
		break;
	}

	return false;
}

/*
function image_upload($image_mode, $image_path, $image_realname, $image_filesize, $image_filetype)
{
	global $db, $lang, $settings;

	$width = $height = $type = '';
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	if ( ( file_exists(@cms_realpath($image_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $image_realname) )
	{
		switch ( $image_mode )
		{
			case 'image_gallery':
				$lang_system_filesize	= $lang['filesize_gallery'];
				$image_system_filesize	= $settings['gallery_filesize'];
				break;
			case 'team_team':
				$lang_system_filesize	= $lang['filesize_logo'];
				$image_system_filesize	= $settings['team_logo_filesize'];
				break;
			case 'image_team_small':
				$lang_system_filesize	= $lang['filesize_logosamll'];
				$image_system_filesize	= $settings['team_logosmall_filesize'];
				break;
			case 'image_network_links':
				$lang_system_filesize	= $lang['filesize_network_links'];
				$image_system_filesize	= $settings['gallery_filesize'];
				break;
			case 'image_network_sponsor':
				$lang_system_filesize	= $lang['filesize_network_sponsor'];
				$image_system_filesize	= $settings['gallery_filesize'];
				break;
			case 'image_network_partner':
				$lang_system_filesize	= $lang['filesize_network_spartner'];
				$image_system_filesize	= $settings['gallery_filesize'];
				break;
			case 'image_user':
				$lang_system_filesize	= $lang['filesize_user'];
				$image_system_filesize	= $settings['gallery_filesize'];
				break;
		}
//		$sfilesize	= ($format == 'n') ? $settings['team_logo_filesize'] : $settings['team_logos_filesize'];
//		$lfilesize	= ($format == 'n') ? $lang['logo_filesize'] : $lang['logos_filesize'];
		
		if ( $image_filesize <= $image_system_filesize && $image_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $image_filetype, $image_filetype);
			$image_filetype = $image_filetype[1];
		}
		else
		{
			$error_msg = sprintf($lang_system_filesize, round($image_system_filesize / 1024));
			
			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
		}

		list($width, $height, $type) = @getimagesize($image_filename);
	}

	if ( !($imgtype = image_check_type($image_filetype)) )
	{
		return;
	}

	switch ( $type )
	{
		// GIF
		case 1:
			if ( $imgtype != '.gif' )
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ( $imgtype != '.jpg' && $imgtype != '.jpeg' )
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ( $imgtype != '.png' )
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	switch ( $image_mode )
	{
		case 'image_gallery':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'team_team':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'image_team_small':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'image_network_links':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'image_network_sponsor':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'image_network_partner':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
		case 'image_user':
			$system_max_width	= $settings['team_logo_max_width'];
			$system_max_height	= $settings['team_logo_max_height'];
			break;
	}
	
#	$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
#	$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];

	if ( $width > 0 && $height > 0 && $width <= $system_max_width && $height <= $system_max_height )
	{
	#	$new_filename = uniqid(rand()) . $imgtype;
		$new_filename = uniqid(rand());
		$new_filename_preview = $new_filename . '_preview' . $imgtype;
		$new_filename = $new_filename . $imgtype;
		
		$new_width = '100';
		$new_height = '75';
		
		//	ändern
		$image_p = imagecreatetruecolor($new_width, $new_height);
		
		switch ($imgtype)
		{
			case '.jpeg':
			case '.pjpeg':
			case '.jpg':
				$image = imagecreatefromjpeg($image_filename);
			break;
			case '.gif':
				$image = imagecreatefromgif($image_filename);
			break;
			case '.png':
				$image = imagecreatefrompng($image_filename);
			break;
		}
		
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		switch ( $imgtype )
		{
			case '.jpeg':
			case '.pjpeg':
			case '.jpg':
				imagejpeg($image_p, './../' . $image_path . "/$new_filename_preview", 80);
			break;
			case '.gif':
				imagegif($image_p, './../' . $image_path . "/$new_filename_preview");
			break;
			case '.png':
				imagepng($image_p, './../' . $image_path . "/$new_filename_preview");
			break;
		}
		
		if ( $current_logo != '' )
		{
			picture_delete($current_logo, $current_logo_preview);
		}
		
		if ( @$ini_val('open_basedir') != '' )
		{
			$move_file = 'move_uploaded_file';
		}
		else
		{
			$move_file = 'copy';
		}
		
	#	debug($move_file);

		if (!is_uploaded_file($image_filename))
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		$move_file($image_filename, './../' . $image_path . "/$new_filename");

		@chmod('./../' . $image_path . "/$new_filename", 0777);
		
		$pic = ", pic_name = '$new_filename', pic_name_preview = '$new_filename_preview',";
		
		return $pic;
	}
	else
	{
#		$limagesize			= ($format == 'n') ? $lang['logo_imagesize'] : $lang['logos_imagesize'];
#		$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
#		$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];
#		
#		$error_msg = sprintf($limagesize, $slogo_max_width, $slogo_max_height);
			
		message(GENERAL_ERROR, 'einfach ein fehler -.-\'' . $error_msg, '', __LINE__, __FILE__);
	}
}
*/
function check_image_type(&$type)
{
	global $lang;

	switch( $type )
	{
		case 'jpeg':
		case 'pjpeg':
		case 'jpg':
			return '.jpg';
			break;
		case 'gif':
			return '.gif';
			break;
		case 'png':
			return '.png';
			break;
		default:
			$error_msg = $lang['wrong_filetype'];

			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
			break;
	}

	return false;
}

function team_logo_upload($mode, $format, &$current_logo, &$current_type, $logo_filename, $logo_realname, $logo_filesize, $logo_filetype)
{
	global $db, $settings, $lang;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	$width = $height = 0;
	$type = '';

	if ( ( file_exists(@cms_realpath($logo_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $logo_realname) )
	{
		$sfilesize	= ($format == 'n') ? $settings['team_logo_filesize'] : $settings['team_logos_filesize'];
		$lfilesize	= ($format == 'n') ? $lang['logo_filesize'] : $lang['logos_filesize'];
		
		if ( $logo_filesize <= $sfilesize && $logo_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $logo_filetype, $logo_filetype);
			$logo_filetype = $logo_filetype[1];
		}
		else
		{
			$error_msg = sprintf($lfilesize, round($sfilesize / 1024));
			
			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
		}

		list($width, $height, $type) = @getimagesize($logo_filename);
	}

	if ( !($imgtype = check_image_type($logo_filetype)) )
	{
		return;
	}

	switch ($type)
	{
		// GIF
		case 1:
			if ($imgtype != '.gif')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ($imgtype != '.jpg' && $imgtype != '.jpeg')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ($imgtype != '.png')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
	$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];

	if ( $width > 0 && $height > 0 && $width <= $slogo_max_width && $height <= $slogo_max_height )
	{
		$new_filename = uniqid(rand()) . $imgtype;

		if ( $mode == 'editteam' && $current_type == LOGO_UPLOAD && $current_logo != '' )
		{
			team_logo_delete($format, $current_type, $current_logo);
		}

		if ( @$ini_val('open_basedir') != '' )
		{
			if ( @phpversion() < '4.0.3' )
			{
				message(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
			}

			$move_file = 'move_uploaded_file';
		}
		else
		{
			$move_file = 'copy';
		}

		if (!is_uploaded_file($logo_filename))
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		$spath = ($format == 'n') ? $settings['team_logo_path'] : $settings['team_logos_path'];
		
		$move_file($logo_filename, './../' . $spath . "/$new_filename");

		@chmod('./../' . $spath . "/$new_filename", 0777);
		
		if ($format == 'n')
		{
			$logo_sql = ( $mode == 'editteam' ) ? "team_logo = '$new_filename', team_logo_type = " . LOGO_UPLOAD . ", " : "'$new_filename', " . LOGO_UPLOAD;
		}
		else
		{
			$logos_sql = ( $mode == 'editteam' ) ? "team_logos = '$new_filename', team_logos_type = " . LOGO_UPLOAD . ", " : "'$new_filename', " . LOGO_UPLOAD;
		}
	}
	else
	{
		$limagesize			= ($format == 'n') ? $lang['logo_imagesize'] : $lang['logos_imagesize'];
		$slogo_max_width	= ($format == 'n') ? $settings['team_logo_max_width'] : $settings['team_logos_max_width'];
		$slogo_max_height	= ($format == 'n') ? $settings['team_logo_max_height'] : $settings['team_logos_max_height'];
		
		$error_msg = sprintf($limagesize, $slogo_max_width, $slogo_max_height);
			
		message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
	}
	
	if ($format == 'n')
	{
		return $logo_sql;
	}
	else
	{
		return $logos_sql;
	}
}

function team_logo_delete($format, $logo_type, $logo_file)
{
	global $settings;
	
	$spath = ($format == 'n') ? $settings['team_logo_path'] : $settings['team_logos_path'];

	$logo_file = basename($logo_file);
	if ( $logo_type == LOGO_UPLOAD && $logo_file != '' )
	{
		if ( @file_exists(@cms_realpath('./../' . $spath . '/' . $logo_file)) )
		{
			@unlink('./../' . $spath . '/' . $logo_file);
		}
	}
	
	if ($format == 'n')
	{
		return "team_logo = '', team_logo_type = " . LOGO_NONE . ", ";
	}
	else
	{
		return "team_logos = '', team_logos_type = " . LOGO_NONE . ", ";
	}
}

function picture_upload($num, &$current_logo, &$current_logo_preview, $logo_filename, $logo_realname, $logo_filetype)
{
	global $settings, $db, $lang;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	$type = '';
	
	if ( ( file_exists(@cms_realpath($logo_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $logo_realname) )
	{
		preg_match('#image\/[x\-]*([a-z]+)#', $logo_filetype, $logo_filetype);
		$logo_filetype = $logo_filetype[1];
			
		list($width, $height, $type) = @getimagesize($logo_filename);
	}
	
	if ( !($imgtype = check_image_type($logo_filetype)) )
	{
		return;
	}
	
	switch ($type)
	{
		// GIF
		case 1:
			if ($imgtype != '.gif')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ($imgtype != '.jpg' && $imgtype != '.jpeg')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ($imgtype != '.png')
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	
	
//	$new_filename = uniqid(rand()) . $imgtype;
	$new_filename = uniqid(rand());
	$new_filename_preview = $new_filename . '_preview' . $imgtype;
	$new_filename = $new_filename . $imgtype;
/*	
	echo $new_filename;
	echo '<br/>';
	echo $new_filename_preview;
	exit;
*/
	//	neue grï¿½ï¿½e
	$new_width = '100';
	$new_height = '75';
	
	//	ï¿½ndern
	$image_p = imagecreatetruecolor($new_width, $new_height);
	
	switch ($imgtype)
	{
		case '.jpeg':
		case '.pjpeg':
		case '.jpg':
			$image = imagecreatefromjpeg($logo_filename);
		break;
		case '.gif':
			$image = imagecreatefromgif($logo_filename);
		break;
		case '.png':
			$image = imagecreatefrompng($logo_filename);
		break;
	}
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	switch ($imgtype)
	{
		case '.jpeg':
		case '.pjpeg':
		case '.jpg':
			imagejpeg($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview", 100);
		break;
		case '.gif':
			imagegif($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview");
		break;
		case '.png':
			imagepng($image_p, './../' . $settings['path_match_picture'] . "/$new_filename_preview");
		break;
	}
	
	if ( $current_logo != '' )
	{
		picture_delete($num, $current_logo, $current_logo_preview);
	}

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
		}

		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}

	if (!is_uploaded_file($logo_filename))
	{
		message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}

	$move_file($logo_filename, './../' . $settings['path_match_picture'] . "/$new_filename");

	@chmod('./../' . $settings['path_match_picture'] . "/$new_filename", 0777);
	
	$pic = "details_map_pic_" . $num . " = '$new_filename', pic_" . $num . "_preview = '$new_filename_preview',";
	
	return $pic;
}

function picture_delete($num, $logo_file, $logo_preview_file)
{
	global $settings;
	
	$logo_file = basename($logo_file);
	$logo_preview_file = basename($logo_preview_file);
	if ($logo_file != '' )
	{
		if ( @file_exists(@cms_realpath('./../' . $settings['path_match_picture'] . '/' . $logo_file)) )
		{
			@unlink('./../' . $settings['path_match_picture'] . '/' . $logo_file);
		}
		
		if ( @file_exists(@cms_realpath('./../' . $settings['path_match_picture'] . '/' . $logo_preview_file)) )
		{
			@unlink('./../' . $settings['path_match_picture'] . '/' . $logo_preview_file);
		}
	}
	
	return "details_map_pic_$num = '', pic_" . $num . "_preview = '', ";
}

?>