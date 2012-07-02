<?php

define('IN_CMS', true);

$root_path = './';

include($root_path . 'common.php');

$userdata = session_pagestart($user_ip, PAGE_USERS);
$userauth = auth_acp_check($userdata['user_id']);

init_userprefs($userdata);

$start	= ( request('start', INT) ) ? request('start', INT) : 0;
$start	= ( $start < 0 ) ? 0 : $start;

$log	= SECTION_USER;
$url	= POST_USERS;

$time	= time();
$file	= basename(__FILE__);
$user	= $userdata['user_id'];

$data	= request($url, INT);
$mode	= request('mode', TXT);

$error	= '';
$fields	= '';

$template->set_filenames(array(
	'body'		=> 'body_users.tpl',
	'comments'	=> 'body_comments.tpl',
	'error'		=> 'info_error.tpl',
));

main_header();

$sort_order	= request('order', 1);
$by_letter	= request('letter', 2) ? request('letter', 2) : 'all';

$mode_types_text = array($lang['Sort_Joined'], $lang['Sort_Username'], $lang['Sort_Location'], $lang['Sort_Posts'], $lang['Sort_Email'],  $lang['Sort_Website'], $lang['Sort_Top_Ten']);
$mode_types = array('joined', 'username', 'location', 'posts', 'email', 'website', 'topten');

$select_sort_mode = '<select name="mode" class="postselect">';
for($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order" class="postselect">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';


$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_FROM' => $lang['Location'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_AIM' => $lang['AIM'],
	'L_YIM' => $lang['YIM'],
	'L_MSNM' => $lang['MSNM'],
	'L_ICQ' => $lang['ICQ'], 
	'L_JOINED' => $lang['Joined'], 
	'L_POSTS' => $lang['Posts'], 
	'L_PM' => $lang['Private_Message'], 

	'S_MODE' => $select_sort_mode,
	'S_ORDER' => $select_sort_order,
	
	'S_ACTION' => check_sid($file),
));

switch( $mode )
{
	case 'joined':		$order_by = "user_regdate $sort_order LIMIT $start, " . $settings['per_page_entry_site'];	break;
	case 'username':	$order_by = "user_name $sort_order LIMIT $start, " . $settings['per_page_entry_site'];		break;
	case 'location':	$order_by = "user_from $sort_order LIMIT $start, " . $settings['per_page_entry_site'];		break;
	case 'posts':		$order_by = "user_posts $sort_order LIMIT $start, " . $settings['per_page_entry_site'];		break;
	case 'email':		$order_by = "user_email $sort_order LIMIT $start, " . $settings['per_page_entry_site'];		break;
	case 'website':		$order_by = "user_website $sort_order LIMIT $start, " . $settings['per_page_entry_site'];	break;
	default:			$order_by = "user_regdate $sort_order LIMIT $start, " . $settings['per_page_entry_site'];	break;
}

$others_sql = '';
$select_letter = '';

#case 2: $pw.=chr(rand(65,90));  break; //A-Z
#case 3: $pw.=chr(rand(97,122)); break; //a-z

for ( $i = 97; $i <= 122; $i++ )
{
	$others_sql .= " AND LOWER(user_name) NOT LIKE '" . chr($i) . "%' ";
	$select_letter .= ( $by_letter == chr($i) ) ? chr($i) . '&nbsp;' : '<a href="' . check_sid("memberlist.php?letter=" . chr($i) . "&amp;order=$sort_order&amp;start=$start") . '">' . chr($i) . '</a>&nbsp;';
}
$select_letter .= ( $by_letter == 'others' ) ? $lang['sort_others'] . '&nbsp;' : '<a href="' . check_sid("memberlist.php?letter=others&amp;order=$sort_order&amp;start=$start") . '">' . $lang['sort_others'] . '</a>&nbsp;';
$select_letter .= ( $by_letter == 'all' ) ? $lang['sort_all'] : '<a href="' . check_sid("memberlist.php?letter=all&amp;&amp;order=$sort_order&amp;start=$start") . '">' . $lang['sort_all'] . '</a>';

