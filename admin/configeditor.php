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
$smarty->assign('pagetitle', 'Config Editor');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>configeditor.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>configeditor.php</i>: Failed to select the database!</center>');


########################################################################

//
// URL Variables
//
$url_serverid = mysql_real_escape_string($_GET['id']);
$url_filename = $_GET['f'];
$url_path     = $_GET['p'];
$url_action   = $_GET['a'];


// Assign values to Smarty
$smarty->assign('serverid', $url_serverid);
$smarty->assign('coded_file_name', $url_filename);
$smarty->assign('coded_file_path', $url_path);

// Decode
$url_filename  = base64_decode($url_filename);
$url_path      = base64_decode($url_path);

########################################################################

// Don't allow ../ in path
if(preg_match("/\.\.\//", $url_path))
{
    die('<center><b>Error:</b> Invalid path specified.</center>');
}

########################################################################

//
// URL Actions
//
$allowed_actions = array('delete');

// Funny business
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    exit;
}

####

// Actions
if(!empty($url_action))
{
    // Delete
    if($url_action == 'delete')
    {
        require('../include/functions/remote.php');
        if(!gpx_remote_file_delete($url_serverid,$url_filename,$url_path))
        {
            die('Failed to delete file');
        }
        else
        {
            // Success.  Send back to File Manager
            $encoded_path = base64_encode($url_path);
            header("Location: filemanager.php?id=$url_serverid&p=$encoded_path&info=deleted");
            exit;
        }
    }
}

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('saved');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Successfully edited
        if($url_info == 'saved')
        {
            $info_msg = 'Successfully saved the file!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}


########################################################################

function gpx_random_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;
}
    
########################################################################
    
//
// First Page
//
if(!isset($_POST['update']))
{
    require('../include/functions/remote.php');

    // Check Remote File type (must be ASCII to edit)
    $remote_type = gpx_remote_file_type($url_serverid,$url_filename,$url_path);



    // Text Files
    if(preg_match("/ASCII/i", $remote_type) || preg_match("/HTML/i", $remote_type))
    {
        if(!$contents = gpx_remote_file_contents($url_serverid,$url_filename,$url_path))
        {
            // Assign error to Smarty
            $smarty->assign('file_contents', 'ERROR: Failed to get file contents.');
        }
        else
        {
            // Assign file contents to Smarty
            
            $clean_file_path  = preg_replace("/\/{2}+/", '/', $url_path);
            $clean_filename   = preg_replace("/\/{2}+/", '/', $url_filename);
            
            // Remove //
            //$clean_file_path  = str_replace('//', '', $clean_file_path);
            //$clean_filename   = str_replace('//', '', $clean_filename);
            
            $smarty->assign('file_path', $clean_file_path . $clean_filename);
            $smarty->assign('file_contents', $contents);
        }
    }
    // Not a text file
    else
    {
        // ERROR
        $smarty->assign('url_back', "javascript:history.go(-1)");
        $smarty->assign('error', 'The selected file is not directly editable.');
        $smarty->display($config['Template'] . '/error.tpl');
        exit;
    }

    ####################################################################

    // Display HTML Page
    $smarty->display($config['Template'] . '/configeditor.tpl');
}

elseif(isset($_POST['update']))
{
    // POST Values
    $post_text = $_POST['file_contents'];
    
    ####################################################################
    
    // Create array out of the config
    $arr_config = explode("\n", $post_text);
    
    ####################################################################
    
    //
    // Check for forbidden commands
    //
    $result_forb = @mysql_query("SELECT * FROM cfg_configs WHERE name = '$url_filename'");
    
    while($row_forb = mysql_fetch_array($result_forb))
    {
        // Loop through 10 config options
        for($i=1; $i<=10;$i++)
        {
            $this_cmd = $row_forb["rmcmd$i"];

            // Loop through every line of the config file
            foreach($arr_config as $config_line)
            {
                // Remove whitespace
                $config_line  = stripslashes($config_line);

                // Remove newlines and returns
                $config_line = str_replace('\n', '\ ', $config_line);
                $config_line = str_replace('\r', '\ ', $config_line);
                
                // Check for a match
                if(preg_match("/^$this_cmd\ /", $config_line) || preg_match("/\ $this_cmd\ /", $config_line))
                {
                    // ERROR
                    $smarty->assign('url_back', "javascript:history.go(-1)");
                    $smarty->assign('error', 'Invalid command entered: ' . $this_cmd);
                    $smarty->display($config['Template'] . '/error.tpl');
                    exit;
                }
            }
        }
    }
    
    ####################################################################
    
    // Edit on remote server
    require('../include/functions/remote.php');
    if(!gpx_remote_file_edit($url_serverid,$url_filename,$url_path,$post_text))
    {
        die('Failed to replace file');
    }
    // Success
    else
    {
        // Success.  Convery strings to base64 and send back.
        $base64_path  = base64_encode($url_path);
        $base64_file  = base64_encode($url_filename);
        header("Location: configeditor.php?id=$url_serverid&p=$base64_path&f=$base64_file&info=saved");
        exit;
    }
}
