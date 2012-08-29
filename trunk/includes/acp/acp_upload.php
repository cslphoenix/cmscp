<?php

function img_check_type($type, $error)
{
	global $lang, $error;

	switch ( $type )
	{
		case 'jpeg':
		case 'pjpeg':
		case 'jpg':	return '.jpg';
			break;
		case 'gif':	return '.gif';
			break;
		case 'png':	return '.png';
			break;
		
		default:	$error = $error ? $error . '<br />' . '1dateitype' : '2dateitype';	break;
	}
	
	return false;
}

function image_delete($image_current, $image_preview, $image_path, $mode_sql = '')
{
	echo $image_path . $image_current;
	
	if ( $image_current != '' )
	{
		if ( @file_exists($image_path . '/' . $image_current) )
		{
			@unlink($image_path . '/' . $image_current);
		}
	}	
	
	if ( $image_preview != '' )
	{
		if ( @file_exists($image_path . '/' . $image_preview) )
		{
			@unlink($image_path . '/' . $image_preview);
		}
	}
	
	/*
	$sql = explode(', ', $mode_sql);
	
	if ( !strstr($mode_sql, 'team') )
	{
		$return = ( count($sql) == '1' ) ? $sql[0] . " = ''" : $sql[0] . " = '', " . $sql[1] . " = ''";
	}
	else
	{
		$return = '';
	}
	*/
	
	$return = '';
	
	return $return;
}

function upload_dl($file_info, $path, $types, $maxsize, &$error)
{
#	global $lang, $settings, $error;
	global $lang, $settings;
	
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	
	$type = '';
	
	$ftemp = $file_info['temp'];
	$fname = $file_info['name'];
	$ftype = $file_info['type'];
	$fsize = $file_info['size'];
	
	if ( file_exists(realpath($ftemp)) )
	{
		if ( $fsize <= $maxsize && $fsize > 0 )
		{
			if ( !in_array($ftype, explode(', ', $types)) )
			{
				$error .= ( $error ? '<br />' : '' ) . sprintf($lang['up_filetype'], $ftype);
			
				return;
			}
		}
		else
		{
			$error .= ( $error ? '<br />' : '' ) . sprintf($lang['up_filesize'], round($maxsize/1024));
			
			return;
		}
	}
	
	$move_file = ( @$ini_val('open_basedir') != '' ) ? 'move_uploaded_file' : 'copy';
		
	if (!is_uploaded_file($ftemp))
	{
		message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
	
	if ( $error )
	{
		@unlink($ftemp);
		return false;
	}
	
	$move_file($ftemp, $path . "/$fname");
	@chmod($path . "/$fname", 0644);
	
	$info['file_filename'] = $fname;
	$info['file_type'] = $ftype;
	$info['file_size'] = $fsize;
	
	return $info;
}

function image_upload($mode, $mode_category, $mode_sql, $mode_preview, $image_current, $image_preview, $image_path, $image_filename, $image_realname, $image_filesize, $image_filetype, &$error)
{
	global $db, $lang, $settings, $error;
	debug($mode_category);
	switch ( $mode_category )
	{
		
		/* system_filesize = sfz */
		/* system_max_width = smw */
		/* system_max_height = smh */
		case 'group_image':
			
			$sfz = ($settings['path_group']['filesize']*1048576);
			list($smw, $smh) = explode(':', $settings['path_group']['dimension']);

			break;
		
		case 'network_image':
		
			$sfz = ($settings['path_network']['filesize']*1048576);
			
			list($smw, $smh) = explode(':', $settings['path_network']['dimension']);
			
			break;
		case 'image_match':
			$sfz	= '10240000';
			$smw	= '1024';
			$smh	= '768';
			$system_pre_width	= '100';
			$system_pre_height	= '75';
			break;
			
		case 'team_flag':
			$sfz	= ($settings['path_team_flag']['filesize']*1048576);
			list($smw, $smh) = explode(':', $settings['path_team_flag']['dimension']);
		#	$smw	= $settings['team_flag_max_width'];
		#	$smh	= $settings['team_flag_max_height'];
			break;
			
		case 'team_logo':
			$sfz	= ($settings['path_team_logo']['filesize']*1048576);
			list($smw, $smh) = explode(':', $settings['path_team_logo']['dimension']);
			
		#	$sfz	= $settings['team_logo_filesize'];
		#	$smw	= $settings['team_logo_max_width'];
		#	$smh	= $settings['team_logo_max_height'];
			break;
		
		case 'image_network_links':
			$sfz	= $settings['gallery_filesize'];
			$smw	= $settings['team_logo_max_width'];
			$smh	= $settings['team_logo_max_height'];
			break;
		case 'image_network_sponsor':
			$sfz	= $settings['gallery_filesize'];
			$smw	= $settings['team_logo_max_width'];
			$smh	= $settings['team_logo_max_height'];
			$system_pre_width	= $settings['team_logo_max_width'];
			$system_pre_height	= $settings['team_logo_max_height'];
			break;
		case 'image_network_partner':
			$sfz	= $settings['gallery_filesize'];
			$smw	= $settings['team_logo_max_width'];
			$smh	= $settings['team_logo_max_height'];
			$system_pre_width	= $settings['team_logo_max_width'];
			$system_pre_height	= $settings['team_logo_max_height'];
			break;
		case 'image_user':
			$sfz	= $settings['gallery_filesize'];
			$smw	= $settings['team_logo_max_width'];
			$smh	= $settings['team_logo_max_height'];
			$system_pre_width	= $settings['team_logo_max_width'];
			$system_pre_height	= $settings['team_logo_max_height'];
			break;
	}
	
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	
	$width = $height = 0;
	$type = '';
	
	if ( ( file_exists(@cms_realpath($image_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $image_realname) )
	{
		if ( $image_filesize <= $sfz && $image_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $image_filetype, $image_filetype);
			$image_filetype = $image_filetype[1];
		}
		else
		{
			$error[] = sprintf($lang['image_filesize'], round($sfz / 1024));
			
			return;
		}

		list($width, $height, $type) = @getimagesize($image_filename);
	}

	if ( !($imgtype = img_check_type($image_filetype, $error)) )
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
			
			break;
	}
	
	if ( $width > 0 && $height > 0 && $width <= $smw && $height <= $smh )
	{
		if ( !$mode_preview )
		{
			$new_filename = uniqid(rand());
			$new_filename = $new_filename . $imgtype;
		}
		else
		{
			$new_filename = uniqid(rand());
			$new_filename_preview = $new_filename . '_preview' . $imgtype;
			$new_filename = $new_filename . $imgtype;
			
			$new_width	= $system_pre_width;
			$new_height	= $system_pre_height;
		
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
					imagejpeg($image_p, $image_path . "/$new_filename_preview", 100);
				break;
				case '.gif':
					imagegif($image_p, $image_path . "/$new_filename_preview");
				break;
				case '.png':
					imagepng($image_p, $image_path . "/$new_filename_preview");
				break;
			}
		}
		
		if ( $image_current )
		{
			image_delete($image_current, $image_preview, $image_path, $mode_sql);
		}
		
		$move_file = ( @$ini_val('open_basedir') != '' ) ? 'move_uploaded_file' : 'copy';
		
		if (!is_uploaded_file($image_filename))
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		if ( $error )
		{
			@unlink($tmp_filename);
			return false;
		}
		
		$move_file($image_filename, $image_path . "/$new_filename");
		@chmod($image_path . "/$new_filename", 0644);
		
		if ( $mode )
		{
			/*
			$sql_pic = ( $mode == 'update' ) ?  "$mode_sql = '$new_filename'," : $new_filename;
			geändert zwecks umstellung der speichermechanismus der daten im acp
			*/
			$sql_pic = $new_filename;
		}
		else
		{
			$sql_pic['map_picture'] = $new_filename;
			$sql_pic['pic_preview'] = $new_filename_preview;
			
#			$sql_pic = $new_filename;
		}
	}
	else
	{
		$error[] = sprintf($lang['sprintf_imagesize'], $smw, $smh);
		
		return;
	}
	
	return $sql_pic;
}

