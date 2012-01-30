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
$smarty->assign('pagetitle', 'Create Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>createserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>createserver.php</i>: Failed to select the database!</center>');


########################################################################


// URL variables
$url_id   = $_GET['id'];
$url_type = $_GET['type'];


// Correct id
if(!empty($url_id) && !is_numeric($url_id))
{
    die('<center><b>Error:</b> <i>createserver.php</i>: Invalid id in the URL!</center>');
}


// No funny business
if($url_type != 'game' && $url_type != 'voip' && $url_type != 'other')
{
    $url_type = 'game';
}

// Assign type to smarty
$smarty->assign('client_id', $url_id);
$smarty->assign('type', $url_type);

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

/*
// List of available game/voip server types
$result_games = @mysql_query("SELECT short_name,long_name FROM cfg WHERE type='$url_type' AND available='Y' ORDER BY long_name ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_games = mysql_fetch_assoc($result_games))
{
    $value_games[] = $line_games;
}

// Smarty mysql loop
$smarty->assign('game_list', $value_games);
*/

########################################################################


// List of available IP Addresses
$result_ip = @mysql_query("SELECT id,ip FROM network WHERE available='Y' ORDER BY ip ASC") or die('<center><b>Error:</b> <i>editclientserver.php:</i> Failed to get user list!</center>');

// Get the values
while ($line_ip = mysql_fetch_assoc($result_ip))
{
    $value_ip[] = $line_ip;
}

// Smarty mysql loop
$smarty->assign('avail_ips', $value_ip);


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################



// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('created');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update success
        if($url_info == 'created')
        {
            $info_msg = 'Successfully created the server!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}

########################################################################


/*
* DEPRECATED in favor of ajax creation
*
//
// POST Update Stuff
//
if(isset($_POST['create']))
{
    // POST Values
    $post_type        = $_POST['type'];
    $post_userid      = $_POST['userid'];
    $post_status      = $_POST['status'];
    $post_desc        = $_POST['description'];
    $post_networkid   = $_POST['ip'];
    $post_port        = $_POST['port'];
    $post_log_file    = $_POST['log_file'];
    $post_max_slots   = $_POST['max_slots'];
    $post_map         = $_POST['map'];
    $post_exe         = $_POST['executable'];
    $post_work_dir    = $_POST['working_dir'];
    $post_setup_dir   = $_POST['setup_dir'];
    $post_config_file = $_POST['config_file'];
    
    // Game-specific
    $post_server      = $_POST['game'];
    $post_cmd_line    = $_POST['cmd_line'];
    $post_show_cmd    = $_POST['show_cmd_line'];
    
    ####################################################################
    
    //
    // Check empty
    //
    if(empty($post_networkid) || empty($post_port) || empty($post_server) || empty($post_userid) || empty($post_type))
    {
        // ERROR
        $smarty->assign('url_back', "createserver.php?type=$post_type");
        $smarty->assign('error', 'You left a required field empty!');
        $smarty->display($config['Template'] . '/error.tpl');
        exit;
    }
    
    ####################################################################
    
    // Get IP Address from the given Network ID
    $result_getip = @mysql_query("SELECT ip FROM network WHERE id = '$post_networkid'");
    
    while($row_getip = mysql_fetch_array($result_getip))
    {
        $post_ip  = $row_getip['ip'];
    }
    
    ####################################################################
    
    //
    // Check IP/Port combination
    //
    $result_check = @mysql_query("SELECT COUNT(id) AS thecount FROM servers WHERE ip = '$post_ip' AND port = '$post_port'") or die('<center><b>Error:</b> <i>createserver.php</i>: Failed to check IP/Port Usage!</center>');
    
    while($row_check = mysql_fetch_array($result_check))
    {
        $check_ip = $row_check['thecount'];
    }
    
    // Error out
    if($check_ip >= 1)
    {
        // ERROR
        $smarty->assign('url_back', "createserver.php?type=$post_type");
        $smarty->assign('error', 'That IP/Port combination is already in use!');
        $smarty->display($config['Template'] . '/error.tpl');
        exit;
    }
    
    ####################################################################
    
    // Create array from 10 config options
    $array_config = array();
    
    // Loop through, add to new array
    for($i = 1; $i <= 10; $i++)
    {
        // Names
        $this_name  = 'opt' . $i . '_name';
        $this_edit  = 'opt' . $i . '_edit';
        $this_value = 'opt' . $i . '_value';
        
        // Values
        $res_name   = $_POST[$this_name];
        $res_edit   = $_POST[$this_edit];
        $res_value  = $_POST[$this_value];
        
        // Add to the query
        $array_config[$this_name]   = $res_name;
        $array_config[$this_edit]   = $res_edit;
        $array_config[$this_value]  = $res_value;
    }
    
    ####################################################################
    
    // Create the server
    require('../include/functions/servers.php');
    
    $id_created = gpx_create_server($post_type,$post_server,$post_userid,$post_status,$post_desc,$post_ip,$post_port,$post_log_file,$post_max_slots,$post_map,$post_exe,$post_work_dir,$post_setup_dir,$post_cmd_line,$post_show_cmd,$post_config_file,$array_config);
    
    
    // Failure occured
    if(!is_numeric($id_created) && preg_match("/^FAILURE\:/", $id_created))
    {
        // Remove "Failure"
        $id_created = str_replace("FAILURE: ", "", $id_created);
        
        // ERROR
        $smarty->assign('url_back', "createserver.php?type=$post_type");
        $smarty->assign('error', $id_created);
        $smarty->display($config['Template'] . '/error.tpl');
        exit;
    }
    
    
    // Empty
    if(empty($id_created))
    {        
        // ERROR
        $smarty->assign('url_back', "createserver.php?type=$post_type");
        $smarty->assign('error', 'There was an error creating the server.  Please go back and try again.');
        $smarty->display($config['Template'] . '/error.tpl');
        exit;
    }    
        
    // Send the user back with info
    header("Location: manageserver.php?id=$id_created&info=created");
    exit;
}
*/

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/createserver.tpl'); 
