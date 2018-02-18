<?php

function img_check_type($type, &$error)
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
		
		default:
		
			if ( $error )
			{
				$error[] = 'dateitype';
			}
			
			break;
	}
	
	return false;
}

function image_delete($cur_img, $pre_img, $path_img, $sql_type = '')
{
#	echo $path_img . $cur_img;
	
	if ( $cur_img != '' )
	{
		if ( @file_exists($path_img . '/' . $cur_img) )
		{
			@unlink($path_img . '/' . $cur_img);
		}
	}	
	
	if ( $pre_img != '' )
	{
		if ( @file_exists($path_img . '/' . $pre_img) )
		{
			@unlink($path_img . '/' . $pre_img);
		}
	}
	
	/*
	$sql = explode(', ', $sql_type);
	
	if ( !strstr($sql_type, 'team') )
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

function upload_file($data_dl, $cur_dl, $path_dl, $types, $maxsize, &$error)
#function upload_file($mode, $category, $sql_type, $mode_preview, $cur_dl, $pre_dl, $path_dl, $data_dl, &$error, $main, $maxsize, $types)
{
#	global $lang, $settings, $error;
	global $lang, $settings;
	
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	
	$type = '';
	
	$ftemp = $data_dl['temp'];
	$fname = $data_dl['name'];
	$ftype = $data_dl['type'];
	$fsize = $data_dl['size'];
	
	$maxsize = $maxsize*1048576;
	
#	debug($types);
	
#	debug($fname, 'fname');
#	debug($types);
#	debug($maxsize);

	if ( !is_array($types) )
	{
		$tmp_types = $types;
	}
	else
	{
		foreach ( $types as $_typ )
		{
			$tmp_type[] = isset($lang[$_typ]) ? $lang[$_typ] : $_typ;
			
		}
		$tmp_types = implode(', ', $tmp_type);
	}
	
	if ( file_exists(realpath($ftemp)) )
	{
		if ( $fsize <= $maxsize && $fsize > 0 )
		{
			if ( !in_array($ftype, $types) )
			{
				$error[] = sprintf($lang['up_filetype'], $tmp_types);
			
				return;
			}
		}
		else
		{
			$error[] = sprintf($lang['up_filesize'], round($maxsize/1024));
			
			return;
		}
	}
	
	$move_file = ( @$ini_val('open_basedir') != '' ) ? 'move_uploaded_file' : 'copy';
		
	if (!is_uploaded_file($ftemp))
	{
		message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
	}
#	debug($error, 'error');
	if ( $error )
	{
		@unlink($ftemp);
		return false;
	}
	
	$move_file($ftemp, $path_dl . "/$fname");
	@chmod($path_dl . "/$fname", 0644);
	
#	$info['dl_name'] = $fname;
#	$info['dl_type'] = $ftype;
#	$info['dl_size'] = $fsize;

	$info = sprintf('%s;%s;%s', $fname, $ftype, $fsize);
	
#	debug($info, 'info');
	
	return $info;
}

function test_size($multi)
{
	switch ( $multi )
	{
		case 0: $size = 1; break;
		case 1: $size = 1024; break;
		case 2: $size = 1048576; break;
	}
	
#	debug($size, 'size 1234');

#	if ( $file >= 1048576 )
#	{
#		$size = round($file / 1048576 * 100) / 100 . " MB";
#	}
#	else if( $file >= 1024 )
#	{
#		$size = round($file / 1024 * 100) / 100 . " KB";
#	}
#	else
#	{
#		$size = $size . " Bytes";
#	}
	
	return $size;
}

function upload_image($mode, $category, $sql_type, $mode_preview, $cur_img, $pre_img, $path_img, $data_img, &$error, $main = false, $maxsize = false, $types = false)
{
	global $db, $lang, $settings;
	
	$image_filename = $data_img['name'];
	$image_realname = $data_img['temp'];
	$image_filesize = $data_img['size'];
	$image_filetype = $data_img['type'];
	
	switch ( $category )
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
			$sfz	= 2*1048576;
			$smw	= '1024';
			$smh	= '768';
			$system_pre_width	= '100';
			$system_pre_height	= '75';
			break;
		case 'team_flag':
			list($sfz, $ssz) = explode(';', $settings['path_team_flag']['filesize']);
			list($smw, $smh) = explode(':', $settings['path_team_flag']['dimension']);
			break;
		case 'team_logo':
			list($sfz, $ssz) = explode(';', $settings['path_team_logo']['filesize']);
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
		case 'map_pic':
			$sfz	= 2*1048576;
			$ssz	= 2;
			$smw	= '2000';
			$smh	= '1500';
			break;
	}
	
	$sfz = ($sfz * test_size(@$ssz));

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	
	$width = $height = 0;
	$type = '';
	
	if ( ( file_exists(@cms_realpath($image_realname)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $image_filename) )
	{
		if ( $image_filesize <= $sfz && $image_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $image_filetype, $image_filetype);
			$image_filetype = $image_filetype[1];
			
			debug($image_filetype, '$image_filetype');
		}
		else
		{
			$error[] = sprintf($lang['IMAGE_FILESIZE'], round($sfz * test_size($ssz)));
			
			return;
		}

		list($width, $height, $type) = @getimagesize($image_realname);
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
				@unlink($image_realname);
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
				@unlink($image_realname);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ( $imgtype != '.png' )
			{
				@unlink($image_realname);
				message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			
			@unlink($image_realname);
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			
			break;
	}
	
	if ( $width > 0 && $height > 0 && $width <= $smw && $height <= $smh )
	{
		if ( $mode_preview == 'unirand' )
		{
			$new_filename = uniqid(rand());
			$new_filename = $new_filename . $imgtype;
		}
		else if ( $mode_preview == 'preview' )
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
					$image = imagecreatefromjpeg($image_realname);
				break;
				case '.gif':
					$image = imagecreatefromgif($image_realname);
				break;
				case '.png':
					$image = imagecreatefrompng($image_realname);
				break;
			}
		
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
			switch ( $imgtype )
			{
				case '.jpeg':
				case '.pjpeg':
				case '.jpg':
					imagejpeg($image_p, $path_img . "/$new_filename_preview", 100);
				break;
				case '.gif':
					imagegif($image_p, $path_img . "/$new_filename_preview");
				break;
				case '.png':
					imagepng($image_p, $path_img . "/$new_filename_preview");
				break;
			}
		}
		else
		{
			$new_filename = $image_filename;
		}
		
		if ( $cur_img )
		{
			image_delete($cur_img, $pre_img, $path_img, $sql_type);
		}
		
		$move_file = ( @$ini_val('open_basedir') != '' ) ? 'move_uploaded_file' : 'copy';
		
		if (!is_uploaded_file($image_realname))
		{
			message(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
		}
		
		if ( $error )
		{
			@unlink($image_realname);
			return false;
		}
		
		$move_file($image_realname, $path_img . "/$new_filename");
		@chmod($path_img . "/$new_filename", 0644);
		
		if ( $mode )
		{
			/*
			$sql_pic = ( $mode == 'update' ) ?  "$sql_type = '$new_filename'," : $new_filename;
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
		$error[] = sprintf($lang['error_imagesize'], $smw, $smh);
		
		return;
	}
	debug($sql_pic, '_sql_pic_');
	return $sql_pic;
}

function gallery_upload($path_img, $image_filename, $image_realname, $image_filesize, $image_filetype, $max_width, $max_height, $max_filesize, $pic_preview_widht, $pic_preview_height)
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
			$error_msg = sprintf($lang['IMAGE_FILESIZE'], round($max_filesize / 1024));
			message(GENERAL_ERROR, $error_msg);
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
				imagejpeg($image_p, './../' . $settings['path']['gallery'] . '/' . $path_img . "/$new_filename_preview", 100);
				break;
			case '.gif':
				imagegif($image_p, './../' . $settings['path']['gallery'] . '/' . $path_img . "/$new_filename_preview");
				break;
			case '.png':
				imagepng($image_p, './../' . $settings['path']['gallery'] . '/' . $path_img . "/$new_filename_preview");
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
		
		$move_file($image_filename, './../' . $settings['path']['gallery'] . '/' . $path_img . "/$new_filename");
		@chmod('./../' . $settings['path']['gallery'] . '/' . $path_img . "/$new_filename", 0644);
		
		$pic['picture'] = $new_filename;
		$pic['preview'] = $new_filename_preview;
		$pic['filesize'] = $image_filesize;
		$pic['type'] = $image_filetype;
		
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