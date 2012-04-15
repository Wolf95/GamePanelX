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

########################################################################

// Check logged-in
session_start();
if(!isset($_SESSION['gpx_username']))// || !isset($_SESSION['gpx_isadmin']) || $_SESSION['gpx_isadmin'] != 1)
{
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

########################################################################

//
// Ajax Include File
//
// This file to be included wherever Ajax requests are made for database queries
//

if(!empty($_GET['a']))
{
    $url_action = $_GET['a'];
    $url_type   = $_GET['type'];
}
elseif(isset($_POST['submit']) && !empty($_GET['a']))
{
    $url_action = $_POST['a'];
}


// Default to game
if(empty($url_type))
{
    $url_type = 'game';
}

// All allowed request types go here
$allowed_reqs = array('createserver_cmdline',
                      'templates_available',
                      'refresh_gamestatus',
                      'check_status',
                      'bar_check_notify',
                      'bar_get_notify',
                      'bar_online_clients',
                      'bar_get_clients',
                      'tab_serverinfo',
                      'tab_serverstatus',
                      'tab_serveredit',
                      'tab_serverfiles',
                      'tab_serverstartup',
                      'tab_serveraddons',
                      'server_restart',
                      'server_stop',
                      'query_server',
                      'save_clientserverdetails',
                      'save_cmd_smp',
                      'save_cmd_adv',
                      'save_cmd_adv_supp',
                      'del_cmd_item',
                      'del_cmd_item_supp',
                      'cmd_add_cur',
                      'file_list',
                      'net_file_list',
                      'dir_add',
                      'file_save',
                      'rcon_server',
                      'rcon_status',
                      'rcon_kick',
                      'archive_start',
                      'archive_save',
                      'archive_delete',
                      'archive_status',
                      'install_supported_server',
                      'createsrv_archive_list',
                      'createsrv_getoptions',
                      'createsrv_check_net',
                      'createsrv_start',
                      'template_info',
                      'steam_update',
                      'view_srv_log');


########################################################################


// Check the URL out
if(!empty($url_action) && !in_array($url_action, $allowed_reqs))
{
    die('Ajax: Invalid action');
}

require('config.php');

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Couldnt connect to the db');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Couldnt select the db');

########################################################################

// Use the correct PHP file for the ajax call
$ajax_file  = GPX_DOCROOT . '/include/ajax/' . $url_action . '.php';

if(file_exists($ajax_file))
{
    require($ajax_file);
}

