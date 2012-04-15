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
                      'delete_file'
                  );


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

?>
