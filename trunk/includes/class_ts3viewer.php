<?php

/*
 * TS3 Web Viewer Class - PHP 5.2.2 or higher is required!
 * only use with beta23 or higher!
 *
 * @author     Branko Wilhelm <bw@z-index.net>
 * @link       http://www.z-index.net
 * @copyright  2010 - 2011 Branko Wilhelm
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @package    ts3.web.viewer.class
 * @version    $Id: class.ts3viewer.php 42 2011-12-09 09:33:32Z bRunO $
 *
 */
class TS3Viewer
{
	/* @var recource socket connection */
    protected $socket = null;

    /* @var integer socket timeout in seconds */
    public $socket_timeout = 5;

    /* @var array player list */
    private $plist = array();

    /* @var array channel list */
    private $clist = array();

    /* @var array server information */
    private $sinfo = array();

    /* @var string image directory */
    public $img_path = './images/';

    /* @var array group data with Name and Icon */
    public $groups = array();

    /* @var array player status (muted, away etc.) */
    public $icons = array();

    /* @var object cache handler */
    public $cache = null;

    /* @var array possible cache handler */
    public $cache_handler = array('Memcache', 'XCache', 'APC', 'File');

    /* @var string cache path for file cache if used */
    public $cache_path = './cache/';

    /* @var integer cache timeout to re-create */
    public $cache_timeout = 60;

    /* @var string ts3 host address */
    private $ts3_host = null;

    /* @var integer ts3 query port */
    private $ts3_query = null;

    /* @var integer ts3 virtual server id */
    private $ts3_sid = null;

    /* @var integer ts3 udp port */
    private $ts3_port = null;

    /* @var string serveradmin password */
    public $serverlogin = array();

    /* @var string html content for first wildcard in tree */
    public $wildcard = '<div class="ts3spacer">&nbsp;</div>';

    /* @var array channel names to hide */
    public $chide = array();

    /* @var array language data */
    public $lang = array();

    /* @var array errors */
    private $errors = array();

    /* @var integer spacer repeat */
    public $spacer_multi = 50;
    
    /* @var integer characters when will the name is shortened  */
    public $player_trim = 0;

    /* constructor - setup default data and arrays
     *
     * @return void */
    public function __construct($host=null, $query=null, $sid=null, $port=null) {

        if ($host === null || $query === null || ($sid === null && $port === null)) {
            $this->errors[] = 'please specify the host, queryport and server port or server id';
            return false;
        }

        $this->ts3_host = $host;
        $this->ts3_query = $query;
        $this->ts3_sid = $sid;
        $this->ts3_port = $port;

        // default groups
        $this->groups['sgroup'][6] = array('n' => 'Server Admin', 'p' => 'client_sa.png');
        $this->groups['cgroup'][5] = array('n' => 'Channel Admin', 'p' => 'client_ca.png');
        $this->groups['cgroup'][6] = array('n' => 'Channel Operator', 'p' => 'client_co.png');

        // default icons
        $this->icons['client_away'] = 1;
        $this->icons['client_is_talker'] = 1;
        $this->icons['client_flag_talking'] = 1;
        $this->icons['client_input_hardware'] = 0;
        $this->icons['client_output_hardware'] = 0;
        $this->icons['client_input_muted'] = 1;
        $this->icons['client_output_muted'] = 1;

        // default lang
        $this->lang['stats']['os'] = 'TS3 OS:';
        $this->lang['stats']['version'] = 'TS3 Version:';
        $this->lang['stats']['channels'] = 'Channels:';
        $this->lang['stats']['onlinesince'] = 'Online since:';
        $this->lang['stats']['uptime'] = 'Uptime:';
        $this->lang['stats']['traffic'] = 'Traffic In/Out:';
        $this->lang['stats']['cache'] = 'Cache time:';
    }

    /* destructor - close socket connection if still open
     *
     * @return void */
    public function __destruct()
	{
		if (is_resource($this->socket))
		{
			fclose($this->socket);
		}
	}

