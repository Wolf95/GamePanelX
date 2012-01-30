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
$smarty->assign('pagetitle', 'Add Supported Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to select the database!</center>');

########################################################################

// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update Account
        if($url_info == 'created')
        {
            $info_msg = 'Server successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}



########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['update']))
{
    // Load GameQ game list INI File
    $ini_file     = '../include/query/games.ini';
    $arr_games    = parse_ini_file($ini_file,true);
    // $total_games  = count($arr_games);

    // Create smarty array
    $smarty_array = array();
    
    // Begin array counter
    $counter = 0;
     
    foreach($arr_games as $single_game=>$game_key)
    {
        // Specifics
        $game_query_name  = mysql_real_escape_string($single_game);
        $game_long_name   = mysql_real_escape_string($game_key['name']);

        // Add to smarty array
        $smarty_array[$counter]['long_name']  = $game_long_name;
        $smarty_array[$counter]['query_name'] = $game_query_name;
        
        // Add to the counter
        $counter++;
    }
    
    // Smarty mysql loop
    $smarty->assign('query_engines', $smarty_array);
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addsupportedserver.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // Basic Setup
    $post_available       = mysql_real_escape_string($_POST['available']);
    $post_type            = mysql_real_escape_string($_POST['type']);
    $post_based_on        = mysql_real_escape_string($_POST['based_on']);
    $post_is_steam        = mysql_real_escape_string($_POST['is_steam']);
    $post_is_punkbuster   = mysql_real_escape_string($_POST['is_punkbuster']);
    $post_query_engine    = mysql_real_escape_string($_POST['query_engine']);
    
    // Naming
    $post_long_name       = mysql_real_escape_string($_POST['long_name']);
    $post_short_name      = mysql_real_escape_string($_POST['short_name']);
    $post_mod_name        = mysql_real_escape_string($_POST['mod_name']);
    $post_steam_name      = mysql_real_escape_string($_POST['steam_name']);
    $post_nickname        = mysql_real_escape_string($_POST['nickname']);
    $post_description     = mysql_real_escape_string($_POST['description']);
    
    // Specifics
    $post_executable      = mysql_real_escape_string($_POST['executable']);
    $post_map             = mysql_real_escape_string($_POST['map']);
    $post_style           = mysql_real_escape_string($_POST['style']);
    $post_log_file        = mysql_real_escape_string($_POST['log_file']);
    $post_setup_cmd       = mysql_real_escape_string($_POST['setup_cmd']);
    $post_working_dir     = mysql_real_escape_string($_POST['working_dir']);
    $post_setup_dir       = mysql_real_escape_string($_POST['setup_dir']);
    $post_cmd_line        = mysql_real_escape_string($_POST['cmd_line']);
    $post_config_file     = mysql_real_escape_string($_POST['config_file']);
    
    // Ports
    $post_port            = mysql_real_escape_string($_POST['port']);
    $post_reserved_ports  = mysql_real_escape_string($_POST['reserved_ports']);
    $post_tcp_ports       = mysql_real_escape_string($_POST['tcp_ports']);
    $post_udp_ports       = mysql_real_escape_string($_POST['udp_ports']);
    
    // Config Values
    $post_cfg_ip          = mysql_real_escape_string($_POST['cfg_ip']);
    $post_cfg_port        = mysql_real_escape_string($_POST['cfg_port']);
    $post_cfg_max_slots   = mysql_real_escape_string($_POST['cfg_max_slots']);
    $post_cfg_map         = mysql_real_escape_string($_POST['cfg_map']);
    $post_cfg_password    = mysql_real_escape_string($_POST['cfg_password']);
    $post_cfg_internet    = mysql_real_escape_string($_POST['cfg_internet']);
    
    // Private Notes
    $post_notes           = mysql_real_escape_string($_POST['notes']);
        
    ####################################################################
    
    //
    // Create Supported Server
    //
    @mysql_query("INSERT INTO cfg (available,type,based_on,is_steam,is_punkbuster,long_name,short_name,query_name,mod_name,steam_name,nickname,description,executable,map,style,log_file,setup_cmd,working_dir,setup_dir,config_file,cmd_line,port,date_added,reserved_ports,tcp_ports,udp_ports,cfg_ip,cfg_port,cfg_max_slots,cfg_map,cfg_password,cfg_internet,notes) VALUES('$post_available','$post_type','$post_based_on','$post_is_steam','$post_is_punkbuster','$post_long_name','$post_short_name','$post_query_engine','$post_mod_name','$post_steam_name','$post_nickname','$post_description','$post_executable','$post_map','$post_style','$post_log_file','$post_setup_cmd','$post_working_dir','$post_setup_dir','$post_config_file','$post_cmd_line','$post_port',NOW(),'$post_reserved_ports','$post_tcp_ports','$post_udp_ports','$post_cfg_ip','$post_cfg_port','$post_cfg_max_slots','$post_cfg_map','$post_cfg_password','$post_cfg_internet','$post_notes')") or die('<b>Error:</b> Failed to add the Supported Server!');
        
    ####################################################################
    
    // Get the ID of the row just created
    $result_id = @mysql_query("SELECT id FROM cfg WHERE short_name = '$post_short_name' AND long_name = '$post_long_name' AND description = '$post_description' AND nickname = '$post_nickname' ORDER BY id DESC LIMIT 0,1");
    
    while($row_id = mysql_fetch_array($result_id))
    {
        $this_id = $row_id['id'];
    }
    
    ####################################################################
    
    // Add row to `cfg_options`
    #@mysql_query("INSERT INTO cfg_options (srvid) VALUES('$this_id')") or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to add to the Supported Server options!</center>');
    
    ####################################################################
    
    
    // Redirect to supportedservers.php
    header("Location: editsupportedcmdline.php?id=$this_id&info=created");
    exit;
}