function gallery_upload($image_path, $image_filename, $image_realname, $image_filesize, $image_filetype, $max_width, $max_height, $max_filesize, $pic_preview_widht, $pic_preview_height)
{
	global $db, $lang, $settings, $error;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	
	$width = $height = 0;
	$type = '';
	
	if ( ( file_exists(@cms_realpath($image_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $image_realname) )
	{
		$lang_system_filesize	= 'groeße';
		
		if ( $image_filesize <= $max_filesize && $image_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $image_filetype, $image_filetype);
			$image_filetype = $image_filetype[1];
		}
		else
		{
			$error_msg = sprintf($lang['image_filesize'], round($max_filesize / 1024));
			message(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
		}

		list($width, $height, $type) = @getimagesize($image_filename);
	}

	if ( !($imgtype = img_check_type($image_filetype, $error)) )
	{
		return;
	}
	
	switch ( $type )
	{
		case 1:
			if ( $imgtype != '.gif' )
			{
				@unlink($tmp_filename);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
			break;
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

	if ( $width > 0 && $height > 0 && $width <= $max_width && $height <= $max_height )
	{
		$new_filename = uniqid(rand());
		$new_filename_preview = $new_filename . '_preview' . $imgtype;
		$new_filename = $new_filename . $imgtype;
		
		$new_width	= $pic_preview_widht;
		$new_height	= $pic_preview_height;
		
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
				imagejpeg($image_p, './../' . $settings['path_gallery'] . '/' . $image_path . "/$new_filename_preview", 100);
				break;
			case '.gif':
				imagegif($image_p, './../' . $settings['path_gallery'] . '/' . $image_path . "/$new_filename_preview");
				break;
			case '.png':
				imagepng($image_p, './../' . $settings['path_gallery'] . '/' . $image_path . "/$new_filename_preview");
			break;
		}
		
		if ( @$ini_val('open_basedir') != '' )
		{
			$move_file = 'move_uploaded_file';
		}
		else
		{
			$move_file = 'copy';
		}
		
		if ( !is_uploaded_file($image_filename) )
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		$move_file($image_filename, './../' . $settings['path_gallery'] . '/' . $image_path . "/$new_filename");
		@chmod('./../' . $settings['path_gallery'] . '/' . $image_path . "/$new_filename", 0644);
		
		$pic['pic_filename'] = $new_filename;
		$pic['pic_preview'] = $new_filename_preview;
		$pic['pic_size'] = $image_filesize;
		
		return $pic;
	}
	else
	{
		$error = sprintf($lang['msg_imagesize'], $max_width, $max_height);
		message(GENERAL_ERROR, $error, '');
	}
}

function gallery_delete($image, $preview, $gallery_path)
{
	$image_file		= basename($image);
	$preview_file	= basename($preview);
	
	if ( $image_file != '' )
	{
		if ( @file_exists(@cms_realpath($gallery_path . '/' . $image_file)) )
		{
			@unlink($gallery_path . '/' . $image_file);
		}
	}	
	
	if ( $preview_file != '' )
	{
		if ( @file_exists(@cms_realpath($gallery_path . '/' . $preview_file)) )
		{
			@unlink($gallery_path . '/' . $preview_file);
		}
	}
	
	return;
}

?>