    /* select storage handler
     *
     * @return void */
    private function set_storage() {
        if (!is_array($this->cache_handler) && !empty($this->cache_handler)) {
            $this->cache_handler = array($this->cache_handler);
        }

        foreach ($this->cache_handler as $handler) {
            if ((extension_loaded($handler) || $handler == 'File') && class_exists('TS3ViewerStorage' . $handler)) {
                $handler = 'TS3ViewerStorage' . $handler;
                $this->cache = new $handler;
                break;
            }
        }

        if ($this->cache === null && class_exists('TS3ViewerStorageFile')) {
            $this->cache = new TS3ViewerStorageFile;
        }
        elseif ($this->cache === null) {
            $this->errors[] = 'no cache handler found..';
            return;
        }
        $this->cache_handler = get_class($this->cache);
        if ($this->cache_handler == 'TS3ViewerStorageFile') {
            $this->cache->path = $this->cache_path;
        }

        $this->cache->timeout = $this->cache_timeout;
    }

    /* check cache and (re)build the data arrays
     *
     * @return bool */
    public function build() {

        $this->set_storage();

        $this->cache->key = 'ts3.' . sha1($this->ts3_host . $this->ts3_query . $this->ts3_sid . $this->ts3_port);

        $cache = $this->cache->get();

        if ($cache !== false && is_array($cache) && !empty($cache)) {
            $this->clist = $cache['clist'];
            $this->plist = $cache['plist'];
            $this->sinfo = $cache['sinfo'];
            return true;
        }

        if ($this->refresh() === false) {
            return false;
        }

        unset($cache);
        $cache['clist'] = $this->clist;
        $cache['plist'] = $this->plist;
        $cache['sinfo'] = $this->sinfo;


        return $this->cache->set($cache);
    }

    /* refresh data from server
     *
     * @return bool */
    private function refresh() {

        // try & catch wär zwar besser, funktioniert aber bei fsockopen leider nicht.. deshalb das unschöne @
        $this->socket = @fsockopen($this->ts3_host, $this->ts3_query, $errno, $errstr, $this->socket_timeout);

        if ($errno > 0 && $errstr != '') {
            $this->errors[] = 'fsockopen connect error: ' . $errno;
            return false;
        }

        if (!is_resource($this->socket)) {
            $this->errors[] = 'socket recource not exists';
            return false;
        }

        stream_set_timeout($this->socket, $this->socket_timeout);

        if (!empty($this->serverlogin) && !$this->sendCmd('login', $this->serverlogin['login'] . ' ' . $this->serverlogin['password'])) {
            $this->errors[] = 'serverlogin as "' . $this->serverlogin['login'] . '" failed';
            return false;
        }

        if (!$this->sendCmd("use " . ( $this->ts3_sid ? "sid=" . $this->ts3_sid : "port=" . $this->ts3_port ))) {
            $this->errors[] = 'server select by ' .  ( $this->ts3_sid ? "ID " . $this->ts3_sid : "UDP Port " . $this->ts3_port ) . ' failed';
            return false;
        }

        if (!$sinfo = $this->sendCmd('serverinfo')) {
            return false;
        }
        else {
            $this->sinfo = $this->splitInfo($sinfo);
            $this->sinfo['cachetime'] = time();

            if (substr($this->sinfo['virtualserver_version'], strpos($this->sinfo['virtualserver_version'], 'Build:') + 8, -1) < 11239) { // beta23 build is required
                $this->errors[] = 'your TS3Server build is to low..';
                return false;
            }
        }

        if (!$clist = $this->sendCmd('channellist', '-topic -flags -voice -limits')) {
            return false;
        }
        else {
            $clist = $this->splitInfo2($clist);
            foreach ($clist as $var) {
                $this->clist[] = $this->splitInfo($var);
            }
        }

        if (!$plist = $this->sendCmd('clientlist', '-away -voice -groups')) {
            $this->errors[] = 'playerlist not readable';
            return false;
        }
        else {
            $plist = $this->splitInfo2($plist);
            foreach ($plist as $var) {
                if (strpos($var, 'client_type=0') !== FALSE) {
                    $this->plist[] = $this->splitInfo($var);
                }
            }

            if (!empty($this->plist)) {
                foreach ($this->plist as $key => $var) {
                    $temp = '';
                    if (strpos($var['client_servergroups'], ',') !== FALSE) {
                        $temp = explode(',', $var['client_servergroups']);
                    }
                    else {
                        $temp[0] = $var['client_servergroups'];
                    }
                    $t = '0';
                    foreach ($temp as $t_var) {
                        if ($t_var == '6') {
                            $t = '1';
                        }
                    }
                    if ($t == '1') {
                        $this->plist[$key]['s_admin'] = '1';
                    }
                    else {
                        $this->plist[$key]['s_admin'] = '0';
                    }
                }

                usort($this->plist, array($this, "cmp2"));
                usort($this->plist, array($this, "cmp1"));
            }
        }

        fputs($this->socket, "quit\n");

        $this->close();

        return true;
    }