$template->assign_vars(array(
	'L_SORT_PER_LETTER' => $lang['sort_by_letter'],
	'S_LETTER_SELECT' => $select_letter,
	'S_LETTER_HIDDEN' => '<input type="hidden" name="letter" value="' . $by_letter . '">')
);

if($by_letter == 'all')
{
	$letter_sql = '';
}
else if($by_letter == 'others')
{
	$letter_sql = $others_sql;
}
else
{
	$letter_sql = " AND LOWER(user_name) LIKE '$by_letter%' ";
}


	$sql = "SELECT * FROM " . USERS . " WHERE user_id <> " . ANONYMOUS . " $letter_sql ORDER BY $order_by";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);

	$sql = "SELECT t.team_id, t.team_name, tu.user_id, g.game_image
				FROM " . TEAMS . " t
					LEFT JOIN " . GAMES . " g ON t.team_game = g.game_id
					LEFT JOIN " . TEAMS_USERS . " tu ON tu.team_id = t.team_id
			ORDER BY t.team_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$teams[$row['user_id']][] = array('team_id' => $row['team_id'], 'team_name' => $row['team_name'], 'game_image' => $row['game_image']);
	}
	
	$sql = "SELECT g.group_id, g.group_name, g.group_type, gu.user_id
				FROM " . GROUPS . " g
					LEFT JOIN " . GROUPS_USERS . " gu ON gu.group_id = g.group_id
			WHERE g.group_single_user = 0
			ORDER BY g.group_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message(GENERAL_ERROR, 'SQL Error', '', __LINE__, __FILE__, $sql);
	}
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $row['group_type'] != GROUP_HIDDEN )
		{
			$groups[$row['user_id']][] = array('group_id' => $row['group_id'], 'group_name' => $row['group_name']);
		}
	}

if ( !$users )
{
	$template->assign_block_vars('entry_empty', array());
}
else
{
	$count = count($users);
	
	for ( $i = $start; $i < min($settings['per_page_entry_site'] + $start, $count); $i++ )
	{
		$user_id = $users[$i]['user_id'];
	
		gen_userinfo($users[$i], $username);
		
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$uteams = $ugroups = '';
	
		if ( isset($teams[$user_id]) )
		{
			foreach ( $teams[$user_id] as $row )
			{
				$game = display_gameicon($row['game_image']);
				$t_ary[$user_id][] =  $game . ' <a href="' . check_sid('teams.php?' . POST_TEAMS . '=' . $row['team_id']) . '">' . $row['team_name'] . '</a>';
			}
	
			$uteams = implode('<br />', $t_ary[$user_id]);
		}
	
		if ( isset($groups[$user_id]) )
		{
			foreach ( $groups[$user_id] as $row )
			{
				$g_ary[$user_id][] =  '<a href="' . check_sid('groups.php?' . POST_GROUPS . '=' . $row['group_id']) . '">' . $row['group_name'] . '</a>';
			}
	
			$ugroups = implode('<br />', $g_ary[$user_id]);
		}
	
		$template->assign_block_vars('row', array(
			'ROW_COLOR'	=> '#' . $row_color,
			'ROW_CLASS'	=> $row_class,
			'USERNAME'	=> $username,
	
			'TEAMS'		=> $uteams,
			'GROUPS'	=> $ugroups,
		));
	}
}

#$pagination = generate_pagination("memberlist.php?mode=$mode&amp;order=$sort_order&amp;letter=$by_letter", $total_members, $config['per_page_entry_site'], $start). '&nbsp;';

$template->assign_vars(array(
#	'PAGINATION' => $pagination,
#	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $config['per_page_entry_site'] ) + 1 ), ceil( $total_members / $config['per_page_entry_site'] )),

#	'L_GOTO_PAGE' => $lang['Goto_page']
));

$template->pparse('body');

main_footer();

?>