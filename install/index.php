<?php
/*
 * GamePanelX Pro
 * Complete Game and Voice server management tool
 * 
 * Copyright(C) 2009-2010 GamePanelX Pro.  All Rights Reserved. 
 * 
 * Email: support@gamepanelx.com
 * Website: http://www.gamepanelx.com
 * 
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.                                                         
 *                                                                      
 * You may not reverse  engineer, decompile, defeat  license  encryption
 * mechanisms, or  disassemble this software product or software product
 * license.  GamePanelX Pro may terminate this license if you don't
 * comply with any of the terms and conditions set forth in our end user
 * license agreement (EULA).  In such event,  licensee  agrees to return
 * licensor  or destroy  all copies of software  upon termination of the
 * license.                                                             
 *                                                                      
 * Please see the EULA file for the full End User License Agreement.    
*/
error_reporting(E_ERROR);

// If they have a current install, send to update.php
if(file_exists('../include/db.php')) include('../include/db.php');

if(isset($config['sql_host']) && isset($config['sql_user']))
{
    if(!empty($config['sql_user']))
    {
        echo '<div align="center">You seem to have a GamePanelX installation already.<br /><br /><a href="update.php">Click Here to update</a>.</div>';
        exit;
    }
}


// Get version
require('version.php');

########################################################################

$config = array();
$config['Language']   = 'english';
$config['Template']   = 'default';
$config['Theme']      = 'default';

########################################################################

//
// First Page (DB settings, admin user)
//
if(!isset($_POST['checkreq']) && !isset($_POST['step1']) && !isset($_POST['step2']))
{
    require('checkrequired.php');
    exit;
}

//
// Begin installation options (Real 1st page)
//
elseif(isset($_POST['checkreq']) && !isset($_POST['step1']) && !isset($_POST['step2']))
{
    // Current directory
    $current_dir  = getcwd();
    $current_dir  = str_replace('/install', '/', $current_dir); // Remove '/install' at the end
    
    require('step1.php');
    exit;
}


//
// Second page (System settings)
//
elseif(isset($_POST['step1']) && !isset($_POST['step2']) && !isset($_POST['checkreq']))
{
    // POST Values
    $post_install_dir   = base64_encode($_POST['install_dir']);
    $post_db_host       = base64_encode($_POST['db_host']);
    $post_db_name       = base64_encode($_POST['db_name']);
    $post_db_user       = base64_encode($_POST['db_user']);
    $post_db_pass       = base64_encode($_POST['db_pass']);
    $post_admin_user    = base64_encode($_POST['admin_user']);
    $post_admin_pass    = base64_encode($_POST['admin_pass']);
    $post_admin_email   = base64_encode($_POST['admin_email']);
    $post_language      = base64_encode($_POST['language']);
    
    ####################################################################
    
    // Check empty
    if(empty($post_install_dir) || 
    empty($post_db_host) || 
    empty($post_db_name) || 
    empty($post_db_user) || 
    empty($post_db_pass) || 
    empty($post_admin_user) || 
    empty($post_admin_pass) || 
    empty($post_admin_email) || 
    empty($post_language))
    {
        die('<b>Error:</b> Required fields were left blank.');
    }
    
    ####################################################################
    
    // Display Step 2
    require('step2.php');
    exit;
}



