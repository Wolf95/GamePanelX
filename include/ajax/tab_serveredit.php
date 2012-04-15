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

// Check logged-in
if(!isset($_SESSION['gpx_username']))
{
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

// Current User
$this_userid  = $_SESSION['gpx_userid'];

########################################################################

if(empty($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

$url_id = mysql_real_escape_string($_GET['id']);

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

//
// Smarty Setup
//
require(GPX_DOCROOT.'/libs/Smarty.class.php');
$smarty = new Smarty;
$smarty->compile_dir = GPX_DOCROOT.'/templates_c/';

// Set user's language
require(GPX_DOCROOT.'/include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

// Set logged-in
$smarty->assign('logged_in', '1');

########################################################################

//
// Get Server Info
//
$server_query = "SELECT 
                    servers.id AS serverid,
                    servers.domainid,
                    DATE_FORMAT(servers.date_created, '%c/%e/%Y %H:%i') AS date_created,
                    servers.type,
                    servers.networkid,
                    servers.userid,
                    servers.logging,
                    servers.status,
                    servers.server,
                    servers.log_file,
                    servers.port,
                    servers.description,
                    servers.max_slots,
                    servers.map,
                    servers.executable,
                    servers.subdomain,
                    servers.cmd_line,
                    servers.working_dir,
                    servers.setup_dir,
                    servers.rcon_password,
                    servers.show_cmd_line,
                    servers.client_file_man,
                    servers.notes,
                    clients.username,
                    cfg.id AS cfgid,
                    cfg.short_name,
                    cfg.long_name,
                    cfg.nickname,
                    network.ip 
                 FROM servers 
                 LEFT JOIN clients ON 
                    servers.userid = clients.id 
                 LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$url_id' 
                 ORDER BY date_created DESC 
                 LIMIT 0,1";

$result = @mysql_query($server_query) or die('<center><b>Error:</b> Failed to get gameserver info</center>');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('server_details', $value);

// Server Type
$this_type = $value[0]['type'];
$smarty->assign('type', $this_type);

########################

// List of available Domain Names
$result_dns = @mysql_query("SELECT id,domain FROM domains WHERE is_enabled = 'Y' ORDER BY domain ASC") or die('Failed to get domain list');

while($row_dns  = mysql_fetch_assoc($result_dns))
{
    $arr_dns[]  = $row_dns;
}
$smarty->assign('domains', $arr_dns);

########################

/*
// List of available IP Addresses
$result_ip = @mysql_query("SELECT id,ip FROM network WHERE available='Y' ORDER BY physical,ip ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_ip = mysql_fetch_assoc($result_ip))
{
    $value_ip[] = $line_ip;
}

// Smarty mysql loop
$smarty->assign('avail_ips', $value_ip);
*/

// Get available Network IP Addresses
$result = @mysql_query("SELECT id,ip,location,description FROM network WHERE available = 'Y' ORDER BY ip ASC LIMIT 0,299") or die('Failed to get available IP Addresses');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('network_ips', $value);

########################

// Print a list of users for "Owner" option
$result_users = @mysql_query("SELECT id,username FROM clients ORDER BY id ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_users = mysql_fetch_assoc($result_users))
{
    $value_users[] = $line_users;
}

// Smarty mysql loop
$smarty->assign('user_list', $value_users);

########################

/*
*
* Using smarty for display; keep these just incase 
*
// Get the values
while($row = mysql_fetch_array($result))
{
    $srv_id           = $row['id'];
    $srv_userid       = $row['userid'];
    $srv_type         = $row['type'];
    $srv_status       = $row['status'];
    $srv_name         = $row['server'];
    $srv_ip           = $row['ip'];
    $srv_port         = $row['port'];
    $srv_descr        = $row['description'];
    $srv_subdom       = $row['subdomain'];
    $srv_notes        = $row['notes'];
    $srv_username     = $row['username'];
    $srv_short_name   = $row['short_name'];
    $srv_long_name    = $row['long_name'];
    $srv_is_steam     = $row['is_steam'];
    $srv_domain       = $row['domainid'];
    $srv_date_added   = $row['date_created'];
    $srv_logfile      = $row['log_file'];
    $srv_max_slots    = $row['max_slots'];
    $srv_map          = $row['map'];
    $srv_exe          = $row['executable'];
    $srv_subdom       = $row['subdomain'];
    $srv_cmd_line     = $row['cmd_line'];
    $srv_work_dir     = $row['working_dir'];
    $srv_setup_dir    = $row['setup_dir'];
    $srv_show_cmd     = $row['show_cmd_line'];
    $srv_cl_file_man  = $row['client_file_man'];
    $srv_cfg_id       = $row['cfgid'];
    $srv_cfg_long_nm  = $row['long_name'];
    $srv_cfg_nick     = $row['nickname'];
    //$srv_cfg_opts     = 
}
*/

########################################################################

// Display HTML Page
if($is_admin) $smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/editclientserver.tpl');
else $smarty->display(GPX_DOCROOT.'/templates/' . $config['Template'] . '/editclientserver.tpl');

?>
