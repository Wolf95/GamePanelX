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
// File Listing for Template creation.  List /home/gpx/ dir
//

if(empty($_GET['id']))
{
    die('<center><b>Error:</b> No network ID given!</center>');
}

// Network ID
$url_id       = mysql_real_escape_string($_GET['id']);

// Others...
$url_file     = base64_decode(mysql_real_escape_string($_GET['f']));
$url_reset    = $_GET['reset'];

if($url_reset == '1')
{
    $url_file               = '';
    $_SESSION['file_prev']  = '';
    unset($_SESSION['file_prev']);
}

// Use session Previous Dir
if($url_file && isset($_SESSION['file_prev']))
{
    // Add to dir
    $url_prevdir  = $_SESSION['file_prev'] . '/' . $url_file;
}
elseif(empty($url_file) && isset($_SESSION['file_prev']))
{
    $url_prevdir  = dirname($_SESSION['file_prev']);
}
else
{
    $url_prevdir  = $url_file;
}

// Save new prev dir
$_SESSION['file_prev'] = $url_prevdir;

########################################################################

//
// Smarty Setup
//
require(GPX_DOCROOT.'/libs/Smarty.class.php');
$smarty = new Smarty;
$smarty->compile_dir = GPX_DOCROOT.'/templates_c/';

// Set user's language
require(GPX_DOCROOT.'/include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

// Set logged-in
$smarty->assign('logged_in', '1');
$smarty->assign('template', 'default');

// Assign Smarty serverid
$smarty->assign('srvid', $url_id);


// Server root
if($url_reset == '1' || $url_prevdir == '/')
{
    $smarty->assign('srvroot', 1);
}

########################################################################

//
// Allow editing text files
//
if(preg_match("/\.(txt|cfg|rc|log)$/i", $url_prevdir))
{
    /*
    // Get remote file contents
    require(GPX_DOCROOT.'/include/functions/remote.php');
    $file_cnt = gpx_remote_file_contents($url_id,$url_prevdir);
    
    // Assign contents to Smarty
    $smarty->assign('file_contents', $file_cnt);
    
    // Display HTML Text Editor
    $smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/fileedit.tpl');
    exit;
    */
    die('Not implemented');
}

########################################################################

// Get remote file list
require(GPX_DOCROOT.'/include/functions/filemanager.php');
$file_list  = gpx_file_list_net($url_id,$url_prevdir); // Empty file/dir means default "/" location

#echo "FILE LIST:: $file_list<br>";


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
        $icons_text = array('txt','cfg','inf','log','rc');              // Text-based
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

########################################################################

// Assign array to Smarty
$smarty->assign('file_list', $arr_link);


// Display HTML Page
$smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/net_filelist.tpl');

?>
