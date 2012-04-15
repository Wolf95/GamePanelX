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

if(!isset($_SESSION['gpx_username']))
{
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

########################################################################

if(empty($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}


$url_id       = mysql_real_escape_string($_GET['id']);
#$url_prevdir  = mysql_real_escape_string($_GET['p']);
$url_file     = base64_decode(mysql_real_escape_string($_GET['f']));
$url_reset    = $_GET['reset'];

if($url_reset == '1')
{
    $url_file               = '';
    $_SESSION['file_prev']  = '';
    unset($_SESSION['file_prev']);
}

########################################################################

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id,client_file_man FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    $has_fileman  = $row_ac[1];
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
    elseif($has_fileman == 'N')
    {
        die('You do not have File Manager access to this server.  Please contact your host.');
    }
}

########################################################################


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


// Assign prev dir to Smarty
#$smarty->assign('previous_dir', $url_prev_dir);

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
if(preg_match("/\.(txt|cfg|rc|log|ini)$/i", $url_prevdir))
{
    // Get remote file contents
    require(GPX_DOCROOT.'/include/functions/remote.php');
    $file_cnt = gpx_remote_file_contents($url_id,$url_prevdir);
    
    // Assign contents to Smarty
    $smarty->assign('file_contents', $file_cnt);
    
    // Display HTML Text Editor
    $smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/fileedit.tpl');
    exit;
}

########################################################################

// Get remote file list
require(GPX_DOCROOT.'/include/functions/filemanager.php');
$file_list  = gpx_file_list($url_id,$url_prevdir); // Empty file/dir means default "/" location


// Create array from list
#$array_list = explode("\n", $file_list);

/*
// If no files in this directory
if($array_list[0] == "success" || preg_match("/\:\ No\ such\ file\ or\ directory/", $file_list))
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
    
    if($array_list[$count_arr] == 'success')
    {
        // Remove 'success' from the end
        array_pop($array_list);
        // $array_list[$count_arr] = str_replace('success', '', $array_list[$count_arr]);
    }
}
*/

// Array for Smarty
$arr_link = array();
$key  = 0;

// Format bytes to nicer numbers (taken from http://php.net/manual/de/function.filesize.php)
function formatBytes($size)
{
    $precision = 2;
    $base = log($size) / log(1024);
    $suffixes = array('B', 'K', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

// Loop through all files
foreach($file_list as $single_file=>$item_attr)
{
    if(!empty($single_file))
    {
        // File info
        $file_name        = $single_file;
        $file_perms       = $item_attr['permissions'];
        $file_size_bytes  = $item_attr['size'];
        $file_last_mod    = $item_attr['mtime'];
        #$file_last_access = $item_attr['atime'];
        $file_type        = $item_attr['type']; // 1 or 2.  1 = File, 2 = Directory
        
        // Skip "." or ".." files
        if(preg_match("/^\./", $file_name)) continue;
        
        // Setup for sorting by dir first
        $sorter[$key]     = $item_attr['type'];
        
        ################################################################
        
        // Directories
        if($file_type == 2)
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
        $arr_link[$key]['keyid']              = $key;
        $arr_link[$key]['file_perms']         = $file_perms;
        $arr_link[$key]['file_size']          = formatBytes($file_size_bytes); //number_format($file_size_bytes/(1024*1024),1);
        $arr_link[$key]['file_date']          = date('m/d/Y', $file_last_mod); //$file_date_month . ' ' . $file_date_day . ' ' . $file_year;
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
        if($file_type == 2)
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
        $icons_text = array('txt','cfg','inf','log','rc','ini');              // Text-based
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
    
    $key++;
}

// Sort the listings, directories first
array_multisort($sorter, SORT_DESC, $arr_link);

########################################################################

// Assign array to Smarty
$smarty->assign('file_list', $arr_link);
$smarty->assign('prev_dir', $url_prevdir); // Assign hard previous dir



// Display HTML Page
$smarty->display(GPX_DOCROOT.'/admin/templates/' . $config['Template'] . '/filelist.tpl');

?>
