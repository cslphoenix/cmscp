<?php

/*
 *
 *
 *							___.          
 *	  ____   _____   ______ \_ |__ ___.__.
 *	_/ ___\ /     \ /  ___/  | __ <   |  |
 *	\  \___|  Y Y  \\___ \   | \_\ \___  |
 *	 \___  >__|_|  /____  >  |___  / ____|
 *		 \/      \/     \/       \/\/     
 *	__________.__                         .__        
 *	\______   \  |__   ____   ____   ____ |__|__  ___
 *	 |     ___/  |  \ /  _ \_/ __ \ /    \|  \  \/  /
 *	 |    |   |   Y  (  <_> )  ___/|   |  \  |>    < 
 *	 |____|   |___|  /\____/ \___  >___|  /__/__/\_ \
 *				   \/            \/     \/         \/ 
 *
 *	- Content-Management-System by Phoenix
 *
 *	- @autor:	Sebastian Frickel © 2009
 *	- @code:	Sebastian Frickel © 2009
 *
 */

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
			message_die(GENERAL_ERROR, $lang['image_filetype_wrong'], '', __LINE__, __FILE__);
		break;
	}

	return false;
}

function image_upload($image_mode, $image_path, $image_realname, $image_filesize, $image_filetype)
{
	global $db, $lang, $settings;

	$width = $height = $type = ''
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
			
			message_die(GENERAL_ERROR, $error_msg, '', __LINE__, __FILE__);
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
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
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
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		// PNG
		case 3:
			if ( $imgtype != '.png' )
			{
				@unlink($tmp_filename);
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
		break;

		default:
			@unlink($tmp_filename);
			message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
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
		
		debug($move_file);

		if (!is_uploaded_file($image_filename))
		{
			message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
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
			
		message_die(GENERAL_ERROR, 'einfach ein fehler -.-\'' . $error_msg, '', __LINE__, __FILE__);
	}
}

?>