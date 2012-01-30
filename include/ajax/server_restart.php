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
// Restart game/voice server
//

if(empty($_GET['id']) || !is_numeric($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

$server_id  = mysql_real_escape_string($_GET['id']);
require(GPX_DOCROOT.'/include/functions/remote.php');

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$server_id' AND userid = '$this_userid' AND status = 'active'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

// Run restart
$result = gpx_remote_server_restart($server_id,false);


// Success
if(preg_match("/^success/", $result))
{
    echo 'success';
}
// Failure
else
{
    echo $result;
}

?>
