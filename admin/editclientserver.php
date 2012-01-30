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

//
// Smarty
//
require '../libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = '../admin/templates_c/';

// Required Files
require('../include/auth.php');
require('../include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Edit Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editclientserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editclientserver.php</i>: Failed to select the database!</center>');


########################################################################


// URL variables
$url_id = mysql_real_escape_string($_GET['id']);

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}


########################################################################



//
// Get Server Info
//
$server_query = "SELECT 
                    servers.id AS serverid,
                    servers.domainid,
                    DATE_FORMAT(servers.date_created, '%c/%e/%Y %H:%i') AS date_created,
                    servers.type,
                    servers.userid,
                    servers.status,
                    servers.server,
                    servers.log_file,
                    servers.ip,
                    servers.port,
                    servers.description,
                    servers.max_slots,
                    servers.map,
                    servers.executable,
                    servers.subdomain,
                    servers.cmd_line,
                    servers.working_dir,
                    servers.setup_dir,
                    servers.show_cmd_line,
                    servers.client_file_man,
                    servers.notes,
                    clients.username,
                    cfg.id AS cfgid,
                    cfg.short_name,
                    cfg.long_name,
                    cfg.nickname,
                    cfg_options.* 
                 FROM servers 
                 LEFT JOIN clients ON 
                    servers.userid = clients.id 
                 LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                 LEFT JOIN cfg_options ON 
                    cfg.id = cfg_options.srvid 
                 WHERE 
                    servers.id = '$url_id'";

$result = @mysql_query($server_query) or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get game server information!</center>');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('server_details', $value);

// Server Type
$this_type = $value[0][type];
$smarty->assign('type', $this_type);


########################################################################

// List of available Domain Names
$result_dns = @mysql_query("SELECT id,domain FROM domains WHERE is_enabled = 'Y' ORDER BY domain ASC");

while($row_dns  = mysql_fetch_assoc($result_dns))
{
    $arr_dns[]  = $row_dns;
}
$smarty->assign('domains', $arr_dns);

########################################################################
// List of available IP Addresses
$result_ip = @mysql_query("SELECT ip FROM network WHERE available='Y' ORDER BY physical,ip ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_ip = mysql_fetch_assoc($result_ip))
{
    $value_ip[] = $line_ip;
}

// Smarty mysql loop
$smarty->assign('avail_ips', $value_ip);

########################################################################


// Print a list of users for "Owner" option
$result_users = @mysql_query("SELECT id,username FROM clients ORDER BY id ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_users = mysql_fetch_assoc($result_users))
{
    $value_users[] = $line_users;
}

// Smarty mysql loop
$smarty->assign('user_list', $value_users);


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################



// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update success
        if($url_info == 'updated')
        {
            $info_msg = 'Successfully updated the server!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}


########################################################################


//
// POST Update Stuff
//
if(isset($_POST['update']))
{
    // POST Values
    $post_id          = $_POST['serverid'];
    $post_userid      = $_POST['userid'];
    $post_status      = $_POST['status'];
    $post_desc        = $_POST['description'];
    $post_ip          = $_POST['ip'];
    $post_port        = $_POST['port'];
    $post_log_file    = $_POST['log_file'];
    $post_max_slots   = $_POST['max_slots'];
    $post_map         = $_POST['map'];
    $post_exe         = $_POST['executable'];
    $post_work_dir    = $_POST['working_dir'];
    $post_setup_dir   = $_POST['setup_dir'];
    $post_file_man    = $_POST['client_file_man'];
    $post_notes       = $_POST['notes'];
    $post_subdomain   = $_POST['subdomain'];
    $post_domainid    = $_POST['domainid'];
    
    
    // Update the server settings
    require('../include/functions/servers.php');
    gpx_update_server($post_id,$post_userid,$post_status,$post_desc,$post_ip,$post_port,$post_log_file,$post_max_slots,$post_map,$post_exe,$post_work_dir,$post_setup_dir,$post_file_man,$post_subdomain,$post_domainid,$post_notes);


    // Send the user back with info
    header("Location: editclientserver.php?id=$post_id&info=updated");
    exit;
}



########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/editclientserver.tpl'); 
