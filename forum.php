<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_FORUM);
init_userprefs($userdata);

#$viewcat = ( !empty($_GET[POST_CATEGORY]) ) ? $_GET[POST_CATEGORY] : -1;

$main	= request('main', TYP) ? request('main', TYP) : 0;

#$viewcat = request('id', INT);

$sqlout = data(FORUM, false, 'main ASC, forum_order ASC', 1, false);

#debug($sqlout, '$sqlout');

#debug($main, 'main');
		
$cats = $forms = $subforms = array();

if ( $sqlout )
{
	foreach ( $sqlout as $rows )
	{
		if ( $rows['type'] == 0 )
		{
			$cat[$rows['forum_id']] = $rows;
		}
		else if ( $rows['main'] != 0 && $rows['type'] == 1 )
		{
			$forms[$rows['main']][$rows['forum_id']] = $rows;
		}
	}
	
	if ( $forms )
	{
		$keys_labels = array_keys($forms);
		
		foreach ( $sqlout as $rows )
		{
			if ( in_array($rows['main'], $keys_labels) )
			{
				$subforms[$rows['main']][] = $rows;
			}
			
		}
	}
}

$template->set_filenames(array('body' => 'body_forum.tpl'));	

$page_title = $lang['forum'];
main_header();

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

	'U_MARK_READ' => check_sid('index.$phpEx?mark=forums'),
));

#debug($cat, 'cat');
debug($forms, 'forms');
#debug($subforms, 'subforms');