/*
 * Removing unnecessary conditionals; if it's not allowed, the array check will catch it.
 * Removed on v1.0.13
 * 
// createserver.php
if($url_action == 'createserver_cmdline')     require(GPX_DOCROOT.'/include/ajax/createserver_cmdline.php');

// createserver.php: Get available templates for this Network ID
elseif($url_action == 'templates_available')  require(GPX_DOCROOT.'/include/ajax/templates_available.php');

// Gameserver Status refresh
elseif($url_action == 'refresh_gamestatus')   require(GPX_DOCROOT.'/include/ajax/refresh_gamestatus.php');

// Check server status
elseif($url_action == 'check_status')         require(GPX_DOCROOT.'/include/ajax/check_status.php');

// Check for new notifications
elseif($url_action == 'bar_check_notify')     require(GPX_DOCROOT.'/include/ajax/bar_check_notify.php');

// Get new notifications
elseif($url_action == 'bar_get_notify')       require(GPX_DOCROOT.'/include/ajax/bar_get_notify.php');

// Get count of Online Clients
elseif($url_action == 'bar_online_clients')   require(GPX_DOCROOT.'/include/ajax/bar_online_clients.php');

// Get currently online clients' names etc
elseif($url_action == 'bar_get_clients')      require(GPX_DOCROOT.'/include/ajax/bar_get_clients');

########################################################################


//
// Tabs
//
elseif($url_action == 'tab_serverinfo')       require(GPX_DOCROOT . '/include/ajax/tab_serverinfo.php');
elseif($url_action == 'tab_serveredit')       require(GPX_DOCROOT . '/include/ajax/tab_serveredit.php');
elseif($url_action == 'tab_serverfiles')      require(GPX_DOCROOT . '/include/ajax/tab_serverfiles.php');
elseif($url_action == 'tab_serverstartup')    require(GPX_DOCROOT . '/include/ajax/tab_serverstartup.php');
elseif($url_action == 'tab_serveraddons')     require(GPX_DOCROOT . '/include/ajax/tab_serveraddons.php');
elseif($url_action == 'tab_serverstatus')     require(GPX_DOCROOT . '/include/ajax/tab_serverstatus.php');

########################################################################

//
// Server Actions (restart/stop)
//
elseif($url_action == 'server_restart')
{
    require(GPX_DOCROOT . '/include/ajax/server_restart.php');
}
elseif($url_action == 'server_stop')
{
    require(GPX_DOCROOT . '/include/ajax/server_stop.php');
}


//
// Server Status (online/offline)
//
elseif($url_action == 'query_server')
{
    require(GPX_DOCROOT . '/include/ajax/query_server.php');
}

// Save server editing
elseif($url_action == 'save_clientserverdetails')
{
    require(GPX_DOCROOT . '/include/ajax/save_clientserverdetails.php');
}

// Save CMD Line params (simple)
elseif($url_action == 'save_cmd_smp')
{
    require(GPX_DOCROOT . '/include/ajax/save_cmd_smp.php');
}
// Save CMD Line params (advanced)
elseif($url_action == 'save_cmd_adv')
{
    require(GPX_DOCROOT . '/include/ajax/save_cmd_adv.php');
}
// Save CMD Line params (advanced) (Supported Servers)
elseif($url_action == 'save_cmd_adv_supp')
{
    require(GPX_DOCROOT . '/include/ajax/save_cmd_adv_supp.php');
}


// Delete CMD-Line param
elseif($url_action == 'del_cmd_item')
{
    require(GPX_DOCROOT . '/include/ajax/del_cmd_item.php');
}
// Delete CMD-Line param (Supported Servers)
elseif($url_action == 'del_cmd_item_supp')
{
    require(GPX_DOCROOT . '/include/ajax/del_cmd_item_supp.php');
}

// Startup cmd: Add an unused config item to a servers items
elseif($url_action == 'cmd_add_cur')
{
    require(GPX_DOCROOT . '/include/ajax/cmd_add_cur.php');
}

// File Manager: Remote file listing
elseif($url_action == 'file_list')
{
    require(GPX_DOCROOT . '/include/ajax/file_list.php');
}
// File Manager: Remote Network Server file listing
elseif($url_action == 'net_file_list')
{
    require(GPX_DOCROOT . '/include/ajax/net_file_list.php');
}

// File Manager: Create new Directory
elseif($url_action == 'dir_add')
{
    require(GPX_DOCROOT . '/include/ajax/dir_add.php');
}

// File Manager: Create new Directory
elseif($url_action == 'file_save')
{
    require(GPX_DOCROOT . '/include/ajax/file_save.php');
}

// Rcon Commands
elseif($url_action == 'rcon_server')
{
    require(GPX_DOCROOT . '/include/ajax/rcon_server.php');
}
// Rcon `status` - for Source servers
elseif($url_action == 'rcon_status')
{
    require(GPX_DOCROOT . '/include/ajax/rcon_status.php');
}

// Rcon kick player(s)
elseif($url_action == 'rcon_kick')
{
    require(GPX_DOCROOT . '/include/ajax/rcon_kick.php');
}

// Start Template/Archive creation
elseif($url_action == 'archive_start')
{
    require(GPX_DOCROOT . '/include/ajax/archive_start.php');
}

// Save archive details (edit)
elseif($url_action == 'archive_save')
{
    require(GPX_DOCROOT . '/include/ajax/archive_save.php');
}

// Delete Archive
elseif($url_action == 'archive_delete')
{
    require(GPX_DOCROOT . '/include/ajax/archive_delete.php');
}

// Get Archive Status (running or completed)
elseif($url_action == 'archive_status')
{
    require(GPX_DOCROOT . '/include/ajax/archive_status.php');
}

// Install a Supported Game/Voice server type
elseif($url_action == 'install_supported_server')
{
    require(GPX_DOCROOT . '/include/ajax/install_supported_server.php');
}

// Create Server: Show list of games with archives
elseif($url_action == 'createsrv_archive_list')
{
    require(GPX_DOCROOT . '/include/ajax/createsrv_archive_list.php');
} 
// Create Server: Get all game options
elseif($url_action == 'createsrv_getoptions')
{
    require(GPX_DOCROOT . '/include/ajax/createsrv_getoptions.php');
}

// Create Server: Check if IP/Port are in use
elseif($url_action == 'createsrv_check_net')
{
    require(GPX_DOCROOT . '/include/ajax/createsrv_check_net.php');
}
// Create Server: Begin creation process
elseif($url_action == 'createsrv_start')
{
    require(GPX_DOCROOT . '/include/ajax/createsrv_start.php');
}

// Template info
elseif($url_action == 'template_info') require(GPX_DOCROOT.'/include/ajax/template_info.php');
*/

?>
