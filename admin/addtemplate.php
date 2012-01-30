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
$smarty->assign('pagetitle', 'Add Template');

########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

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
        if($url_info == 'updated')
        {
            $info_msg = 'Account successfully updated!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}



########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to select the database!</center>');

########################################################################

// Get current template ID from the URL
$url_id = mysql_real_escape_string($_GET['id']);

if(!empty($url_id) && !is_numeric($url_id))
{
    die('<center><b>Error:</b> <i>addtemplate.php</i>: Invalid ID in the URL!</center>');
}

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

//
// If there's an ID in the URL, get template info
//
if(!empty($url_id))
{
    $query_tpl  = "SELECT 
                      templates.id,
                      templates.networkid,
                      templates.type,
                      templates.server,
                      templates.file_path,
                      network.ip,
                      network.description,
                      cfg.long_name 
                  FROM templates 
                  LEFT JOIN network ON 
                      templates.networkid = network.id 
                  LEFT JOIN cfg ON 
                      templates.server = cfg.short_name 
                  WHERE templates.id = '$url_id'";

    // Get info
    $result_tpl = @mysql_query($query_tpl);
    
    while($row_tpl = mysql_fetch_array($result_tpl))
    {
        $tpl_id         = $row_tpl['id'];
        $tpl_networkid  = $row_tpl['networkid'];
        $tpl_type       = $row_tpl['type'];
        $tpl_server     = $row_tpl['server'];
        $tpl_file_path  = $row_tpl['file_path'];
        $tpl_ip         = $row_tpl['ip'];
        $tpl_desc       = $row_tpl['description'];
        $tpl_long_name  = $row_tpl['long_name'];
    }
    
    // Assign these to smarty
    $smarty->assign('tpl_id', $tpl_id);
    $smarty->assign('tpl_networkid', $tpl_networkid);
    $smarty->assign('tpl_type', $tpl_type);
    $smarty->assign('tpl_server', $tpl_server);
    $smarty->assign('tpl_file_path', $tpl_file_path);
    $smarty->assign('tpl_ip', $tpl_ip);
    $smarty->assign('tpl_description', $tpl_desc);
    $smarty->assign('tpl_long_name', $tpl_long_name);
    
    /*
    // Smarty loop
    while ($line_tpl = mysql_fetch_assoc($result_tpl))
    {
        $value_tpl[] = $line_tpl;
    }

    // Smarty mysql loop
    $smarty->assign('template_info', $value_tpl);
    */
}


########################################################################


//
// First Page
//
if(!isset($_POST['update']))
{
    //
    // Get all available games
    //
    $result_servers = @mysql_query("SELECT short_name,long_name FROM cfg ORDER BY long_name ASC") or die('<center><b>Error:</b> <i>addtemplate.php:</i> Failed to list available server types!</center>');

    // Smarty loop
    while ($line_servers = mysql_fetch_assoc($result_servers))
    {
        $value_servers[] = $line_servers;
    }

    // Smarty mysql loop
    $smarty->assign('servers', $value_servers);
    
    ####################################################################
    
    //
    // Get all available Network Servers
    //
    $result_netinfo = @mysql_query("SELECT id,ip,description FROM network WHERE physical = 'Y' AND available = 'Y' ORDER BY ip ASC") or die('<center><b>Error:</b> <i>addtemplate.php:</i> Failed to list available server types!</center>');

    // Smarty loop
    while ($line_netinfo = mysql_fetch_assoc($result_netinfo))
    {
        $value_netinfo[] = $line_netinfo;
    }

    // Smarty mysql loop
    $smarty->assign('network_servers', $value_netinfo);
    
    ####################################################################
    
    // Get list of available languages
    require('languages.php');
    
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addtemplate.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to select the database!</center>');


    // POST Values (escape all of them)
    $post_desc        = $_POST['description'];
    $post_type        = $_POST['template_type'];
    $post_file_path   = $_POST['file_path'];
    $post_server      = $_POST['server'];
    $post_networkid   = $_POST['networkid'];
    $post_avail       = $_POST['available'];
    $post_default     = $_POST['is_default'];
    
    ####################################################################
    
    // Check empty
    if(empty($post_type) || empty($post_file_path) || empty($post_server) || empty($post_networkid))
    {
        die('<center><b>Error:</b> <i>addtemplate.php</i>: Required values were left out!</center>');
    }
    
    ####################################################################
    
    //
    // Update Template
    //
    require('../include/functions/templates.php');
    
    
    ########
    
    //
    // New Template
    //
    if(empty($url_id))
    {
        // Create Template
        if(!gpx_template_create($post_networkid,$post_type,$post_avail,$post_default,$post_server,$post_desc,$post_file_path))
        {
            die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to create the template!</center>');
        }
    }
    
    //
    // Existing installation, just make a template
    //
    else
    {
        // Create Template
        if(!gpx_template_create_am($url_id,$post_avail,$post_default,$post_desc))
        {
            die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to create the template!</center>');
        }
    }

    
    // Redirect to templates.php
    header("Location: templates.php?info=created");
    exit;
}