if ( $cat )
{
	foreach ( $cat as $_numc => $_valc )
	{
		#debug($_numc);
		
		if ( isset($forms[$_numc]) && auth('f_read', array_keys($forms[$_numc]), $userdata))
		{
			debug($forms[$_numc], 'test 1234');
			
			$template->assign_block_vars('_c', array(
				'NAME'	=> $_valc['forum_name'],
			));
		}
	}
}
/*	
if ( $cat )
{
#	foreach ( $cat as $_rows )
#	{
#		$template->assign_block_vars('_cat', array(
#			'CAT_ID'	=> $_rows['forum_id'],
#			'CAT_NAME'	=> $_rows['forum_name'],
#		));
#		
#	}
	
	
#	$sql = "SELECT f.*, p.post_time, p.post_user_name, u.user_name, u.user_id
#				FROM (( " . FORUM . " f
#				LEFT JOIN " . POSTS . " p ON p.post_id = f.forum_last_post_id )
#				LEFT JOIN " . USERS . " u ON u.user_id = p.poster_id )
#				WHERE f.forum_id != 0
#				ORDER BY f.forum_id, f.forum_order";
//	$forms = _cached($sql, 'forum_forms');
#	if ( !($result = $db->sql_query($sql)) )
#	{
#		message(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
#	}
#	$forms = $db->sql_fetchrowset($result);
	
#	debug($forms);

	if ( $forms )
	{
		//
		// Find which forums are visible for this user
		//
		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forms);
		
		//
		// Let's decide which categories we should display
		//
		$display_categories = array();
	
		for ( $i = 0; $i < count($forms); $i++ )
		{
			if ( $is_auth_ary[$forms[$i]['forum_id']]['auth_view'] )
			{
				$display_categories[$forms[$i]['cat_id']] = true;
			}
		}
	
		for ( $i = 0; $i < count($cats); $i++ )
		{
			$cat_id = $cats[$i]['cat_id'];

			if ( isset($display_categories[$cat_id]) && $display_categories[$cat_id] )
			{
				if ( $viewcat == $cat_id || $viewcat == -1 )
				{
					$template->assign_block_vars('cat_row', array(
						'CAT_ID' => $cat_id,
						'CAT_DESC' => $cats[$i]['cat_name'],
						'U_VIEWCAT' => check_sid('forum.php?' . POST_CATEGORY . '=' . $cat_id)
					));
					
					for ( $j = 0; $j < count($forms); $j++ )
					{
						$forum_id = $forms[$j]['forum_id'];
						
						$sql = "SELECT * FROM " . FORUM . " WHERE forum_sub = " . $forms[$j]['forum_id'] . " ORDER BY forum_order ASC";
						if ( !($result = $db->sql_query($sql)) )
						{
							message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
						}
						$subforms = $db->sql_fetchrowset($result);
						
						if ( $cat_id == $forms[$j]['cat_id'] )
						{
							$forum_id = $forms[$j]['forum_id'];
	
							if ( $is_auth_ary[$forum_id]['auth_view'] )
							{
								if ( $forms[$j]['forum_status'] == FORUM_LOCKED )
								{
									$folder_image = $images['forum_locked']; 
									$folder_alt = $lang['Forum_locked'];
								}
								else
								{
									$unread_topics = false;
									if ( $userdata['session_logged_in'] && $forms[$j]['forum_topics'] )
									{
										$sql = 'SELECT topic_id FROM ' . TOPICS . ' WHERE forum_id = ' . $forum_id;
										$forum_topics = _cached($sql, 'forum_' . $forum_id . '_topics');
									<--
										if ( !($result = $db->sql_query($sql)) )
										{
											message(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
										}
										$forum_topics = $db->sql_fetchrowset($result);
									-->
										//debug($forum_topics);
										
										$sql = 'SELECT tr.topic_id
													FROM ' . TOPICS_READ . ' tr
														LEFT JOIN ' . TOPICS . ' t ON t.topic_id = tr.topic_id
													WHERE tr.user_id = ' . $userdata['user_id'] . ' AND tr.forum_id = ' . $forum_id . ' AND tr.read_time < t.topic_time';
										if ( !($result = $db->sql_query($sql)) )
										{
											message(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
										}
										$forum_topics_unread = $db->sql_fetchrowset($result);
										//debug($forum_topics_unread);
										
										if ( is_array($forum_topics_unread) )
										{
											$forum_topics_diff = array_diff_assoc($forum_topics, $forum_topics_unread);
										//	debug($forum_topics_diff);
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
	
								$posts = $forms[$j]['forum_posts'];
								$topics = $forms[$j]['forum_topics'];
	
								if ( $forms[$j]['forum_last_post_id'] )
								{
									$last_post_time = create_date($config['default_dateformat'], $forms[$j]['post_time'], $config['default_timezone']);
	
									$last_post = $last_post_time . '<br>';
	
									$last_post .= ( $forms[$j]['user_id'] == ANONYMOUS ) ? ( ($forms[$j]['post_user_name'] != '' ) ? $forms[$j]['post_user_name'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . check_sid('profile.$phpEx?mode=viewprofile&amp;id='  . $forms[$j]['user_id']) . '">' . $forms[$j]['user_name'] . '</a> ';
									
									$last_post .= '<a href="' . check_sid('viewtopic.php?'  . POST_POST . '=' . $forms[$j]['forum_last_post_id']) . '#' . $forms[$j]['forum_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
								}
								else
								{
									$last_post = $lang['No_Posts'];
								}
	
								$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
								$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
								$template->assign_block_vars('cat_row.forum_row',	array(
									'ROW_COLOR' => '#' . $row_color,
									'ROW_CLASS' => $row_class,
									'FORUM_FOLDER_IMG' => $folder_image, 
									'FORUM_NAME' => $forms[$j]['forum_name'],
									'FORUM_DESC' => $forms[$j]['forum_desc'],
									'POSTS' => $forms[$j]['forum_posts'],
									'TOPICS' => $forms[$j]['forum_topics'],
									'LAST_POST' => $last_post,
									'L_FORUM_FOLDER_ALT' => $folder_alt, 
	
									'U_VIEWFORUM' => check_sid('forum_view.php?' . POST_FORUM . '=' . $forum_id),
								));
								
								if ( $forms[$j]['forum_legend'] )
								{
									for ( $k = 0; $k < count($subforms); $k++ )
									{
										$forumsub_id = $subforms[$k]['forum_sub'];
										
										if ( $forum_id == $subforms[$k]['forum_sub'] )
										{
											$template->assign_block_vars('cat_row.forum_row.subforum_row', array(
												'NAME' => $subforms[$k]['forum_name'],
											));
										}
									} # subforms noch nicht abgestimmt mit ansicht also rechten soweit!
								}
							}
						}	
					} # forms
				}
			}
		} # cats
	}
	else
	{
		$template->assign_vars(array('NO_FORUMS' => $lang['No_forums']));
		$template->assign_block_vars('no_forums', array() );
	}
	
}
else
{
	$template->assign_vars(array('NO_FORUMS' => $lang['No_forums']));
	$template->assign_block_vars('no_forums', array() );
}
*/

$template->pparse('body');
	
main_footer();

?>