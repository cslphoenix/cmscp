<?php

define('IN_CMS', true);
$root_path = './';
include($root_path . 'common.php');

//
//	Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FORUM);
init_userprefs($userdata);

$page_title = $lang['forum'];
include($root_path . 'includes/page_header.php');

$template->set_filenames(array('body' => 'forum_body.tpl'));	

$sql = "SELECT cat_id, cat_title, cat_intern, cat_order FROM " . FORUM_CATEGORIE_TABLE . " WHERE cat_intern != 1 ORDER BY cat_order";
if( !$q_categories = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
}

if( $total_categories = $db->sql_numrows($q_categories) )
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT * FROM " . FORUM_FORUMS_TABLE . " WHERE forum_intern != 1 ORDER BY cat_id, forum_order";
	if(!$q_forums = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
	}

	if( $total_forums = $db->sql_numrows($q_forums) )
	{
		$forum_rows = $db->sql_fetchrowset($q_forums);
	}

	//
	// Okay, let's build the index
	//
	$gen_cat = array();

	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		$template->assign_block_vars("catrow", array( 
			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],

			'U_VIEWCAT' => append_sid($root_path."forum.php?" . POST_CAT_URL . "=$cat_id"))
		);

		for($j = 0; $j < $total_forums; $j++)
		{
			$forum_id = $forum_rows[$j]['forum_id'];
			
			if ($forum_rows[$j]['cat_id'] == $cat_id && $forum_rows[$j]['forum_parent'] == 0)
			{
				$template->assign_block_vars("catrow.forumrow",	array(
					'FORUM_NAME' => $forum_rows[$j]['forum_name'],
					'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
					
					'TOPICS' => $forum_rows[$j]['forum_topics'],
					'POSTS' => $forum_rows[$j]['forum_posts'],

					'U_VIEWFORUM' => append_sid($root_path."viewforum.php?" . POST_FORUM_URL . "=$forum_id"),
				));
				
				for( $k = 0; $k < $total_forums; $k++ )
				{
					$forum_id2 = $forum_rows[$k]['forum_id'];
					if ( $forum_rows[$k]['forum_parent'] == $forum_id )
					{
						$template->assign_block_vars("catrow.forumrow.parent",	array(
							'FORUM_NAME'	=> $forum_rows[$k]['forum_name'],
							'U_VIEWFORUM'	=> append_sid($root_path."viewforum.php?" . POST_FORUM_URL . "=$forum_id2"),
						));
					}
				}
			}// if ... forumid == catid
		} // for ... forums
	} // for ... categories
}// if ... total_categories

$template->assign_vars(array(
	
	'L_SUBFORUMS' => $lang['subforums'],
	'L_TOPICS' => $lang['Topics'],
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

$template->pparse("body");
	
	
include($root_path . 'includes/page_tail.php');

?>