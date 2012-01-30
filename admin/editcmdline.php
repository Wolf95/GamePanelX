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
$smarty->assign('pagetitle', 'Edit Command-Line');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Correct ID
if(empty($_GET['id']) || !is_numeric($_GET['id']))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editcmdline.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editcmdline.php</i>: Failed to select the database!</center>');

// URL variables
$url_id = mysql_real_escape_string($_GET['id']);

########################################################################


//
// Get the raw command-line
//
$query_cmd  = "SELECT 
                  servers.id AS serverid,
                  servers.type,
                  servers.ip,
                  servers.port,
                  servers.map,
                  servers.max_slots,
                  servers.executable,
                  servers.log_file,
                  servers.setup_dir,
                  servers.working_dir,
                  servers.cmd_line,
                  servers_options.* 
               FROM servers 
               LEFT JOIN servers_options ON
                  servers.id = servers_options.srvid 
               WHERE 
                  servers.id = '$url_id'";

$result = @mysql_query($query_cmd) or die('<center><b>Error:</b> <i>editcmdline.php:</i> Failed to get the raw command-line!</center>');

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


//
// Parse the command-line
//
//require('../include/functions/cmd.php');
require(GPX_DOCROOT.'/include/functions/cmd.php');
$full_cmd_line = gpx_cmd_parse($url_id);
$smarty->assign('full_cmd_line', $full_cmd_line);



// Server Type
//$this_type = $value[0][type];
//$smarty->assign('type', $this_type);

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
        // Create account
        if($url_info == 'updated')
        {
            $info_msg = 'Successfully updated server options!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}




########################################################################


//
// POST Update
//
if(isset($_POST['update']))
{
    // POST Values
    $post_id    = $_POST['serverid'];
    $post_array = $_POST;
  
    // Update this command-line
    require_once('../include/functions/cmd.php');
    gpx_cmd_update($post_id,$post_array);
    
    
    // Send back with infobox
    header("Location: editcmdline.php?id=$post_id&info=updated");
    exit;
}

########################################################################


// Display HTML Page
$smarty->display($config['Template'] . '/editcmdline.tpl'); 
