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

// Configuration
require('../include/db.php');

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to select the database!</center>');

########################################################################

/*
*
* AVAILABLE POST VALUES:
*
$arr_details['first_name']);
$arr_details['middle_name']);
$arr_details['last_name']);
$arr_details['company']);
$arr_details['phone']);
$arr_details['email']);
$arr_details['address1']);
$arr_details['address2']);
$arr_details['city']);
$arr_details['state']);
$arr_details['zip']);
$arr_details['country']);
$arr_details['external_pid']);
$arr_details['cp_username']);
$arr_details['cp_password']);

$arr_details['server']);
$arr_details['rcon_password']);
$arr_details['private_password']);
$arr_details['player_slots']);
$arr_details['game_private']);

$arr_details['ext_clientid'] // External billing unique client id
$arr_details['ext_serverid'] // External billing unique server id
*/

########################################################################

//
// Accept POST requests
//
$post_action    = $_POST['action'];
$post_api_key   = $_POST['api_key'];

// Check API key
if($post_api_key != $config['api_key'])
{
    die('ERROR: Invalid API Key specified.');
}

########################################################################


//
// Create Account
//
if($post_action == 'addclient')
{
    require('clients.php');
    $Clients  = new Clients;

    // Create client account
    $client_result  = $Clients->create($_POST);
    
    if($client_result == 'success')
    {
        echo 'success';
    }
    else
    {
        echo $client_result;
    }
    
    // Exit
    exit;
}



//
// Suspend Server (Turn off server and suspend it)
//
elseif($post_action == 'suspendserver')
{
    require('servers.php');
    $Servers  = new Servers;
    $result_suspend = $Servers->suspend($_POST['ext_serverid']);
    
    if($result_suspend == 'success')
    {
        echo 'success';
    }
    else
    {
        echo $result_suspend;
    }
}



//
// Un-Suspend Server (Turn server back on and unsuspend it)
//
elseif($post_action == 'unsuspendserver')
{
    require('servers.php');
    $Servers  = new Servers;
    $result_unsuspend = $Servers->unsuspend($_POST['ext_serverid']);
    
    if($result_unsuspend == 'success')
    {
        echo 'success';
    }
    else
    {
        echo $result_unsuspend;
    }
}




//
// Terminate Server (Turn server back on and unsuspend it)
//
elseif($post_action == 'terminateserver')
{
    require('servers.php');
    $Servers  = new Servers;
    $result_terminate = $Servers->terminate($_POST['ext_serverid']);
    
    if($result_terminate == 'success')
    {
        echo 'success';
    }
    else
    {
        echo $result_terminate;
    }
}



########################################################################


else
{
    die('ERROR: Unknown action given.');
}
?>
