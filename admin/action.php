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

require('../include/db.php');

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>action.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>action.php</i>: Failed to select the database!</center>');

########################################################################

session_start();
if(!isset($_SESSION['gpx_userid']))
{
    die('<b>Error:</b> You are not authorized to view this page');
}


//
// URL Values
//
$url_action = mysql_real_escape_string($_GET['a']);
$url_id     = mysql_real_escape_string($_GET['id']);

if(empty($url_action))
{
    die('<center><b>Error:</b> You must specify an action!</center>');
}

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}


########################################################################

//
// Get script path
//
$result_configs = @mysql_query("SELECT Value FROM configuration WHERE Setting = 'DocRoot'") or die ($query_error);

while($row_configs = mysql_fetch_array($result_configs))
{
    $doc_root = $row_configs['Value'];
}

// Define the constant
define('GPX_DOCROOT', $doc_root);

########################################################################

// Remote Server functions
require('../include/functions/remote.php');


// Find out if this is a Game or Voice server (for the redirect at the end)
$result_type = @mysql_query("SELECT type FROM servers WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>action.php</i>: Failed to get server type!</center>');

while($row_type = mysql_fetch_array($result_type))
{
    $server_type = $row_type['type'];
}


//
// Restart Server
//
if($url_action == 'restart')
{
    if(!gpx_remote_server_restart($url_id))
    {
        die('<center><b>Error:</b> <i>action.php:</i> Failed to restart the server!</center>');
    }
    else
    {
        // Forward to success message
        header("Location: servers.php?type=$server_type&info=restarted");
        exit;
    }
}



//
// Stop Server
//
elseif($url_action == 'stop')
{
    if(!gpx_remote_server_stop($url_id))
    {
        die('<center><b>Error:</b> <i>action.php:</i> Failed to stop the server!</center>');
    }
    else
    {
        // Forward to success message
        header("Location: servers.php?type=$server_type&info=stopped");
        exit;
    }
}

?>
