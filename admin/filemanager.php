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
$smarty->assign('pagetitle', 'File Manager');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>filemanager.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>filemanager.php</i>: Failed to select the database!</center>');

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################



// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('created','deleted');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Create account
        if($url_info == 'created')
        {
            $info_msg = 'Successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
        
        // Delete Account
        elseif($url_info == 'deleted')
        {
            $info_msg = 'Successfully deleted!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}




########################################################################


// Directory / Previous Dir from URL
$url_dir          = base64_decode($_GET['f']);
$url_prev_dir     = base64_decode($_GET['p']);

// Add to dir
$url_prev_dir     = $url_prev_dir . '/' . $url_dir;

// Assign prev dir to Smarty
$smarty->assign('previous_dir', $url_prev_dir);

########################################################################


// URL variables
$url_id = $_GET['id'];

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// Assign server ID
$smarty->assign('serverid', $url_id);

########################################################################


//
// First Page
//
if(!isset($_POST['create_dir']))
{
    // Get full file list
    require('../include/functions/filemanager.php');

    if(!$file_list = gpx_file_list($url_id,$url_prev_dir))
    {
        die('<center><b>Error:</b> <i>filemanager.php</i>: Failed to get remote file list!</center>');
    }

    ########################################################################

    // Create array from list
    $array_list = explode("\n", $file_list);

    
    // If no files in this directory
    if($array_list[0] == "success")
    {
        // Assign no results
        $smarty->assign('file_list', 0);
        
        // Display HTML Page
        $smarty->display($config['Template'] . '/filemanager.tpl');
        exit;
    }
    else
    {
        // Remove ending line of array
        array_pop($array_list);
        
        // Get total count
        $count_arr = count($array_list) - 1;
        
        /*
        echo '<pre>';
        var_dump($array_list);
        echo '</pre>';
        echo "ENDING: " . end($array_list);
        */
        
        if($array_list[$count_arr] == 'success')
        {
            // Remove 'success' from the end
            array_pop($array_list);
            // $array_list[$count_arr] = str_replace('success', '', $array_list[$count_arr]);
        }
    }
    

    // Array for Smarty
    $arr_link = array();

    // Loop through all files
    foreach($array_list as $key=>$single_file)
    {
        if(!empty($single_file))
        {
            // Separate by space
            $arr_items = explode(" ", $single_file);
            
            // File info
            $file_perms       = $arr_items[0];
            $file_size_bytes  = $arr_items[1];
            $file_date_month  = $arr_items[2];
            $file_date_day    = $arr_items[3];
            $file_name        = $arr_items[5];
            
            ################################################################
            
            // File Time/Year
            if(preg_match("/\:/", $arr_items[4]))
            {
                // This is current year, has a time
                $file_time  = $arr_items[4];
                $file_year  = date('Y');
            }
            // This is another year, no time
            else
            {
                $file_time  = '';
                $file_year  = $arr_items[4];
            }
            
            ################################################################
            
            // Directories
            if(substr($file_name, -1) == '/')
            {
                $a_href = 'filemanager.php';
            }
            // Files
            else
            {
                $a_href = 'configeditor.php';
            }
            
            // Encode File and Previous dir in Base64
            $encoded_prev_dir = base64_encode($url_prev_dir);
            $encode_filename  = base64_encode($file_name);
            
            // Add to array
            $arr_link[$key]['file_perms']         = $file_perms;
            $arr_link[$key]['file_size']          = number_format($file_size_bytes/(1024*1024),1);
            $arr_link[$key]['file_date']          = $file_date_month . ' ' . $file_date_day . ' ' . $file_year;
            $arr_link[$key]['file_name']          = $file_name;
            $arr_link[$key]['file_enc_prev_dir']  = $encoded_prev_dir;
            $arr_link[$key]['file_enc_name']      = $encode_filename;
            $arr_link[$key]['file_href']          = $a_href;
            
            ################################################################
            
            //
            // File Extension
            //
            $arr_filename = explode('.', $file_name);
            
            if(count($arr_filename) > 1)
            {
                $file_ext = end($arr_filename);
            }
            else
            {
                $file_ext = "";
            }
            
            // Add file extension
            $arr_link[$key]['file_extension']     = $file_ext;
            
            ################################################################
            
            // Directory or File
            if(substr($file_name, -1) == '/')
            {
                $arr_link[$key]['file_is_dir']    = 1;
            }
            else
            {
                $arr_link[$key]['file_is_dir']    = 0;
            }
            
            // Is a directory
            $is_dir = $arr_link[$key]['file_is_dir'];
            
            ################################################################
            
            //
            // Icons
            //
            $icons_text = array('txt','cfg','inf','log','rc','log','bat');              // Text-based
            $icons_lib  = array('so','dll');                                // Libraries / Modules
            $icons_exe  = array('bin','sh','pl','py','bash','exe','bat');   // Executables
            
            // Text
            if(in_array($file_ext, $icons_text) && $is_dir != 1)
            {
                $icon_name = 'txt.png';
            }
            
            // Libraries
            elseif(in_array($file_ext, $icons_lib) && $is_dir != 1)
            {
                $icon_name = 'lib.png';
            }
            
            // Executables
            elseif(in_array($file_ext, $icons_exe) && $is_dir != 1)
            {
                $icon_name = 'exe.png';
            }
            
            // Directories
            elseif($is_dir)
            {
                $icon_name = 'dir.png';
            }
            
            // All other files
            else
            {
                $icon_name = 'all.png';
            }
            
            ########
            
            // Add icon name to array
            $arr_link[$key]['file_icon'] = $icon_name;
        }
    }
    
    ####################################################################

    // Assign array to Smarty
    $smarty->assign('file_list', $arr_link);
    
    // Back button link
    $back_link = dirname($url_prev_dir);
    
    // Encode back link in Base 64
    $back_link = base64_encode($back_link);
    
    // Assign back button to smarty
    $smarty->assign('back_link', "filemanager.php?id=$url_id&p=$back_link");

    
    // Display HTML Page
    $smarty->display($config['Template'] . '/filemanager.tpl');
}





//
// Submit page
//
elseif(isset($_POST['create_dir']))
{
    // Directory name
    $post_prev_dir  = $_POST['previous_dir'];
    $post_dir_name  = $_POST['dir_name'];
    
    ####################################################################
    
    // Check empty
    if(empty($post_prev_dir) || empty($post_dir_name))
    {
        die('<center><b>Error:</b> <i>filemanager.php</i>: Create Directory: Required values were left out!</center>');
    }
    
    // Check for ".." in dir name
    if(preg_match("/\.\./", $post_dir_name))
    {
        die('<center><b>Error:</b> <i>filemanager.php</i>: Create Directory: Invalid directory name entered!</center>');
    }
    
    ####################################################################

    require('../include/functions/filemanager.php');
    if(!gpx_file_create_dir($url_id,$post_prev_dir,$post_dir_name))
    {
        die('<center><b>Error:</b> <i>filemanager.php</i>: Failed to create the directory!</center>');
    }
    
    
    // Show success
    header("Location: filemanager.php?id=$url_id&info=created");
    exit;
}

?>
