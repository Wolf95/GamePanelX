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

//
// Query game/voice server for online/offline status
//

if(empty($_GET['id']) || !is_numeric($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

$server_id  = mysql_real_escape_string($_GET['id']);
require(GPX_DOCROOT.'/include/functions/query.php');
$status_info  = array();

########################################################################

// Swap GPX game name for GameQ game name
$query_name   = "SELECT 
                    cfg.query_name,
                    servers.port,
                    network.ip 
                 FROM cfg 
                 LEFT JOIN servers ON 
                    cfg.short_name = servers.server 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$server_id' 
                 LIMIT 0,1";

$result_name  = @mysql_query($query_name);

while($row_name = mysql_fetch_array($result_name))
{
    $status_info[0]['server'] = $row_name['query_name'];
    $status_info[0]['ip']     = $row_name['ip'];
    $status_info[0]['port']   = $row_name['port'];
}

// We have a GameQ server name
if(!empty($status_info[0]['server']))
{
    // Run GameQ query on server
    $result_array = gpx_query($status_info);
    $this_status  = trim($result_array[0]['current_status']);
    $srv_cur_players  = trim($result_array[0]['current_numplayers']);
    $srv_cur_maxplyrs = trim($result_array[0]['current_maxplayers']);
    
    // Return numplayers, maxplayers and online status in JSON format
    $json_rsp = '{"players":"' . $srv_cur_players . '","maxslots":"' . $srv_cur_maxplyrs . '","status":"' . $this_status . '"}';    
    echo $json_rsp;
    
    
    /*
    echo '<pre>';
    var_dump($result_array);
    echo '</pre>';
    */
}
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

?>