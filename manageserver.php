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
require 'libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = 'templates_c/';

// Required Files
require('include/auth.php');
require('include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Manage Server');


########################################################################

// URL variables
$url_action = $_GET['action'];
$url_id     = $_GET['id'];

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// List of allowed actions
$allowed_actions = array('delete');

// Correct action
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>manageserver.php:</i> Invalid URL Parameters!</center>');
}


########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>manageserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>manageserver.php</i>: Failed to select the database!</center>');

// URL Server ID
$url_id = mysql_real_escape_string($url_id);

########################################################################


//
// URL Actions
//
if(!empty($url_action) && !empty($url_id))
{
    // Delete user
    if($url_action == 'delete')
    {
        // Get server type
        $result_type = @mysql_query("SELECT type FROM servers WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get server type!</center>');
        
        while($row_type = mysql_fetch_array($result_type))
        {
            $this_server_type = $row_type['type'];
        }
        
        ################################################################
        
        
        // Delete account from 'gameservers' table
        require('include/functions/servers.php');
        
        if(!gpx_delete_server($url_id))
        {
            die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to delete the server!</center>');
        }
        else
        {
            // Send to success page
            header("Location: servers.php?type=$this_server_type&info=deleted");
            exit;
        }
    }
}


########################################################################


//
// Get Server Info
//
$server_query = "SELECT 
                    servers.id,
                    DATE_FORMAT(servers.date_created, '%c/%e/%Y %H:%i') AS date_created,
                    servers.type,
                    servers.client_file_man,
                    servers.status,
                    servers.server,
                    servers.port,
                    servers.description,
                    servers.creation_status,
                    servers.update_status,
                    servers.subdomain,
                    servers.notes,
                    clients.username,
                    cfg.short_name,
                    cfg.long_name,
                    cfg.is_steam,
                    domains.domain,
                    network.ip 
                 FROM servers 
                 LEFT JOIN clients ON 
                    servers.userid = clients.id 
                 LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                 LEFT JOIN domains ON 
                    servers.domainid = domains.id 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$url_id'";

$result = @mysql_query($server_query) or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get game server information!</center>');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Current Server Creation status
$server_status    = $value[0]['status'];
$creation_status  = $value[0]['creation_status'];
$creation_status  = $value[0]['update_status'];

########################################################################

// First, kill page server is suspended.
if($server_status == 'suspended' || $server_status == 'closed')
{
    die('<div align="center">This server is currently suspended.  Please contact your host immediately to find out why.</div>');
}

########################################################################

// Smarty mysql loop
$smarty->assign('server_details', $value);

// Server Type
$this_type = $value[0][type];
$smarty->assign('type', $this_type);


########################################################################

// Current Template
$template = $config['Template'];

//
// Get all icons for this page
//
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='manageserver.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get icon order!</center>');

while ($line_icons = mysql_fetch_assoc($result_icons))
{
    $value_icons[] = $line_icons;
}

// Smarty array
$smarty->assign('icons', $value_icons);


########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('created');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Server created
        if($url_info == 'created')
        {
            $info_msg = 'Server successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}




########################################################################

// Set user's language
require('include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

// First Page
#if(!isset($_POST['status']))
#{
    // Display HTML Page
    $smarty->display($config['Template'] . '/manageserver.tpl');
#}

/*
// Action page
elseif(isset($_POST['status']))
{
    $post_action = $_POST['action'];
    
    if($post_action == 'restart')
    {
        header("Location: action.php?id=$url_id&a=restart");
        exit;
    }
    elseif($post_action == 'stop')
    {
        header("Location: action.php?id=$url_id&a=stop");
        exit;
    }
}
*/
