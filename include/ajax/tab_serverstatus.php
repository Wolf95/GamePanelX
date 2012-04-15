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

if(empty($_GET['id']) || !is_numeric($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

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
$smarty->assign('template', 'default');

// Set logged-in
$smarty->assign('logged_in', '1');


########################################################################

$server_id  = mysql_real_escape_string($_GET['id']);

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$server_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

require(GPX_DOCROOT.'/include/functions/query.php');
$status_info  = array();

// Server ID
$smarty->assign('srvid', $server_id);


########################################################################

// Swap GPX game name for GameQ game name
$result_name  = @mysql_query("SELECT 
                                  cfg.is_steam,
                                  cfg.query_name,
                                  cfg.short_name,
                                  servers.logging,
                                  servers.type,
                                  servers.port,
                                  servers.rcon_password,
                                  network.ip 
                               FROM cfg 
                               LEFT JOIN servers ON 
                                  cfg.short_name = servers.server 
                               LEFT JOIN network ON 
                                  servers.networkid = network.id 
                               WHERE 
                                  servers.id = '$server_id' 
                               LIMIT 0,1") or die('Failed to query: '.mysql_error());

while($row_name = mysql_fetch_array($result_name))
{
    $status_info[0]['server'] = $row_name['query_name'];
    $status_info[0]['ip']     = $row_name['ip'];
    $status_info[0]['port']   = $row_name['port'];
    $is_steam                 = $row_name['is_steam'];
    $short_name               = $row_name['short_name'];
    $server_rcon              = $row_name['rcon_password'];
    $server_type              = $row_name['type'];
    $server_logging           = $row_name['logging'];
}

// Assign server type
$smarty->assign('logging', $server_logging);
$smarty->assign('type', $server_type);



// Check update status
$result_up  = @mysql_query("SELECT update_status FROM servers WHERE id = '$server_id'");
$row_up     = mysql_fetch_row($result_up);
$upd_status = $row_up[0];
$smarty->assign('update_status', $upd_status);

if($upd_status == 'running')
{
    // Check remote server for status
    require(GPX_DOCROOT.'/include/functions/remote.php');
    
    $result_upd = gpx_remote_status_steam_update($server_id);
    
    // Still beginning, means 'Updating Installation'
    if($result_upd == 'Updating')
    {
        $result_upd = '<font color="blue">Starting Update ...</font>';
    }
    elseif(preg_match("/HLDS/", $result_upd))
    {
        // Update database
        @mysql_query("UPDATE servers SET update_status = 'complete' WHERE id = '$server_id'") or die('Failed to set update status!');
        $result_upd = '<font color="green">Complete!</font>';
    }
    else $result_upd = '<font color="blue">' . $result_upd . '</font>';
    $smarty->assign('steam_update', $result_upd);
}



// We have a GameQ server name
if(!empty($status_info[0]['server']))
{
    // Run GameQ query on server
    $result_array = gpx_query($status_info);
    
    /*
    $this_status  = trim($result_array[0]['current_status']);
    $srv_cur_players  = trim($result_array[0]['current_numplayers']);
    $srv_cur_maxplyrs = trim($result_array[0]['current_maxplayers']);
    
    // Return numplayers, maxplayers and online status in JSON format
    $json_rsp = '{"players":"' . $srv_cur_players . '","maxslots":"' . $srv_cur_maxplyrs . '","status":"' . $this_status . '"}';    
    echo $json_rsp;
    */
    
    // Assign Smarty values
    $smarty->assign('cur_status', $result_array[0]['current_status']);
    $smarty->assign('cur_hostname', $result_array[0]['current_hostname']);
    $smarty->assign('cur_map', $result_array[0]['current_mapname']);
    $smarty->assign('cur_numplayers', $result_array[0]['current_numplayers']);
    $smarty->assign('cur_maxplayers', $result_array[0]['current_maxplayers']);
    $smarty->assign('cur_mod', $result_array[0]['current_mod']);
    $smarty->assign('cur_os', $result_array[0]['current_os']);
    $smarty->assign('cur_has_pass', $result_array[0]['current_has_pw']);
    $smarty->assign('cur_protocol', $result_array[0]['current_protocol']);
    $smarty->assign('srv_name', $short_name);
    $smarty->assign('is_steam', $is_steam);
    
    /*
    echo '<pre>';
    var_dump($result_array);
    echo '</pre>';
    */
    
    /*
    [2]=>
      array(7) {
        ["id"]=>
        int(0)
        ["name"]=>
        string(8) "Gnarkill"
        ["score"]=>
        int(2)
        ["time"]=>
        float(1062.3814697266)
        ["gq_name"]=>
        string(8) "Gnarkill"
        ["gq_score"]=>
        int(2)
        ["gq_ping"]=>
        bool(false)
        */
        
    // Assign current players array
    $smarty->assign('cur_players', $result_array[0]['current_players']);
}
else
{
    die('No query method for this server type');
}

/*
// No GameQ name; run generic TCP query
else
{
    // Run a generic query
    if(gpx_query_generic($value[$i]['ip'],$value[$i]['port']))
    {
        // $result_array[$i]['current_status'] = 'online';
        echo 'online';
    }
    else
    {
        // $result_array[$i]['current_status'] = 'offline';
        echo 'offline';
    }
}
*/

########################################################################

// Display HTML Page
if($is_admin) $smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/serverstatus.tpl');
else $smarty->display(GPX_DOCROOT.'/templates/' . $config['Template'] . '/serverstatus.tpl');

?>
