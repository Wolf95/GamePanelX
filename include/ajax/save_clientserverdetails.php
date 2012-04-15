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

$this_userid  = $_SESSION['gpx_userid'];

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

########################################################################

// Escape/fix everything
$url_srvid          = mysql_real_escape_string($_GET['id']);
$url_ownerid        = mysql_real_escape_string($_GET['ownerid']);
$url_logging        = mysql_real_escape_string($_GET['logging']);
$url_status         = mysql_real_escape_string(htmlspecialchars($_GET['status']));
$url_description    = mysql_real_escape_string(htmlspecialchars($_GET['description']));
$url_subdomain      = mysql_real_escape_string(htmlspecialchars($_GET['subdomain']));
$url_domain         = mysql_real_escape_string(htmlspecialchars($_GET['domain']));
$url_networkid      = mysql_real_escape_string(htmlspecialchars($_GET['ip']));
$url_port           = mysql_real_escape_string(htmlspecialchars($_GET['port']));
$url_logfile        = mysql_real_escape_string(htmlspecialchars($_GET['logfile']));
$url_maxslots       = mysql_real_escape_string(htmlspecialchars($_GET['maxslots']));
$url_map            = mysql_real_escape_string(htmlspecialchars($_GET['map']));
$url_exe            = mysql_real_escape_string(htmlspecialchars($_GET['exe']));
$url_working_dir    = mysql_real_escape_string(htmlspecialchars($_GET['workingdir']));
$url_setup_dir      = mysql_real_escape_string(htmlspecialchars($_GET['setupdir']));
$url_rcon_pass      = mysql_real_escape_string($_GET['rcon']);
$url_cl_file_man    = mysql_real_escape_string($_GET['clfileman']);
$url_notes          = mysql_real_escape_string(htmlspecialchars($_GET['notes']));
$url_cmd_line       = mysql_real_escape_string(htmlspecialchars($_GET['cmd_line'])); // Edit raw cmd-line

// Admin update
if($is_admin)
{
    @mysql_query("UPDATE servers SET 
                      networkid       = '$url_networkid',
                      logging         = '$url_logging',
                      userid          = '$url_ownerid',
                      status          = '$url_status',
                      description     = '$url_description',
                      subdomain       = '$url_subdomain',
                      domainid        = '$url_domain',
                      port            = '$url_port',
                      log_file        = '$url_logfile',
                      max_slots       = '$url_maxslots',
                      map             = '$url_map',
                      executable      = '$url_exe',
                      working_dir     = '$url_working_dir',
                      setup_dir       = '$url_setup_dir',
                      rcon_password   = '$url_rcon_pass',
                      client_file_man = '$url_cl_file_man',
                      cmd_line        = '$url_cmd_line',
                      notes           = '$url_notes' 
                  WHERE 
                      id = '$url_srvid'") or die('Failed to update the settings');

    // Server Status Change (suspended etc)
    if($url_status == 'suspended' || $url_status == 'closed')
    {
        require(GPX_DOCROOT.'/include/functions/remote.php');
        
        // Run server stop.
        // Once stopped and db is set to suspended, they cannot login to the panel, and FTP will not allow login either, since they're marked as suspended.
        $result_stop = gpx_remote_server_stop($url_srvid,false);
        
        // Success
        if($result_stop != 'success')
        {
            echo 'Failed to stop server: ' . $result_stop;
            exit;
        }
    }
}
// Normal user update
else
{
    @mysql_query("UPDATE servers SET 
                      description     = '$url_description',
                      map             = '$url_map',
                      rcon_password   = '$url_rcon_pass' 
                  WHERE 
                      id = '$url_srvid'") or die('Failed to update the settings');

}

########################################################################

// Require logging if not suspending (conflicts)
if($url_status != 'suspended' && $url_status != 'closed')
{
    require(GPX_DOCROOT.'/include/class/log.php');
}

// Log this action (9: Update server details)
$Log = new Log;
$log_result = $Log->addlog('9',$this_userid,$url_srvid);

if($log_result == 'success') echo 'success';
else echo $log_result;

?>