//
// Second page (Install)
//
elseif(isset($_POST['step2']) && !isset($_POST['step1']) && !isset($_POST['checkreq']))
{
    // DB Info
    $post_db_host       = stripslashes(base64_decode($_POST['db_host']));
    $post_db_name       = stripslashes(base64_decode($_POST['db_name']));
    $post_db_user       = stripslashes(base64_decode($_POST['db_user']));
    $post_db_pass       = stripslashes(base64_decode($_POST['db_pass']));
    
    ####################################################################
    
    // Connect to the database with the given values
    $db = @mysql_connect($post_db_host,$post_db_user,$post_db_pass) or die('<center><b>Error:</b> <i>install</i>: Failed to connect to the database!</center>');
    @mysql_select_db($post_db_name) or die('<center><b>Error:</b> <i>install</i>: Failed to select the database!</center>');
    
    ####################################################################
    
    // POST Values
    $post_install_dir   = mysql_real_escape_string(base64_decode($_POST['install_dir']));
    $post_admin_user    = mysql_real_escape_string(base64_decode($_POST['admin_user']));
    $post_admin_pass    = mysql_real_escape_string(base64_decode($_POST['admin_pass']));
    $post_admin_email   = mysql_real_escape_string(base64_decode($_POST['admin_email']));
    $post_language      = mysql_real_escape_string(base64_decode($_POST['language']));
    $post_os            = mysql_real_escape_string($_POST['os']);
    $post_ip            = mysql_real_escape_string($_POST['ip']);
    $post_description   = mysql_real_escape_string($_POST['description']);
    $post_location      = mysql_real_escape_string($_POST['location']);
    $post_datacenter    = mysql_real_escape_string($_POST['datacenter']);
    $post_conn_user     = mysql_real_escape_string($_POST['conn_user']);
    $post_conn_pass     = mysql_real_escape_string($_POST['conn_pass']);
    $post_conn_port     = mysql_real_escape_string($_POST['conn_port']);
    
    ####################################################################

    // Check empty
    if(empty($post_install_dir) || 
    empty($post_db_host) || 
    empty($post_db_name) || 
    empty($post_db_user) || 
    empty($post_db_pass) || 
    empty($post_admin_user) || 
    empty($post_admin_pass) || 
    empty($post_admin_email) || 
    empty($post_language) || 
    empty($post_os) || 
    empty($post_ip) || 
    empty($post_conn_user) || 
    empty($post_conn_pass) || 
    empty($post_conn_port))
    {
        die('<b>Error:</b> Required fields were left blank.');
    }
    
    ####################################################################
    
    //
    // Install database tables
    //
    if(file_exists('versions/tables.php'))
    {
        require('versions/tables.php');
    }
    else
    {
        die('<b>Error:</b> versions/tables.php file not found');
    }
    
    /*
     * OLD [version].php files
     * DEPRECATED as of 1.0.2
     * 
    $version_file = 'versions/' . GPX_VERSION . '.php';
    
    if(file_exists($version_file))
    {
        require($version_file);
    }
    else
    {
        die('Install file "' . $version_file . '" does not exist.');
    }
    */
    
    ####################################################################
    
    //
    // Generate a random API key and Encryption key
    //
    function gpx_gen_random_str($length)
    {
        if(empty($length))
        {
            $length = 10;
        }
        
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $stringz = "";
        
        for ($p = 0; $p < $length; $p++)
        {
            $stringz .= $characters[mt_rand(0, strlen($characters))];
        }
        return $stringz;
    }
    
    // Create an API Key
    $random_api_key = gpx_gen_random_str(128);
    
    // Create an encryption key
    $random_enc_key = gpx_gen_random_str(64);
    
    ####################################################################
    
    //
    // Create config file (db.php)
    //
    $fh = fopen('../include/db.php', 'w') or die('Failed opening "include/db.php".  Rename "include/db.php.new" to "include/db.php" and try again.  Otherwise check for file write permissions.');

    // Add the config items
    fwrite($fh, '<?php' . "\n");
    fwrite($fh, '// This file was automatically generated by the GamePanelX Pro installer' . "\n");
    fwrite($fh, 'error_reporting(E_ERROR);' . "\n");
    fwrite($fh, '$config[\'sql_host\']  = \'' . $post_db_host . '\';' . "\n");
    fwrite($fh, '$config[\'sql_user\']  = \'' . $post_db_user . '\';' . "\n");
    fwrite($fh, '$config[\'sql_pass\']  = \'' . $post_db_pass . '\';' . "\n");
    fwrite($fh, '$config[\'sql_db\']  = \'' . $post_db_name . '\';' . "\n");
    fwrite($fh, '$config[\'encrypt_key\']  = \'' . $random_enc_key . '\';' . "\n");
    fwrite($fh, '$config[\'api_key\']  = \'' . $random_api_key . '\';' . "\n");
    fwrite($fh, '?>' . "\n");
    
    // Close the file
    fclose($fh);

    ####################################################################
    
    //
    // Create admin user
    //
    $admin_status = 'active';
    $admin_notes  = 'Created by the GamePanelX Pro installer';
    
    @mysql_query("INSERT INTO admins (date_added,status,notes,username,password,email_address,language) VALUES(NOW(),'$admin_status','$admin_notes','$post_admin_user',MD5('$post_admin_pass'),'$post_admin_email','$post_language')") or die('Failed to add to the admins table');
    
    ####################################################################

    //
    // Create network server
    //
    $net_available  = 'Y';
    $net_physical   = 'Y';

    @mysql_query("INSERT INTO network (available,physical,os,date_added,conn_user,conn_pass,conn_port,ip,location,datacenter,description) VALUES('$net_available','$net_physical','$post_os',NOW(),AES_ENCRYPT('$post_conn_user','$random_enc_key'),AES_ENCRYPT('$post_conn_pass','$random_enc_key'),AES_ENCRYPT('$post_conn_port','$random_enc_key'),'$post_ip','$post_location','$post_datacenter','$post_description')") or die('Failed to add to the network table');
    
    ####################################################################
    
    /*
     * OLD, using SQL file for the default insertions
     * 
    //
    // Add game/voice CFG support
    //
    $query_insert_cfg = "INSERT INTO `cfg` (`id`, `max_slots`, `port`, `date_added`, `last_updated`, `type`, `available`, `based_on`, `is_steam`, `is_punkbuster`, `notes`, `description`, `cmd_line`, `automagical`, `size`, `short_name`, `query_name`, `steam_name`, `long_name`, `mod_name`, `nickname`, `style`, `log_file`, `reserved_ports`, `tcp_ports`, `udp_ports`, `executable`, `map`, `setup_cmd`, `working_dir`, `setup_dir`, `config_file`, `pid_file`, `cfg_default`, `cfg_ip`, `cfg_port`, `cfg_max_slots`, `cfg_map`, `cfg_password`, `cfg_internet`) VALUES
(1, 64, 27015, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', 'Remake of Counter-Strike: 1.6 with the Source engine', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '3054600', 'cs_s', 'cssource', 'Counter-Strike Source', 'Counter-Strike: Source', 'cstrike', 'CS Source', 'FPS', '%working_dir%/', '27000,27020,27019', '1200,27000_27039', '1200,27000_27039', 'srcds_run', 'de_dust2', '', 'orangebox', '', 'server.cfg', '', '// server name\r\n\r\nhostname \"%hostname%\" \r\n\r\n\r\n\r\n// rcon passsword\r\n\r\nrcon_password \"%rconpassword%\" \r\n\r\n\r\n\r\n// Server password\r\n\r\nsv_password \"%privatepassword%\" \r\n\r\n\r\n\r\n// server cvars\r\n\r\nmp_friendlyfire 0 \r\n\r\nmp_footsteps 1 \r\n\r\nmp_autoteambalance 1 \r\n\r\nmp_autokick 0 \r\n\r\nmp_flashlight 0 \r\n\r\nmp_tkpunish 1 \r\n\r\nmp_forcecamera 0 \r\n\r\nsv_alltalk 0 \r\n\r\nsv_pausable 0 \r\n\r\nsv_cheats 0 \r\n\r\nsv_consistency 1 \r\n\r\nsv_allowupload 1 \r\n\r\nsv_allowdownload 1 \r\n\r\nsv_maxspeed 320 \r\n\r\nmp_limitteams 2 \r\n\r\nmp_hostagepenalty 5 \r\n\r\nsv_voiceenable 1 \r\n\r\nmp_allowspectators 1 \r\n\r\nmp_chattime 10 \r\n\r\nsv_timeout 65 \r\n\r\n\r\n\r\n// round specific cvars\r\n\r\nmp_freezetime 6 \r\n\r\nmp_roundtime 5 \r\n\r\nmp_startmoney 800 \r\n\r\nmp_c4timer 45 \r\n\r\nmp_fraglimit 0 \r\n\r\nmp_maxrounds 0 \r\n\r\nmp_winlimit 0 \r\n\r\nmp_playerid 0 \r\n\r\nmp_spawnprotectiontime 5 \r\n\r\n\r\n\r\n// bandwidth rates/settings\r\n\r\nsv_minrate 0 \r\n\r\nsv_maxrate 0 \r\n\r\ndecalfrequency 10 \r\n\r\nsv_maxupdaterate 60 \r\n\r\nsv_minupdaterate 10 \r\n\r\n\r\n\r\n// server logging\r\n\r\nlog off \r\n\r\nsv_logbans 0 \r\n\r\nsv_logecho 1 \r\n\r\nsv_logfile 1 \r\n\r\nsv_log_onefile 0 \r\n\r\n\r\n\r\n// operation\r\n\r\nsv_lan 0 \r\n\r\nsv_region 255 \r\n\r\n\r\n\r\n// execute ban files\r\n\r\nexec banned_user.cfg \r\n\r\nexec banned_ip.cfg ', '-ip', '-port', '+maxplayers', '+map', '+sv_password', '+sv_lan'),
(2, 32, 27015, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', 'Remake of Counter-Strike: 1.6 with bots and new maps', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '1104044', 'cs_cz', 'cs', 'czero', 'Counter-Strike: Condition Zero', 'czero', 'CZ', 'FPS', '%working_dir%/', '27000,27020,27019', '1200,27000_27039', '1200,27000_27039', 'hlds_run', 'de_dust2_cz', '', 'czero', 'czero', 'server.cfg', '', '// server name\r\n\r\nhostname \"%hostname%\" \r\n\r\n\r\n\r\n// rcon passsword\r\n\r\nrcon_password \"%rconpassword%\" \r\n\r\n\r\n\r\n// Server password\r\n\r\nsv_password \"%privatepassword%\" \r\n\r\n\r\n\r\n// server cvars\r\n\r\nmp_friendlyfire 0 \r\n\r\nmp_footsteps 1 \r\n\r\nmp_autoteambalance 1 \r\n\r\nmp_autokick 0 \r\n\r\nmp_flashlight 0 \r\n\r\nmp_tkpunish 1 \r\n\r\nmp_forcecamera 0 \r\n\r\nsv_alltalk 0 \r\n\r\nsv_pausable 0 \r\n\r\nsv_cheats 0 \r\n\r\nsv_consistency 1 \r\n\r\nsv_allowupload 1 \r\n\r\nsv_allowdownload 1 \r\n\r\nsv_maxspeed 320 \r\n\r\nmp_limitteams 2 \r\n\r\nmp_hostagepenalty 5 \r\n\r\nsv_voiceenable 1 \r\n\r\nmp_allowspectators 1 \r\n\r\nmp_chattime 10 \r\n\r\nsv_timeout 65 \r\n\r\n\r\n\r\n// round specific cvars\r\n\r\nmp_freezetime 6 \r\n\r\nmp_roundtime 5 \r\n\r\nmp_startmoney 800 \r\n\r\nmp_c4timer 45 \r\n\r\nmp_fraglimit 0 \r\n\r\nmp_maxrounds 0 \r\n\r\nmp_winlimit 0 \r\n\r\nmp_playerid 0 \r\n\r\nmp_spawnprotectiontime 5 \r\n\r\n\r\n\r\n// bandwidth rates/settings\r\n\r\nsv_minrate 0 \r\n\r\nsv_maxrate 0 \r\n\r\ndecalfrequency 10 \r\n\r\nsv_maxupdaterate 60 \r\n\r\nsv_minupdaterate 10 \r\n\r\n\r\n\r\n// server logging\r\n\r\nlog off \r\n\r\nsv_logbans 0 \r\n\r\nsv_logecho 1 \r\n\r\nsv_logfile 1 \r\n\r\nsv_log_onefile 0 \r\n\r\n\r\n\r\n// operation\r\n\r\nsv_lan 0 \r\n\r\nsv_region 255 \r\n\r\n\r\n\r\n// execute ban files\r\n\r\nexec banned_user.cfg \r\n\r\nexec banned_ip.cfg ', '+ip', '+port', '+maxplayers', '+map', '', ''),
(3, 8, 3784, '2010-05-20 19:56:51', '0000-00-00 00:00:00', 'voip', 'Y', 'cfg', 'N', 'N', '', 'Voice Communication Server', './%executable% -d', '', '', 'vent', 'ventrilo', '', 'Ventrilo', '', 'Vent', '', 'ventrilo_srv.log', '3784', '3784', '3784', 'ventrilo_srv', '', '', '', '', 'ventrilo_srv.ini', 'ventrilo_srv.pid', '', '', '', '', '', '', ''),
(4, 64, 16567, '2010-05-25 17:09:11', '0000-00-00 00:00:00', 'game', 'Y', 'cfg', 'N', 'Y', '', '', './%executable% ', '', '', 'bf_2', 'bf2', '', 'Battlefield 2', 'bf2', 'BF2', 'FPS', '', '16567', '16567', '16567', 'start.sh', 'strike_at_karkand', '', '', '', 'serversettings.con', '', '', 'sv.serverIP', 'sv.serverPort', 'sv.maxPlayers', '', 'sv.password', 'sv.internet'),
(5, 64, 28960, '2010-06-03 15:18:59', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'cod2', '', '', 'Call of Duty 2', '', '', 'FPS', 'games_mp.log', '', '28960', '20500,20510,28960', 'cod2_lnxded', 'mp_strike', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, 24, 28960, '2010-06-03 15:19:08', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'cod4', '', '', 'Call of Duty 4', '', '', 'FPS', 'heya', '20500,20510,28960', '28960', '20500,20510,28960', 'cod4_lnxded', 'mp_strike', '', 'cod4', 'pbsetup', '', '', '', '', '', '', '', '', ''),
(7, 32, 28960, '2010-06-03 15:19:15', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1% %opt5%', '', '', 'cod_waw', '', '', 'Call of Duty: World at War', '', '', 'FPS', '', '', '28960', '20500,20510,28960', 'codwaw_lnxded', 'mp_castle', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 64, 27015, '2010-06-03 15:19:23', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game cspromod -ip %ip% -port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', '', '', 'cs_pm', '', 'Counter-Strike Source', 'Counter-Strike: Pro Mod', 'cstrike', '', 'FPS', 'cspromod/logs', '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'srcds_run', 'csp_dust2', '', 'cspromod', '', '', '', '', '', '', '', '', '', ''),
(9, 32, 27015, '2010-06-03 15:19:30', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', 'cd %working_dir% ; ./%executable% -game dod -ip %ip% -port %port% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', '', '', 'dod_s', '', 'dods', 'Day of Defeat: Source', 'dods', '', 'FPS', 'orangebox/dod/logs', '27020,27040,27041', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'dod_anzio', '', 'orangebox', '', '', '', '', '', '', '', '', '', ''),
(10, 8, 27015, '2010-06-03 15:19:37', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game left4dead -ip %ip% -port %port% +map %default_map% +exec %opt1%', '', '', 'l4d', '', 'l4d_full', 'Left 4 Dead', 'left4dead', '', 'FPS', 'l4d/left4dead/logs', '27020,27039', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'l4d_airport01_greenhouse', '', 'l4d', '', '', '', '', '', '', '', '', '', ''),
(11, 8, 27015, '2010-06-03 15:19:43', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game left4dead2 -ip %ip% -port %port% +map %default_map% +exec %opt1%', '', '', 'l4d_2', '', 'left4dead2', 'Left 4 Dead 2', 'left4dead2', '', 'FPS', '%working_dir%/logs', '27015,27020,27040,27041,1200', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'c2m1_highway', '', 'l4d/left4dead2', '', '', '', '', '', '', '', '', '', ''),
(12, 16, 27960, '2010-06-03 15:19:48', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt3% +set sv_punkbuster %opt2% +map %default_map% +exec %opt1% +set sv_maxclients %max_players%', '', '', 'ws_et', '', '', 'Wolfenstein: Enemy Territory', '', '', 'FPS', '%working_dir%/etconsole.log', '27950,27952,27960,27965', '27950,27952,27960,27965', '27950,27952,27960,27965', 'etded', 'oasis', '', 'etmain', '', '', '', '', 'net_ip', 'net_port', 'sv_maxclients', 'map', '', 'dedicated'),
(13, 32, 27015, '2010-06-29 19:02:30', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game %mod_name% +ip %ip% +port %port% +map %map% +maxplayers %max_slots%', '', '710400', 'cs_16', 'cs', 'cstrike', 'Counter-Strike: 1.6', 'cstrike', 'CS 1.6', 'FPS', '', '27015', '27015', '27015', 'hlds_run', 'de_dust2', '', '', '', 'server.cfg', '', '', '+ip', '+port', '+maxplayers', '+map', '+sv_password', '+sv_lan'),
(14, 100, 64738, '2010-09-15 19:18:00', '0000-00-00 00:00:00', 'voip', 'Y', 'cfg', 'N', 'N', '', 'VOiP Server for the Mumble client', './%executable%', '', '', 'mrm', '', '', 'Murmur', '', '', '', 'murmur.log', '64738', '64738', '64738', 'murmur.x86', '', '', '', '', 'murmur.ini', 'murmur.pid', '', 'host=', 'port=', 'users=', '', 'serverpassword=', ''),
(15, 32, 27015, '2010-09-22 15:31:45', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '', 'tf2', 'source', 'tf', 'Team Fortress 2', 'tf', 'TF2', 'FPS', '', '27015', '27015,1200', '27015', 'srcds_run', 'cp_dustbowl', '', 'orangebox', '', 'server.cfg', '', '', 'ip', 'port', 'maxplayers', 'map', 'sv_password', 'sv_lan'),
(16, 32, 27015, '2010-09-23 21:52:16', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', 'The original Day of Defeat', './%executable% -game %mod_name% +ip %ip% +port %port% +maxplayers %max_slots% +map %default_map%', '', '', 'dod', 'dod', 'dod', 'Day of Defeat', 'dod', 'DOD', 'FPS', '', '27015', '27015', '27015', 'hlds_run', 'dod_avalanche', '', '', '', 'server.cfg', '', '', '+ip', '+port', '+maxplayers', '+map', '+sv_password', '+sv_lan')";

    // Insert the games
    @mysql_query($query_insert_cfg) or die('Failed to add to the cfg table');
    
    ####################################################################    
    
    //
    // Add the cfg addons support
    //
    $query_insert_cfg_addons = "INSERT INTO `cfg_configs` (`id`, `srvid`, `name`, `dir`, `description`, `rmcmd1`, `rmcmd2`, `rmcmd3`, `rmcmd4`, `rmcmd5`, `rmcmd6`, `rmcmd7`, `rmcmd8`, `rmcmd9`, `rmcmd10`) VALUES
(12, 1, 'mapcycle.txt', 'cstrike/', 'Map Cycle file', '', '', '', '', '', '', '', '', '', ''),
(2, 1, 'server.cfg', 'cstrike/cfg/', 'Main server config', 'ip', 'port', 'maxplayers', 'fps_max', 'tickrate', '', '', '', '', ''),
(5, 0, '', '', 'wefewf', '', '', '', '', '', '', '', '', '', ''),
(11, 1, 'maplist.txt', 'cstrike/', 'Map List file', '', '', '', '', '', '', '', '', '', ''),
(4, 3, 'server.cfg', 'cstrike/cfg/', '', 'ip', 'port', 'maxplayers', 'fps_max', 'tickrate', '', '', '', '', ''),
(13, 1, 'motd.txt', 'cstrike/', 'Message of the Day HTML file', '<script>', '', '', '', '', '', '', '', '', '');";

    @mysql_query($query_insert_cfg_addons) or die('Failed to add to the cfg_addons table');
    
    ####################################################################
    
    $query_insert_options = "INSERT INTO `cfg_options` (`id`, `srvid`, `opt1_name`, `opt1_edit`, `opt1_value`, `opt2_name`, `opt2_edit`, `opt2_value`, `opt3_name`, `opt3_edit`, `opt3_value`, `opt4_name`, `opt4_edit`, `opt4_value`, `opt5_name`, `opt5_edit`, `opt5_value`, `opt6_name`, `opt6_edit`, `opt6_value`, `opt7_name`, `opt7_edit`, `opt7_value`, `opt8_name`, `opt8_edit`, `opt8_value`, `opt9_name`, `opt9_edit`, `opt9_value`, `opt10_name`, `opt10_edit`, `opt10_value`) VALUES
(1, 1, 'Config File', 'N', 'server.cfg', 'FPS Max', 'N', '1000', '-autoupdate', 'Y', 'Y %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
(21, 27, 'Exec Config', '', 'server.cfg', '-autoupdate', '', 'N %switch%', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, 26, 'Exec Config', '', 'server.cfg', '-autoupdate', '', 'Y %switch%', 'Lan Server', '', '0', 'Tickrate', '', '66', 'FPS Max', '', '300', 'SourceTV', '', '0', 'SourceTV Port', '', '27020', '', '', '', '', '', '', '', '', ''),
(19, 25, 'Exec Config', '', 'server.cfg', '-autoupdate', '', 'N %switch%', 'Lan Server', '', '0', 'Tickrate', '', '66', 'FPS Max', '', '300', 'SourceTV', '', '0', 'SourceTV Port', '', '27020', '', '', '', '', '', '', '', '', ''),
(18, 24, 'Server Config', '', 'server.cfg', 'Dedicated', '', '2', 'Enable Punkbuster', '', '1', 'Pure Server', '', '1', 'map_rotate', '', '+map_rotate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 19, '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
(16, 22, 'Server Config', '', 'server.cfg', 'Dedicated', '', '2', 'Enable Punkbuster', '', '1', 'Pure Server', '', '1', '+map_rotate', '', 'N %switch%', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, 23, 'Server Config', '', 'server.cfg', 'Dedicated', '', '2', 'Enable Punkbuster', '', '1', 'Pure Server', '', '1', '+map_rotate', '', 'N %switch%', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(22, 28, 'Exec Config', '', 'server.cfg', '-autoupdate', '', 'N %switch%', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, 29, 'Exec Server Config', '', 'server.cfg', 'Enable Punkbuster', '', '1', 'Dedicated', '', '2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 14, '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '')";

    @mysql_query($query_insert_options) or die('Failed to add to the cfg_options table');
    
    ####################################################################
    
    //
    // Add rcon stuff
    //
    $query_insert_rcon  = "INSERT INTO `cfg_rcon` (`id`, `srvid`, `cmd`, `allow_client`) VALUES
(1, 1, 'kickid %playerid%', 'Y'),
(2, 1, 'banid %playerid%', 'Y')";

    @mysql_query($query_insert_rcon) or die('Failed to add to the cfg_rcon table');

    ####################################################################
    
    //
    // Ajax Notifications: Insert notify types
    //
    $query_insert_notify  = "INSERT INTO `notify_types` (`id`, `notify_type`) VALUES
(1, 'New Client'),
(2, 'New Server'),
(3, 'New Template'),
(4, 'Template Finished'),
(5, 'New Client Ticket'),
(6, 'Client Ticket Updated')";

    @mysql_query($query_insert_notify) or die('Failed to add to the notify_types table');

    ####################################################################
    */
    
    // Current Version
    $this_version = GPX_VERSION;
    
    //
    // Insert main configuration
    //
    $query_insert_config  = "INSERT INTO `configuration` (`setting`, `value`) VALUES
('CompanyName', 'GamePanelX'),
('Template', 'default'),
('Language', '$post_language'),
('PrimaryEmail', '$post_admin_email'),
('SecondaryEmail', ''),
('StartServerAfterCreate', 'Y'),
('DocRoot', '$post_install_dir'),
('DefaultSlotNum', '12'),
('RemoteServerTimeout', '6'),
('ServerQueryTimeout', '200'),
('BalanceServerLimit', '16'),
('BalanceLoadLimit', '4'),
('BalanceDefaultPortOnly', 'N'),
('EmailNewClients', 'Y'),
('Version', '$this_version')";

    @mysql_query($query_insert_config) or die('Failed to add to the configuration table');
    
    ####################################################################
    
    // Get the Network ID just inserted
    $result_netid = @mysql_query("SELECT id FROM network ORDER BY id DESC LIMIT 0,1");
    
    while($row_netid  = mysql_fetch_array($result_netid))
    {
        $this_netid = $row_netid['id'];
    }
    
    //
    // Update parentid to itself (for FTP server's query to work properly)
    //
    @mysql_query("UPDATE network SET parentid = '$this_netid' WHERE id = '$this_netid'");


    // Set doc root temporarily
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }
    
    // Attempt to get remote home directory
    include('../include/functions/remote.php');
    $remote_home  = @gpx_remote_get_home($this_netid);
    
    // Update dir
    if(!empty($remote_home))
    {
        // Strip newline, add /accounts
        $remote_home = trim($remote_home) . '/accounts/';
        
        if(!preg_match("/Unable\ to\ connect/i",$remote_home))
        {
            @mysql_query("UPDATE network SET accounts_dir = '$remote_home' WHERE id = '$this_netid'");
        }
    }
    
    ####################################################################

    // Display success
    require('success.php');
    exit;
}

?>