    /* @param  array
     * @param  array
     * @return integer */
    private function cmp1($a, $b) {
        return strcmp($b["s_admin"], $a["s_admin"]);
    }

    /* @param  array
     * @param  array
     * @return integer */
    private function cmp2($a, $b) {
        return strcmp($b["client_channel_group_id"], $a["client_channel_group_id"]);
    }

    /* close socket connection
     *
     * @return bool false if no socket present, otherwise true if socket closed */
    private function close() {
        if (is_resource($this->socket)) {
            fclose($this->socket);
            return true;
        }
        return false;
    }

    /* send given cmd to the TS3 Server
     *
     * @param  string command
     * @return mixed  false if an error occurred otherwise the server response */
    private function sendCmd($cmd, $params='') {

        if (!is_resource($this->socket)) {
            return false;
        }

        fputs($this->socket, $cmd . ' ' . $params . "\n");

        $msg = '';
        while (strpos($msg, 'msg=') === false) {
            $msg .= fread($this->socket, 8096);
        }

        if (strpos($msg, 'error id=2568') !== false) {
            $this->errors[] = '&quot;<b>' . $cmd . '</b>&quot; command failed! insufficient client permissions!';
            return false;
        }

        if (strpos($msg, 'msg=ok') === false) {
            return false;
        }
        else {
            return $msg;
        }
    }

    /* cleanup the serverinfo and put it into an array
     *
     * @param string serverinfo response
     * @return array */
    private function splitInfo($info) {
        $info = trim(str_replace('error id=0 msg=ok', '', $info));
        $info = explode(' ', $info);
        foreach ($info as $var) {
            if (strpos($var, '=') === false) {
                $return[$var] = '';
            }
            else {
                $a = str_replace('TS3', '', $var);
                $b = trim(substr($a, 0, (strpos($a, '='))));
                $return[$b] = substr($var, (strpos($var, '=') + 1));
            }
        }
        return $return;
    }

    /* cleanup the given info and put it into an array
     *
     * @param string serverinfo response
     * @return array */
    private function splitInfo2($info) {
        $info = trim(str_replace('error id=0 msg=ok', '', $info));
        $info = explode('|', $info);
        return $info;
    }

    /* cleanup escaped string
     *
     * @param  string
     * @return string */
    private function rep($var) {
        $search[] = chr(194);
        $replace[] = '';
        $search[] = chr(183);
        $replace[] = '&#183;';
        $search[] = chr(180);
        $replace[] = '&#180;';
        $search[] = chr(175);
        $replace[] = '&#175;';
        $search[] = '\/';
        $replace[] = '/';
        $search[] = '\s';
        $replace[] = ' ';
        $search[] = '\p';
        $replace[] = '|';
        $search[] = '[URL]';
        $replace[] = '';
        $search[] = '[/URL]';
        $replace[] = '';
        $search[] = '[b]';
        $replace[] = '';
        $search[] = '[/b]';
        $replace[] = '';

        return str_replace($search, $replace, $var);
    }

    /* converts a bytevalue into the highest possible unit
     *
     * @param  integer byte number
     * @return string  human readable byte string */
    private function convert_bytes($byte) {
        $bz = array(" B", " kB", " MB", " GB", " TB");
        $count = 0;
        while ($byte > 1024) {
            $byte /= 1024;
            $count++;
        }
        return number_format($byte, 2, ',', '.') . $bz[$count];
    }

