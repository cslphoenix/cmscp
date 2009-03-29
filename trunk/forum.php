<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FORUM);
init_userprefs($userdata);

$viewcat = ( !empty($HTTP_GET_VARS[POST_CAT_URL]) ) ? $HTTP_GET_VARS[POST_CAT_URL] : -1;

$sql = 'SELECT c.cat_id, c.cat_title, c.cat_order
			FROM ' . CATEGORIES_TABLE . ' c 
			ORDER BY c.cat_order';
//$category_rows = _cached($sql, 'forum_cat');

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
}

$category_rows = array();
while ($row = $db->sql_fetchrow($result))
{
	$category_rows[] = $row;
}
$db->sql_freeresult($result);

$template->set_filenames(array('body' => 'forum_body.tpl'));	

//
	// Start output of page
	//
	$page_title = $lang['forum'];
	include($root_path . 'includes/page_header.php');

if( ( $total_categories = count($category_rows) ) )
{
	//
	// Define appropriate SQL
	//
	$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id
				FROM (( " . FORUMS_TABLE . " f
				LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
				LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
				ORDER BY f.cat_id, f.forum_order";
//	$forum_data = _cached($sql, 'forum_forms');
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}
	
	$forum_data = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_data[] = $row;
	}
	$db->sql_freeresult($result);
	
	if ( !($total_forums = count($forum_data)) )
	{
	//	message_die(GENERAL_MESSAGE, $lang['No_forums']);
		
		$template->assign_vars(array('NO_FORUMS' => $lang['No_forums']));
		$template->assign_block_vars('switch_no_forums', array() );
	}
	
	//
	// Find which forums are visible for this user
	//
	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

	
	
	


	$template->assign_vars(array(

		'FORUM_IMG' => $images['forum'],
		'FORUM_NEW_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],

		'L_FORUM' => $lang['Forum'],
		'L_TOPICS' => $lang['Topics'],
		'L_REPLIES' => $lang['Replies'],
		'L_VIEWS' => $lang['Views'],
		'L_POSTS' => $lang['Posts'],
		'L_LASTPOST' => $lang['Last_Post'], 
		'L_NO_NEW_POSTS' => $lang['No_new_posts'],
		'L_NEW_POSTS' => $lang['New_posts'],
		'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'], 
		'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'], 
		'L_ONLINE_EXPLAIN' => $lang['Online_explain'], 

		'L_MODERATOR' => $lang['Moderators'], 
		'L_FORUM_LOCKED' => $lang['Forum_is_locked'],
		'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'], 

		'U_MARK_READ' => append_sid("index.$phpEx?mark=forums"))
	);

	//
	// Let's decide which categories we should display
	//
	$display_categories = array();

	for ($i = 0; $i < $total_forums; $i++ )
	{
		if ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_view'])
		{
			$display_categories[$forum_data[$i]['cat_id']] = true;
		}
	}

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		//
		// Yes, we should, so first dump out the category
		// title, then, if appropriate the forum list
		//
		if (isset($display_categories[$cat_id]) && $display_categories[$cat_id])
		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'],
				'U_VIEWCAT' => append_sid("forum.php?" . POST_CAT_URL . "=$cat_id"))
			);

			if ( $viewcat == $cat_id || $viewcat == -1 )
			{
				for($j = 0; $j < $total_forums; $j++)
				{
					if ( $forum_data[$j]['cat_id'] == $cat_id )
					{
						$forum_id = $forum_data[$j]['forum_id'];

						if ( $is_auth_ary[$forum_id]['auth_view'] )
						{
							if ( $forum_data[$j]['forum_status'] == FORUM_LOCKED )
							{
								$folder_image = $images['forum_locked']; 
								$folder_alt = $lang['Forum_locked'];
							}
							else
							{
								$unread_topics = false;
								if ( $userdata['session_logged_in'] && $forum_data[$j]['forum_topics'] )
								{
									$sql = 'SELECT topic_id FROM ' . TOPICS_TABLE . ' WHERE forum_id = ' . $forum_id;
									$forum_topics = _cached($sql, 'forum_' . $forum_id . '_topics');
								/*
									if ( !($result = $db->sql_query($sql)) )
									{
										message_die(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
									}
									$forum_topics = $db->sql_fetchrowset($result);
								*/
									//_debug_post($forum_topics);
									
									$sql = 'SELECT tr.topic_id
												FROM ' . TOPICS_READ_TABLE . ' tr
													LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = tr.topic_id
												WHERE tr.user_id = ' . $userdata['user_id'] . ' AND tr.forum_id = ' . $forum_id . ' AND tr.read_time < t.topic_time';
									if ( !($result = $db->sql_query($sql)) )
									{
										message_die(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
									}
									$forum_topics_unread = $db->sql_fetchrowset($result);
									//_debug_post($forum_topics_unread);
									
									if ( is_array($forum_topics_unread) )
									{
										$forum_topics_diff = array_diff_assoc($forum_topics, $forum_topics_unread);
									//	_debug_post($forum_topics_diff);
										$unread_topics = ( !$forum_topics_diff ) ? false : true;
									}
									else
									{
										$unread_topics = true;
									}
								}

								$folder_image = ( $unread_topics ) ? 'images/forum/Folder-red-48x48.png' : 'images/forum/Folder-white-48x48.png';
								$folder_alt = ( $unread_topics ) ? $lang['New_posts'] : $lang['No_new_posts']; 
							}

							$posts = $forum_data[$j]['forum_posts'];
							$topics = $forum_data[$j]['forum_topics'];

							if ( $forum_data[$j]['forum_last_post_id'] )
							{
								$last_post_time = create_date($config['default_dateformat'], $forum_data[$j]['post_time'], $config['board_timezone']);

								$last_post = $last_post_time . '<br />';

								$last_post .= ( $forum_data[$j]['user_id'] == ANONYMOUS ) ? ( ($forum_data[$j]['post_username'] != '' ) ? $forum_data[$j]['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $forum_data[$j]['user_id']) . '">' . $forum_data[$j]['username'] . '</a> ';
								
								$last_post .= '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $forum_data[$j]['forum_last_post_id']) . '#' . $forum_data[$j]['forum_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
							}
							else
							{
								$last_post = $lang['No_Posts'];
							}

							$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
							$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

							$template->assign_block_vars('catrow.forumrow',	array(
								'ROW_COLOR' => '#' . $row_color,
								'ROW_CLASS' => $row_class,
								'FORUM_FOLDER_IMG' => $folder_image, 
								'FORUM_NAME' => $forum_data[$j]['forum_name'],
								'FORUM_DESC' => $forum_data[$j]['forum_desc'],
								'POSTS' => $forum_data[$j]['forum_posts'],
								'TOPICS' => $forum_data[$j]['forum_topics'],
								'LAST_POST' => $last_post,
								'L_FORUM_FOLDER_ALT' => $folder_alt, 

								'U_VIEWFORUM' => append_sid("viewforum.php?" . POST_FORUM_URL . "=$forum_id"))
							);
						}
					}
				}
			}
		}
	} // for ... categories

}// if ... total_categories
else
{
	$template->assign_vars(array('NO_FORUMS' => $lang['No_forums']));
	$template->assign_block_vars('switch_no_forums', array() );
}


$template->pparse("body");
	
include($root_path . 'includes/page_tail.php');

?>