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

if($is_admin) $smarty->compile_dir = GPX_DOCROOT.'/admin/templates_c/';
else $smarty->compile_dir = GPX_DOCROOT.'/templates_c/';

// Set user's language
require(GPX_DOCROOT.'/include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

// Set logged-in
$smarty->assign('logged_in', '1');

// Assign Smarty serverid
$smarty->assign('srvid', $url_id);

########################################################################

// Get current server name (CS Source, etc)
$query_srv  = "SELECT 
                  cfg.id,
                  cfg.max_slots 
               FROM servers 
               LEFT JOIN cfg ON 
                  servers.server = cfg.short_name 
               WHERE 
                  servers.id = '$url_id'";

$result_srv   = @mysql_query($query_srv);
$row_srv      = mysql_fetch_row($result_srv);
$this_cfg_id  = $row_srv[0];
$cfg_maxslots = $row_srv[1];

// Smarty Max slots
$smarty->assign('maxslots', $cfg_maxslots);

########################################################################

if($is_admin)
{
    // Get "simple" config items
    $query  = "SELECT 
                  servers_cfg.item_value,
                  cfg_items.id,
                  cfg_items.simpleid,
                  cfg_items.name,
                  cfg_items.description 
               FROM servers_cfg 
               LEFT JOIN cfg_items ON 
                  servers_cfg.itemid = cfg_items.id 
               WHERE 
                  servers_cfg.srvid = '$url_id' 
                  AND cfg_items.simpleid > 0 
                  AND servers_cfg.deleted = '0'";

    $result = @mysql_query($query) or die('Failed to get current smp config items');

    // Get the values
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('cfg_simple', $value);
    $query  = '';
    $value  = '';
    $line   = '';
    $result = '';
}

########################################################################

// Only show client-editable to clients
if(!$is_admin) $sql_add  = 'AND servers_cfg.client_edit = \'1\'';
else $sql_add = '';

// Get this all server's current config items
$server_query = "SELECT 
                    servers_cfg.item_value,
                    servers_cfg.item_order,
                    servers_cfg.client_edit,
                    cfg_items.id,
                    cfg_items.simpleid,
                    cfg_items.required,
                    cfg_items.name,
                    cfg_items.description 
                 FROM servers_cfg 
                 LEFT JOIN cfg_items ON 
                    servers_cfg.itemid = cfg_items.id 
                 WHERE 
                    servers_cfg.srvid = '$url_id' 
                    AND servers_cfg.deleted = '0' 
                    AND cfg_items.deleted = '0' 
                    $sql_add 
                 ORDER BY 
                    servers_cfg.item_order ASC";

$result = @mysql_query($server_query) or die('Failed to get current adv config items');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('cfg_adv', $value);
$value  = '';
$line   = '';
$result = '';

########################################################################

// Get all 4 "Simple" values from `servers`
$query_smp  = "SELECT 
                  servers.networkid,
                  servers.port,
                  servers.max_slots,
                  servers.map,
                  network.ip 
               FROM servers 
               LEFT JOIN network ON 
                  servers.networkid = network.id 
               WHERE 
                  servers.id = '$url_id'";

$result_smp = @mysql_query($query_smp);
$row_smp    = mysql_fetch_row($result_smp);

// Assign all 4 to Smarty
$smarty->assign('srv_networkid', $row_smp[0]);
$smarty->assign('srv_port', $row_smp[1]);
$smarty->assign('srv_maxslots', $row_smp[2]);
$smarty->assign('srv_map', $row_smp[3]);
$smarty->assign('srv_ip', $row_smp[4]);

########################################################################

if($is_admin)
{
    // Get all available config items for this server
    $server_query = "SELECT 
                        cfg_items.id,
                        cfg_items.usr_def,
                        cfg_items.simpleid,
                        cfg_items.required,
                        cfg_items.name,
                        cfg_items.default_value,
                        cfg_items.description 
                     FROM cfg_items 
                     WHERE 
                        cfg_items.srvid = '$this_cfg_id' 
                        AND cfg_items.deleted = '0' 
                        
                        AND cfg_items.id NOT IN ( 
                          SELECT 
                              itemid AS id 
                           FROM servers_cfg 
                           WHERE 
                              srvid = '$url_id' 
                              AND deleted = '0' 
                           ORDER BY 
                              item_order ASC 
                        )";

    $result = @mysql_query($server_query) or die('Failed to get available config items');

    // Get the values
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('cfg_avail', $value);
    $value  = '';
    $line   = '';
    $result = '';
}

########################################################################

// Get available Network IP Addresses
$result = @mysql_query("SELECT id,ip,location,description FROM network WHERE available = 'Y' ORDER BY ip ASC LIMIT 0,299") or die('Failed to get available config items');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('network_ips', $value);

########################################################################

// Display HTML Page
if($is_admin) $smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/editcmdline.tpl');
else $smarty->display(GPX_DOCROOT.'/templates/' . $config['Template'] . '/editcmdline.tpl');


?>