    /* return the current player icon
     *
     * @param  string  current player flag
     * @return string  the icon filename */
    private function player_icon($var) {
        foreach ($this->icons as $key => $opt) {
            if (isset($var[$key]) && $var[$key] == $opt) {
                return $key . '.png';
            }
        }
        return 'client_player.png';
    }
    
    /* return the trimed player name if required
     *
     * @param  string  current player name
     * @return string  trimed player name */
    private function player_trim($var) {
    	$var = $this->rep($var);
    	
    	if($this->player_trim) {
    		$var = mb_substr($var, 0, $this->player_trim);
    	}
    	
    	return $var;
    }

    /* return the Server Uptime
     *
     * @param  integer  server uptime in seconds
     * @return string   human readable Server Uptime */
    private function uptime($sec) {
        $sec = abs($sec);

        return sprintf("%d Days %02d Hours %02d Min", $sec / 60 / 60 / 24, ($sec / 60 / 60) % 24, ($sec / 60) % 60);
    }

    /* output debug data
     *
     * @param  mixed  string, array or object
     * @return echo   array or object as html output
     * @example self::debug($this->sinfo); */
    public function debug($str) {
        if (!empty($str)) {
            echo '<pre>' . (is_array($str) ? print_r($str, true) : $str) . '</pre>';
        }
    }

    /* @return mixed html with formated channel spacer */
    private function spacer($matches) {
        if (!isset($matches[1]) || !isset($matches[2])) {
            return;
        }

        switch ($matches[1]) {
            case "*":
                return '<div class="ts3spacer_repeat">' . str_repeat($matches[2], $this->spacer_multi) . '</div>';
            case "c":
                return '<div class="ts3spacer_center">' . $matches[2] . '</div>';
            case "r":
                return '<div class="ts3spacer_right">' . $matches[2] . '</div>';
            default:
                return '<div class="ts3spacer_left">' . $matches[2] . '</div>';
        }
    }

    /* @return mixed string with the number or false if empty */
    public function useron() {
        if (isset($this->sinfo['virtualserver_clientsonline'])) {
            return ($this->sinfo['virtualserver_clientsonline'] - $this->sinfo['virtualserver_queryclientsonline']) . '/' . $this->sinfo['virtualserver_maxclients'];
        }
        else {
            return false;
        }
    }

    /* @return mixed string with the html tag or false if empty */
    public function website() {
        if (!empty($this->sinfo['virtualserver_hostbutton_url'])) {
            return '<a href="' . $this->rep($this->sinfo['virtualserver_hostbutton_url']) . '" ' . (empty($this->sinfo['virtualserver_hostbutton_tooltip']) ? '' : 'title="' . $this->rep($this->sinfo['virtualserver_hostbutton_tooltip']) . '"') . ' target="_blank">' . (empty($this->sinfo['virtualserver_hostbutton_tooltip']) ? $this->rep($this->sinfo['virtualserver_hostbutton_url']) : $this->rep($this->sinfo['virtualserver_hostbutton_tooltip'])) . '</a>';
        }
        else {
            return false;
        }
    }

    /* @return mixed string with the html tag or false if empty */
    public function banner() {
        if (!empty($this->sinfo['virtualserver_hostbanner_gfx_url'])) {
            return '<img class="ts3banner" src="' . $this->rep($this->sinfo['virtualserver_hostbanner_gfx_url']) . '" alt="" />';
        }
        else {
            return false;
        }
    }

    /* @return string html content with legend of Groups */
    public function legend() {
        $return = '<div class="ts3legend">';
        foreach ($this->groups as $group) {
            foreach ($group as $key => $var) {
                $return .= '<img src="' . $this->img_path . $group[$key]['p'] . '" alt="' . $group[$key]['p'] . '" />&nbsp;' . $group[$key]['n'] . "<br/>\n";
            }
        }
        $return .= '</div>';
        return $return;
    }

