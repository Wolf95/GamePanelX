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
$smarty->assign('pagetitle', 'Edit Template');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// ID from the URL
$url_id = $_GET['id'];

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
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
            $info_msg = 'Template successfully updated!';
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


//
// First Page
//
if(!isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>edittemplate.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>edittemplate.php</i>: Failed to select the database!</center>');


    //
    // Get template details
    //
    
    // Loop through templates
    $query_templates = "SELECT 
                          templates.id,
                          templates.server,
                          templates.available,
                          templates.is_default,
                          templates.description,
                          templates.file_path,
                          templates.template_hash,
                          network.ip 
                        FROM templates 
                        LEFT JOIN network ON 
                          templates.networkid = network.id 
                        WHERE templates.id = '$url_id'";

    $result_templates = @mysql_query($query_templates) or die('<center><b>Error:</b> <i>templates.php:</i> Failed to get icon order!</center>');

    while ($line_templates = mysql_fetch_assoc($result_templates))
    {
        $value_templates[] = $line_templates;
    }

    // Smarty array
    $smarty->assign('server_templates', $value_templates);

    ####################################################################

    //
    // Get all available games
    //
    $result_servers = @mysql_query("SELECT short_name,long_name FROM cfg ORDER BY long_name ASC") or die('<center><b>Error:</b> <i>edittemplate.php:</i> Failed to list available server types!</center>');

    // Smarty loop
    while ($line_servers = mysql_fetch_assoc($result_servers))
    {
        $value_servers[] = $line_servers;
    }

    // Smarty mysql loop
    $smarty->assign('servers', $value_servers);
    
    ####################################################################    
    
    // Get list of available languages
    require('languages.php');
    
    ####################################################################
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/edittemplate.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>edittemplate.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>edittemplate.php</i>: Failed to select the database!</center>');


    // POST Values (escape all of them)
    $post_desc        = $_POST['description'];
    $post_server      = $_POST['server'];
    $post_avail       = $_POST['available'];
    $post_default     = $_POST['is_default'];


    //
    // Update Template
    //
    require("../include/functions/templates.php");
    
    // Run the update
    if(!gpx_template_update($url_id,$post_desc,$post_server,$post_avail,$post_default))
    {
        die('<center><b>Error:</b> <i>edittemplate.php</i>: Failed to update the template!</center>');
    }
    

    
    // Redirect to edittemplate.php
    header("Location: edittemplate.php?id=$url_id&info=updated");
    exit;
}