    /* @param  string  statistics options that should be hidden, e.g. "cache, created"
     * @return string  html content with an overview of server statistics */
    public function stats($hide=null) {
        if (empty($this->sinfo)) {
            return false;
        }

        $this->sinfo['virtualserver_created'] = date('d M Y', $this->sinfo['virtualserver_created']);

        $traffic_out = $this->convert_bytes($this->sinfo['connection_bytes_sent_total']);
        $traffic_in = $this->convert_bytes($this->sinfo['connection_bytes_received_total']);

        $return = '<table class="ts3stats" cellpadding="1" cellspacing="0">' . "\n";
        if (strpos($hide, 'os') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['os'] . '</td><td>' . $this->rep($this->sinfo['virtualserver_platform']) . '</td></tr>' . "\n";
        if (strpos($hide, 'version') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['version'] . '</td><td>' . $this->rep($this->sinfo['virtualserver_version']) . '</td></tr>' . "\n";
        if (strpos($hide, 'channels') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['channels'] . '</td><td>' . $this->rep($this->sinfo['virtualserver_channelsonline']) . '</td></tr>' . "\n";
        if (strpos($hide, 'created') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['onlinesince'] . '</td><td>' . $this->rep($this->sinfo['virtualserver_created']) . '</td></tr>' . "\n";
        if (strpos($hide, 'uptime') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['uptime'] . '</td><td>' . $this->uptime($this->sinfo['virtualserver_uptime']) . '</td></tr>' . "\n";
        if (strpos($hide, 'traffic') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['traffic'] . '</td><td>' . $traffic_in . ' / ' . $traffic_out . '</td></tr>' . "\n";
        if (strpos($hide, 'cache') === false)
            $return .= '<tr><td class="ts3statsle">' . $this->lang['stats']['cache'] . '</td><td>' . date('d M Y, H:i', $this->sinfo['cachetime']) . 'h</td></tr>' . "\n";
        $return .= "</table>\n";

        return $return;
    }

    /* @return mixed string with the servername or false if empty */
    public function title() {
        if (!empty($this->sinfo['virtualserver_name'])) {
            return $this->rep($this->sinfo['virtualserver_name']);
        }
        else {
            return false;
        }
    }

    /* @return mixed html with serverimage and servername or false if empty */
    public function tree_head() {
        if (!empty($this->sinfo['virtualserver_name'])) {
            return '<span class="ts3head"><img src="' . $this->img_path . 'serverimg.png" alt="" title="' . $this->rep($this->sinfo['virtualserver_welcomemessage']) . '"/>&nbsp;' . $this->rep($this->sinfo['virtualserver_name']) . "</span>\n";
        }
        else {
            return false;
        }
    }

    /* @return mixed html with complete channel and playerlist or false if channellist empty */
    public function tree($channel=0, $wildcard='') {
        if ($this->error_handler()) {
            return false;
        }

        if (empty($this->clist)) {
            return false;
        }

        $return = '';
        foreach ($this->clist as $key => $var) {
            if ($var['pid'] != $channel || isset($this->chide[strtolower($this->rep($var['channel_name']))])) {
                continue;
            }

            if (preg_match("#\[(.*)spacer.*\](.*)#", $this->rep($var['channel_name']), $spacer) && !$var['pid']) {
                $return .= $this->spacer($spacer);
            }
            else {

                $return .= $wildcard . $this->wildcard . "\n";
                $return .= '<div class="ts3na"><img src="' . $this->img_path . ($var['channel_flag_password'] == 1 ? 'channel_locked.png' : 'channel.png') . '" alt="channel.png" title="' . $this->rep($var['channel_topic']) . '" /></div>' . "\n";
                $return .= '<div class="ts3na">' . $this->rep($var['channel_name']) . '</div>' . "\n";
                if ($var['channel_flag_default'] == 1) {
                    $return .= '<div class="ts3ca"><img src="' . $this->img_path . 'home.png" alt="home.png" title="' . $this->rep($var['channel_topic']) . '" /></div>' . "\n";
                }

                if ($var['channel_flag_password'] == 1) {
                    $return .= '<div class="ts3ca"><img src="' . $this->img_path . 'locked.png" alt="locked.png" title="locked" /></div>' . "\n";
                }

                if ($var['channel_codec'] == 3) {
                    $return .= '<div class="ts3ca"><img src="' . $this->img_path . 'channel_music.png" alt="channel_music.png" title="Music Codec" /></div>' . "\n";
                }

                $return .= '<br class="ts3clear" />' . "\n";
                if ($var['total_clients'] >= 1 && !empty($this->plist)) {
                    foreach ($this->plist as $u_key => $u_var) {
                        if ($u_var['cid'] == $var['cid']) {
                            $p_img = $this->player_icon($u_var);

                            $g_img = '';
                            $g_temp = '';
                            if (strpos($u_var['client_servergroups'], ',') !== FALSE) {
                                $g_temp = explode(',', $u_var['client_servergroups']);
                            }
                            else {
                                $g_temp[0] = $u_var['client_servergroups'];
                            }
                            foreach ($g_temp as $sg_var) {
                                if (isset($this->groups['sgroup'][$sg_var]['p'])) {
                                    $g_img .= '<div class="ts3ca"><img src="' . $this->img_path . $this->groups['sgroup'][$sg_var]['p'] . '" title="' . $this->groups['sgroup'][$sg_var]['n'] . '" alt="" /></div>' . "\n";
                                }
                            }
                            if (isset($this->groups['cgroup'][$u_var['client_channel_group_id']]['p'])) {
                                if (isset($this->groups['cgroup'][$u_var['client_channel_group_id']]['p'])) {
                                    $g_img .= '<div class="ts3ca"><img src="' . $this->img_path . $this->groups['cgroup'][$u_var['client_channel_group_id']]['p'] . '" title="' . $this->groups['cgroup'][$u_var['client_channel_group_id']]['n'] . '" alt="" /></div>' . "\n";
                                }
                            }

                            if ($u_var['client_is_priority_speaker'] == 1) {
                                $g_img .= '<div class="ts3ca"><img src="' . $this->img_path . 'client_is_priority_speaker.png" alt="client_is_priority_speaker.png" title="Priority speaker" /></div>' . "\n";
                            }

                            $return .= $wildcard . $this->wildcard . '<div class="ts3spacer">&nbsp;</div><div class="ts3na"><img src="' . $this->img_path . $p_img . '" alt="' . $p_img . '" /></div><div class="ts3na"><b>' . $this->player_trim($u_var['client_nickname']) . '</b></div>' . $g_img . '<div class="ts3clear"></div>' . "\n";
                        }
                    }
                }
            }
            $return .= $this->tree($var['cid'], $wildcard . '<div class="ts3spacer">&nbsp;</div>');
        }
        return $return;
    }

    /* @return mixed html with error context if exists */
    private function error_handler() {
        if (!empty($this->errors)) {
            echo '<ul class="ts3error">';
            foreach ($this->errors as $err) {
                echo '<li>' . $err . '</li>';
            }
            echo '</ul>';
            return true;
        }
        return false;
    }

}

/* TS3 Web Viewer Storage File Class
 *
 * @author     Branko Wilhelm <mail@nerd-zone.de>
 * @link       http://www.nerd-zone.de
 * @copyright  2010 - 2011 Branko Wilhelm
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @package    ts3.web.viewer.class
 * @version    $Id: class.ts3viewer.php 42 2011-12-09 09:33:32Z bRunO $ */
final class TS3ViewerStorageFile {

    /* @var string cachename */
    public $key = null;

    /* @var string cache path */
    public $path = null;

    /* @var string cachefile extension */
    public $ext = '.php';

    /* @var integer timeout to re-create */
    public $timeout = 60;

    /* store given data serialized into a file
     *
     * @param  string  filename
     * @param  array   data array
     * @return bool    false if an error occurred otherwise true */
    public function set($data) {
        if ($this->path === null) {
            trigger_error('TS3Viewer: no cache path given', E_USER_WARNING);
            return false;
        }

        if (!file_exists($this->path) || !is_writable($this->path)) {
            trigger_error("TS3Viewer: cache directory '" . $this->path . "' does't exists or isn't writable", E_USER_WARNING);
            return false;
        }

        return file_put_contents($this->path . $this->key . $this->ext, serialize($data));
    }

    /* get unserialized data from cachefile
     *
     * @param  string filename
     * @return mixed  false or array */
    public function get() {

        if (!file_exists($this->path . $this->key . $this->ext)) {
            return false;
        }
        if (filemtime($this->path . $this->key . $this->ext) < time() - $this->timeout) {
            return false;
        }

        return unserialize(file_get_contents($this->path . $this->key . $this->ext));
    }

}

/* TS3 Web Viewer Storage Memcache Class
 *
 * @author     Branko Wilhelm <mail@nerd-zone.de>
 * @link       http://www.nerd-zone.de
 * @copyright  2010 - 2011 Branko Wilhelm
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @package    ts3.web.viewer.class
 * @version    $Id: class.ts3viewer.php 42 2011-12-09 09:33:32Z bRunO $ */
final class TS3ViewerStorageMemcache {

    /* @var resource kann overwrite with own memcache obj if exists */
    public $_db = null;

    /* @var string cachename */
    public $key = null;

    /* @var string memcache host */
    public $host = 'localhost';

    /* @var integer memcache port */
    public $port = 11211;

    /* @var integer timeout to re-create */
    public $timeout = 60;

    /* connect the memcache server
     *
     * @return bool */
    private function connect() {
        $this->_db = new memcache;
        return $this->_db->connect($this->host, $this->port);
    }

    /* store given data into memcache
     *
     * @param  string filename
     * @param  array  data array
     * @return bool   false if an error occurred otherwise true */
    public function set($data) {
        if ($this->_db == null) {
            $this->connect();
        }

        return $this->_db->set($this->key, $data, MEMCACHE_COMPRESSED, $this->timeout);
    }

    /* get data from memcache
     *
     * @return mixed false or array */
    public function get() {
        if ($this->_db == null) {
            $this->connect();
        }
        return $this->_db->get($this->key);
    }

}

/* TS3 Web Viewer Storage APC Class
 *
 * @author     Branko Wilhelm <mail@nerd-zone.de>
 * @link       http://www.nerd-zone.de
 * @copyright  2010 - 2011 Branko Wilhelm
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @package    ts3.web.viewer.class
 * @version    $Id: class.ts3viewer.php 42 2011-12-09 09:33:32Z bRunO $ */
final class TS3ViewerStorageAPC {

    /* @var string cachename */
    public $key = null;

    /* @var integer timeout to re-create */
    public $timeout = 60;

    /* store given data into apc
     *
     * @param  string filename
     * @param  array  data array
     * @return bool   false if an error occurred otherwise true */
    public function set($data) {
        return apc_store($this->key, $data, $this->timeout);
    }

    /* get data from apc
     *
     * @return mixed false or array */
    public function get() {
        return apc_fetch($this->key);
    }

}

/* TS3 Web Viewer Storage XCache Class
 *
 * @author     Branko Wilhelm <mail@nerd-zone.de>
 * @link       http://www.nerd-zone.de
 * @copyright  2010 - 2011 Branko Wilhelm
 * @license    GNU Public License <http://www.gnu.org/licenses/gpl.html>
 * @package    ts3.web.viewer.class
 * @version    $Id: class.ts3viewer.php 42 2011-12-09 09:33:32Z bRunO $ */
final class TS3ViewerStorageXCache {

    /* @var string cachename */
    public $key = null;

    /* @var integer timeout to re-create */
    public $timeout = 60;

    /* store given data into xcache
     *
     * @param  string filename
     * @param  array  data array
     * @return bool   false if an error occurred otherwise true */
    public function set($data) {
        return xcache_set($this->key, $data, $this->timeout);
    }

    /* get data from xcache
     *
     * @return mixed false or array */
    public function get() {
        if (!xcache_isset($this->key)) {
            return false;
        }

        return xcache_get($this->key);
    }

}
